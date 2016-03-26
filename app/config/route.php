<?php
/**
 * ROUTING CONFIG
 *
 * Be carefull, routing is case sensitive
 *
 *
 */

/**
 * default_controller       // default application controller (first controller called)
 * root_controller          // root controller
 * 404                      // page not found controller
 * method_separator         // method separator to call method in url ('_' | '-' | default is '_')
 */

$route = array(

    'default_controller'        => 'Welcome',

    'root_controller'           => 'Base',

    '404'                       => '',

    'method_separator'          => '',

);
