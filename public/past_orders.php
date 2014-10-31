<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

//var_dump($info);

foreach($info as $inf) {
	echo "Order #:			".$inf['oid']."<br>";	
	foreach($inf['o_inf'] as $i) {
   		echo "
      		ISBN:			".$i['isbn']."<br>
      		Quantity:		".$i['qty']."<br>
      		Date Added:		".$i['date']."<br><br>
   		";
   	}//end of inner foreach
	echo "Total Price: $".$inf['price']."<br><hr>";
}//end of outer foreach

?>