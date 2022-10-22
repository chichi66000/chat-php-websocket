<?php
require '../vendor/autoload.php';


use App\Router;


$router = new Router(dirname(__DIR__) . '/views');

// $router = new Router(dirname(__DIR__));

$router
    // ->get('/', 'index.php', 'home')
    ->match('/', 'login', 'login')
    ->match('/logout', 'logout', 'logout')
    ->match('/signup', 'signup', 'signup')
    ->match('/users', 'users', 'users')
    ->get('/usersList', 'fetchAPI/usersList', 'usersList')
    ->post('/search', 'fetchAPI/searchUsers', 'search')
    ->match('/chat/id=[i:id]', 'chat', 'chat')
    ->get('/error', 'NotFound', 'notfound')
    ->run();

