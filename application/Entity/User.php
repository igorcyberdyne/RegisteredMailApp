<?php

namespace RegisteredMailApp\Entity;

class User
{
    private $id;
    private $data;

    /**
     * @param int $id
     * @param array $data
     */
    public function __construct(int $id, array $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return User
     */
    public function setData(array $data): User
    {
        $this->data = $data;
        return $this;
    }


}