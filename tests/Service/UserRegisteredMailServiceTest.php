<?php

namespace RegisteredMailApp\Tests\Service;

use Exception;
use PHPUnit\Framework\TestCase;
use RegisteredMailApp\Entity\User;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Exception\UserException;
use RegisteredMailApp\Gateway\AR24\Impl\AttachmentGateway;
use RegisteredMailApp\Gateway\AR24\Impl\RegisteredMailGateway;
use RegisteredMailApp\Gateway\AR24\Impl\UserGateway;
use RegisteredMailApp\Helper\Tools;
use RegisteredMailApp\Repository\Impl\AttachmentRepository;
use RegisteredMailApp\Repository\Impl\RegisteredMailRepository;
use RegisteredMailApp\Repository\Impl\UserRepository;
use RegisteredMailApp\Repository\Interfaces\AttachmentRepositoryInterface;
use RegisteredMailApp\Repository\Interfaces\UserRepositoryInterface;
use RegisteredMailApp\Service\UserRegisteredMailService;
use RegisteredMailApp\Tests\Gateway\AR24\Impl\HelperTrait;
use Symfony\Component\Mime\Part\DataPart;

class UserRegisteredMailServiceTest extends TestCase
{
    use HelperTrait;

    /**
     * @var UserRegisteredMailService
     */
    private $userService;


    /**
     * @var User
     */
    private $userCreated;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var AttachmentRepositoryInterface */
    private $attachmentRepository;
    private $registeredMailRepository;

    /**
     * @param string|null $email
     * @param string|null $firstname
     * @param string|null $lastname
     * @return void
     * @throws ResourceNotFoundException
     * @throws UserException
     */
    public function givenUser(
        ?string $email = null,
        ?string $firstname = null,
        ?string $lastname = null
    ): void
    {
        $email = $email ?? "ig.gamath+gogo-" . uniqid() . "@gmail.com";
        $this->userCreated = $this->userService->createUser($this->givenUserData(
            $email,
            $firstname ?? "Igor J.",
            $lastname ?? "GAMATH GOUBILI"
        ));

        $this->assertEquals($email, $this->userCreated->getData()["email"]);
    }

    /**
     * @throws ResourceNotFoundException
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $apiAccessRepo = $this->createMockForApiAccessRepositoryInterface();
        $this->userService = new UserRegisteredMailService(
            new UserGateway($apiAccessRepo),
            new AttachmentGateway($apiAccessRepo),
            new RegisteredMailGateway($apiAccessRepo),
            $this->userRepository = new UserRepository(),
            $this->attachmentRepository = new AttachmentRepository(),
            $this->registeredMailRepository = new RegisteredMailRepository()
        );

        $this->userCreated = null;
        $this->generateTextFile();
    }

    private function givenUserData(
        ?string $email = null,
        ?string $firstname = null,
        ?string $lastname = null
    ): array
    {
        $uniqid = uniqid();
        return [
            'email' => $email ?? "ig.gamath+" . $uniqid . "@gmail.com",
            'firstname' => $lastname ?? 'Hugo',
            'lastname' => $firstname ?? 'Dupont',
            'country' => 'FR',
            'address1' => '1 rue de la république',
            'statut' => 'particulier',
            //'company' => sprintf('EKOLO-%s SAS', $uniqid),
            'city' => 'Paris',
            'zipcode' => '75000',
            'gender' => 'F',
            'password' => 'yqxEJfvs4Rx5S9T',
            'company_siret' => rand(123456, 999999),
            'company_tva' => '',
            'address2' => 'Batiment B',
            'confirmed' => '1',
            'cgu' => '1',
            'billing_email' => str_replace("+", "+facture-", $email),
            'notify_ev' => '1',
            'notify_ar' => '1',
            'notify_ng' => '1',
            'notify_consent' => '1',
            'notify_eidas_to_valid' => '1',
            'notify_recipient_update' => '1',
            'notify_waiting_ar_answer' => '1',
            'is_legal_entity' => '0'
        ];
    }

    /**
     * @throws UserException
     * @throws ResourceNotFoundException
     */
    public function testCreateUser()
    {
        $this->givenUser();

        return $this;
    }

    /**
     * @throws UserException
     * @throws ResourceNotFoundException
     */
    public function test_get_user()
    {
        $id = $this->testCreateUser()->userCreated->getId();

        $userRetrieve = $this->userService->getUserById($id);

        $this->assertNotEmpty($userRetrieve);
        $this->assertEquals($id, $userRetrieve->getId());

    }

    /**
     * @throws UserException
     * @throws ResourceNotFoundException
     * @throws Exception
     */
    public function test_get_all_users()
    {
        $this->userRepository->removeAll();

        $this->testCreateUser()->testCreateUser();

        $users = $this->userService->getUsers();
        $this->assertIsArray($users);
        $this->assertCount(2, $users);
    }

    /**
     * @throws ResourceNotFoundException
     * @throws Exception
     */
    public function test_add_attachment()
    {
        $this->testCreateUser();

        $attachment = $this->userService->addAttachment([
            'id_user' => "{$this->userCreated->getId()}",
            'file_name' => sprintf("%s-" . basename($this->filenameGenerated), uniqid()),
            'file' => DataPart::fromPath($this->filenameGenerated),
        ]);

        $this->assertNotEmpty($attachment);
    }

    /**
     * @throws ResourceNotFoundException
     * @throws Exception
     */
    public function test_send_mail()
    {
        // create user
        $name = "Nom " . uniqid();
        $this->givenUser(
            "ig.gamath+" . Tools::slugify($name) . "@gmail.com",
            "Prénom",
            $name
        );
        $fullName = $this->userCreated->getData()["firstname"];
        $fullName .= " " . strtoupper($this->userCreated->getData()["lastname"]);

        // create attachment
        $attachment = $this->userService->addAttachment([
            'id_user' => "{$this->userCreated->getId()}",
            'file_name' => sprintf("%s-" . basename($this->filenameGenerated), Tools::slugify($fullName)),
            'file' => DataPart::fromPath($this->filenameGenerated),
        ]);

        // send mail and associate attachment
        $recipientName = 'RecipientName-' . uniqid();
        $mail = $this->userService->sendMailTo([
            'id_user' => $this->userCreated->getId(),
            'to_firstname' => 'Recipient firstname',
            'to_lastname' => $recipientName,
            'to_email' => "ig.gamath+" . Tools::slugify($recipientName) . "@gmail.com",
            'dest_statut' => 'particulier',
            'attachment[0]' => "{$attachment->getId()}",
            'content' => "Bonjour <h1>$recipientName</h1>, <br>Je suis $fullName compte test ID: {$this->userCreated->getId()}<br>Ceci est le test d'un courrier AR24 ",
        ]);

        $this->assertNotEmpty($mail);
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function test_getMailInfo()
    {
        // mail: 401800, user:63068
        $mail = $this->userService->getMailInfo(401800);

        $this->assertNotEmpty($mail);
    }


}
