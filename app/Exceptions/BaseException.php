<?php
namespace App\Exceptions;

use Log;
class BaseException extends \Exception {
    protected $status_code;
    protected $messages;
    
    public function report() {

        $response = $this->makeErrorResponse();
        return response()->json($response);
    }

    protected function makeErrorResponse() {
        return [
            'status' => $this->status_code,
            'error' => [[
                'key' => $this->keyname,
                'code' => $this->code,
                'messages' => $this->messages
            ]]
        ];
    }
}
