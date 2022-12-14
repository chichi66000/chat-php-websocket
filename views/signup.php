<?php

require_once ("../src/config/configCloudinary.php");
require_once ("../src/config/load_env.php");

use App\Form;
use App\Validate;
use App\Connection;
use App\ConnectionPDO;
use App\ValidateUploadFile;
use Cloudinary\Api\Upload\UploadApi;

$errors = [];
$link = $router->url('users');

if ( empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['password']) || (empty($_FILES['file']))) 
{
    $errors = ['champs' => 'Please give us your information'];

}
else {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // validate the entry data
    $v = new Validate($_POST);

    // validate ok
    if($v->validateSignup()) {
        $connect = Connection::getPDO();
        $pdo = new ConnectionPDO($connect);
        $ok = $pdo->checkIfUserExists('email', $email);
        dump($ok);
        // check if email exists : false = user does not exist
        if( empty($ok)) {
                // validate image : pas plus de 2Mo; format .jpg jpeg, png
            if (isset($_FILES['file']) && $_FILES['file']['name'] !== "")
            {
                $image = new ValidateUploadFile($_FILES['file']);
                $check = $image->checkFile();

                if ($check === true) 
                {
                    $upload = new UploadApi();
                    // $upload = new CloudinaryUpload();
                    try {
                        $data = $upload->upload($_FILES['file']['tmp_name']);
                        $status = "Online";
                        $random_id = rand(time(), 100000);
                        $created_at = (new \DateTime())->format('Y-m-d H:i:s');
                        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                        // insert into table user
                        $result = $pdo->insertUser($random_id, $first_name, $last_name, $email, $passwordHash, $data['secure_url'], $status, $created_at);
                        
                        // insert ok
                        if ($result === true) {
                            session_start();
                            $_SESSION['unique_id'] = $random_id;
                            header('Location: ' . $link);
                            exit();
                        }
                        else {
                            $errors[] = "Problem to insert user into table user";
                        }
                    }
                    // error upload file to Cloudinary
                    catch (\Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                }
                // validate image not ok
                else {
                    $errors[] = $image->getMessageError();
                }
            }
            // pas image 
            else {
                $errors[] = "Please send a photo for your profile";
            }
        }
        // email exist in table user
        else {
            http_response_code(400);
            $errors[] = "Email is already in use";
        }
    }
    // validate not ok
    else {
        $err = $v->getErrors();
        $errors= $v->changeErrorMessage($err);
        // check if there is password error; then add the message 
        if (strpos(json_encode($errors),'Password') !== false) {
            $errors[] = "Password must be between 8 and 20 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character(!@#\$%\^&\*.) ";
        }
    }
}

$messagesArray = [];
if(!empty($errors)) {
    foreach($errors as $key=> $error) {
        if (is_array($error)) {
            foreach($error as $value) {
                $messagesArray[] = $value;
            }
        }
        else {
            $messagesArray[] = $error;
        }
    }
}

$form = new Form($_POST, $errors);
?>

<header class="my-2 mx-auto col p-2 text-center fs-1 " style="color: #2E4234" >Realtime Chat App</header>

<section class="container mx-auto p-4 col-md-6 col-lg-5 border border-1">
    <form method="post" enctype="multipart/form-data" class="form-group mx-auto signup">

        <!-- Error validate input -->
        <?php if(!empty($messagesArray)): ?>
            <div class="alert alert-danger">
                <?php foreach ( $messagesArray as $err): ?>
                    <?= $err . "<br>" ?>
                <?php endforeach; ?>
            </div>
        <?php endif ?>

        <!-- the input fields generated by class Form -->
        <?= $form->input('first_name', 'First name', 'text') ?>
        <?= $form->input('last_name', 'Last name', 'text') ?>
        <?= $form->input('email', 'Email', 'email') ?>
        <?= $form->input('password', 'Password', 'password') ?>
        <?= $form->input('file', 'Image Profile (Max: 2Mo)', 'file') ?>

        <div class="mx-auto bg-black rounded text-center my-2 ">
            <input class="btn text-white" id="btnSubmit" type="submit" value="Continue to chat">
        </div>
    </form>

    <div>Already to chat?
        <a href="<?= $router->url('login') ?>" class="">Login now</a>
    </div>

</section>

<script src="showhidepass.js">
</script>