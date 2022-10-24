<?php
namespace App;

use PDO;
use App\ConnectionPDO;

class ChatRoom {
    public $userId;
    public $message;
    public $created_on;
    // private $chat_id;
    public $receiverId;
    public $status;
    protected $connect;

    // public function __construct ($dsn='mysql:dbname=chat;host=127.0.0.1', $user='root', $password='0123') {
    //     $this->connect = new PDO($dsn, $user, $password, [
    //         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    //     ]);
    // }

    public function __construct ($userId, $receiverId, $message, $created_on, $status) {
        $this->connect = new PDO('mysql:dbname=chat;host=127.0.0.1', 'root', '0123', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $this->userId = $userId;
        $this->message = $message;
        $this->created_on = $created_on;
        $this->receiverId = $receiverId;
        $this->status = $status;
    }

    // public function setChatId ($chat_id) 
    // {
    //     $this->chat_id = $chat_id;
    // }

    // public function getChatId () 
    // {
    //     return $this->chat_id;
    // }

    // public function setReceiverId ($receiverId) 
    // {
    //     $this->receiverId = $receiverId;
    // }

    // public function getReceiverId () 
    // {
    //     return $this->receiverId;
    // }

    // public function setUserId ($userId) 
    // {
    //     $this->userId = $userId;
    //     // return dump($this->userId);
    // }

    // public function getUserId () 
    // {
    //     return $this->userId;
    // }

    // public function setMessage ($message) 
    // {
    //     $this->message = $message;
    // }

    // public function getMessage () 
    // {
    //     return $this->message;
    // }

    // public function setCreatedOn ($createdOn) 
    // {
    //     $this->created_on =$createdOn;
    // }

    // public function getCreatedOn () 
    // {
    //     return $this->created_on;
    // }

    // public function setStatus ($status) 
    // {
    //     $this->status = $status;
    // }

    public function saveChat ($userId, $receiverId, $message, $created_on, $status) 
    {
        $statement = $this->connect->prepare("INSERT INTO messages (from_user_id, to_user_id, message, created_on, status) VALUES (:from_user_id, :to_user_id, :message, :created_on, :status) ");
        dump("statement ", $statement);
        dump($this->userId);
        // $statement->bindValue(':from_userid',$this->userId);
        // $statement->bindValue(':to_userid',$this->receiverId);
        // $statement->bindValue(':message',$this->message);
        // $statement->bindValue(':created_on',$this->created_on);
        // $statement->bindValue(':status',$this->status);
        $statement->execute([
            ':from_user_id' => $userId,
            ':to_user_id' => $receiverId,
            ':message'=> $message,
            ':created_on'=> $created_on,
            ':status'=> $status
        ]);

        return $this->connect->lastInsertId();
    }

    public function get_all_chat_data () 
    {
        $query = "SELECT * FROM messages 
                    INNER JOIN user 
                    ON messages.from_user_id = user.unique_id OR messages.to_user_id = user.unique_id
                    ORDER BY messages.chat_id ASC ";
        $statement = $this->connect->prepare($query);
        $statement->execute([]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_user_all_data_with_status_count () {
        $query = "SELECT unique_id, first_name, last_name, file, status, 
                    (SELECT * FROM messages WHERE to_user_id = :user_id AND from_user_id = user.unique_id AND status = 'No') 
                    AS count_status FROM user ";
        $statement = $this->connect->prepare($query);
        $statement->execute([":user_id" => $this->userId]);
        return $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}