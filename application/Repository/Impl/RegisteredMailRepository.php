<?php

namespace RegisteredMailApp\Repository\Impl;

use Exception;
use RegisteredMailApp\Entity\Attachment;
use RegisteredMailApp\Entity\RegisteredMail;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Repository\Interfaces\RegisteredMailRepositoryInterface;

class RegisteredMailRepository extends FileStorageRepository implements RegisteredMailRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findById($id): RegisteredMail
    {
        $data = $this->getStore()["user-mail-key-$id"] ?? null;

        if (empty($data)) {
            throw new ResourceNotFoundException("Mail with 'id=$id' not found");
        }

        return new RegisteredMail($id, $data["data"]);
    }


    /**
     * @throws Exception
     */
    public function saveRegisteredMail($id, $idUser, array $data): RegisteredMailRepositoryInterface
    {
        $this->setStore([
            "user-mail-key-$id" => [
                "id" => $id,
                "userId" => $idUser,
                "data" => $data,
            ]
        ]);

        return $this;
    }
}