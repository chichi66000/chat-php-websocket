<?php
    $connect = new PDO('mysql:dbname=chat; host=127.0.0.1', 'root','0123', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    
?>