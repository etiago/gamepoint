<?php
// This file generated by Propel 1.7.1 convert-conf target
// from XML runtime conf file /mount/usb-lexar/webroot/tiagoespinha.net/gamepoint/runtime-conf.xml
$conf = array (
  'datasources' => 
  array (
    'gamepoint' => 
    array (
      'adapter' => 'mysql',
      'connection' => 
      array (
        'dsn' => 'mysql:host=localhost;dbname=gamepoint',
        'user' => 'gamepoint_user',
        'password' => 'xxxxxxx',
      ),
    ),
    'default' => 'gamepoint',
  ),
  'generator_version' => '1.7.1',
);
$conf['classmap'] = include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classmap-gamepoint-conf.php');
return $conf;