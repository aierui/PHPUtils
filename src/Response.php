<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/30
 * Time: 上午8:25
 */

namespace PHPUtils;

class Response
{

    protected static $meta = [];


    // 输出参数
    protected static $options = [
        // 根节点名
        'root_node' => 'root',
        // 根节点属性
        'root_attr' => '',
        //数字索引的子节点名
        'item_node' => 'item',
        // 数字索引子节点key转换的属性名
        'item_key' => 'id',
        // 数据编码
        'encoding' => 'utf-8',
    ];

    protected static $contentType = 'text/xml';


    /**
     * 重定向
     * @param $url string
     */
    public static function redirect($url)
    {
        @header('Cache-Control: no-cache, no-store');
        @header('Location: ' . $url);
    }

    /**
     * 设置http响应头
     * @param $name string
     * @param $value mixed
     */
    public static function header($name, $value)
    {
        @header("$name:$value", false);
    }


    public static function outJson($code, $message = '', $data = NULL, $return_string = false)
    {
        ($data === NULL) && $data = new \stdClass();
        $json_string = json_encode(array_merge(self::$meta, [
            "code" => $code,
            "msg" => strval($message),
            "data" => $data,
            "time" => time(),
        ]));
        if ($return_string) {
            return $json_string;
        } else {
            echo $json_string;
        }
        exit();
    }

    public static function outXml($data)
    {
        return self::xmlEncode($data, self::$options['root_node'], self::$options['item_node'], self::$options['root_attr'], self::$options['item_key'], self::$options['encoding']);
    }


    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id 数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    protected static function xmlEncode($data, $root, $item, $attr, $id, $encoding)
    {
        if (is_array($attr)) {
            $array = [];
            foreach ($attr as $key => $value) {
                $array[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $array);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= self::dataToXml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id 数字索引key转换为的属性名
     * @return string
     */
    protected static function dataToXml($data, $item, $id)
    {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $id && $attr = " {$id}=\"{$key}\"";
                $key = $item;
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= (is_array($val) || is_object($val)) ? self::dataToXml($val, $item, $id) : $val;
            $xml .= "</{$key}>";
        }
        return $xml;
    }


}