<?php

require("models/config.php");
require("models/master_page.php");

$euser = $_SESSION['euser'];

$info = $euser->getPastOrders();

//var_dump($info);
?>


<div class='container center-block'>
    <div id='top'><div id='logo'></div></div>
        <div id='content'>
            <h2>Account</h2>
        </div>
        <div class 'row'>
         <div class'rcol-md-4' id='leftnav'>
             <?php
            require_once("models/account-nav.php");
            ?>
        </div>
    <div class'col-md-8' id='content'>
<?php
    if(is_null($info)) {
        echo "<h3>You have no past orders.</h3>";
    } else {
        foreach($info as $inf) {
            echo "

                <div class='panel panel-default'>
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
    </div>
</div>
    <!-- Footer-->
    <?php   require 'models/footer.php';  ?>
