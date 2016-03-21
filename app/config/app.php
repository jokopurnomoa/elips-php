<?php
/**
 * Application Config
 *
 *
 */

/**
 * Application Base Url
 *
 * Example:
 * $config['base_url'] = 'example.com';
 *
 */

$config['base_url'] = '';

/**
 * Session Config
 *
 * Example:
 * $config['session']['name']   = 'ELIPS_PHP_SESS';     // session name
 * $config['session']['driver'] = 'file';               // session driver (file)
 * $config['session']['expire'] = 7200;                 // session expire time
 * $config['session']['match_ip'] = true;               // session match ip address (true | false)
 * $config['session']['max_size'] = 1048576;            // session max file size (if use file driver)
 */

$config['session']['name']   = 'ELIPS_PHP_SESS';
$config['session']['driver'] = 'file';
$config['session']['expire'] = 7200;
$config['session']['match_ip'] = true;
$config['session']['max_size'] = 1048576;

/**
 * Encryption Key
 *
 * Used by Encryption library, Session library, Cache library
 *
 * Example:
 * $config['encryption_key'] = 'MY SECRET KEY';
 */

$config['encryption_key'] = '427ba3403980c1b3471f3fe226b1b92a896063e223';

/**
 * Cache Config
 *
 * Example:
 * $config['cache']['driver'] = 'file';     // cache driver (file | apc)
 * $config['cache']['active'] = false;      // is cache active (true | false)
 * $config['cache']['encrypt'] = false;     // encrypt cache data (true | false)
 */

$config['cache']['driver'] = 'file';
$config['cache']['active'] = false;
$config['cache']['encrypt'] = false;
