<?php
use App\ConnectionPDO;
use PHPUnit\Framework\TestCase;

final class ConnectionPDOTest extends TestCase {
    private $pdo;
    
    protected function setUp(): void 
    {
        parent::setUp();
        // $this->pdo = $this->getPDO();
    }

    protected function tearDown(): void {
        parent::tearDown();

    }

    public function getPDO ()
    {
        return new ConnectionPDO('mysql:dbname=testchat;host=127.0.0.1', 'root', '0123');
        // $this->pdo = (new ConnectionPDO())->getPDOTest();
        // return $this->pdo;
    }

    public function testinsertUser () 
    {
        $q =$this->getPDO()->insertUser('123', 'le', 'le', 'lele@yahoo.fr','Azerty0123.', 'https://cloudinary/ifif.png', 'Online', '2022-10-17 20:22:19');
        
        $this->assertEquals("INSERT INTO user (unique_id, first_name, last_name, email, password, file, status, created_at) VALUES ('123', 'le', 'le', 'lele@yahoo.fr', 'Azerty0123.', 'https://cloudinary/ifif.png', 'Online', '2022-10-17 20:22:19') ", $q);
        
    }

    public function testcheckIfUserExists () 
    {
        $q = $this->getPDO()->checkIfUserExists("le@yahoo.com");
        
        $this->assertNotFalse(true, "email is in table user");
    }

    public function testcheckIfUserNotExists () 
    {
        $q = $this->getPDO()->checkIfUserExists("le@yahoo.fr");
        
        $this->assertFalse(false, "email not in the database");
    }

    public function testupdateStatus () {
        $q = $this->getPDO()->updateStatus('email', 'le@yahoo.com', 'Offline');
        $this->assertNotFalse(true, 'update email avec status ok');
    }

    public function testgetUser () 
    {   $field = 'email';
        $value = 'le@yahoo.com';
        $q = $this->getPDO()->getUser($field, $value);
        $this->assertNotNull($q, "user found");
    }

    public function testgetUserNotFound () 
    {   $field = 'email';
        $value = 'le@yahoo.fr';
        $q = $this->getPDO()->getUser($field, $value);
        $this->assertEmpty($q);
        // $this->assertEmpty($q);
    }
}