<?php


namespace RegisteredMailApp\Helper;

class FieldsChecker
{
    /** @var array */
    protected $errors = [];

    /** @var array */
    protected $missingFunctions = [];

    /** @var array */
    protected $fieldRules = [];

    /** @var array */
    protected $data = [];
    private $errorAsString = "";

    public function __construct(
        array $data,
        array $fieldRules
    )
    {
        $this->data = $data;
        $this->fieldRules = $fieldRules;
    }

    /**
     * @param array $data
     * @param array $fieldRules
     * @return FieldsChecker
     */
    public static function create(
        array $data,
        array $fieldRules
    ): FieldsChecker
    {
        return new FieldsChecker($data, $fieldRules);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        foreach ($this->fieldRules as $field => $values) {
            $values["value"] = $this->data[$field] ?? null;
            $currentRegex = $this->fieldRules[$field]["regex"] ?? null;

            if (!empty($values["value"])) {
                $functionAndErrorLabel = RegExpChecker::getFunctionAndErrorLabelByRegex($currentRegex);
                $currentFunc = $functionAndErrorLabel["functionName"];

                if (empty($currentFunc)) {
                    // Must define function checker for the regex given
                    $this->missingFunctions[$field] = $currentRegex;
                    continue;
                }

                if (!RegExpChecker::$currentFunc($values["value"])) {
                    $this->errorAsString = "Field '$field' {$functionAndErrorLabel["errorLabel"]}";
                }

                $valueIn = $this->fieldRules[$field]["valueIn"] ?? null;
                if (!empty($valueIn) && !in_array($values["value"], $valueIn)) {
                    $this->errorAsString = "Field '$field' required value in (" . implode(",", $valueIn) .")" ;
                }

            } elseif (!empty($this->fieldRules[$field]["require"])) {
                $this->errorAsString = "Field '$field' is required";
            }

            if (!empty($this->errorAsString)) {
                return false;
            }
        }

        return true;
    }


    public function getErrorAsString(): string
    {
        return $this->errorAsString;
    }
}