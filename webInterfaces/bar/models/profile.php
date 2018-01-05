<?php

class Profile{

    private $email;
    private $fullname;
    private $pwHash;
    private $role;

    public function __construct(string $email, 
                                string $fullname=null, 
                                string $pwHash, 
                                int    $role=1){

        $this->conn     = $conn;
        $this->db       = $db;
        $this->email    = $email;
        $this->fullname = $fullname;
        $this->pwHash   = $pwHash;
        $this->role     = $role;
    }

    /**
     * Creates this current profile in the db
     * @return bool True/Exception depending on success or not
     */
    public function insertToDb(Connection $conn, Crud $db){
        $mail     = $this->email;
        $fullName = $this->fullname;
        $upass    = $this->pwHash;
        $role     = $this->role;

        try{
            $table = 'profiles';
            $data = ['umail' => $mail, 'upass' => $password, 'fullName' => $fullName, 'role' => $role];
            $db->create($table, $data);
        } catch (Exception $e){
            return $e.getMessage();
        }

        return true;
    }

}

?>
