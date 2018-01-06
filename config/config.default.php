<?php

/** Before Start:
 * in comand lane "composer install"
 * add in vendor/composer/autoload_pse4.php :
 *      'app\\controller\\' => array($baseDir . '/src/controller'),    
 *      'app\\model\\' => array($baseDir . '/src/model')
 * this need for correct namespaces in project  
 */

// Slim cfg
$config['displayErrorDetails'] = false;
$config['addContentLengthHeader'] = false;

// DB
$config['db']['host'] = '';
$config['db']['dbname'] = '';
$config['db']['charset'] = '';
$config['db']['user'] = '';
$config['db']['password'] = '';

// Api key for NP
$config['apiKey'] = '';

// Methods list
$config['list'] = ['getCities', 'getDepartments', 'getCargoTypes'];
