<?php
namespace module\cms\helpers;

class UrlParamEncoder
{
    public static $inst = null;
    protected $_map = [];
    protected $_values = [];

    public function __construct($params, $map)
    {
        $this->_map = $map;
        $this->_values = $this->parse($params);
        $_GET = array_merge($_GET, $this->_values);
    }

    public static function setup($params, $map)
    {
        return self::$inst = new self($params, $map);
    }

    // 解码
    public function parse($params)
    {
        $paramValues = [];
        $params = explode('_', $params);
        foreach($params as $param) {
            if (count(explode('-', $param)) === 2) {
                list($key, $value) = explode('-', $param);
                $paramValues[$key] = $value;
            }
        }

        $values = [];
        foreach($this->_map as $name=>$shortName) {
            if (isset($paramValues[$shortName])) {
                $values[$name] = $paramValues[$shortName];
            }
        }
        return $values;
    }

    // 编码
    public function encode($values)
    {
        $varr = [];
        foreach($values as $name=>$value) {
            if(isset($this->_map[$name]) && $value !== null) {
                $key = $this->_map[$name];
                array_push($varr, $key.'-'.$value);
            }
        }
        return join('_', $varr);
    }

    // 添加一个参数
    public function setParam($key, $value, $replace = false)
    {
        $values = [$key=>$value];
        if (!$replace) {
            $values = array_merge($this->_values, $values);
        }

        //粗暴地清除分页
        if ($key !== 'page' && isset($_GET['page'])) {
            unset($values['page']);
        }
        
        return $this->encode($values);
    }
}