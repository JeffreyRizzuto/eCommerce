<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
require_once("models/master_page.php");
$euser = $_SESSION["euser"];
?>


<div class="col-xs-12 col-sm-5 col-md-5 text-left pull-right">
    <div class="bigcart"></div>
    <h1>Your shopping cart</h1>
    <p>
        <a href='index.php'>Continue Shopping</a>
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
                        <li>Title: " . $c['title'] . "</li>
                        <li>ISBN: " . $c['isbn'] . "</span></li>
                        <li>Quantity: <input type='text' id='qty' name='quantity' value='".$c['qty']."'/></span><input type='Submit' value='Update Quantity' /></li>
                        <li>Date Added: " . $c['date'] . "</span></li>
                        <input type='hidden' id='qty_isbn' name='qty_isbn' value='".$c['isbn']."'/>
                        </form><li>Price: $".getPrice($c['isbn'])."</li>
                        <form name='deleteFromCart' action='".$_SERVER['PHP_SELF']."' method='post'><li><input type='Submit' value='Delete' /></li>
                        <input type='hidden' id='del_isbn' name='del_isbn' value='".$c['isbn']."'/>
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
                <form name='checkout' action='".$_SERVER['PHP_SELF']."' method='post'>";
                    if(!is_null($cartInfo)) {
                        echo "<input type='hidden' id='checkout' name='checkout'/>
                        <button class='btn btn-success' value='Checkout'>Checkout</button>";
                    } else {
                        echo "<button class='btn btn-disabled' value='Checkout'>Checkout</button>";
                    }
                    echo "</form><br>";
            ?>
                    <!--<input type='Submit' value='Checkout' /> -->
            </a></span>
        </div>
</div>

<?php
require 'models/footer.php';
?>