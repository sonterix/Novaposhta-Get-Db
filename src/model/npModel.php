<?php

namespace app\model;

use \PDO as PDO;
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

    public function setMethod($method = '')
    {
         if(in_array($method, $this->config['list'])){
            return $this->$method();        
        } else {
            return false;
        }
    }

    public function getCities()
    {     
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

        // Work with db
        $sqlCheckTable = $this->dbh->query("SHOW TABLES LIKE 'np_cities'");

        if($sqlCheckTable->rowCount()){
            $this->dbh->query("TRUNCATE TABLE np_cities"); 
        } else {
            $this->dbh->query("CREATE TABLE `np_cities` (
                `id` INT(6) NOT NULL AUTO_INCREMENT,
                `description_ua` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `description_ru` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `ref` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `city_id` INT(6) NOT NULL,
                `date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(`id`)
            ) ENGINE = InnoDB");
        }

        // Check data from respons
        foreach ($data['data'] as $key => $value) {
            $sqlInsert = $this->dbh->prepare("INSERT INTO np_cities (description_ua, description_ru, ref, city_id) VALUES(:description_ua, :description_ru, :ref, :city_id)");
            $sqlInsert->execute([
                ':description_ua' => $value['Description'],
                ':description_ru' => $value['DescriptionRu'],
                ':ref' => $value['Ref'],
                ':city_id' => $value['CityID']
            ]);
        }

        return true;
    }

    public function getDepartments()
    {
        // cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.novaposhta.ua/v2.0/json/AddressGeneral/getWarehouses');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/json'
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode( [
            "modelName" => "AddressGeneral",
            "calledMethod" => "getWarehouses",
            "methodProperties" => [
                "Language" => "ru"
            ],
            "apiKey" => $this->conf['apiKey']
        ]));

        $data = curl_exec($ch); 
        curl_close($ch);

        // get respons data
        $data = json_decode($data, true);

        // Work with db
        $sqlCheckTable = $this->dbh->query("SHOW TABLES LIKE 'np_departments'");

        if($sqlCheckTable->rowCount()){
            $this->dbh->query("TRUNCATE TABLE np_departments"); 
        } else {
            $this->dbh->query("CREATE TABLE `np_departments` (
                `id` INT(6) NOT NULL AUTO_INCREMENT,
                `description_ua` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `description_ru` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `ref` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `city_ref` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(`id`)
            ) ENGINE = InnoDB");
        }

        // check data from respons
        foreach($data['data'] as $value){
            $sqlInsert = $this->dbh->prepare("INSERT INTO np_departments (description_ua, description_ru, city_ref, ref ) VALUES(:description_ua, :description_ru, :ref, :city_ref)");
            $sqlInsert->execute([
                ':description_ua' => $value['Description'],
                ':description_ru' => $value['DescriptionRu'],
                ':ref' => $value['Ref'],
                ':city_ref' => $value['CityRef']
            ]);
        }

        return true;
    }

    public function getCargoTypes()
    {
        // cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.novaposhta.ua/v2.0/json/Common/getCargoTypes');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/json'
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode( [
            "modelName" => "Common",
            "calledMethod" => "getCargoTypes",
            "apiKey" => $this->conf['apiKey']
        ]));

        $data = curl_exec($ch); 
        curl_close($ch);

        // get respons data
        $data = json_decode($data, true);

        // Work with db
        $sqlCheckTable = $this->dbh->query("SHOW TABLES LIKE 'np_cargo_types'");

        if($sqlCheckTable->rowCount()){
            $this->dbh->query("TRUNCATE TABLE np_cargo_types"); 
        } else {
            $this->dbh->query("CREATE TABLE `np_cargo_types` (
                `id` INT(6) NOT NULL AUTO_INCREMENT,
                `description_ua` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `ref` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(`id`)
            ) ENGINE = InnoDB");
        }

        // check data from respons
        foreach($data['data'] as $value){
            $sqlInsert = $this->dbh->prepare("INSERT INTO np_cargo_types (description_ua, ref) VALUES(:description_ua, :ref)");
            $sqlInsert->execute([
                ':description_ua' => $value['Description'],
                ':ref' => $value['Ref'],
            ]);
        }

        return true;
    }

    public function getDeliveryTypes()
    {
        // cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.novaposhta.ua/v2.0/json/Common/getServiceTypes');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/json'
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode( [
            "modelName" => "Common",
            "calledMethod" => "getServiceTypes",
            "apiKey" => $this->conf['apiKey']
        ]));

        $data = curl_exec($ch); 
        curl_close($ch);

        // get respons data
        $data = json_decode($data, true);

        // Work with db
        $sqlCheckTable = $this->dbh->query("SHOW TABLES LIKE 'np_delivery_types'");

        if($sqlCheckTable->rowCount()){
            $this->dbh->query("TRUNCATE TABLE np_delivery_types"); 
        } else {
            $this->dbh->query("CREATE TABLE `np_delivery_types` (
                `id` INT(6) NOT NULL AUTO_INCREMENT,
                `description_ua` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `ref` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(`id`)
            ) ENGINE = InnoDB");
        }

        // check data from respons
        foreach($data['data'] as $value){
            $sqlInsert = $this->dbh->prepare("INSERT INTO np_delivery_types (description_ua, ref) VALUES(:description_ua, :ref)");
            $sqlInsert->execute([
                ':description_ua' => $value['Description'],
                ':ref' => $value['Ref'],
            ]);
        }

        return true;
    }

}