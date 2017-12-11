<?php
namespace module\estate\models;

class House extends \common\listhub\estate\House
{
    public function getUrl()
    {
        if ($this->prop_type === 'RN') {
            return '/lease/'.$this->id.'/';
        }
        return '/purchase/'.$this->id.'/';
    }
}