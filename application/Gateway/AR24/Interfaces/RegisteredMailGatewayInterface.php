<?php

namespace RegisteredMailApp\Gateway\AR24\Interfaces;

use RegisteredMailApp\Exception\RegisteredMailException;

interface RegisteredMailGatewayInterface
{
    /**
     * @throws RegisteredMailException
     */
    public function getRegisteredMail(array $queryParam): array;

    /**
     * @throws RegisteredMailException
     */
    public function sendMail(array $data): array;
}