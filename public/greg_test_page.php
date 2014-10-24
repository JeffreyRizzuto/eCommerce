<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

    //This is my test code dont touch it :O
    $fname = "Greg";
    $lname = "Gomez";
    $pn = "2101234567";
    $type = "both";
    $st = "street";
    $no = 1;
    $city = "satown";
    $state = "TX";
    $zip = 78258;

    $euser = new EUser($loggedInUser->username, "qwerty12", $loggedInUser->email, $fname, $lname, $pn);
    $euser->addAddress($type, $st, $no, $city, $state, $zip);
    $euser->addEUser();

    $_SESSION["eUser"] = $euser;

    $isbn = 9780132492676;
    $qty = 2;
    //$euser->getCart();
    $euser->addToCart($isbn, $qty);

    echo "Success. Continue to ".<a href="cart.php">."cart.php";

?>