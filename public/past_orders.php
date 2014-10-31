<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

var_dump($info);

foreach($info as $inf) {
	if(!is_array($inf)) {
		echo "Order #:			".$inf."<br>";	
	} else {
		foreach($inf as $i) {
   			echo "
      			ISBN:			".$i['isbn']."<br>
      			Quantity:		".$i['qty']."<br>
      			Date Added:		".$i['date']."<br><hr>
   			";
   		}//end of inner foreach
	}//end of else 
}//end of outer foreach

?>