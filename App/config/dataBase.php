<?php

namespace App\config;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $con;

    private function __construct() {
        try {
            $this->con = new PDO(
                "mysql:host=localhost;dbname=market_db;charset=utf8mb4",
                "root", 
                ""
            );
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("ERROR : " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public static function getConnection() {
        return self::getInstance()->con;
    }
}