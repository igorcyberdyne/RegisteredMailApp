<?php

namespace RegisteredMailApp\Gateway\AR24\Impl;

use DateTime;
use Exception;
use RegisteredMailApp\Exception\MaintenanceException;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Exception\ResponseErrorException;
use RegisteredMailApp\Repository\Interfaces\ApiAccessRepositoryInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

abstract class AbstractGateway
{
    /** @var HttpClientInterface */
    private $httpClient;
    private $apiAccess;

    /**
     * @throws ResourceNotFoundException
     */
    public function __construct(
        ApiAccessRepositoryInterface $apiAccessRepository
    )
    {
        $this->httpClient = HttpClient::create();
        $this->apiAccess = $apiAccessRepository->findAR24ApiAccess();
    }

    private function createSignature(string $date): string
    {
        $private_key = $this->apiAccess->getEncryptionKey();
        $hashed_private_key = hash('sha256', $private_key);

        // Initialization Vector : First 16 bytes of 2 times hashed private key
        $iv = mb_strcut(hash('sha256', $hashed_private_key), 0, 16, 'UTF-8');

        return openssl_encrypt($date, 'aes-256-cbc', $hashed_private_key, false, $iv);
    }

    private function decryptResponse(string $response, string $date)
    {
        if (empty($response)) {
            return [];
        }

        $private_key = $this->apiAccess->getEncryptionKey();
        $key = hash('sha256', $date . $private_key);

        // Initialization Vector : First 16 bytes of 2 times hashed private key
        $iv = mb_strcut(hash('sha256', hash('sha256', $private_key)), 0, 16, 'UTF-8');

        $decrypted_response = openssl_decrypt($response, 'aes-256-cbc', $key, false, $iv);

        return json_decode($decrypted_response, true)["result"];
    }

    /**
     * @throws Exception
     */
    public function get(string $route, array $queryParam = [])
    {
        $query = http_build_query($queryParam);

        $url = $this->apiAccess->getBaseUrl() . "/" . ltrim(rtrim(trim($route), "/"), "/") . "?" . $query;

        return $this->httpRequest("GET", $url);
    }

    /**
     * @throws Exception
     */
    public function post(string $route, array $body = [])
    {
        $url = $this->apiAccess->getBaseUrl() . "/" . ltrim(rtrim(trim($route), "/"), "/");
        return $this->httpRequest("POST", $url, ["body" => $body]);
    }


    /**
     * @throws Exception
     */
    private function httpRequest(string $method, string $url, array $options = [])
    {
        try {
            date_default_timezone_set("Europe/Paris");
            $date = (new DateTime())->format("Y-m-d H:i:s");
            $signature = $this->createSignature($date);

            $additionalParam = [
                "token" => $this->apiAccess->getToken(),
                "date" => $date,
            ];
            $headers = [];
            if ($method === "POST") {
                $options["body"] = array_merge($options["body"] ?? [], $additionalParam);

                $hasDataPart = false;
                foreach ($options["body"] as $value) {
                    if ($value instanceof DataPart) {
                        $hasDataPart = true;
                        break;
                    }
                }

                if ($hasDataPart) {
                    // Here to upload file
                    $formData = new FormDataPart($options["body"]);
                    $headers = $formData->getPreparedHeaders()->toArray();
                    $options["body"] = $formData->bodyToString();
                }
            } elseif ($method === "GET") {
                $url .= "&" . http_build_query($additionalParam);
            }

            $response = $this
                ->httpClient
                ->withOptions([
                    "headers" => array_merge($headers, ["signature" => $signature,])
                ])
                ->request($method, $url, $options);

            if ($response->getStatusCode() != 200) {
                throw new Exception("Cannot perform request", $response->getStatusCode());
            }

            $content = $response->getContent(false);
            $result = json_decode($content, true);

            if (!empty($result["status"] ?? "") && in_array(strtolower($result["status"]), ["maintenance", "error"])) {
                if ($result["status"] == "maintenance") {
                    throw new MaintenanceException("Gateway is in maintenance");
                }

                throw new ResponseErrorException(
                    $result["message"] ?? "Undefined error"
                );
            }

            return $this->decryptResponse($content, $date);
        } catch (Throwable $exception) {
            if ($exception instanceof MaintenanceException || $exception instanceof ResponseErrorException) {
                throw $exception;
            }

            throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}