<?php

require_once 'classes/tenant.php';
$tenant=new tenant();
$message='';

if($_SERVER['REQUEST_METHOD']=='POST')
{
  $username=$_POST['username'];
  $password=$_POST['password'];

  if($tenant->login($username,$password))
  {
    header('Location:dashboard.php');
    exit;

  }
  else
  {
    $message='Invalid Credentials';
  }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Tenant Hub Login</h1>
    <form method="POST">
      <label for="username">Username</label>
        <input type="text" name="username" placeholder="username" required><br>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="password" required><br>
        <button type="submit">Login</button>
</form>
<p><?php echo $message; ?></p>
</body>
</html>