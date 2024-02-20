<?php

namespace RegisteredMailApp\Gateway\AR24\Impl;

use Exception;
use RegisteredMailApp\Exception\RegisteredMailException;
use RegisteredMailApp\Gateway\AR24\Interfaces\RegisteredMailGatewayInterface;
use RegisteredMailApp\Helper\FieldsChecker;
use RegisteredMailApp\Helper\RegExpChecker;

class RegisteredMailGateway extends AbstractGateway implements RegisteredMailGatewayInterface
{
    const REGISTERED_MAIL_FIELD_RULES = [
        "id" => [
            "require" => true,
            "regex" => RegExpChecker::NUMERIC,
        ],
    ];
    const SEND_MAIL_FIELD_RULES = [
        "id_user" => [
            "require" => true,
            "regex" => RegExpChecker::NUMERIC,
        ],
        "to_email" => [
            "require" => true,
            "regex" => RegExpChecker::EMAIL,
        ],
        "dest_statut" => [
            "require" => true,
            "regex" => RegExpChecker::ALPHA_NUMERIC,
            "valueIn" => ["particulier", "professional"],
        ],
        "to_firstname" => [
            "require" => true,
            "regex" => RegExpChecker::TEXT_NO_HTML,
        ],
        "to_lastname" => [
            "require" => true,
            "regex" => RegExpChecker::TEXT_NO_HTML,
        ],
        "to_company" => [
            "require" => false,
            "regex" => RegExpChecker::TEXT_NO_HTML,
        ],
    ];

    public function getRegisteredMail(array $queryParam): array
    {
        $fieldsChecker = FieldsChecker::create($queryParam, self::REGISTERED_MAIL_FIELD_RULES);
        if (!$fieldsChecker->isValid()) {
            throw new RegisteredMailException($fieldsChecker->getErrorAsString());
        }

        try {
            return $this->get("/mail", $queryParam);
        } catch (Exception $exception) {
            throw new RegisteredMailException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }

    public function sendMail(array $data): array
    {
        $rules = self::SEND_MAIL_FIELD_RULES;
        if (($data["dest_statut"] ?? "") == "professional") {
            $rules["to_firstname"]["require"] = false;
            $rules["to_lastname"]["require"] = false;
            $rules["to_company"]["require"] = true;
        }

        $fieldsChecker = FieldsChecker::create($data, $rules);
        if (!$fieldsChecker->isValid()) {
            throw new RegisteredMailException($fieldsChecker->getErrorAsString());
        }

        try {
            return $this->post("/mail", $data);
        } catch (Exception $exception) {
            throw new RegisteredMailException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }
}