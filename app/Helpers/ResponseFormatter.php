<?php

namespace app\Helpers;

class ResponseFormatter {
    static function response($code, $msg, $data){
        return [
            'meta' => ['status : ' . $code, 'message : ' . $msg],
            'data' => $data
        ];
    }
}
