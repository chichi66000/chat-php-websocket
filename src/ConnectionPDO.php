<?php
namespace App;

use PDO;

class ConnectionPDO {
    private $pdo;
    // private $pdoTest;

    private $dsn = 'mysql:dbname=testdb;host=127.0.0.1';
    private $user = 'dbuser';
    private $password = 'dbpass';
    // private $first_name;
    // private $last_name; 
    // private $password; 
    // private $status; 
    // private $email;
    // private $created_at; 

    public function __construct ($dsn='mysql:dbname=chat;host=127.0.0.1', $user='root', $password='0123') 
    {
        $this->pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        // $this->pdoTest = new PDO('mysql:dbname=testchat;host=127.0.0.1', 'root', '0123', [
        //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        // ]);
    }

    // public function getPDO() 
    // {   
    //     return $this->pdo;
    // }
    
    // public function getPDOTest ()
    // {
    //     return $this->pdoTest;
    // }

    // insert user into table user
    public function insertUser (string $unique_id, string $firstName, string $lastName, string $email, string $password,string $file, string $status, string $created_at): bool 
    {
        $query = $this->pdo->prepare("INSERT INTO user (unique_id, first_name, last_name, email, password, file, status, created_at) VALUES (:unique_id, :first_name, :last_name, :email, :password, :file, :status, :created_at)");
        $result = $query->execute([
            "unique_id" => $unique_id,
            "first_name" => $firstName,
            "last_name" => $lastName,
            "email" => $email,
            "password" => $password,
            "file" => $file,
            "status" => $status,
            "created_at" => $created_at, 
            
        ]);
        // insert false => retire photo du Cloudinary
        if ($result === false) {
            http_response_code(400);
            throw new \Exception("Problem to insert user into table user");
            return false;
        }
        else {
            http_response_code(200);
            return true;
        }
    }

    public function checkIfUserExists (string $email) :bool
    {
        $query = $this->pdo->prepare("SELECT email FROM user WHERE email = :email");
        $query->execute(['email' => $email]);
        $result = $query->fetchAll();
        // user not found => array vide
        if (empty($result)) {
            return false;
        }
        else {
            return true;
        }
    }

    public function updateStatus (string $field, string $value, string $status): bool 
    {
        $query = $this->pdo->prepare("UPDATE user SET status = :status WHERE $field = :field");
        $result = $query->execute([
            ":status" => $status,
            ":field" => $value
        ]);
        if ($result === false) {
            return false;
        }
        else { return true; }
    }

    public function getUser(string $field, string $value) 
    {
        $query = $this->pdo->prepare("SELECT * FROM user WHERE $field = :field");
        
        $r = $query->execute([":field" => $value]);
        // dd($query);
        $result = $query->fetchAll();
        if (empty($result)) {
            // http_response_code(400);
            // throw new \PDOException("User not founded");
            return [];
        }
        else {
            return $result;
        }
    }

    public function deleteLastInsertUser () 
    {
        $id = $this->pdo->lastInsertId();
        $query = $this->pdo->prepare("DELETE FROM user WHERE user_id = :id");
        $result = $query->execute([":id" => $id]);
        if ($result === true) {
            return "User delete";
        }
        else { return 'User not been deleted' ;}
    }

    // public function logOut (string $field, string $value) 
    // {
    //     $query = $this->pdo->prepare("SELECT status FROM user WHERE $field = ? ");
    //     $query->execute([$field => $value]);
    //     $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
    //     if (!empty($result)) {
    //         // changer le status du user => Offline 
    //         // $status = "Offline";
    //         // $this->updateStatus($field, $value, $status);
    //         // http_response_code('200');
    //         return true;
    //     }
    //     else {
    //         // http_response_code(400);
    //         // throw new \PDOException("User not founded");
    //     }
    // }
}