<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
require_once("models/master_page.php");
$euser = $_SESSION["euser"];
$sd = $euser->getShippingDetails();
?>
<div class="col-xs-12 col-sm-5 col-md-5 text-left pull-right">
    <div class="bigcart"></div>
    <h1>Confirmation</h1>
    <p>
        Are you sure you would like to checkout?
    </p>
    <p>
        Please make sure the following information is correct.<br/>
        <?php echo"
            Address:                ".$sd['street']."<br>
            City:                   ".$sd['city']."<br>
            State:                  ".$sd['state']."<br>
            Zip:                    ".$sd['zip']."<br>
            Phone:                  ".$sd['phone']."<br>
        ";
        ?>
    </p>
</div>

<div class="col-xs-12 col-sm-7 col-md-7 pull-left">
    <?php
    $cartInfo = $euser->getCartInfo();
    $total = $euser->getCartTotal();
    ?>

    <ul>

    <?php
    if(is_null($cartInfo)) {
        echo "Your cart is emtpy D:<br>";
    } else {
        foreach ($cartInfo as $c) {
            echo "
                <div class='panel panel-default'>
                    <div class='panel-body'>
                    <span class=''><img src=" . $c['pic'] . "></span>
                    <ul class='pull-right' style='list-style-type:none'>
                        <form name='updateCart' action='".$_SERVER['PHP_SELF']."' method='post'>
                        <li>ISBN: " . $c['isbn'] . "</span></li>
                        <li>Quantity: <input type='text' id='qty' name='qty' value='".$c['qty']."'/></span><input type='Submit' value='Update Quantity' /></li>
                        <li>Date Added: " . $c['date'] . "</span></li>
                        <input type='hidden' id='qty_isbn' name='qty_isbn' value='".$c['isbn']."'/>
                        <li>Price: $".getPrice($c['isbn'])."</li>
                        </form>
                    </ul>
                    </div>
                </div>
            ";
            }
        }//end of else
        ?>
        <div class='panel panel-default' >
            <div class='panel-body'>
            <span>Total: $<?php echo "$total"; ?></span>
            <span class="order pull-right"> <a class="text-center">
            <?php
            echo "
                <a href='index.php'><button class='btn'>Go Back</button></a>
                <form name='confirm' action='".$_SERVER['PHP_SELF']."' method='post'>
                    <input type='hidden' id='confirm' name='confirm'/>
                    <button class='btn btn-success' value='Checkout'>Confirm</button>
                </form><br>";
            ?>
                    <!--<input type='Submit' value='Checkout' /> -->
            </a></span>
        </div>
</div>

<?php
require 'models/footer.php';
?>