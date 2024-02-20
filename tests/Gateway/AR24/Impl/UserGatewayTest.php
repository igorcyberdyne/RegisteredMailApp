<?php

namespace RegisteredMailApp\Tests\Gateway\AR24\Impl;

use PHPUnit\Framework\TestCase;
use RegisteredMailApp\Exception\UserException;
use RegisteredMailApp\Gateway\AR24\Impl\UserGateway;

class UserGatewayTest extends TestCase
{
    use HelperTrait;

    /**
     * @var UserGateway
     */
    private $userGateway;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userGateway = new UserGateway($this->createMockForApiAccessRepositoryInterface());
    }

    /**
     * @throws UserException
     */
    public function testGetUser()
    {
        $result = $this->userGateway->getUser([
            "email" => "ig.gamath+gogo-65d3788a61c29@gmail.com"
        ]);
        $this->assertIsArray($result);

    }

    /**
     * @throws UserException
     */
    public function testCreateUser()
    {
        $result = $this->userGateway->createUser([
            'firstname' => 'Hugo' ,
            'lastname' => 'Dupont',
            'email' => "ig.gamath+" . uniqid() . "@gmail.com" ,
            'country' => 'FR' ,
            'address1' => '1 rue de la république' ,
            'statut' => 'particulier' ,
            'company' => 'ABC SAS',
            'city' => 'Paris' ,
            'zipcode' => '75000' ,
            'gender' => 'F' ,
            'password' => 'yqxEJfvs4Rx5S9T' ,
            'company_siret' => '123456',
            'company_tva' => '' ,
            'address2' => 'Batiment B' ,
            'confirmed' => '0' ,
            'billing_email' => 'facturation@example.com' ,
            'notify_ev' => '1' ,
            'notify_ar' => '1' ,
            'notify_ng' => '1' ,
            'notify_consent' => '1' ,
            'notify_eidas_to_valid' => '1' ,
            'notify_recipient_update' => '1',
            'notify_waiting_ar_answer' => '1',
            'is_legal_entity' => '0'
        ]);

        $this->assertIsArray($result);
    }
}
