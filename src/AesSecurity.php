<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2019/1/14
 * Time: 9:57 AM
 * AesSecurity aes加密，支持PHP7.1
 */

class AesSecurity
{

    /**
     * 加密
     * @param $input 要加密的数据
     * @param $key 加密key
     * @return string 加密后的数据
     */
    public static function encrypt($input, $key)
    {
        $data = openssl_encrypt($input, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * 解密
     * @param $sStr 要解密的数据
     * @param $sKey 加密key
     * @return string 解密后的数据
     */
    public static function decrypt($sStr, $sKey)
    {
        $decrypted = openssl_decrypt(base64_decode($sStr), 'AES-128-ECB', $sKey, OPENSSL_RAW_DATA);
        return $decrypted;
    }
}
