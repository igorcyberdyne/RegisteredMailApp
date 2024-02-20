<?php

namespace RegisteredMailApp\Gateway\AR24\Interfaces;
use RegisteredMailApp\Exception\AttachmentException;

interface AttachmentGatewayInterface
{
    /**
     * @param array $data
     * @return array
     * @throws AttachmentException
     */
    public function addAttachment(array $data) : array;
}