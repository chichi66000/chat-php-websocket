<!-- <?php
namespace App;

require_once ((__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'configCloudinary.php');
require_once ((__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . "load_env.php");

use Cloudinary\Api\Upload\UploadApi;

class CloudinaryUpload extends UploadApi {
    private $upload;
    private $file;

    public function __construct () {
        $this->upload = new UploadApi();
        // $this->file = $file;
    }
    
    public function destroyFile(string $id) 
    {
        try {
            $this->upload->destroy($id);
        }
        catch (\Exception $e) {
            $e->getMessage();
        }
    }
} -->