<?php

namespace RegisteredMailApp\Helper;


abstract class RegExpChecker
{
    const ALPHA_NUMERIC = '/^[0-9a-z\_\-\s]+$/i';

    /**
     * Expression régulière de mail - PhpMailer
     * @var String
     */
    const EMAIL = '/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/';

    /**
     * Expression régulière pour un chiffre/nombre
     * @var String
     */
    const NUMERIC = '/^[0-9]+$/';

    /**
     * Expression régulière des chaines de caractéres interdisant le <html>
     * @var String
     */
    const TEXT_NO_HTML = '/^[^\<\>]{1,}$/i';

    private static $capture = [];

    public static function check($value, $regExp): bool
    {
        if (empty($value) || empty($regExp)) {
            self::$capture = [
                ["Param is empty"]
            ];
            return false;
        }

        return preg_match($regExp, $value, self::$capture, PREG_OFFSET_CAPTURE) == 1;
    }

    static public function isNumeric(string $value): bool
    {
        return self::check($value, self::NUMERIC);
    }

    public static function isTextNoHtml($value): bool
    {
        return self::check($value, self::TEXT_NO_HTML);
    }

    public static function isAlphaNumeric($value): bool
    {
        return self::check($value, self::ALPHA_NUMERIC);
    }

    public static function isEmail($value): bool
    {
        return self::check($value, self::EMAIL);
    }

    public static function getFunctionAndErrorLabelByRegex(string $regex): array
    {
        if (empty($regex)) {
            return [
                "functionName" => null,
                "errorLabel" => null,
            ];
        }

        $funcName = "";
        $errorName = "";
        switch ($regex) {
            case self::TEXT_NO_HTML:
                $funcName = "isTextNoHtml";
                $errorName = "is not a text";
                break;
            case self::NUMERIC:
                $funcName = "isNumeric";
                $errorName = "is not a numeric";
                break;
            case self::ALPHA_NUMERIC:
                $funcName = "isAlphaNumeric";
                $errorName = "is not alpha-numeric";
                break;
            case self::EMAIL:
                $funcName = "isEmail";
                $errorName = "is not valid email";
                break;
        }

        return [
            "functionName" => $funcName,
            "errorLabel" => $errorName,
        ];
    }
}