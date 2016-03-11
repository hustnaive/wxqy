<?php
namespace wxqy\crypt;

class PrpcryptError extends \Exception {

    const RESULT_LENGHT_LESS_THAN_16 = -101;
    const CORPID_IS_NOT_VALID = -102;

    public $code;
    public $message;
    public $line;
    public $file;
    public $trace;

    public function __construct($code, $message, $line=__LINE__, $file=__FILE__, $trace=[]) {
        $this->code = $code;
        $this->message = $message;
        $this->line = $line;
        $this->file = $file;
    }
}