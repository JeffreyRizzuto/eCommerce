<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
require_once("models/master_page.php");
$euser = $_SESSION["euser"];
?>

<div class="col-md-5 col-sm-12">
    <?php
    $cartInfo = $euser->getCartInfo();
    $total = $euser->getCartTotal();
    ?>

    <ul>
    <?php
    foreach ($cartInfo as $c) {
        echo "<div class='panel panel-default' >
            <div class="panel-body">
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
        ?>
        <li class="row totals">
            <span class="itemName">Total:</span>
            <span class="price"><?php $total ?></span>
            <span class="order"> <a class="text-center">ORDER</a></span>
    </ul>
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