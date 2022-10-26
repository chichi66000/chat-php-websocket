<?php
namespace App;
require_once (__DIR__ . DIRECTORY_SEPARATOR . "config/load_env.php");

use PDO;

class Connection {
    public static function getPDO () :PDO 
  {
    // $cleardb_url= $_ENV['CLEARDB_DATABASE_URL'];
    $cleardb_server = $_ENV['CLEARDB_HOST'];
    $cleardb_username = $_ENV['CLEARDB_USER'];
    $cleardb_password = $_ENV['CLEARDB_PASSWORD'];
    $cleardb_db = $_ENV['CLEARDB_DB'];

    return new PDO("mysql:dbname=$cleardb_db;host=$cleardb_server", "$cleardb_username", "$cleardb_password", [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
  }
}