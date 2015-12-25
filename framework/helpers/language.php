<?php
/**
 * LANGUAGE HELPER
 *
 *
 */

function lang($key){
    global $lang;
    if(isset($lang[$key])){
        return $lang[$key];
    }
}
