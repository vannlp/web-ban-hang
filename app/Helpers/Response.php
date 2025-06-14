<?php
namespace App\Helpers;


class Response {
    public static function success($message, $data = []) {
        return response()->json([
            'message'=> $message,
            'data' => $data
        ], 200);
    }
    
    public static function fail($message, $status = 400) {
        return response()->json([
            'message'=> $message,
        ], $status);
    }
}