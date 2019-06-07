<?php
namespace Slixa\Template;
use Slixa\Contract\FactoryInterface;
use Slixa\Config\ConfigFactory;
use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class TemplateFactory implements FactoryInterface
{
    static $templateHandlers = array();
    static $configType = ConfigFactory::TYPE_DEFAULT;
    const TYPE_DEFAULT = "twig";
    public static function getInstance($type = self::TYPE_DEFAULT, $configType = null) {
        if ((!$type)) {
            $type = self::TYPE_DEFAULT;
        }
        if ($configType) {
            self::$configType = $configType;
        }
        if ((is_string($type)) && array_key_exists($type,self::$templateHandlers)) {
            return self::$templateHandlers[$type];
        }
        switch ($type) {
            case "twig":
                return self::getTwigTemplateHandler($type);
            default:
                return false;
                break;
        }
    }

    protected static function getTwigTemplateHandler($type) {
        $config = ConfigFactory::getInstance(self::$configType);
        $loader = new FilesystemLoader($config->TemplatePath);
        $twig = new Environment($loader, [
            'cache' => $config->TemplateCompilationCache,
        ]);
        self::$templateHandlers[$type] = $twig;
        return $twig;
    }
}