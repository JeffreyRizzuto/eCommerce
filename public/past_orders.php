<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

//var_dump($info);

if(is_null($info)) {
	echo "You have no past orders.<br>";
} else {
	foreach($info as $inf) {
		echo "Order #:			".$inf['oid']."<br><br>";	
		foreach($inf['o_inf'] as $i) {
   			echo "
                             Title:                     ".$i['title']."<br>
      			ISBN:			".$i['isbn']."<br>
      			Quantity:		".$i['qty']."<br>
      			Date Added:		".$i['date']."<br>
                             Price:                     ".$i['book_price']."<br><br>
   			";
   		}//end of inner foreach
		echo "Total Price: $".$inf['price']."<br><hr>";
	}//end of outer foreach
}//end of else
?>