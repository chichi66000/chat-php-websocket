<?php
session_start();
// require_once ("../src/config/configPDO.php");

use App\Form;
use App\Validate;
use App\Connection;
use App\ConnectionPDO;


$errors = [];
$link = $router->url('users');
$form = new Form($_POST, $errors);

if (empty($_POST) || !isset($_POST['email']) ||  !isset($_POST['password'])) {
    http_response_code(400);
    
    // $errors[] = "Please enter email and password";
}
else {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // validate the entry data
    $v = new Validate($_POST);
    // validate ok => 
    if ($v->validateLogin()) 
    {
        // check if email exist in database
        $connect = Connection::getPDO();
        $pdo = new ConnectionPDO($connect);
        $result = $pdo->checkIfUserExists('email', $email);
        // user isn't in table user
        if (empty($result)) {
            http_response_code(400);
            $errors[] = "Email / password invalid";
        }
        // user founded verify password
        else {
            $user = $result[0];
            // dd($user);

            // $user = $pdo->getUser('email', $email);
            // dd($user['password']);
            // user founded
            if (!empty($user)) {
                if(password_verify($password, $user['password']) === true) {
                // update status = Online; login to page Users
                $status = "Online";
                $pdo->updateStatus('email', $email, $status);
                
                http_response_code(200);
                session_start();
                $_SESSION['unique_id'] = $user['unique_id'];
                header("Location: " . $router->url('users'));
                }
                // password false
                else {
                    http_response_code(400);
                    $errors[] = "Email / password invalid";
                }
            }
            // user vide
            else {
                http_response_code(400);
                $errors[] = "Email / password invalid";
            }
            
        }
    }
    // validate email & password not ok 
    else {
        // $err = $v->getErrors();
        // $errors= $v->changeErrorMessage($err);
        // if (strpos(json_encode($errors),'Password') !== false) {
        //     $errors[] = "Password must be between 8 and 20 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character(!@#\$%\^&\*.) ";
        // }
        $errors[] = "Email / password invalid";
    }
}

?>


<header class="my-2 mx-auto col p-2 text-center fs-1" style="color: #2E4234">Login</header>

<section class="container mx-auto p-4 col-md-6 col-lg-5 border border-1 h-auto vh-50">
    <form action="" method="post" class="form-group mx-auto p-1 ">
        <!-- Error validate input -->
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ( $errors as $err): ?>
                    <?= $err . "<br>" ?>
                <?php endforeach; ?>
            </div>
        <?php endif ?>

        <?= $form->input('email', 'Email', 'email') ?>
        <?= $form->input('password', 'Password', 'password') ?>
        <div class="col mx-auto w-25 bg-black rounded text-center my-2">
            <input class="btn text-white" type="submit" value="Login">
        </div>

    </form>

    <div class= "p-1">Not yet have account?
        <a href="<?= $router->url('signup') ?>" class="">Sign up</a>
    </div>
</section>

<script src="showhidepass.js">
</script>