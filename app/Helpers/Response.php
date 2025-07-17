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
    
    public static function successAndRedirect($to = null, $message = '') {
        if($to) {
            return redirect()->route($to)->with('success', $message);  
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public static function failAndRedirect($to = null, $message = '') {
        if($to) {
            return redirect()->route($to)->with('error', $message);  
        }
        
        return redirect()->back()->with('error', $message);
    }
}