<?php
namespace tests\wxqy;

class MsgHelperTest extends \PHPUnit_Framework_TestCase {
    
    public function testEncryptDecryptMsg() {
        $msg_xml = '<xml>
   <ToUserName><![CDATA[toUser]]></ToUserName>
   <FromUserName><![CDATA[fromUser]]></FromUserName> 
   <CreateTime>1348831860</CreateTime>
   <MsgType><![CDATA[text]]></MsgType>
   <Content><![CDATA[this is a test]]></Content>
</xml>';
        $msgHelper = new \wxqy\MsgHelper('QBf1mHO3wZAnyejEH6ljir08yVs8ojHIjKnhzGjuAua', '0123456789', 'test');
        $encryptMsg = $msgHelper->encryptMsg($msg_xml);
        echo $encryptMsg;
        var_dump($msgHelper->decryptMsg($encryptMsg));
        
    }
}