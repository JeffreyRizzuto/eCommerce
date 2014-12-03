<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

//var_dump($info);

if(is_null($info)) {
	echo "<h3>You have no past orders.</h3>";
} else {
	foreach($info as $inf) {
		echo "
            <div class='panel panel-default col-xs-12 col-sm-6 col-md-6'>
                <div class='panel-body'>
                    <h3>Order #:			".$inf['oid']."</h3>
		            <ul class='list-group'>";
                    foreach($inf['o_inf'] as $i) {
                        echo "
                        <li class='list-group-item'>
                            Title:          ".$i['title']."<br>
                            ISBN:			".$i['isbn']."<br>
                            Quantity:		".$i['qty']."<br>
                            Date Added:		".$i['date']."<br>
                            Price:          ".$i['book_price']."<br>
                        </li>
                        ";
                    }//end of inner foreach
                    echo "<h4>Total Price: $".$inf['price']."</h4>
                                </ul>
                            </div>
                        </div>";
	}//end of outer foreach
}//end of else
?>