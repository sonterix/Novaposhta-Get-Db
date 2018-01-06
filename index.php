<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once 'vendor/autoload.php';
require_once 'config/config.php';

$app = new \Slim\App(['settings' => $config]);

// for access to $app
$GLOBALS['app'];

$app->get('/', 'app\controller\FrontController:home');
$app->get('/getInfo', 'app\controller\FrontController:getInfo');
$app->get('/np/{method}', 'app\controller\FrontController:np');
$app->get('/{any}', function($request, $response){
    return $response->withRedirect('/');
});

$app->run();