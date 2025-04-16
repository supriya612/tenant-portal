<!-- Logout Script -->

<?php

require_once 'classes/Tenant.php';
$tenant=new Tenant();
$tenant->logout();
header('Location:index.php');
exit;
?>