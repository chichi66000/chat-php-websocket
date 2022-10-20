<?php

use App\ConnectionPDO;
session_start();

dump($_SESSION['unique_id']);
if (!isset($_SESSION['unique_id'])) {
    header('Location: ' . $router->url('login'));
}
else {
    $unique_id = $_SESSION['unique_id'];
    $user = (new ConnectionPDO)->checkIfUserExists('unique_id', $unique_id)[0];
    $users = (new ConnectionPDO)->getAllUsersExceptOne('unique_id', $unique_id);
    
}
?>

<script>
    // setInterval(function () {
    //     fetch('/users', { 
    //         method="post",
    //         body: new URLSearchParams({
    //             "unique_id": })
    //     })
    // }
    // , 10000);
</script>

<div class="d-flex justify-content-between my-2 mx-auto col p-2 col-md-6 col-lg-5 border border-1 bg-white">
    <div class="d-flex">
        <div class="">
            <img src="<?= $user['file'] ?>" alt="image profile" class="img rounded-circle img-fluid img-thumbnail" style="width: 5rem; height: 5rem" />
        </div>
        <div class="align-self-center">
            <h5 class=""><?= $user['last_name'] . ' ' . $user['first_name'] ?></h5>
            <h6 class="">
                <i class="bi bi-circle-fill px-1 text-success"></i><?= $user['status'] ?>
            </h6>
        </div>
    </div>
    
    <div>

        <a href="<?= $router->url('logout') ?>" class="btn bg-black text-white flex-shrink" >Logout</a>
    </div>
</div>

<div class="mx-auto p-4 col-md-6 col-lg-5 border border-1 bg-white">
    <form action="" class="form-group" method="post">
        <span for="search">Select a user to start</span>
        <div class="input-group flex-nowrap">
            <input id="search" type="search" name="search" placeholder="Enter user to start chatting" class="form-control" >
            <button class="input-group-text bg-black search-button btn" type="submit">
                <i class="bi bi-search text-white" id="search-icon"></i>
            </button>
        </div>
    </form>

    <!-- list all users -->
    <div class="users_list">
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
        
    </div>

</div>