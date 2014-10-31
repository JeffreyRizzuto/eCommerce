<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

foreach($info as $inf => $in) {
	echo "Order #:        ".$info[$inf]."<br>";
	foreach($in as $i) {
   		echo "
      		ISBN:             ".$i['isbn']."<br>
      		Quantity:       ".$i['qty']."<br>
      		Date Added:  ".$i['date']."<br><hr>
   		";
   	}
}

?>