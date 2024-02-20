<?php

namespace RegisteredMailApp\Helper;

use Exception;
use RuntimeException;

abstract class Tools
{
    public static function slugify($text, string $divider = '-'): ?string
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return null;
        }

        return $text;
    }


    public static function relativeProjectDir(): string
    {
        return dirname(__DIR__ . "/../../..");
    }

    public static function dataDir(string $dir): string
    {
        $dir = self::relativeProjectDir() . "/" . rtrim(ltrim($dir, "/"), "/");

        self::createDir($dir);

        return $dir;
    }
    public static function fileStorageDataDir(): string
    {
        return self::dataDir("/FileStorageData");
    }

    /**
     * @param string $dir
     * @return void
     */
    private static function createDir(string $dir): void
    {
        if (is_dir($dir)) {
            return;
        }

        try {
            mkdir($dir);
        } catch (Exception $exception) {
            throw new RuntimeException("Error to load dir : " . $exception->getMessage(), $exception->getCode());
        }
    }

}