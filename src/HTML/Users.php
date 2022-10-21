<?php
namespace App\HTML;
use App\ConnectionPDO;
use App\Router;

class Users {
    public $users;
    public $user;
    public $user_key;
    public $user_value;

    public function __construct(string $user_key, string $user_value) {
        $this->user_key = $user_key;
        $this->user_value = $user_value;
        $this->user = (new ConnectionPDO)->checkIfUserExists($user_key, $user_value);
        $this->users = (new ConnectionPDO)->getAllUsersExceptOne($user_key, $user_value);
        
    }

    public function renderAllUser (array $users) 
    {
        if (empty($users)) {
            return "No users found";
        }

        $html = "<div class='user-list d-flex justify-content-between flex-wrap'>";
        
        foreach($users as $user) {
            $class = $user['status'] === 'Online' ? 'bi bi-circle-fill text-success' : 'bi-circle-fill text-danger';
            $html .= "<a href= \"chat/id={$user['unique_id']} \" class='p-1 d-flex flex-wrap flex-row'>
                        <img src=\"{$user['file']}\" class='img img-fluid rounded-circle' alt='img profile' style=\"width:3rem; height: 3rem \">
                        <div>
                            <span>{$user['first_name']}  {$user['last_name']}</span>
                        </div>
                    </a>
                    <div class='user-status align-self-center'>
                        <i class=\"{$class}\"></i>
                    </div>
                    "
            ;
        }
        $html .= "</div>";
        return $html;
    }
}


