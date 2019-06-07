<?php
namespace Slixa\Database;
use Slixa\Contract\FactoryInterface;
use Slixa\Config\ConfigFactory;
class DatabaseFactory implements FactoryInterface
{
    static $databases = array();
    static $configType = ConfigFactory::TYPE_DEFAULT;
    const TYPE_DEFAULT = "pdo";
    public static function getInstance($type = self::TYPE_DEFAULT, $configType = null) {
        if ((!$type)) {
            $type = self::TYPE_DEFAULT;
        }
        if ($configType) {
            self::$configType = $configType;
        }
        if ((is_string($type)) && array_key_exists($type,self::$databases)) {
            return self::$databases[$type];
        }
        switch ($type) {
            case "pdo":
                return self::getPdoDb($type);
            default:
                return false;
                break;
        }
    }

    protected static function getPdoDb($type) {
        $config = ConfigFactory::getInstance(self::$configType);
        try{
            $myPDO = new \PDO( "mysql:host=" . $config->DatabaseHost . ";dbname=" . $config->DatabaseName . "",
                $config->DatabaseUsername,
                $config->DatabasePassword,
                array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        }
        catch(\PDOException $ex){
            die("ERROR: Unable to connect");
        }
        self::$databases[$type] = $myPDO;
        return $myPDO;
    }
}