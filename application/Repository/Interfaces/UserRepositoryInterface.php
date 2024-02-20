<?php

namespace RegisteredMailApp\Repository\Interfaces;

use RegisteredMailApp\Entity\User;
use RegisteredMailApp\Exception\ResourceNotFoundException;

interface UserRepositoryInterface
{
    /**
    * @throws ResourceNotFoundException
    */
    public function findById($id): User;

    /**
     * @return User[]|array
     */
    public function findAll(): array;
    public function removeAll(): UserRepositoryInterface;

    public function saveUser(
        $id,
        array $data
    ): UserRepositoryInterface;
}