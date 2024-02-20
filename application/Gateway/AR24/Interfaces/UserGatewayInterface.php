<?php

namespace RegisteredMailApp\Gateway\AR24\Interfaces;
use RegisteredMailApp\Exception\UserException;

interface UserGatewayInterface
{
    /**
     * @param array $data
     * @return array
     * @throws UserException
     */
    public function createUser(array $data) : array;

    /**
     * @param array $queryParam
     * @return array
     * @throws UserException
     */
    public function getUser(array $queryParam) : array;
}