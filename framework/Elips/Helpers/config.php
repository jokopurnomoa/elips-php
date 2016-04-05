<?php
/**
 * Config Helper
 *
 * Get Config Item
 *
 */

/**
 * @param string $item
 * @return mixed | null
 */
function get_app_config($item, $sub_item = null, $sub_sub_item = null){
    $config = $GLOBALS['config'];

    if($sub_sub_item != null && $sub_item != null && isset($config[$item][$sub_item][$sub_sub_item])){
        return $config[$item][$sub_item][$sub_sub_item];
    }
    elseif($sub_item != null && isset($config[$item][$sub_item])){
        return $config[$item][$sub_item];
    }
    elseif(isset($config[$item])){
        return $config[$item];
    }
    return null;
}