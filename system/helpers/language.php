<?php
/**
 * LANGUAGE HELPER
 *
 *
 */

function lang($key){
    global $___lang;
    if(isset($___lang[$key])){
        return $___lang[$key];
    }
}
