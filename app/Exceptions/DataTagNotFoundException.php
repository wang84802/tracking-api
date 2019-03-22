<?php
namespace App\Exceptions;

class DataTagNotFoundException extends BaseException {
    protected $status_code = '403';
    protected $messages = ['data' => ["The 'data' format is incorrect."]];
}
