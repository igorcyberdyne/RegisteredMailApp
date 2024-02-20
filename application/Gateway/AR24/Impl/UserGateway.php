<?php

namespace RegisteredMailApp\Gateway\AR24\Impl;

use Exception;
use RegisteredMailApp\Exception\UserException;
use RegisteredMailApp\Gateway\AR24\Interfaces\UserGatewayInterface;
use RegisteredMailApp\Helper\FieldsChecker;
use RegisteredMailApp\Helper\RegExpChecker;

class UserGateway extends AbstractGateway implements UserGatewayInterface
{
    const CREATE_USER_FIELD_RULES = [
        "firstname" => [
            "require" => true,
            "regex" => RegExpChecker::TEXT_NO_HTML,
        ],
        "lastname" => [
            "require" => true,
            "regex" => RegExpChecker::TEXT_NO_HTML,
        ],
        "email" => [
            "require" => true,
            "regex" => RegExpChecker::EMAIL,
        ],
        "statut" => [
            "require" => true,
            "regex" => RegExpChecker::ALPHA_NUMERIC,
            "valueIn" => ["particulier", "professional"],
        ],
        "country" => [
            "require" => true,
            "regex" => RegExpChecker::ALPHA_NUMERIC,
        ],
        "address1" => [
            "require" => true,
            "regex" => RegExpChecker::TEXT_NO_HTML,
        ],
        "zipcode" => [
            "require" => true,
            "regex" => RegExpChecker::NUMERIC,
        ],
        "city" => [
            "require" => true,
            "regex" => RegExpChecker::TEXT_NO_HTML,
        ],
        "company" => [
            "require" => true,
            "regex" => RegExpChecker::TEXT_NO_HTML,
        ],
        "company_siret" => [
            "require" => true,
            "regex" => RegExpChecker::ALPHA_NUMERIC,
        ],
        "company_tva" => [
            "require" => true,
            "regex" => RegExpChecker::ALPHA_NUMERIC,
        ],
    ];

    /**
     * @inheritDoc
     */
    public function createUser(array $data): array
    {
        $rules = self::CREATE_USER_FIELD_RULES;
        if (($data["statut"] ?? "") == "particulier") {
            $rules["company"]["require"] = false;
        }
        if (($data["country"] ?? "") != "FR") {
            $rules["company_siret"]["require"] = false;
        }
        if (($data["country"] ?? "") == "FR") {
            $rules["company_tva"]["require"] = false;
        }

        $fieldsChecker = FieldsChecker::create($data, $rules);
        if (!$fieldsChecker->isValid()) {
            throw new UserException($fieldsChecker->getErrorAsString());
        }

        try {
            return $this->post("/user", $data);
        } catch (Exception $exception) {
            throw new UserException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }

    /**
     * @param array $queryParam
     * @inheritDoc
     */
    public function getUser(array $queryParam): array
    {
        try {
            if (!(!empty($queryParam["email"]) || !empty($queryParam["id_user"]))) {
                throw new UserException("Required 'email' or 'id_user' param");
            }

            if (!empty($queryParam["email"]) && !RegExpChecker::isEmail($queryParam["email"])) {
                throw new UserException("Email provide is not a valid format");
            }

            return $this->get("/user", $queryParam);
        } catch (Exception $exception) {
            throw new UserException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}