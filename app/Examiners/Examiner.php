<?php
namespace App\Examiners;

use Log;
use Validator;

use Illuminate\Http\Exceptions\HttpResponseException;
use App\Exceptions\ValidateException;
use App\Exceptions\DataTagNotFoundException;
use App\Exceptions\ValidationFailedException;
use App\Exceptions\AftershipException;
use App\Exceptions\DuplicateOlsKeyException;
use App\Exceptions\MixedNumberNotFoundException;

class Examiner {
    public static function examineDataTag($condition) {
        $exception = new DataTagNotFoundException;
        self::reportExceptionOrNot($condition, $exception);
    }

    public static function examineRequests($data, $rule) {
        
        $validator = Validator::make($data, $rule);
        $condition = !$validator->fails();
        
        /* 20190215-Chris : Error_code integrated */
        $errors = $validator->failed();
        if ($errors == NULL)
            return NULL;
        $keyname = key($errors);
        $serviceCode = config('error_code.service_code');
        $errorCode = array_merge(config('error_code.custom'),config('error_code.base'));
        $code = "400{$serviceCode}{$errorCode[snake_case(key(array_first($errors)))]}";
        $messages = $validator->errors()->first();

        throw new HttpResponseException(response()->json(
            [
                'status' => 400,
                'error' => [[
                    'key' => $keyname,
                    'code' => "400{$serviceCode}{$errorCode[snake_case(key(array_first($errors)))]}",
                    'message' => $validator->messages()->first()
                ]],
            ]
            , 400));
        ////////////////////////////////////////////
    }

    public static function aftershipResponse($tracking_number, $response) {

        if (empty($response))
            throw new AftershipException($tracking_number,'response','400069107', 'empty response');
        if (empty($response['data']['tracking']))
        {
            if(strpos($response,'slug')||strpos($response,'courier'))
                throw new AftershipException($tracking_number,'slug','400069106', $response);
            if(strpos($response,'Tracking'))
                throw new AftershipException($tracking_number, 'tracking', '400069108', $response);
            else
                throw new AftershipException($tracking_number,'tracking_number','400069105', $response);
        }
    }

    public static function duplicateOlsKey($trackings) {
        $ols_keys = [];
        foreach ($trackings as $tracking)
            array_push($ols_keys, $tracking['ols_key']);

        $ols_key_total = array_count_values($ols_keys);
        foreach ($ols_key_total as $ols_key => $number)
            if ($number > 1)
                throw new DuplicateOlsKeyException($ols_key);
    }

    public static function mixedNumberNotFound($condition) {
        if (!$condition)
            throw new MixedNumberNotFoundException;
    }

    protected static function reportExceptionOrNot($condition, $exception) {
        if (empty($condition)) throw $exception;
    }

    public static function CheckpointInvalid($input) {
        if($input['status'] != 'OlsReceived' && $input['status'] != 'OlsProcessed')
            throw new ValidationFailedException('checkpoint_status','400069109', 'Checkpoint_status is invalid.');
    }
}
