<?php
session_start();
use App\Connection;
use App\HTML\Users;

$unique_id = $_SESSION['unique_id'];
$pdo = Connection::getPDO();
$users = new Users('unique_id', $unique_id,$pdo );

$allUsers = $users->users;
$response = $users->renderAllUser($allUsers);
echo($response);