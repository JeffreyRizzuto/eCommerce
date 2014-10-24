<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

echo "Someone else can make this pretty. I am just doin my work<br>";

$euser = $_SESSION["eUser"];

$cartInfo[] = $euser->getCartInfo();
$total = $euser->getCartTotal();

echo "<html>";
foreach ($cartInfo as $c) {
	echo "
	OID: 		$c['oid']<br>
	ISBN:		$c['isbn']<br>
	Quantity:	$c['qty']<br>
	Date Added:	$c['date']<br><hr>";
}

echo "Subtotal: $$total<br>";


echo "</html>";

?>