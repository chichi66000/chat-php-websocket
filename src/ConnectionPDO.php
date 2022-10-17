<?php
namespace App;

use PDO;

class ConnectionPDO {
    private $pdo;
    // private $first_name;
    // private $last_name; 
    // private $password; 
    // private $status; 
    // private $email;
    // private $created_at; 

    public function __construct () {
        $this->pdo = new PDO('mysql:dbname=chat;host=127.0.0.1', 'root', '0123', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

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
            "status" => $status,
            $field => $value
        ]);
        dump($query);
        if ($result === false) {
            return false;
        }
        else { return true; }
    }

    public function getUser(string $field, string $value) 
    {
        $query = $this->pdo->prepare("SELECT * FROM user WHERE $field = ?");
        $query->execute([$field => $value]);
        $result = $query->fetchAll();
        return $result;
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