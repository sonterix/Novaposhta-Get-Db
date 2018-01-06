<?php

namespace app\model;

use \PDO;
use app\model\DbModel as DbModel;

class NpModel
{

    private $dbh;
    private $config;

    public function __construct($conf = [])
    {   
        // Get config data
        $this->config = $conf;

        // Connect to db
        $dbModel = new DbModel($this->config);
        $this->dbh = $dbModel->getDbh();
    }

    public function setMethod($method = ''){
         if(in_array($method, $this->config['list'])){
            return $this->$method();        
        } else {
            return false;
        }
    }

    public function getCities(){     
        // cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.novaposhta.ua/v2.0/json/Address/getCities');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/json'
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "modelName" => "Address",
            "calledMethod" => "getCities",
            "apiKey" => $this->conf['apiKey']
        ]));

        $data = curl_exec($ch);
        curl_close($ch);

        // Get respons data
        $data = json_decode($data, true);
        $city = [];

        // Work with db
        $sqlCheckTable = $this->dbh->query("SHOW TABLES LIKE 'np_cities'");

        if($sqlCheckTable->rowCount()){
            $this->dbh->query("TRUNCATE TABLE np_cities"); 
        } else {
            $this->dbh->query("CREATE TABLE `np_cities` (
                `id` INT(6) NOT NULL AUTO_INCREMENT,
                `description` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `description_ru` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `ref` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `city_id` INT(6) NOT NULL,
                `date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(`id`)
            ) ENGINE = InnoDB");
        }

        // Check data from respons
        foreach ($data['data'] as $key => $value) {
            $sqlInsert = $this->dbh->prepare("INSERT INTO np_cities (description, description_ru, ref, city_id) VALUES(:description, :description_ru, :ref, :city_id)");
            $sqlInsert->execute([
                ':description' => $value['Description'],
                ':description_ru' => $value['DescriptionRu'],
                ':ref' => $value['Ref'],
                ':city_id' => $value['CityID']
            ]);
        }

        return true;
    }

    public function getDepartments(){
        echo 'getDepartments';
        return true;
    }

    public function getCargoTypes(){
        echo 'getCargoTypes';
        return true;
    }

}