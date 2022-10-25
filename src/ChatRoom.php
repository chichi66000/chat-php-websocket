<?php
namespace App;

use PDO;
// use App\ConnectionPDO;

class ChatRoom {
    public $userId;
    public $message;
    public $created_on;
    private $chat_id;
    public $receiverId;
    public $status;
    protected $pdo;

    public function __construct (PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function setChatId ($chat_id) 
    {
        $this->chat_id = $chat_id;
    }

    public function getChatId () 
    {
        return $this->chat_id;
    }

    public function setReceiverId ($receiverId) 
    {
        $this->receiverId = $receiverId;
    }

    public function getReceiverId () 
    {
        return $this->receiverId;
    }

    public function setUserId ($userId) 
    {
        $this->userId = $userId;
        // return dump($this->userId);
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

    public function setStatus ($status) 
    {
        $this->status = $status;
    }

    public function saveChat () 
    {
        $statement = $this->pdo->prepare("INSERT INTO messages (from_user_id, to_user_id, message, created_on, status) VALUES (:from_user_id, :to_user_id, :message, :created_on, :status) ");
        $statement->execute([
            ':from_user_id' => $this->userId,
            ':to_user_id' => $this->receiverId,
            ':message'=> $this->message,
            ':created_on'=> $this->created_on,
            ':status'=> $this->status
        ]);

        return $this->pdo->lastInsertId();
    }

    public function updateChatMessageStatus() {
        $query = "UPDATE messages SET status = :status WHERE chat_id = :chat_id";
        $statement = $this->pdo->prepare($query);
        $statement->execute([
            ":status" => $this->status,
            ":chat_id" => $this->chat_id
        ]);
    }

    public function get_all_chat_data ($userId, $receiverId)  
    {
        $query = "SELECT * FROM messages 
                    INNER JOIN user a
                        ON messages.from_user_id = a.unique_id 
                    INNER JOIN user b
                        ON  messages.to_user_id = b.unique_id
                    WHERE (messages.from_user_id = $userId AND messages.to_user_id = $receiverId) 
                        OR (messages.from_user_id = $receiverId AND messages.to_user_id = $userId)
                    ORDER BY messages.chat_id ASC ";
        $statement = $this->pdo->prepare($query);
        $statement->execute([]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function get_user_all_data_with_status_count () {
    //     $query = "SELECT unique_id, first_name, last_name, file, status, 
    //                 (SELECT * FROM messages WHERE to_user_id = :user_id AND from_user_id = user.unique_id AND status = 'No') 
    //                 AS count_status FROM user ";
    //     $statement = $this->connect->prepare($query);
    //     $statement->execute([":user_id" => $this->userId]);
    //     return $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    // }
}