<?php
session_start();
use App\HTML\Users;
use App\ConnectionPDO;

$unique_id = $_SESSION['unique_id'];
if (isset($_POST['searchUsers'])) {
    $searchUsers = $_POST['searchUsers'];
    $users = (new ConnectionPDO)->getSearchUsersExceptOne($searchUsers, $unique_id);
    $resultSearch = (new Users('unique_id', $unique_id))->renderAllUser($users);
    echo ($resultSearch);
}


?>
