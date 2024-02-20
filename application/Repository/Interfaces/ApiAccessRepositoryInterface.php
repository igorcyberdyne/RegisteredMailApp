<?php

namespace RegisteredMailApp\Repository\Interfaces;

use RegisteredMailApp\Entity\ApiAccess;
use RegisteredMailApp\Exception\ResourceNotFoundException;

interface ApiAccessRepositoryInterface
{

    /**
    * @throws ResourceNotFoundException
    */
    public function findAR24ApiAccess(): ApiAccess;

    public function saveAR24ApiAccess(
        string $token,
        string $encryptionKey,
        string $baseUrl
    ): ApiAccessRepositoryInterface;
}