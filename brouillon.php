<?php

<!-- Error validate image -->
 if(!empty($e)): ?>
    <div class="alert alert-danger">
        <?php foreach ( $e as $err): ?>
            <?= $err . "<br>" ?> 
        <?php endforeach; ?>
    </div>
<?php endif ?>


// public function getUser(string $field, string $value) 
    // {
    //     $query = $this->pdo->prepare("SELECT * FROM user WHERE $field = :field");
        
    //     $r = $query->execute([":field" => $value]);
    //     // dd($query);
    //     $result = $query->fetchAll();
    //     if (empty($result)) {
    //         // http_response_code(400);
    //         // throw new \PDOException("User not founded");
    //         return [];
    //     }
    //     else {
    //         return $result[0];
    //     }
    // }

    function getAllUsers (string $key, string $value) {
        dump($value);
        $users = (new ConnectionPDO)->getAllUsersExceptOne($key, $value);
        return $users;
    }

    ?>



    <?php foreach ($users as $user): ?>
            <div class="user-list d-flex justify-content-between flex-wrap">
                <a href="" class="p-1 d-flex flex-wrap flex-row ">
                    <img src="<?= $user['file'] ?>" alt="img profile" class="img img-fluid rounded-circle" style="width:3rem; height: 3rem" />
                    <div>
                        <span><?= $user['last_name'] . ' ' . $user['first_name'] ?></span>
                        <p><?= $user['status'] ?></p>
                    </div>
                </a>

                <div class="user-status align-self-center">
                    <i class="<?= $user['status']=== "Online" ? "bi bi-circle-fill text-success" : "bi bi-circle-fill" ?>"></i>
                </div>
            </div>
        <?php endforeach; ?>


<?php
// ChatRoom class
function setUserToken ($userToken) {
    $this->userToken = $userToken;
}
function setUserConnectionID ($user_connection_id) {
    $this->user_connection_id = $user_connection_id;
}
function getUserToken() { return $this->userToken; }

function getUserConnectionID() { return $this->user_connection_id; }

function update_user_connection_id () {
    $q = "UPDATE chat_user_table SET user_connection_id = :user_connection_id WHERE user_token = :user_token";
    $statement = $this->connect->prepare($q);
    $statement->execute([
        ":user_connection_id" => $this->user_connection_id, 
        "user_token" => $this->user_token
    ]);
}
// après login=> generate unique connection string
$user_token = md5($unique_id);

// update user_token value into table user after Login
// user this token in websocket connection: ex: ws://localhost:8080?$token=....
// in Chat ->onOpen
public function onOpen(ConnectionInterface $conn) {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);

    /**
     * add this line to set user_connection_id into table user when there is a chat
     */
    $queryString = $connect->httpRequest->getUri()->getQuery();
    parse_str($queryString, $queryarray);
    $user_object = new \ChatUser;
    $user_object->setUserToken($querryarray['token']);
    $user_object->setUserConnectionId($conn->resourceId);
    $user_object->update_user_connection_id();

    echo "New connection! ({$conn->resourceId})\n";
}
