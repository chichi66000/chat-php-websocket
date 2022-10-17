<?php
namespace App;

class ValidateUploadFile {
    private $file;
    private $upload;
    private $errors = [];
    private const MAXSIZE = 2097152; // 2Mo

    public function __construct (array $file, array $errors = []) {
        $this->file = $file;
        $this->errors = $errors;
    }

    public function checkFile (): bool 
    {   
        // if (!empty($this->file)) {
            $img_name = $this->file['name'];
            $img_size = $this->file['size'];
            $img_type = $this->file['type'];
            $img_tmp_name = $this->file['tmp_name'];
            $img_error = $this->file['error'];
            // extraire extention .jpg .png
            $extract = explode('.', $img_name);
            $extentions = ['jpg', 'jpeg', 'png'];
            $type = ['image/jpg', 'image/jpeg', 'image/png'];
            // check if isValid format type, size & no error
        
            if ($img_size > self::MAXSIZE) {
                $this->errors[] =" file too big, must be under 2M";
                // echo " file too big, must be under 2M";
                return false;
            }
            if (in_array(strtolower(end($extract)), $extentions) === false) {
                $this->errors[] = "File Image format must be .jpg, .png, .jpeg";
                return false;
            }
            
            else return true;
    }

    public function getMessageError (): array 
    {
        return $this->errors;
    }
    
}