<?php
/**
 * Validate Library
 *
 *
 */

class Validate {

    /**
     * Validate Email
     *
     * @param $email
     * @return mixed
     */
    public static function email($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validate URL
     *
     * @param $url
     * @return mixed
     */
    public static function url($url){
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Validate IP Address
     *
     * @param $ip
     * @return mixed
     */
    public static function ip($ip){
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /**
     * Validate Mac Address
     *
     * @param $mac
     * @return mixed
     */
    public static function mac($mac){
        return filter_var($mac, FILTER_VALIDATE_MAC);
    }

    /**
     * Validate Boolean
     *
     * @param $boolean
     * @return mixed
     */
    public static function boolean($boolean){
        return filter_var($boolean, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Validate Float
     *
     * @param $float
     * @return mixed
     */
    public static function float($float){
        return filter_var($float, FILTER_VALIDATE_FLOAT);
    }

    /**
     * Validate Integer
     *
     * @param $int
     * @return mixed
     */
    public static function int($int){
        return filter_var($int, FILTER_VALIDATE_INT);
    }

    /**
     * Validate Regular Expression
     *
     * @param $reg_exp
     * @return mixed
     */
    public static function regExp($reg_exp){
        return filter_var($reg_exp, FILTER_VALIDATE_REGEXP);
    }

    /**
     * Validate Minimum Length
     *
     * @param $string
     * @param $min_length
     * @return bool
     */
    public static function minLength($string, $min_length){
        return strlen($string) >= $min_length ? true : false;
    }

    /**
     * Validate Maximum Length
     *
     * @param $string
     * @param $max_length
     * @return bool
     */
    public static function maxLength($string, $max_length){
        return strlen($string) <= $max_length ? true : false;
    }

}
