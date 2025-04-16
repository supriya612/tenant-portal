<?php

header('Content-Type:application/json');

require_once 'C:/xampp/htdocs/tenant_hub/classes/Database.php';

session_start();

if(!isset($_SESSION['tenant_id']))
{
    http_response_code(401);
    echo json_encode(['error' =>'Unauthorized']);
    exit;
}

try
{
    $db=(new Database())->getConnection();
    $stmt=$db->prepare("SELECT amount,payment_date FROM rent_payments WHERE tenant_id = ? ORDER BY payment_date DESC");
    $stmt->execute([$_SESSION['tenant_id']]);
    echo json_encode(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
   
}
catch(Exception $e)
{
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch payments: ' . $e->getMessage()]);

}
?>