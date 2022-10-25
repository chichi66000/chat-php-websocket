<?php
session_start();
use App\Connection;
use App\HTML\Users;
use App\ConnectionPDO;

$unique_id = $_SESSION['unique_id'];
if (isset($_POST['searchUsers'])) {
    $searchUsers = $_POST['searchUsers'];
    $pdo = Connection::getPDO();
    $users = (new ConnectionPDO($pdo))->getSearchUsersExceptOne($searchUsers, $unique_id);
    $resultSearch = (new Users('unique_id', $unique_id, $pdo))->renderAllUser($users);
    echo ($resultSearch);
}


?>
