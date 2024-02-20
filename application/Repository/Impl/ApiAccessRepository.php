<?php

namespace RegisteredMailApp\Repository\Impl;

use Exception;
use RegisteredMailApp\Entity\ApiAccess;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Repository\Interfaces\ApiAccessRepositoryInterface;

class ApiAccessRepository extends FileStorageRepository implements ApiAccessRepositoryInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function findAR24ApiAccess(): ApiAccess
    {
        $data = $this->getStore()["AR24"] ?? null;

        if (empty($data)) {
            throw new ResourceNotFoundException("AR24 api access not found");
        }

        return new ApiAccess(
            $data["token"],
            $data["encryptionKey"],
            $data["baseUrl"]
        );
    }


    /**
     * @throws Exception
     */
    public function saveAR24ApiAccess(
        string $token,
        string $encryptionKey,
        string $baseUrl
    ): ApiAccessRepositoryInterface
    {
        $this->setStore([
            "AR24" => [
                "token" => $token,
                "encryptionKey" => $encryptionKey,
                "baseUrl" => $baseUrl
            ]
        ]);

        return $this;
    }
}