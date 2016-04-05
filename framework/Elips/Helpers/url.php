<?php
/**
 * URL Helper
 *
 *
 */

/**
 * Get application base url
 *
 * @return string
 */
function base_url(){
    $base_url = app_config('base_url');
    if($base_url !== ''){
        return trim($base_url, '/') . '/';
    }

    $base_dir = explode('/', strrev(trim(__DIR__, '/')));

    $arrDir = array();
    if ($base_dir != null) {
        foreach ($base_dir as $key => $val) {
            $val = strrev($val);
            if ($val === 'public_html' || $val === 'htdocs' || $val === 'www') {
                break;
            }
            if ($key >= 2) {
                $arrDir[] = trim($val);
            }
        }
    }

    $dir = implode('/', array_reverse($arrDir));
    return 'http' . (isset($_SERVER["HTTPS"]) ? $_SERVER["HTTPS"] == 'on' ? 's' : '' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . trim($dir) . '/';
}
