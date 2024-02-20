<?php

namespace RegisteredMailApp\Entity;

abstract class Entity
{
    protected $id;
    protected $data;

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
     * @return Entity
     */
    public function setId($id): Entity
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
     * @return Entity
     */
    public function setData($data): Entity
    {
        $this->data = $data;
        return $this;
    }
}