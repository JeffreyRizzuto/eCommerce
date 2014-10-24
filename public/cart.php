<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }


/*
Example of getting the cart info and total and the way to access these values

$euser = $_SESSION["eUser"];
$cartInfo = $euser->getCartInfo();
$total = $euser->getCartTotal();
foreach ($cartInfo as $c) {
	echo "
	OID: 		".$c['oid']."<br>
	ISBN:		".$c['isbn']."<br>
	Quantity:	".$c['qty']."<br>
	Date Added:	".$c['date']."<br>
	Picture:	<img src=".$c['pic']."><br>
	<hr>";
}

echo "Subtotal: $$total</html>";
*/


echo "<html>Someone else can make this pretty. I am just doin my work<br><hr>";

$euser = $_SESSION["eUser"];

$cartInfo = $euser->getCartInfo();
$total = $euser->getCartTotal();


foreach ($cartInfo as $c) {
	echo "
	OID: 		".$c['oid']."<br>
	ISBN:		".$c['isbn']."<br>
	Quantity:	".$c['qty']."<br>
	Date Added:	".$c['date']."<br>
	Picture:	<img src=".$c['pic']."><br>
	<hr>";
}

echo "Subtotal: $$total</html>";

?>