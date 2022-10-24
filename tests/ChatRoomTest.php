<?php

use App\ChatRoom;

use PHPUnit\Framework\TestCase;

class ChatRoomTest extends TestCase {

    // private $userId;
    // private $message= 'hello 2';
    // private $created_on='2022-12-10 22:22:00';
    // private $chat_id;
    // private $receiverId='2';
    // private $status='yes';

    public function testClassConstructor () {
        $pdo = new ChatRoom('mysql:dbname=testchat;host=127.0.0.1', 'root', '0123');
    }
    public function getPDO ()
    {
        

        return $pdo;
        // $this->pdo = (new ConnectionPDO())->getPDOTest();
        // return $this->pdo;
    }

    public function testSetUserId() {
        $this->getPDO()->setUserId('123');
        $userId = $this->getPDO()->getUserId();
        // dd($this->userId);
        $this->assertSame('123', $userId);
    }
   
    public function testSaveChat() {
        $q = $this->getPDO()->saveChat();
    }
}