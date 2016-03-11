<?php
namespace tests\crypt;

class PrpcryptTest extends \PHPUnit_Framework_TestCase {
    
    public function testEncodeDecode() {
        $prpcrypt = new \wxqy\crypt\Prpcrypt('QBf1mHO3wZAnyejEH6ljir08yVs8ojHIjKnhzGjuAua');
        $encryptedText = $prpcrypt->encrypt('abcdefghijklmn', '1234567890');
        $this->assertEquals('abcdefghijklmn', $prpcrypt->decrypt($encryptedText, '1234567890'));
    }
}