<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
require_once("models/master_page.php");
$euser = $_SESSION["euser"];
?>

<div class="col-md-5 col-sm-12 pull-left">
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
                <div class='panel panel-default' >
                    <div class='panel-body'>
                    <span class=''><img src=" . $c['pic'] . "></span>
                    <ul class='pull-right' style='list-style-type:none'>
                        <li><span class=''>ISBN: " . $c['isbn'] . "</span></li>
                        <li><span class='Quantity: '>Quantity: " . $c['qty'] . "</span></li>
                        <li><span class='Date Added: '>Date Added: " . $c['date'] . "</span></li>
                    </ul>
                    </div>
                </div>
            ";
            }
        }//end of else
        ?>
        <div class='panel panel-default' >
            <div class='panel-body'>
            <span>Total: <?php echo "$total"; ?></span>
            <span class="order pull-right"> <a class="text-center">
            <?php
            echo "
                <form name='checkout' action='".$_SERVER['PHP_SELF']."' method='post'>
                    <input type='hidden' id='checkout' name='checkout'/>
                    <input type='Submit' value='Checkout' />
                </form><br>";
            ?>
            </a></span>
        </div>
</div>

<div class="col-md-7 col-sm-12 text-left">
    <div class="bigcart"></div>
    <h1>Your shopping cart</h1>
    <p>
        Something Something shopping cart
    </p>
</div>

<?php
require 'models/footer.php';
?>