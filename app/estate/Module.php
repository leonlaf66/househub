<?php
namespace module\estate;

use module\core as Core;

class Module extends Core\Module
{
	public function getConfigs($name)
    {
        $file = dirname(__FILE__).'/etc/'.$name.'.php';
        return include($file);
    }
}