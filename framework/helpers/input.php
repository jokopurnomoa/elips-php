<?php
/**
 * Input helper
 *
 *
 */

/**
 * Get Method Input
 *
 * @param $name
 * @param bool $xss_clean
 * @return null|string
 */
function get_input($name, $xss_clean = false){
    if(isset($_GET[$name]) && $name != null){
        if($xss_clean){
            return Security::xssFilter($_GET[$name]);
        } else {
            return $_GET[$name];
        }
    }
    return null;
}

/**
 * Post Method Input
 *
 * @param $name
 * @param bool $xss_clean
 * @return null|string
 */
function post_input($name, $xss_clean = false){
    if(isset($_POST[$name]) && $name != null){
        if($xss_clean) {
            return Security::xssFilter($_POST[$name]);
        } else {
            return $_POST[$name];
        }
    }
    return null;
}

/**
 * Post & Get Method Input (Post First)
 *
 * @param $name
 * @param bool $xss_clean
 * @return null
 */
function post_get_input($name, $xss_clean = false){
    if(isset($_POST[$name])){
        return post_input($name, $xss_clean);
    } else {
        return get_input($name, $xss_clean);
    }
    return null;
}
