<?php
namespace App;
require_once (__DIR__ . DIRECTORY_SEPARATOR . "config/load_env.php");

use PDO;

class Connection {
    public static function getPDO () :PDO 
  {
    // $cleardb_url= $_ENV['CLEARDB_DATABASE_URL'];
    $cleardb_server = $_ENV['MYSQLHOST'];
    $cleardb_username = $_ENV['MYSQLUSER'];
    $cleardb_password = $_ENV['MYSQLPASSWORD'];
    $cleardb_db = $_ENV['MYSQLDATABASE'];
    $cleardb_port= $_ENV['MYSQLPORT'];
    // dd($cleardb_db, $cleardb_password, $cleardb_username, $cleardb_server, $cleardb_port); 

    return new PDO('mysql:host='.$cleardb_server.';dbname='.$cleardb_db.';port='.$cleardb_port, $cleardb_username, $cleardb_password, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
  }
}