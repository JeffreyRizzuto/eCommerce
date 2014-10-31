<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

var_dump($info);

foreach($info as $inf) {
	echo "Order #:			".$inf['oid']."<br>";	
	if(is_array($inf)) {
		//foreach($inf as $i) {
   			echo "
      			ISBN:			".$inf['o_inf']['isbn']."<br>
      			Quantity:		".$inf['o_inf']['qty']."<br>
      			Date Added:		".$inf['o_inf']['date']."<br>
   			";
   		//}//end of inner foreach
	}//end of if 
	echo "Total Price: $".$inf['price']."<br><hr>";
}//end of outer foreach

?>