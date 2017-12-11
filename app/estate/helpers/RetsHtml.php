<?php
namespace module\estate\helpers;

use module\estate\widgets\search\Result;
use yii\helpers\ArrayHelper;

class RetsHtml
{
    public static function parseValueHtml($result)
    {
        $prefixHtml = '';
        $suffixHtml = '';

        if(is_null($result['value']) || $result['value'] === '') {
            $result['value'] = tt('Unknown', '未提供');
        }
        else {
            if(isset($result['prefix'])) {
                $prefixHtml = "<span class=\"attr-prefix\">{$result['prefix']}</span>";
            }
            if(isset($result['suffix'])) {
                $suffixHtml = "<span class=\"attr-suffix\">{$result['suffix']}</span>";
            }
        }

        if (is_array($result['value'])) {
            $valueItems = [];
            foreach ($result['value'] as $value) {
                $valueItems[] = '<span class="v-item">'.$value.'</span>';
            }
            $result['value'] = implode('', $valueItems);
        }

        return "
            <span class=\"attr-value-box attr-{$result['id']}-box\">
                {$prefixHtml}<span class=\"attr-value\">{$result['value']}</span>{$suffixHtml}
            </span>
        ";
    }

    public static function parseFieldHtml($result)
    {
        $formatedValueHtml = self::parseValueHtml($result);
        $containerTag = 'div';

        return "<{$containerTag} class=\"attr-field attr-field-{$result['id']}\">
                    <label class=\"attr-label\">{$result['title']}</label>{$formatedValueHtml}
                </{$containerTag}>";
    }

    public static function getValueHtml($model, $field, $options=[])
    {
        $data = $model->getFieldData($field, $options);
        return self::parseValueHtml($data);
    }

    public static function getFieldHtml($model, $field, $options=[])
    {
        $data = $model->getFieldData($field, $options);
        return self::parseFieldHtml($data);
    }
}