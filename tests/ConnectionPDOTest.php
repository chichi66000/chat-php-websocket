<?php
use PHPUnit\Framework\TestCase;

final class ConnectionPDOTest extends TestCase {

    public function getPDO (): \App\ConnectionPDO 
    {
        return new App\ConnectionPDO;
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
}