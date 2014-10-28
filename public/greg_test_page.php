<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

    $euser = $_SESSION['euser'];

    echo "Testing the checkout function<br>";
    echo "Old cart: $euser->cart";

    $euser->checkout();

    echo "New cart: $euser->cart<br>";


?>