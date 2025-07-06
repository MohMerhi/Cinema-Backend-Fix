<?php

class ResponseService {

    public static function success_response($payload){
        $response = [];
        $response["status"] = 200;
        $response["payload"] = $payload;
        return json_encode($response);
    }

    public static function created($payload){
        $response = [];
        $response["status"] = 201;
        $response["payload"] = $payload;
        return json_encode($response);
    }

    public static function OK(){
        $response = [];
        $response["status"] = 202;
        return json_encode($response);
    }

    
    public static function not_found($value){
        $response = [];
        $response["status"] = 404;
        $response["error"] = $value . " not found";
        return json_encode($response);
    }

    public static function no_response(){
        $response = [];
        $response["status"] = 204;
        return json_encode($response);
    }

    public static function error_message($message){
        $response = [];
        $response["status"] = 400;
        $response["message"] = $message;
        return json_encode($response);
    }

    

}