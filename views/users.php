<?php
session_start();

dump($_SESSION['unique_id']);
?>

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