<?php
header('Content-Type:application/json');

require_once 'C:/xampp/htdocs/tenant_hub/classes/Database.php';
try{
    $db=(new Database())->getConnection();
    if(!$db)
    {
        throw new Exception('Database connection failed');
    }

    $stmt=$db->prepare("SELECT title,message,posted_at FROM announcements ORDER BY posted_at DESC");
    $stmt->execute();
    $announcements=$stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['data' =>$announcements]);
}
catch(Exception $e)
{
    http_response_code(500);
    echo json_encode(['error' =>"Failed to fetch announcements: " . $e->getMessage()]);

}

?>