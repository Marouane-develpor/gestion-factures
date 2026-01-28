<?php

namespace App\config;

use PDO;
use PDOException;

class dataBase{
      private static $con;

      static function getCon($con){
        try{
        $this->con = new PDO("mysql:host=localhost;dbname=market_db;charset=utf8mb4","root", "");
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }catch(PDOException $e){
        die("ERROR : ".$e->getMessage());
      }
      return $con;
      }
    
}