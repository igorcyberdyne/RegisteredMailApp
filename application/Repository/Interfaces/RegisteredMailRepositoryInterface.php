<?php

namespace RegisteredMailApp\Repository\Interfaces;

use RegisteredMailApp\Entity\RegisteredMail;
use RegisteredMailApp\Exception\ResourceNotFoundException;

interface RegisteredMailRepositoryInterface
{
    /**
    * @throws ResourceNotFoundException
    */
    public function findById($id): RegisteredMail;


    public function saveRegisteredMail(
        $id,
        $idUser,
        array $data
    ): RegisteredMailRepositoryInterface;
}