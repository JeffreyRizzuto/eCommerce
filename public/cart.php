<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

echo "Someone else can make this pretty. I am just doin my work";

$euser = $_SESSION["eUser"];

$cartInfo = $euser->getCartInfo();
$total = $euser->getCartTotal();

/*
foreach ($cartInfo as $c) {
	echo "
	OID: 		$c['oid']
	ISBN:		$c['isbn']
	Quantity:	$c['qty']
	Date Added:	$c['date']";
}

echo "Subtotal: $$total";
*/
?>