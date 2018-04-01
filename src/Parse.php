<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/18
 * Time: 下午7:34
 */

namespace PHPUtils;

class Parse
{
    public static function syntax($root, $subfix = '.php')
    {
        $files = File::listDir($root, $subfix);

        $total = count($files);
        try {
            $i = 0;

            foreach ($files as $filePath) {

                $status = 0;
                exec('php -l ' . $filePath, $output, $status);

                $status !== 0 && $i++;

            }

            echo "Syntax error/total number : {$i}/{$total}  \n";

        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function json($config)
    {
        if (is_file($config)) {
            $config = file_get_contents($config);
        }
        $result = json_decode($config, true);
        return $result;
    }

    public static function ini($config)
    {
        if (is_file($config)) {
            return parse_ini_file($config, true);
        } else {
            return parse_ini_string($config, true);
        }
    }

    public static function xml($config)
    {
        if (is_file($config)) {
            $content = simplexml_load_file($config);
        } else {
            $content = simplexml_load_string($config);
        }
        $result = (array)$content;
        foreach ($result as $key => $val) {
            if (is_object($val)) {
                $result[$key] = (array)$val;
            }
        }
        return $result;
    }

}