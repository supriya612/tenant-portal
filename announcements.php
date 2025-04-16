<?php
session_start();

require_once 'classes/database.php';


if(!isset($_SESSION['tenant_id']))
{
    header('Location:index.php');
    exit;
}

try
{
    $db=(new Database())->getConnection();
    if(!$db)
    {
        die("Failed to get database connection");
    }
$stmt=$db->prepare("select title,message, posted_at from announcements order by posted_at desc");
$stmt->execute();
$announcements=$stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e)
{
    $announcements=[];
    $error_message='Error fetching announcements';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Announcements</title>
        <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Announcements</h1>
    <?php if(isset($error_message)): ?>
        <p><?php echo $error_message; ?></p>
        <?php elseif(empty($announcements)): ?>
            <p>No announcements yet.</p>
            <?php else: ?>
            <div>
                <?php foreach($announcements as $ann): ?>
                    <div class="announcement">
                    <strong><?php echo $ann['title']; ?></strong>
                    <?php echo $ann['title']; ?> <?php echo $ann['posted_at']; ?></li>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <p><a href="dashboard.php">Back to Dashboard</a></p>
                </body>
                </html>
                