<?php
namespace module\indexer;

class Module extends \yii\base\Module
{
    public $urlRules = [];

    public function initConfigs()
    {
        $configFile = $this->getBasePath().'/etc/config.php';
        if(file_exists($configFile)) {
            $configValues = include($configFile);
            foreach($configValues as $name=>$values) {
                $this->$name = $values;
            }
        }

        \WS::$app->getUrlManager()->addRules($this->urlRules);
    }
}