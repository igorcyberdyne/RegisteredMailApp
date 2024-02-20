<?php

namespace RegisteredMailApp\Entity;

class RegisteredMail
{
    private $id;
    private $data;

    /**
     * @param $id
     * @param $data
     */
    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return RegisteredMail
     */
    public function setId($id): RegisteredMail
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return RegisteredMail
     */
    public function setData($data): RegisteredMail
    {
        $this->data = $data;
        return $this;
    }

}