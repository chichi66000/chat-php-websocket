<?php
namespace App;
use Valitron\Validator;

class Validate extends Validator {
    private $validator;
    public function __construct () {
        $this->validator = new Validator($_POST);
    }

    public function changeErrorMessage (array $errors) : ?array 
    {
        // if (!empty($errors)) {
            $messagesArray = [];
            foreach ( $errors as $key=> $value) {
                if(is_array($value)) {
                    foreach($value as $va) {
                        $messagesArray [] = $va;
                    }
                } else {
                    $messagesArray [] = $value;
                }
            }
            return $messagesArray;
        // }
    }
    
    public function validateSignup () 
    {
        $this->validator
            ->rule('required', ['first_name', 'email', 'last_name', 'password'])
            ->rule('email', 'email')
            ->rule('lengthBetween', 'password', 8 , 20)
            ->rule('alpha', ['first_name','last_name'] )
            ->rule('regex', 'password', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*.]).{8,20}$/');
        return $this->validator->validate();;
    }

    public function validateLogin () 
    {
        $this->validator
            ->rule('required', ['email', 'password'])
            ->rule('email', 'email')
            ->rule('regex', 'password', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*.]).{8,20}$/');
        return $this->validator->validate();;
    }

    public function getErrors (): ?array 
    {
        return $this->validator->errors();
    }
}