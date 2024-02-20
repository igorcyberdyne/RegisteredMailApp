<?php

namespace RegisteredMailApp\Tests\Gateway\AR24\Impl;

use Exception;
use RegisteredMailApp\Entity\ApiAccess;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Repository\Impl\ApiAccessRepository;

trait HelperTrait
{
    protected $filenameGenerated = "";
    /**
     * @throws Exception
     */
    public function createMockForApiAccessRepositoryInterface()
    {
        $repository = new ApiAccessRepository();
        try {
            $repository->findAR24ApiAccess();
        } catch (ResourceNotFoundException $exception) {
            if (empty($apiAccess)) {
                $repository = $repository->saveAR24ApiAccess(
                    "57027783d6a71f2bf0a89afe7a7d5227e2f0b19b",
                    "58ef357e06af9ddb42eec4c1a77c9ac811a48b7a",
                    "https://sandbox.ar24.fr/api"
                );
            }
        }

        return $repository;
    }

    public function generateTextFile()
    {
        $this->filenameGenerated = sys_get_temp_dir()."\\text-file-test-" . uniqid() . ".txt";
        file_put_contents($this->filenameGenerated, "Ceci est un fichier de test");

        return $this->filenameGenerated;
    }

    public function removeGenerateTextFile()
    {
        if (file_exists($this->filenameGenerated)) {
            return;
        }

        unlink($this->filenameGenerated);
    }
}