<?php

namespace Slx\Config;

use Slx\Contract\FactoryInterface;

class ConfigFactory implements FactoryInterface
{
    static $configs = array();
    const TYPE_DEFAULT = "dotenv";

    public static function getInstance($type = self::TYPE_DEFAULT)
    {
        if ((!$type)) {
            $type = self::TYPE_DEFAULT;
        }
        if ((is_string($type)) && array_key_exists($type, self::$configs)) {
            return self::$configs[$type];
        }
        switch ($type) {
            case "dotenv":
                $dotenv = new DotEnvConfig(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);
                self::$configs["dotenv"] = $dotenv;
                return self::$configs["dotenv"];
            default:
                return false;
                break;
        }
    }
}