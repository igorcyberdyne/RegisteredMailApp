<?php

namespace RegisteredMailApp\Tests\Gateway\AR24\Impl;

use Exception;
use PHPUnit\Framework\TestCase;
use RegisteredMailApp\Exception\RegisteredMailException;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Gateway\AR24\Impl\RegisteredMailGateway;

class RegisteredMailGatewayTest extends TestCase
{
    use HelperTrait;
    private $registeredMailGateway;

    /**
     * @throws ResourceNotFoundException
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->registeredMailGateway = new RegisteredMailGateway($this->createMockForApiAccessRepositoryInterface());
    }


    /**
     * @throws RegisteredMailException
     */
    public function testSendMail()
    {
        $result = $this->registeredMailGateway->sendMail([
            'id_user' => '63011' ,
            'to_firstname' => 'Josphin',
            'to_lastname' => 'GOUBILI',
            'to_email' => 'ig.gamath@gmail.com',
            'dest_statut' => 'particulier',
            'attachment[0]' => "7964372",
            'content' => "Bonjour <h1>Josphin GAMATH</h1>, <br>Je suis Igor J. GAMATH compte test ID: 63011<br>Ceci est le test d'un courrier AR24 référence: " . uniqid(),
        ]);

        $this->assertIsArray($result);
    }

    /**
     * @throws RegisteredMailException
     */
    public function test_getRegisteredMail()
    {
        $result = $this->registeredMailGateway->getRegisteredMail([
            'id' => '401753'
        ]);

        $this->assertIsArray($result);
    }
}
