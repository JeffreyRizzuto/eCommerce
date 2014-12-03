<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

//var_dump($info);

if(is_null($info)) {
	echo "<h2>You have no past orders.</h2>";
} else {
	foreach($info as $inf) {
		echo "
            <div class='panel panel-default'>
                <div class='panel-body'>
                    <h4>Order #:			".$inf['oid']."</h4>
		            <ul class='list-group'>";
                    foreach($inf['o_inf'] as $i) {
                        echo "
                        <li class='list-group-item'>
                            <li>Title:          ".$i['title']."</li>
                            <li>ISBN:			".$i['isbn']."</li>
                            <li>Quantity:		".$i['qty']."</li>
                            <li>Date Added:		".$i['date']."</li>
                            <li>Price:          ".$i['book_price']."</li>
                        </li>
                        ";
                    }//end of inner foreach
                    echo "Total Price: $".$inf['price']."
                                </ul>
                            </div>
                        </div>";
	}//end of outer foreach
}//end of else
?>