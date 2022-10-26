<?php
namespace App;

class Form {

    private $data;
    private $errors;

    public function __construct($data, array $errors=[]) 
    {
        $this->data= $data;
        $this->errors= $errors;
    }

    // get value of input from $_POST; ex : $_POST['email']
    public function getValueInput (string $key) 
    {
        return $this->data[$key]?? null;
    }

    public function input ( string $key, string $label, string $type, ?string $classList=""): string
    {
        $value = $this->getValueInput($key);
        if ($type === "password") {
            $classList = "bi bi-eye-fill input-group-text btn bg-warning";
            return <<<HTML
            <div class="form-group mt-1 p-1">
                <label for="{$key}" class="">$label *</label>
                <div class="input-group flex-nowrap field">
                    <input type="{$type}" class="form-control" placeholder="{$label}" name="{$key}" id="{$key}" value = "{$value}" required>              
                    <i class="{$classList}" ></i>
                </div>
            </div>
HTML;
        }
        else {
            return <<<HTML
            <div class="form-group mt-1 p-1">
                <label for="{$key}" class="">$label *</label>
                <input type="{$type}" class="form-control" placeholder="{$label}" name="{$key}" id="{$key}" value = "{$value}" required>
            </div>
HTML;
        }
    }


    // public function showHidePassword (string $type , string $classList) 
    // {
    //     $classList = "bi bi-eye-fill input-group-text btn bg-danger";
    //     if ($type === "text") {
    //         $type === 'password';
    //         dd($type);

    //         str_replace("bi-eye-fill", "bi-eye-slash-fill", $classList);
    //     }
    //     else {
    //         $type === 'text';
    //         str_replace("bi-eye-slash-fill", "bi-eye-fill", $classList);
    //     }
    // }
}