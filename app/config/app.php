<?php
/**
 * Application Config
 *
 *
 */

$config = array(
    /**
     * Application Base Url
     *
     * Example:
     * $config['base_url'] = 'example.com';
     *
     */

    'base_url' => '',

    /**
     * Session Config
     *
     * name         session name                                // 'ELIPS_PHP_SESS'
     * driver       session driver (file)                       // 'file'
     * expire       session expire time                         // 7200
     * match_ip     session match ip address (true | false)     // true
     * max_size     session max file size (if use file driver)  // 1048576
     */

    'session' => array(
        'name'      => 'ELIPS_PHP_SESS',
        'driver'    => 'file',
        'expire'    => 7200,
        'match_ip'  => true,
        'max_size'  => 1048576
    ),

    /**
     * Encryption Key
     *
     * Used by Encryption library, Session library, Cache library
     *
     * Example:
     * $config['encryption_key'] = 'MY SECRET KEY';
     */

    'encryption_key' => '427ba3403980c1b3471f3fe226b1b92a896063e223',

    /**
     * Cache Config
     *
     * driver     cache driver (file | apc)             // 'file'
     * active     is cache active (true | false)        // false
     * encrypt    encrypt cache data (true | false)     // false
     */

    'cache' => array(
        'driver'    => 'file',
        'active'    => false,
        'encrypt'   => false
    )

);
