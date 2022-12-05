<?php
namespace App;
use App\ChatRoom;
use App\Connection;
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

        // save user_connection_id
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryarray);
        $pdo = Connection::getPDO();
        $user_object = new ConnectionPDO($pdo);
        $user_object->updateUserConnectionId($conn->resourceId, $queryarray['sender'] );

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
        // dump($data['msg']);
        $message = urldecode($data['msg']);
        // $message = html_entity_decode($data['msg']);
        // $message = rawurlencode($data['msg']);

        // dump("eee ", $message);
        $created_on = date('Y-m-d H:i:s');
        $status = 'Yes';

        // connecte to database
        $pdo = Connection::getPDO();

        $chat_object = new ChatRoom($pdo);
        $chat_object->setUserId($userId);
        $chat_object->setReceiverId($receiverId);
        $chat_object->setMessage($message);
        $chat_object->setCreatedOn($created_on);
        $chat_object->setStatus($status);
        $chat_message_id = $chat_object->saveChat();

        // get info of user from table user
        // get info of receiver from table user
        $sender = (new ConnectionPDO($pdo))->checkIfUserExists('unique_id', $data['userId'])[0];
        $sender_userName = $sender['first_name'] . ' ' . $sender['last_name'];
        $senderImg = $sender['file'];
        
        $receiver = (new ConnectionPDO($pdo))->checkIfUserExists('unique_id', $data['friendId'])[0];
        $receiverName = $receiver['first_name'] . ' ' . $receiver['last_name'];
        $receiver_user_connection_id = $receiver['user_connection_id'];
        $receiverImg = $receiver['file'];

        $data['dt'] = date('Y-m-d H:i:s');
        $data['senderImg'] = $senderImg;
        $data['receiverImg'] = $receiverImg;
        $data['receiver'] = $receiverName;

        foreach ($this->clients as $client) {
            // if ($from !== $client) {
            //     // The sender is not the receiver, send to each client connected
            //     $client->send($msg);
            // }
            // show msg upon sender or receiver
            if ($from === $client) {
                $data['from'] = 'Me';
            }
            else {
                $data['from'] = $sender_userName;
            }
            
            // check if receiver is connected?
            if ( $client->resourceId === $receiver_user_connection_id || $from == $client) {
                $client->send(json_encode($data));
            }
            else {
                $chat_object->setStatus('No');
                $chat_object->setChatId($chat_message_id);
                $chat_object->updateChatMessageStatus();
            }
            
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