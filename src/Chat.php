<?php
namespace App;
use App\ChatRoom;
use App\HTML\Users;
use App\ConnectionPDO;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
// require_once ("ChatRoom.php");

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        /**
         * @ $msg 
         */
        
        // convert into array the $msg
        $data = json_decode($msg, true);

        $userId = $data['userId'];
        $receiverId = $data['friendId'];
        $message = $data['msg'];
        $created_on = date('Y-m-d H:i:s');
        $status = 'Yes';
        // connecte to database
        $chat_object = new ChatRoom($userId, $receiverId, $message, $created_on, $status);
        
        // dd($chat_object->receiverId);
        // save info of message to database
        // $id = $chat_object->setUserId($data['userId']);
        // dd($id);
        // $chat_object->setReceiverId($data['friendId']);
        // $chat_object->setMessage($data['msg']);
        // $chat_object->setCreatedOn(date('Y-m-d H:i:s'));
        // $chat_object->setStatus('Yes');
        // dd($chat_object->getMessage());
        $chat_message_id = $chat_object->saveChat($userId, $receiverId, $message, $created_on, $status);


        // get info of user from table user
        // get info of receiver from table user

        $sender = (new ConnectionPDO)->checkIfUserExists('unique_id', $data['userId'])[0];
        $sender_userName = $sender['first_name'] . ' ' . $sender['last_name'];
        $senderImg = $sender['file'];
        
        $receiver = (new ConnectionPDO)->checkIfUserExists('unique_id', $data['friendId'])[0];
        $data['dt'] = date('Y-m-d H:i:s');

        // $friendName = $friend['first_name'] . ' ' . $friend['last_name'];
        // $friendImg = $friend['file'];

        // $data['friendName'] = $friendName;
        // $data['userName'] = $userName;
        // $data['friendImg'] = $friendImg;
        // $data['userImg'] = $userImg;
        foreach ($this->clients as $client) {
            // if ($from !== $client) {
            //     // The sender is not the receiver, send to each client connected
            //     $client->send($msg);
            // }
            // show msg upon sender or receiver
            if ($from === $client) {
                $data['from'] = 'Me';
                // $data['img'] = $userImg;
            }
            else {
                $data['from'] = $sender_userName;
                // $data['img'] = $friendImg;

            }
            $client->send(json_encode($data));

            // check connection id
            dd("clien " , $client->resourceId);
            
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}