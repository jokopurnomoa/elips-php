<?php
/**
 * CURL Library
 *
 *
 */
class CURL {

    /**
     * CURL Get Request
     *
     * @param $url
     * @param int $timeout
     * @return mixed
     */
    public static function get($url, $timeout = 30){
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curlHandle, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
        curl_setopt($curlHandle, CURLOPT_POST, 0);
        $result = curl_exec($curlHandle);
        curl_close($curlHandle);
        return $result;
    }

    /**
     * CURL Post Request
     *
     * @param $url
     * @param array $post_field
     * @param int $timeout
     * @return mixed
     */
    public static function post($url, $post_field = array(), $timeout = 30){
        $_post_field = '';
        if($post_field != null){
            if(is_array($post_field)){
                $_i = 0;
                foreach($post_field as $key => $val){
                    if($_i === 0){
                        $_post_field .= $key . '=' . $val;
                    } else {
                        $_post_field .= '&' . $key . '=' . $val;
                    }

                    $_i++;
                }
            } else {
                $_post_field = $post_field;
            }
        }

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $_post_field);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curlHandle, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $result = curl_exec($curlHandle);
        curl_close($curlHandle);
        return $result;
    }

}
