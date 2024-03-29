<?php
namespace Slx\Config;
use Dotenv\Dotenv;
class DotEnvConfig
{
    public function __construct($directory)
    {
        $dotenv = Dotenv::create($directory);
        $dotenv->load();
    }
    public function __get($name)
    {
        return getenv(strtoupper($this->from_camel_case($name)));
    }
    function from_camel_case($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}