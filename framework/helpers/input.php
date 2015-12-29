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
 * @return null
 */
function get_input($name){
    if(isset($_GET[$name])){
        return $_GET[$name];
    }
    return null;
}

/**
 * Post Method Input
 *
 * @param $name
 * @return null
 */
function post_input($name){
    if(isset($_POST[$name])){
        return $_POST[$name];
    }
    return null;
}
