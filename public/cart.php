<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
require_once("models/master_page.php");
$euser = $_SESSION["euser"];
?>

<div class="col-md-5 col-sm-12">
    <ul>
        <li class="row list-inline columnCaptions">
            <span>QTY</span>
            <span>ITEM</span>
            <span>Price</span>
        </li>
        <li class="row">
            <span class="quantity">1</span>
            <span class="itemName">Item Name</span>
            <span class="popbtn"><a class="arrow"></a></span>
            <span class="price">$49.95</span>
        </li>
        <li class="row">
            <span class="quantity">1</span>
            <span class="itemName">Item Name</span>
            <span class="popbtn"><a class="arrow"></a></span>
            <span class="price">$5.00</span>
        </li>
        <li class="row">
            <span class="quantity">1</span>
            <span class="itemName">Item Name</span>
            <span class="popbtn"><a class="arrow"></a></span>
            <span class="price">$919.99</span>
        </li>
        <li class="row">
            <span class="quantity">1</span>
            <span class="itemName">Item Name</span>
            <span class="popbtn"><a class="arrow"></a></span>
            <span class="price">$269.45</span>
        </li>
        <li class="row">
            <span class="quantity">1</span>
            <span class="itemName">Item Name</span>
            <span class="popbtn"  data-parent="#asd" data-toggle="collapse" data-target="#demo"><a class="arrow"></a></span>
            <span class="price">$450.00</span>
        </li>
        <li class="row totals">
            <span class="itemName">Total:</span>
            <span class="price">$Something/span>
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
$cartInfo = $euser->getCartInfo();
$total = $euser->getCartTotal();

foreach ($cartInfo as $c) {
	echo "
	OID: 		".$c['oid']."<br>
	ISBN:		".$c['isbn']."<br>
	Quantity:	".$c['qty']."<br>
	Date Added:	".$c['date']."<br>
	Picture:	<img src=".$c['pic']."><br>
	<hr>";
}

echo "Subtotal: $total</html>";


require 'models/footer.php';
?>
