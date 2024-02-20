<?php

namespace RegisteredMailApp\Gateway\AR24\Impl;

use Exception;
use RegisteredMailApp\Exception\AttachmentException;
use RegisteredMailApp\Gateway\AR24\Interfaces\AttachmentGatewayInterface;
use RegisteredMailApp\Helper\RegExpChecker;
use Symfony\Component\Mime\Part\DataPart;

class AttachmentGateway extends AbstractGateway implements AttachmentGatewayInterface
{
    public function addAttachment(array $data): array
    {
        if (empty($data["id_user"]) || !RegExpChecker::isNumeric($data["id_user"])) {
            throw new AttachmentException("id_user must be an integer");
        }

        if (empty($data["file"]) || !$data["file"] instanceof DataPart) {
            throw new AttachmentException(empty($data["file"]) ? "file is required" : "file must be a File type");
        }

        // Calculate attachment size
        $filename = $data["file"]->getPreparedHeaders()->getHeaderParameter('Content-Disposition', 'filename');
        $filename = sys_get_temp_dir()."\\".$filename;
        file_put_contents($filename, $data["file"]->getBody());
        $size = round(filesize($filename) / 1000000);
        unlink($filename);
        if ($size > 256) {
            throw new AttachmentException("file max size is 256 MB, given $size MB");
        }

        try {
            return $this->post("/attachment/", $data);
        } catch (Exception $exception) {
            throw new AttachmentException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }
}