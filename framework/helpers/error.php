<?php
/**
 * Error Helper
 *
 * Show error message
 *
 */

/**
 * Dumping Error
 *
 * @param $message
 */
function error_dump($message){
    echo '<pre style="font-family: \'Courier New\', Monospace;padding: 30px;border: 1px solid #eee">';
    print_r($message);
    echo '</pre>';
}
