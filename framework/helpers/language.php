<?php
/**
 * Language Helper
 *
 *
 */

/**
 * Get Language By Key
 *
 * @param $key
 * @return mixed
 */
function lang($key){
    global $lang;
    if(isset($lang[$key])){
        return $lang[$key];
    }
}
