<?php
namespace App;

use PDO;

class ChatRoom {
    private $userId;
    private $message;
    private $created_on;
    private $chat_id;
    protected $connect;

    public function __construct ($dsn='mysql:dbname=chat;host=127.0.0.1', $user='root', $password='0123') {
        $this->connect = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function setChatId ($chat_id) 
    {
        $this->chat_id = $chat_id;
    }

    public function getChatId () 
    {
        return $this->chat_id;
    }

    public function setUserId ($userId) 
    {
        $this->userId = $userId;
    }

    public function getUserId () 
    {
        return $this->userId;
    }

    public function setMessage ($message) 
    {
        $this->message = $message;
    }

    public function getMessage () 
    {
        return $this->message;
    }

    public function setCreatedOn ($createdOn) 
    {
        $this->created_on =$createdOn;
    }

    public function getCreatedOn () 
    {
        return $this->created_on;
    }

    public function saveChat () 
    {
        
    
        $query = "INSERT INTO messages (userId, message, created_on) VALUES (:userId, :message, :created_on)";
        $statement = $this->connect->prepare($query);
        $statement->bindValue(':userId',$this->userId);
        $statement->bindValue(':message',$this->message);
        $statement->bindValue(':created_on',$this->created_on);
        $statement->execute();
    
    }
}