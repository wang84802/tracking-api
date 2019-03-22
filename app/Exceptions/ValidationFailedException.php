<?php
namespace App\Exceptions;

class ValidationFailedException extends BaseException {
    protected $status_code = '400';

    public function __construct($keyname,$code,$messages) {
        $this->keyname = $keyname;
        $this->code = $code;
        $this->messages = $messages;
    }
}
