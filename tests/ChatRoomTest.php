<?php

use App\ChatRoom;

use App\ConnectionPDO;
use App\ConnectionTest;
use PHPUnit\Framework\TestCase;

class ChatRoomTest extends TestCase {
    // private $pdo;
    public $userId;
    public $message;
    public $created_on;
    private $chat_id;
    public $receiverId;
    public $status;

    public function __construct() {
        parent::__construct();
        // Your construct here
    //    $this->pdo = ConnectionTest::getPDO();

    }

    public function getPDO ()
    {   $pdo = ConnectionTest::getPDO();
        $p= new ChatRoom($pdo);
        
    }

    // public function testsetChatId () {
    //     $e = $this->getPDO()->setChatId('12');
    //     $id = $this->chat_id;
    //     dd($id);
    //     $this->assertEquals('12', $id);
    // }

    // public function testSaveChat () {
    //     $this->getPDO()->saveChat()
    // }
}