<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            </button>
            <a class="navbar-brand" href="index.php">CSBS</a>
        </div>
        <div class="navbar-collapse collapse" id="searchbar">
            <ul class="nav navbar-nav navbar-right">
            <?php
                    if(isUserLoggedIn()){
                        echo "
                            <li><a href='account.php'>Account</a></li>
                            <li><a href='cart.php'>Cart</a></li>
                            <li><a href='logout.php'>Logout</a></li>
                            ";
                    }
                    else{
                        echo"
                            <li><a href='login.php'>Login</a></li>
                            <li><a href='register.php'>Register</a></li>
                            <li><a href='cart.php'>Cart</a></li>
                            ";
                    }
//                $count = $euser->getCartSize();
//                print $count;
//                if(isUserLoggedIn()){
//                    echo "<li><a href='account.php'>Account</a></li>";
//                    if($count){
//                        echo "<li><a href='cart.php'>Cart</a><span class='badge'>$count</span></li>";
//                    }else{
//                        echo "<li><a href='cart.php'>Cart</a></li>";
//                    }
//                    echo"<li><a href='logout.php'>Logout</a></li>";
//                }
//                else{
//                    echo"
//                        <li><a href='login.php'>Login</a></li>
//                        <li><a href='register.php'>Register</a></li>";
//                    if($count){
//                        echo"<li><a href='cart.php'>Cart</a><span class='badge'>$count</span></li>";
//                    }else{
//                        echo"<li><a href='cart.php'>Cart</a></li>";
//                    }
//                }
                ?>
            </ul>
            <?php echo" <form class='navbar-form' name='search' action='".$_SERVER['PHP_SELF']."' method='post'> "?>
            <!-- form class="navbar-form" -->
                <div class="form-group" style="display:inline;">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                        <input class="form-control" name="search" placeholder="Search" autocomplete="off" autofocus="off" type="text">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php

//form posted
if(!empty($_POST)) {

    if(!is_null($_POST['search'])) {
        //searching possibility
        $searchString = $_POST['search'];
        $_POST['search'] = NULL;
        $_SESSION['search'] = $searchString;
        $results = search($searchString);
        if($results !== NULL) {
            header("Location: search_results.php"); die();
        } else {
            echo "No products match your search: $searchString<br>";
        }
    } elseif (!is_null($_POST['isbn'])) {
        //add to cart possibility
        $isbn = $_POST['isbn'];
        $_POST['isbn'] = NULL;
        $euser = $_SESSION['euser'];
        $qty = 1;
        $euser->addToCart($isbn, $qty);
        header("Location: cart.php"); die();
    } elseif (!is_null($_POST['checkout'])) {
        $_POST['checkout'] = NULL;
        header("Location: confirmation_page.php");
    } elseif (!is_null($_POST['confirm'])) {
        $_POST['confirm'] = NULL;
        $euser = $_SESSION['euser'];
        $euser->checkout();
        header("Location: cart.php");
    } elseif (!is_null($_POST['qty'])) {
        $isbn = $_POST['qty_isbn'];
        $newQty = $_POST['qty'];
        $_POST['qty_isbn'] = NULL;
        $_POST['qty'] = NULL;
        $euser = $_SESSION['euser'];
        if($newQty == 0) {
            $euser->removeFromCart($isbn);
        } else {
            $euser->updateQuantity($newQty, $isbn);
        }
    } elseif (!is_null($_POST['addtocart'])) {
       $isbn = $_POST['addtocart'];
       $_POST['addtocart'] = NULL;
       $euser = $_SESSION['euser'];
       $euser->addToCart($isbn,1);
       header("Location: cart.php"); die();
    }
}//end of POST

?>