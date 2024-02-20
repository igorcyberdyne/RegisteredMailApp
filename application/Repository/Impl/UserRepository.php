<?php

namespace RegisteredMailApp\Repository\Impl;

use Exception;
use RegisteredMailApp\Entity\User;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Repository\Interfaces\UserRepositoryInterface;

class UserRepository extends FileStorageRepository implements UserRepositoryInterface
{
    public function findById($id): User
    {
        $data = $this->getStore()["user-key-$id"] ?? null;

        if (empty($data)) {
            throw new ResourceNotFoundException("User with 'id=$id' not found");
        }

        return new User($id, $data["data"]);
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $users = [];
        foreach ($this->getStore() as $user) {
            $users[] = new User($user["id"], $user["data"]);
        }

        return $users;
    }

    /**
     * @throws Exception
     */
    public function removeAll(): UserRepositoryInterface
    {
        $this->setStore([]);

        return $this;
    }


    /**
     * @throws Exception
     */
    public function saveUser($id, array $data): UserRepositoryInterface
    {
        $this->setStore([
            "user-key-$id" => [
                "id" => $id,
                "data" => $data,
            ]
        ]);

        return $this;
    }
}