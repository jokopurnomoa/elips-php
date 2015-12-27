<?php
/**
 * APP CONFIG
 *
 *
 */

$config['session']['name']   = 'ELIPS_PHP_SESS';
$config['session']['driver'] = 'file';
$config['session']['expire'] = 7200;
$config['session']['match_ip'] = true;
$config['session']['max_size'] = 1048576;

$config['encryption_key'] = '427ba3403980c1b3471f3fe226b1b92a896063e2';

$config['cache']['driver'] = 'file';
$config['cache']['active'] = false;
$config['cache']['encrypt'] = false;
$config['cache']['max_size'] = 8388608;

