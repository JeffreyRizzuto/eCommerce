<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

foreach($info as $i) {
   echo "
      Order #:        ".$i['order']."<br>
      ISBN:             ".$i['isbn']."<br>
      Quantity:       ".$i['qty']."<br>
      Date Added:  ".$i['date']."<br><hr>
   ";
}

?>