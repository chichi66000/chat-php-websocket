<?php
session_start();

use App\Form;
use App\ChatRoom;
use App\ConnectionPDO;
dump($_SESSION['unique_id']);

if (!isset($_SESSION['unique_id'])) {
    header('Location: ' . $router->url('users'));
}
else {
    $unique_id = $_SESSION['unique_id'];
    $user = (new ConnectionPDO)->checkIfUserExists('unique_id', $unique_id)[0];
   
    $friend_id = explode('=', $_SERVER['REQUEST_URI'])[1];
    $friend = (new ConnectionPDO)->checkIfUserExists('unique_id', $friend_id)[0];
    // no user or no friend in table user => go back to users page
    $chat_object = new ChatRoom;
    $chat_data = $chat_object->get_all_chat_data($unique_id, $friend_id);
    // dump($chat_data);
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
    <?php foreach ($chat_data as $chat): ?>
        <?php if ($chat['from_user_id'] == $unique_id): ?>
            <div class="outcome_message d-flex flex-row flex-wrap justify-content-end  my-1 p-1 mx-1 ">
                <p class="rounded text-right p-1 text-break bg-success" style="width: 50%;"><?= $chat['message'] ?> <br>
                <span class="text-light fst-italic" style="font-size: 0.5rem"><?= $chat['created_on']?></span>
            </p>
            <img class="img rounded-circle img-fluid p-1 m-1" style="width: 2rem; height: 2rem" alt="" src="<?=$user['file'] ?>" />
            </div>
        <?php else : ?>
            <div class="income_message d-flex flex-row flex-wrap justify-content-start  my-1 p-1 mx-1 ">
                <img class="img rounded-circle img-fluid p-1 m-1" style="width: 2rem; height: 2rem" alt="" src="<?=$friend['file'] ?>" />
                <p class="bg-black text-white p-1 rounded text-break" style="width: 50%;"><?= $chat['message'] ?><br>
                    <span class="text-light fst-italic" style="font-size: 0.5rem"><?= $chat['created_on']?></span>
                </p>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
    
<form id="form" class="form-group mx-auto col p-2 col-md-6 col-lg-5 border border-1 bg-white " action="" method="post">
    <div id="error-form" class="alert alert-danger d-none"></div>

    <input type="text" class="form-control d-none" value="<?= $user['unique_id'] ?>" >
    <div class="input-group flex-nowrap ">
        <!-- <input type="text" name="message" class="form-control" placeholder="Enter your text here"> -->
        <input type="text" class="form-control" name="message" id="message" placeholder="Enter your message here (max: 1000 characters)" maxlength="1000"  >
        <button class="btn btn-secondary " name="send" id="send" type="submit">
            <i class="bi bi-send input-group-text btn bg-info"></i>
        </button>
    </div>
    
</form>

<script>
    // document.addEventListener('DOMContentLoaded', function () {
    //     chatBox = document.getElementById('chat-box');
    //     // chatBox.scrollTop(chatBox[0].scrollHeight);
    //     chatBox.scrollTop = chatBox.scrollHeight;

    // })

    var conn = new WebSocket('ws://localhost:8080?sender=<?php echo $unique_id?>');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        
        console.log(e.data);
        let data = JSON.parse(e.data);
        let chatBox = document.getElementById('chat-box')
        chatBox.scrollTop = chatBox.scrollHeight;
        
        let divChatUser = document.createElement('div');
        let pChatUser = document.createElement('p');
        let imgChatUser = document.createElement('img');
        let spanChat = document.createElement('span');
        let fromChat = document.createElement('span');
        let pClass = "";
        let divClass = "";
        // console.log("msg ", data);
        if (data.from === 'Me') {
            divClass='outcome_message d-flex flex-row flex-wrap justify-content-end  my-1 p-1 mx-1';
            pClass='bg-success rounded text-right p-1 text-break';
            // imgChatUser.setAttribute('src', data.senderImg);
        }
        else {
            divClass='income_message d-flex flex-row flex-wrap justify-content-start  my-1 p-1 mx-1';
            pClass='bg-black text-white p-1 rounded text-break';
            // imgChatUser.setAttribute('src', data.receiverImg);

        }
        // add new message in chat-box
        divChatUser.setAttribute('class', divClass);

        pChatUser.setAttribute('class', pClass);
        pChatUser.setAttribute('style', "width: 50%");
        pChatUser.innerHTML = data.msg + "<br>";

        spanChat.setAttribute('class', 'text-light fst-italic');
        spanChat.setAttribute('style', 'font-size: 0.5rem');

        spanChat.innerHTML = data.dt;

        imgChatUser.setAttribute('src', data.senderImg);

        imgChatUser.setAttribute('alt', "image profile");
        imgChatUser.setAttribute('class', 'img rounded-circle img-fluid p-1');
        imgChatUser.setAttribute('style', "width: 2rem; height: 2rem");
        // imgChatUser.setAttribute('src', data.img);

        fromChat.innerHTML = data.from;
        pChatUser.appendChild(spanChat);
        divChatUser.appendChild(pChatUser);
        divChatUser.appendChild(imgChatUser);
        divChatUser.appendChild(fromChat);
        chatBox.appendChild(divChatUser);
        
        // scroll to end of chat-box
        chatBox.scrollTop = chatBox.scrollHeight;

    };

    // sanitizer textarea 
    function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g,'&apos').replace(/'/g,'&apos');
    }

    let message = document.getElementById('message');
    let form = document.getElementById('form');
    let errorForm = document.getElementById('error-form');

    // form submit
    form.addEventListener('submit', (e)=> {
        e.preventDefault();
        if(message.value === "") {
            errorForm.textContent = "Please enter a message";
            errorForm.classList.remove('d-none');
        }
        if (message.value.length > 1000) {
            errorForm.textContent = "No longer than 1000 characters please";
            errorForm.classList.remove('d-none');

        }
        else {
            let userId = <?php echo $unique_id; ?>;
            let friendId = <?php echo $friend_id; ?>;
            let msg = htmlEntities(message.value);
            let data = {
                'userId': userId,
                'friendId': friendId,
                'msg': msg,
            }
            // send data via websocket connection
            conn.send(JSON.stringify(data));
            message.value = "";
        }
    })



</script>