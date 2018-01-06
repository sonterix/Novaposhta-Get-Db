<?php

namespace app\model;

use \PDO as PDO;

class DbModel
{

    private $dbh;
    private $config;

    public function __construct($conf = [])
    {   
        // Get config data
        $this->config = $conf;

        // Connect to db
        $dbData = $this->config['db'];
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => FALSE,
        ];

        $this->dbh = new PDO("mysql:host=".$dbData['host'].";dbname=".$dbData['dbname'].";charset=".$dbData['charset'], $dbData['user'], $dbData['pass'], $opt);
    }

    public function getDbh(){
        return $this->dbh;
    }

    public function getMethodsList()
    {
        return $this->config['list'];
    }

}