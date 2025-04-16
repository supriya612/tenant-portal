<!-- Tenant management class -->


<?php

require 'Database.php';
class Tenant
{
    private $db;

    public function __construct()
    {
        $this->db=(new Database())->getConnection();
    }

    public function login($username,$password)
    {
        $stmt=$this->db->prepare("SELECT * FROM tenants WHERE user_name= :user_name");
        $stmt->execute(['user_name' => $username]);
        $tenant=$stmt->fetch(PDO::FETCH_ASSOC);

        if($tenant && password_verify($password,$tenant['password']))
        {
            session_start();
            $_SESSION['tenant_id']=$tenant['id'];
            $_SESSION['full_name']=$tenant['full_name'];
            return true;
        }
        return false;
    }

    public function logout()
    {
        session_start();
        session_destroy();
    }
}
?>