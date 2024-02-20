<?php

namespace RegisteredMailApp\Entity;

class ApiAccess
{
    private $token;
    private $encryptionKey;
    private $baseUrl;

    /**
     * @param $token
     * @param $encryptionKey
     * @param $baseUrl
     */
    public function __construct($token, $encryptionKey, $baseUrl)
    {
        $this->token = $token;
        $this->encryptionKey = $encryptionKey;
        $this->baseUrl = $baseUrl;
    }


    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getEncryptionKey(): string
    {
        return $this->encryptionKey;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

}