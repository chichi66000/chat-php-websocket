<?php

<!-- Error validate image -->
 if(!empty($e)): ?>
    <div class="alert alert-danger">
        <?php foreach ( $e as $err): ?>
            <?= $err . "<br>" ?> 
        <?php endforeach; ?>
    </div>
<?php endif ?>


// public function getUser(string $field, string $value) 
    // {
    //     $query = $this->pdo->prepare("SELECT * FROM user WHERE $field = :field");
        
    //     $r = $query->execute([":field" => $value]);
    //     // dd($query);
    //     $result = $query->fetchAll();
    //     if (empty($result)) {
    //         // http_response_code(400);
    //         // throw new \PDOException("User not founded");
    //         return [];
    //     }
    //     else {
    //         return $result[0];
    //     }
    // }

    function getAllUsers (string $key, string $value) {
        dump($value);
        $users = (new ConnectionPDO)->getAllUsersExceptOne($key, $value);
        return $users;
    }

    ?>



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