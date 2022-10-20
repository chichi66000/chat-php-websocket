<?php
session_start();
use App\HTML\Users;

$unique_id = $_SESSION['unique_id'];
$users = new Users('unique_id', $unique_id);

$allUsers = $users->users;
$response = $users->renderAllUser($allUsers);
echo($response);