<?php
session_start();


use App\ConnectionPDO;
if(isset($_SESSION['unique_id'])) {
    $unique_id = $_SESSION['unique_id'];
    $status = 'Offline';
    $field = 'unique_id';
    $pdo = new ConnectionPDO();
    $pdo->getUser('unique_id', $unique_id);
    $pdo->updateStatus($field, $unique_id, $status);
    session_unset();
    session_destroy();
    http_response_code(200);
    header("Location: " . $router->url('login'));
    exit();
}
else {
    http_response_code(400);
    $error = "Somthing went wrong!";
    session_unset();
    session_destroy();
    header("Location: " . $router->url('login'));
    exit();

}
?>
<div class="alert alert-danger">
    <?php if (!empty($error)): ?>
        <?= $error; ?>
    <?php endif ?>
</div>