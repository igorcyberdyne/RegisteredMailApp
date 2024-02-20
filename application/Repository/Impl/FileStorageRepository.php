<?php

namespace RegisteredMailApp\Repository\Impl;

use Exception;
use RegisteredMailApp\Helper\Tools;

abstract class FileStorageRepository
{
    private $store;
    private $storeFilename;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->storeFilename = Tools::fileStorageDataDir() . "/" . Tools::slugify(get_called_class()) . ".json";

        $this->loadStore();
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function loadStore(): void
    {
        $this->store = [];

        if (!file_exists($this->storeFilename)) {
            return;
        }

        try {
            $content = json_decode(file_get_contents($this->storeFilename), true);
            if (!is_array($content)) {
                throw new Exception("Store is empty");
            }

            $this->store = $content;
        } catch (Exception $exception) {
            throw new Exception("Error on loading store", $exception->getCode());
        }
    }

    /**
     * @return array
     */
    protected function getStore(): array
    {
        return $this->store;
    }

    /**
     * @param array $store
     * @throws Exception
     */
    protected function setStore(array $store): void
    {
        $this->store = empty($store) ? [] : array_merge($this->store, $store);

        $this->saveStore();
    }

    /**
     * @throws Exception
     */
    private function saveStore(): void
    {
        try {
            file_put_contents($this->storeFilename, json_encode($this->store));
        } catch (Exception $exception) {
            throw new Exception("Error on saving in file storage", $exception->getCode());
        }
    }

}