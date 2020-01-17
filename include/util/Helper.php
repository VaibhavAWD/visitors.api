<?php

class Helper {

    /**
     * Http Status Codes
     */
    // 2xx
    const STATUS_OK = 200;
    const STATUS_CREATED = 201;

    // 4xx
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND = 404;
    const STATUS_CONFLICT = 409;

    // response message properties
    const ERROR  = "error";
    const MESSAGE = "message";

    public static function hasRequiredParams($params, $response) {
        $error = false;
        $error_params = "";
        $request_params = $_REQUEST;
        
        foreach ($params as $param) {
            if (!isset($request_params[$param]) || strlen(trim($request_params[$param])) <= 0) {
                $error = true;
                $error_params .= $param . ", ";
            }
        }

        if ($error) {
            $message[self::ERROR] = true;
            $message[self::MESSAGE] = "Required param(s) " . substr($error_params, 0, -2) . " is/are missing.";
            self::buildResponse(self::STATUS_BAD_REQUEST, $message, $response);
            return false;
        } else {
            return true;
        }
    }

    public static function buildResponse($status_code, $message, $response) {
        $response->withHeader('Content-type', 'application/json');
        $response->withStatus($status_code);
        return $response->write(json_encode($message));
    }

    public static function isValidEmail($email, $response) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message[self::ERROR] = true;
            $message[self::MESSAGE] = "Email is invalid. Please check and try again";
            self::buildResponse(self::STATUS_BAD_REQUEST, $message, $response);
            return false;
        } else {
            return true;
        }
    }

}

?>