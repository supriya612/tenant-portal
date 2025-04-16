

<?php

//Database connection classes 

class Database
{
    private $host='localhost';
    private $db='tenant_hub';
    private $user='root';
    private $pass='';
    private $conn;

    public function __construct()
    {
        try{
            $this->conn=new PDO("mysql:host=$this->host;dbname=$this->db",$this->user,$this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
             $this->conn=null;
        }
    }
    public function getConnection()
    {
        return $this->conn;
    }
}
?>