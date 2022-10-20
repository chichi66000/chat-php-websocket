<?php
session_start();
use App\ConnectionPDO;

dump($_POST['searchUsers']);
$unique_id = $_SESSION['unique_id'];
if (isset($_POST['searchUsers'])) {
    $searchUsers = $_POST['searchUsers'];
    $users = (new ConnectionPDO)->getSearchUsersExceptOne($searchUsers, $unique_id);
    echo $users;
}


?>

<h1>Search</h1>