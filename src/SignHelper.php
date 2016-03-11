<?php
namespace wxqy;

/**
 * 签名验签帮助类
 * @author fangl
 *
 */
class SignHelper {
    
    /**
     * 获取签名
     * @param string $token
     * @param string $timestamp
     * @param string $nonce
     * @param string $encrypt_msg
     * @return string sha1加密签名串
     */
    static function getSign($token, $timestamp, $nonce, $encrypt_msg) {
        $array = array($encrypt_msg, $token, $timestamp, $nonce);
        sort($array, SORT_STRING);
        $str = implode($array);
        $sha1 = sha1($str);
        return $sha1;
    }
    
    /**
     * 验证签名
     * @param string $msg_signature
     * @param string $token
     * @param string $timestamp
     * @param string $nonce
     * @param string $encrypt_msg
     * @return boolean
     */
    static function isSigValid($msg_signature, $token, $timestamp, $nonce, $encrypt_msg) {
        return strcmp(self::getSig($token, $timestamp, $nonce, $encrypt_msg),$msg_signature) == 0;
    }
    
}