<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();


echo "Order #:".$info[0]['order'];
foreach($info as $i) {
   echo "
      ISBN:             ".$i['isbn']."<br>
      Quantity:       ".$i['qty']."<br>
      Date Added:  ".$i['date']."<br><hr>
   ";
}

?>