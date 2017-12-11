<?php
namespace module\indexer\models;

use WS;

class Index
{
    public static function writeOne($table, $data, $id)
    {
        $oldData = WS::$app->search->createCommand('select * from rt_rets where id='.$id)->queryOne();
        $data = array_merge($oldData, $data);

        $columns = [];
        $valueVars = [];
        $values = [];

        foreach($data as $key=>$value) {
            $columns[] = $key;
            $valueVars[] = ':'.$key;
            $values[':'.$key] = $value;
        }
        $columns = implode(',', $columns);
        $valueVars = implode(',', $valueVars);

        $sql = "REPLACE INTO {$table}({$columns}) VALUES({$valueVars})";

        $code = WS::$app->search->createCommand($sql)->bindValues($values)->execute();
    }

    public static function initFields()
    {
        $oldFields = WS::$app->search->createCommand('desc rt_rets')->queryColumn();
        $allFields = include(dirname(__FILE__).'/../etc/index-field-type-map.php');

        foreach($allFields as $fieldName=>$dataType) {
            if(! in_array($fieldName, $oldFields)) {
                self::_addField($fieldName, $dataType);
            }
        }

        return true;
    }

    protected static function _addField($name, $type)
    {
        WS::$app->search->createCommand("alter table rt_rets add column {$name} {$type}")->execute();
    }

    protected static function _dropField($name)
    {
        WS::$app->search->createCommand("alter table rt_rets drop column {$name}")->execute();
    }
}