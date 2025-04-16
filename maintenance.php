<!-- Maintenance request page -->

<?php
session_start();

if(!isset($_SESSION['tenant_id']))
{
    header('Location:index.php');
    exit;
}

require_once 'classes/Maintenance.php';
$maintenance=new Maintenance();
$messagee='';

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $details=$_POST['details'];
    if($maintenance->submitRequest($_SESSION['tenant_id'],$details))
    $message='Request submitted successfully!';
}
else
{
    $message='Failed to submit request.';
}
 
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Maintenance Request</title>
        <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <h1>Submit Maintenance Request</h1>
    <form method="POST">
        <textarea name="details" placeholder="Describe your issue" required></textarea><br>
        <button type="submit">Submit </button>
</form>
<p><?php echo $message; ?></p>
<a href="dashboard.php">Back to Dashboard</a>
<?php include 'includes/footer.php'; ?>

</body>
</html>
