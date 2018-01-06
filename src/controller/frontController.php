<?php

namespace app\controller;

use app\model\DbModel as dbModel;
use app\model\NpModel as NpModel;

class FrontController
{

    private $config;

    public function __construct()
    {   
        // Get config data
        $this->config = $GLOBALS['app']->getContainer()->get('settings');
    }

    public function home($request, $response)
    {     
        return $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write(
                $response->withStatus(404) . '<br><br>' .
                'Method: "' . $request->getMethod() . '"<br>' .
                'Scheme: "' . $request->getUri()->getScheme() . '"<br>' .
                'Host: "' . $request->getUri()->getAuthority() . '"<br>' .
                'Result: <span style="color:red;">"Error! Not Found"</span> <br><br>' .
                '<b>Send GET request "/getInfo" for get requests list<b>'
            );
        }

    public function getInfo($request, $response)
    {
        $dbModel = new DbModel($this->config);
        $configList = $dbModel->getMethodsList();
        $list = implode("<br>", $configList);
        
        return $response->write('<b>Requests List:</b><br>' . $list . '<br><br><b>To send a request, add the prefix "/np/"</b>');
    }

    public function np($request, $response, $args)
    {
        $method = trim($args['method']);

        $npModel = new NpModel($this->config);
        $result = $npModel->setMethod($method);
        
        if($result){
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'text/html')
            ->write(
                $response->withStatus(200) . '<br><br>' .
                'Method: "' . $request->getMethod() . '"<br>' .
                'Scheme: "' . $request->getUri()->getScheme() . '"<br>' .
                'Host: "' . $request->getUri()->getAuthority() . '"<br>' .
                'Path: "' . $request->getUri()->getPath() . '"<br>' .
                'Result: <span style="color:green;">"Success!"</span> <br><br>' .
                '<b>Send GET request "/getInfo" for get requests list<b>'
            );
        } else {
            return $response->withRedirect('/');
        }
    }

}