<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
require_once("models/master_page.php");
$euser = $_SESSION["euser"];
?>

<div class="list-group col-md-5 col-sm-12">
    <?php
    $cartInfo = $euser->getCartInfo();
    $total = $euser->getCartTotal();
    ?>

    <ul>
    <?php
    foreach ($cartInfo as $c) {
        echo "<li class='jumbotron row'>
            <span class='Picture:'><img src=" . $c['pic'] . "></span>
            <div class 'list-group'>
                <li><span class='OID: '>" . $c['isbn'] . "</span></li>
                <li><span class='ISBN: '>" . $c['isbn'] . "</span></li>
                <li><span class='Quantity: '>" . $c['qty'] . "</span></li>
                <li><span class='Date Added: '>" . $c['date'] . "</span></li>
            </div>
        ";
        }
        ?>
        <li class="row totals">
            <span class="itemName">Total:</span>
            <span class="price"><?php $total ?></span>
            <span class="order"> <a class="text-center">ORDER</a></span>
        </li>
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