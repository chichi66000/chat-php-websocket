<?php
namespace App;
use PDO;

class ConnectionTest {
    public static function getPDO () :PDO 
  {
    return new PDO('mysql:dbname=testchat;host=127.0.0.1', 'root', '0123', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
  }
}