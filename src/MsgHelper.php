<?php
namespace wxqy;

/**
 * 回调消息加解密帮助类
 * @author fangl
 *
 */
class MsgHelper {
    
    private $_aeskey;
    private $_corpid;
    private $_token;
    
    /**
     * 
     * @param string $aeskey
     * @param string $corpid
     * @param string $token
     */
    public function __construct($aeskey, $corpid, $token) {
        $this->_aeskey = $aeskey;
        $this->_corpid = $corpid;
    }
    
    private function toObj($msg_xml) {
        return simplexml_load_string ( $msg_xml, 'SimpleXMLElement', LIBXML_NOCDATA );
    }
    
    private function toStr($msg_obj) {
        return is_object($msg_obj)?json_encode($this->objectToArray($msg_obj)):$msg_obj;
    }
    
    private function objectToArray($obj) {
        $_arr= is_object($obj) ? get_object_vars($obj) : $obj;
        foreach($_arr as $key=> $val)
        {
            $val= (is_array($val) || is_object($val)) ? $this->objectToArray($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }
    
    /**
     * 解密微信post消息体
     * @param string|obj $msg 如果解密不成功时，通过$msg返回解密后的内容
     * @return SimpleXMLElement|NULL
     */
    public function decryptMsg($msg) {
        if(!is_object($msg)) {
            $msg_obj = $this->toObj($msg);
        }
        else $msg_obj = $msg;
        if(!isset($msg_obj->MsgSignature,$msg_obj->TimeStamp,$msg_obj->Nonce,$msg_obj->Encrypt)) {
            throw new \Exception('msg is a not valid wxqy msg string');
        }
        else {
            $sign = SignHelper::getSign($this->_token, $msg_obj->TimeStamp, $msg_obj->Nonce, $msg_obj->Encrypt);
            if(strcmp($sign, $msg_obj->MsgSignature) !== 0) {
                throw new \Exception('sign is not valid');
            }
            $prpcrypt = new \wxqy\crypt\Prpcrypt($this->_aeskey);
            return $this->toObj($prpcrypt->decrypt($msg_obj->Encrypt, $this->_corpid, $this->_corpid != false));
        }
    }
    
    /**
     * 加密待回复给微信的消息体
     * @param string $msg_xml_str
     * @param string $corpid
     * @param string $aeskey
     * @param string $token
     * @param string $timestamp
     * @param string $nonce
     * @return string|NULL
     */
    public function encryptMsg($msg_xml_str) {
        $timestamp = time();
        $nonce = uniqid();
        $prpcrypt = new \wxqy\crypt\Prpcrypt($this->_aeskey);
        $encrypt = $prpcrypt->encrypt($msg_xml_str, $this->_corpid);
        $signature = SignHelper::getSign($this->_token, $timestamp, $nonce, $encrypt);
        $format = '<xml><Encrypt><![CDATA[%s]]></Encrypt><MsgSignature><![CDATA[%s]]></MsgSignature><TimeStamp>%s</TimeStamp><Nonce><![CDATA[%s]]></Nonce></xml>';
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }
}