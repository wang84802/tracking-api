<?php
namespace App\Exceptions;

class MixedNumberNotFoundException extends BaseException {
    protected $status_code = '400';
    protected $keyname = 'mixed_number';
    protected $code = '400069104';
    protected $messages = "The ols_key or tracking_number is incorrect.";
}
