<?php
/**
 * Database Config
 *
 * host         localhost
 * user         database user
 * pass         database password
 * db           database name
 * driver       database driver (mysqli, sqlite)
 *
 */

$config['db'] = array(
    'main' => array(
        'host'      => 'localhost',
        'user'      => 'root',
        'pass'      => 'root157',
        'db'        => 'elips',
        'driver'    => 'mysqli'
    ),

    'optional' => array(
        'host'      => 'localhost',
        'user'      => 'root',
        'pass'      => '',
        'db'        => 'elips',
        'driver'    => 'sqlite'
    )
);
