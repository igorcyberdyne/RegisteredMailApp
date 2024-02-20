<?php

namespace RegisteredMailApp\Entity;

class Attachment
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
     * @return Attachment
     */
    public function setId($id): Attachment
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
     * @return Attachment
     */
    public function setData($data): Attachment
    {
        $this->data = $data;
        return $this;
    }

}