<?php
namespace App;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryUpload {
    private $upload;

    public function __construct () {
        $this->upload = new UploadApi();
    }
    
}