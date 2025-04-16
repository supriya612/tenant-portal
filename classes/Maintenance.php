<!-- maintenance request class -->

<?php
require 'Database.php';
class Maintenance
{
    private $db;

    public function __construct()
    {
        $this->db=(new Database())->getConnection();
    }

    public function submitRequest($tenant_id,$details)
    {
        $stmt=$this->db->prepare("INSERT INTO maintenance_requests(tenant_id,request_details) VALUES (:tenant_id, :details)");
        return $stmt->execute(['tenant_id' =>$tenant_id,'details' => $details]);
    }
}
?>
