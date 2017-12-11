<?php
namespace module\home;

class Module extends \module\core\Module
{
    public $urlRules = [];

    public function getConfigs($name)
    {
        $file = dirname(__FILE__).'/etc/'.$name.'.php';
        return include($file);
    }
}