<?php
use Cloudinary\Configuration\Configuration;
// use Cloudinary\Api\Upload\UploadApi;

require_once 'load_env.php';

Configuration::instance([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD'], 
      'api_key' => $_ENV['CLOUDINARY_APIKEY'], 
      'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
        'url' => [
      'secure' => true]
    ]]);


