<?php

namespace RegisteredMailApp\Repository\Impl;

use Exception;
use RegisteredMailApp\Entity\Attachment;
use RegisteredMailApp\Entity\User;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Repository\Interfaces\AttachmentRepositoryInterface;
use RegisteredMailApp\Repository\Interfaces\UserRepositoryInterface;

class AttachmentRepository extends FileStorageRepository implements AttachmentRepositoryInterface
{
    public function findById($id): Attachment
    {
        $data = $this->getStore()["user-attachment-key-$id"] ?? null;

        if (empty($data)) {
            throw new ResourceNotFoundException("Attachment with 'id=$id' not found");
        }

        return new Attachment($id, $data["data"]);
    }

    public function findAllByUserId($userId): array
    {
        $attachments = [];
        foreach ($this->getStore() as $attachment) {
            if ($attachment["userId"] != $userId) {
                continue;
            }

            $attachments[] = new Attachment($attachment["id"], $attachment["data"]);
        }

        return $attachments;
    }


    /**
     * @throws Exception
     */
    public function saveAttachment($id, $idUser, array $data): AttachmentRepositoryInterface
    {
        $this->setStore([
            "user-attachment-key-$id" => [
                "id" => $id,
                "userId" => $idUser,
                "data" => $data,
            ]
        ]);

        return $this;
    }
}