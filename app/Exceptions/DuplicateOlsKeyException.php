<?php
namespace App\Exceptions;

class DuplicateOlsKeyException extends BaseException {
    protected $status_code = '400';
    protected $keyname = 'ols_key';
    protected $code = '400069049';

    public function __construct($ols_key) {
        $this->messages = "The 'ols_key' $ols_key in the bulk request is duplicated.";
    }
}
