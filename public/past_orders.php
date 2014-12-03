<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

//var_dump($info);

if(is_null($info)) {
	echo "<div class='panel panel-default'>
                <div class='panel-body'>
                    <ul class='pull-left' style='list-style-type:none'>
                        <h1>You have no past orders.</h1>";
} else {
	foreach($info as $inf) {
		echo "<h1>Order #:			".$inf['oid']."</h1><br><br>";
		foreach($inf['o_inf'] as $i) {
   			echo "

                        <li>Title:          ".$i['title']."</li>
                        <li>ISBN:			".$i['isbn']."</li>
                        <li>Quantity:		".$i['qty']."</li>
                        <li>Date Added:		".$i['date']."</li>
                        <li>Price:          ".$i['book_price']."</li>
   			";
   		}//end of inner foreach
		echo "Total Price: $".$inf['price']."
		            </ul>
                </div>
            </div>";
	}//end of outer foreach
}//end of else
?>