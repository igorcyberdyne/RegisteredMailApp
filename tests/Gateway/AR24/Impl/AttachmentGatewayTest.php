<?php

namespace RegisteredMailApp\Tests\Gateway\AR24\Impl;

use Exception;
use PHPUnit\Framework\TestCase;
use RegisteredMailApp\Exception\AttachmentException;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Gateway\AR24\Impl\AttachmentGateway;
use RegisteredMailApp\Helper\Tools;
use Symfony\Component\Mime\Part\DataPart;

class AttachmentGatewayTest extends TestCase
{
    use HelperTrait;
    private $attachmentGateway;

    /**
     * @throws ResourceNotFoundException
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->attachmentGateway = new AttachmentGateway($this->createMockForApiAccessRepositoryInterface());
        $this->generateTextFile();
    }

    /**
     * @throws AttachmentException
     * @throws Exception
     */
    public function test_addAttachment()
    {
        $result = $this->attachmentGateway->addAttachment([
            'id_user' => '63011',
            'file_name' => sprintf("%s-" . basename($this->filenameGenerated), uniqid()),
            'file' => DataPart::fromPath($this->filenameGenerated),
        ]);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result["file_id"]);
    }

}
