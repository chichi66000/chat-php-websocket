<?php
session_start();

use App\Form;
use App\ConnectionPDO;

if (!isset($_SESSION['unique_id'])) {
    header('Location: ' . $router->url('users'));
}
else {
    $unique_id = $_SESSION['unique_id'];
    $friend_id = explode('=', $_SERVER['REQUEST_URI'])[1];
    $user = (new ConnectionPDO)->checkIfUserExists('unique_id', $unique_id)[0];
    $friend = (new ConnectionPDO)->checkIfUserExists('unique_id', $friend_id)[0];
    // no user or no friend in table user => go back to users page
    if (empty($user) || empty($friend)) {
        header('Location: ' . $router->url('users'));
    }
    // continue to chat
    else 
    {

    }
}




?>

<header class="d-flex my-2 mx-auto col p-2 col-md-6 col-lg-5 border border-1 bg-white">
    <a href="<?= $router->url('users') ?> " class="align-self-center me-2" >
        <i class="bi bi-arrow-left"  style="font-size: 2rem;"></i>
    </a>
    <div class="d-flex flex-wrap ">
        <img src="<?= $user['file'] ?>" alt="image profile" class="img rounded-circle img-fluid img-thumbnail" style="width: 5rem; height: 5rem" />
        <div class="align-self-center">
            <h5 class=""><?= $user['first_name'] . ' ' . $user['last_name']?></h5>
            <h4 class=""><?= $user['status'] ?></h4>
        </div>
    </div>
</header>

<div id="chat-box" class="my-2 mx-auto col p-2 col-md-6 col-lg-5 border border-1 bg-white overflow-auto">
    <div class="income_message d-flex flex-row flex-wrap justify-content-start my-1  mx-1 ">
        <img class="img rounded-circle img-fluid pe-1" style="width: 2rem; height: 2rem" alt="" src="2977.jpg" />
        <p class="bg-black text-white p-1 rounded text-break" style="width: 50%;">Loremmm mem em Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure quis atque nisi deleniti pariatur, quibusdam corporis, eveniet enim excepturi quae vero provident earum placeat soluta? At ea eos velit repudiandae?</p>
    </div>

    <div class="outcome_message d-flex flex-row flex-wrap justify-content-end  my-1 p-1 mx-1 ">
        <p class="bg-success rounded text-right p-1 text-break" style="width: 50%;">Loremmm mem em Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quis magnam, asperiores voluptatem aliquam architecto, maiores optio praesentium perspiciatis eligendi dolore blanditiis reiciendis sed id dolorem repellendus, quas officia qui recusandae!</p>
        <img class="img rounded-circle img-fluid ps-1" style="width: 2rem; height: 2rem" alt="" src="2977.jpg" />
    </div>
</div>
    
<form class="form-group mx-auto col p-2 col-md-6 col-lg-5 border border-1 bg-white " action="" method="post">
    <input type="text" class="form-control d-none" value="<?= $user['unique_id'] ?>" >
    <div class="input-group flex-nowrap ">
        <input type="text" name="message" class="form-control" placeholder="Enter your text here">
        <i class="bi bi-send input-group-text btn bg-info"></i>
    </div>
</form>