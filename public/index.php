<?php
require '../vendor/autoload.php';


use App\Router;


// use Ratchet\Server\IoServer;
// use Ratchet\Http\HttpServer;
// use Ratchet\WebSocket\WsServer;
// use App\Chat;

$router = new Router(dirname(__DIR__) . '/views');


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


    
// $server = IoServer::factory(
//     new HttpServer(
//         new WsServer(
//             new Chat()
//         )
//     ),
//     8080
// );

// $server->run();