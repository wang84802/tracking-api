<?php
namespace App\Exceptions;

use Log;

class AftershipException extends BaseException {
    protected $status_code = '400';
    protected $keyname;
    protected $code;
    public function __construct($tracking_number,$keyname,$code, $messages) {
        
        $this->messages = $messages;
        $this->keyname = $keyname;
        $this->code = $code;

        //        $this->messages = [
//            'tracking_number' => $tracking_number,
//            'aftership_error_message' => $messages
//        ];
    }
}
