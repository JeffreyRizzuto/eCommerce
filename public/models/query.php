<?php
//Query object for getting our information

$edb_host = "localhost";
$edb_name = "ecommerce";
$edb_user = "forge";
$edb_pass = "roadrunners";

$myQuery = new mysqli($edb_host, $edb_user, $edb_pass, $edb_name);
GLOBAL $myQuery;

if(mysqli_connect_errno()) {
    echo "Connection Failed: " . mysqli_connect_errno();
    exit();
}
?>
