<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
require_once("models/master_page.php");
$euser = $_SESSION["euser"];
?>





<?php
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

echo "Subtotal: $total</html>";

<!-- Footer-->
require 'models/footer.php';
?>
