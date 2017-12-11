<?php
namespace module\indexer\models;

class Rets
{
    protected $_data = null;

    public static function init($data)
    {
        static $instance = null;
        if(is_null($instance)) {
            $instance = new self();
        }
        return $instance->setData($data);
    }

    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    public function get($name, $defValue='')
    {
        $dataType = gettype($defValue);

        $returnValue = isset($this->_data->$name) ? $this->_data->$name : '';
        if(is_null($returnValue) || $returnValue === '') $returnValue = $defValue;

        switch ($dataType) {
            case 'integer':
                $returnValue = intval($returnValue);
                break;
            case 'double':
                $returnValue = floatval($returnValue);
                break;
        }

        return $returnValue;
    }
}