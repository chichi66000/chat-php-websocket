<?php

use App\Connection;
use App\HTML\Users;
use App\ConnectionPDO;

session_start();
// $users = [];
// $user = [];

// dump($_SESSION['unique_id']);
// if (!isset($_SESSION['unique_id'])) {
//     header('Location: ' . $router->url('login'));
// }
// else {
//     $unique_id = $_SESSION['unique_id'];
    
//     $user = (new ConnectionPDO)->checkIfUserExists('unique_id', $unique_id)[0];
//     $users = (new ConnectionPDO)->getAllUsersExceptOne('unique_id', $unique_id);
//     // $users = getAllUsers('unique-id', $unique_id);
//     dump($users);

// }
dump($_SESSION['unique_id']);
if (isset($_SESSION['unique_id'])) {
    $unique_id = $_SESSION['unique_id'];
    $pdo = Connection::getPDO();
    $users = new Users('unique_id', $unique_id, $pdo);
    $userLogin = $users->user[0];
    $allUsers = $users->users;
    $response = $users->renderAllUser($allUsers);
}
else {
    header('Location: ' . $router->url('login'));
}


// dump($_POST['searchUsers']);

?>

<div class="d-flex justify-content-between my-2 mx-auto col p-2 col-md-6 col-lg-5 border border-1 bg-white">
    <div class="d-flex">
        <div class="">
            <img src="<?= $userLogin['file'] ?>" alt="image profile" class="img rounded-circle img-fluid img-thumbnail" style="width: 5rem; height: 5rem" />
        </div>
        <div class="align-self-center">
            <h5 class=""><?= $userLogin['last_name'] . ' ' . $userLogin['first_name'] ?></h5>
            <h6 class="">
                <i class="bi bi-circle-fill px-1 text-success"></i><?= $userLogin['status'] ?>
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
            <input id="searchInput" type="search" name="search" placeholder="Enter user to start chatting" class="form-control" >
            <button id='searchButton' class="input-group-text bg-black search-button btn" type="submit">
                <i class="bi bi-search text-white" id="search-icon"></i>
            </button>
        </div>
    </form>

    <!-- list all users -->
    <div class="users_list" id="usersList">
       
    </div>

</div>


<script>
    // alert('hi');
    let usersList = document.getElementById('usersList');
    let searchInput = document.getElementById('searchInput');
    let searchButton = document.getElementById('searchButton')
    let unsetInterval = false;

    // appel API to search user by last name/first name
    function searchUsers () {
        unsetInterval = true;
        let searchUsers = searchInput.value;
        console.log(searchUsers);
        fetch('/search', {
            method: 'POST',
            body: new URLSearchParams({
                'searchUsers' : searchUsers
            })
        })
        .then(response => {
            console.log(response);
                return response.text()
        })
        .then(body => {
            usersList.innerHTML = body;
        })
        .catch(error => {
            console.log(error);
            usersList.innerHTML = "Problem with server please try later"
        })
    }

    // les events of search button with click and enter
    searchButton.addEventListener("keypress", function (e) {
        if(e.key === "Enter") {
            e.preventDefault();
            searchUsers();
        }
        
    })
    searchButton.addEventListener('click', function (e) {
        e.preventDefault();
        searchUsers();
    })

    // les events with searchInput; to restart setInterval when value = ""
    searchInput.addEventListener('input', function (e) {
        let value = this.value;
        if (value === "") {
            unsetInterval= false;
            intervalListener()
        }
    })
    
    // fetch to charge list update Users Connected
    function updateUsersList () {
        console.log(unsetInterval, "unset");
        if (unsetInterval === true) {
            clearInterval(intervalListener)
        }
        else {
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "/usersList", true);
            xhr.onload = ()=>{
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        let data = xhr.response;
                        usersList.innerHTML = data;
                    }
                }
            }
            xhr.send();
        }
        
    }

    function intervalListener () {self.setInterval(function () {
        updateUsersList();
        
    }, 20000000)
    }
    intervalListener()
    document.addEventListener('DOMContentLoaded', updateUsersList());
</script>