<?php
/**
 * ERROR
 *
 * Show error message
 *
 */

function errorDump($message){
    echo '<pre style="font-family: \'Courier New\', Monospace;padding: 30px;border: 1px solid #eee">';
    print_r($message);
    echo '</pre>';
}
