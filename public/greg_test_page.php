<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

    $euser = $_SESSION['euser'];

    echo "Testing the checkout function<br><hr>";
?>
<div class="col-md-5 col-sm-12 pull-left">
    <?php
    $cartInfo = $euser->getCartInfo();
    $total = $euser->getCartTotal();
    ?>

    <ul>
    <?php
    foreach ($cartInfo as $c) {
        echo "<div class='panel panel-default' >
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
        ?>
        <div class='panel panel-default' >
            <div class='panel-body'>
            <span>Total: <?php echo "$total"; ?></span>
            <span class="order pull-right"> <a class="text-center">ORDER</a></span>
        </div>
</div>
<?php

    $euser->checkout();
?>

<div class="col-md-5 col-sm-12 pull-left">
    <?php
    $cartInfo = $euser->getCartInfo();
    $total = $euser->getCartTotal();
    ?>

    <ul>
    <?php
    foreach ($cartInfo as $c) {
        echo "<div class='panel panel-default' >
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
        ?>
        <div class='panel panel-default' >
            <div class='panel-body'>
            <span>Total: <?php echo "$total"; ?></span>
            <span class="order pull-right"> <a class="text-center">ORDER</a></span>
        </div>
</div>