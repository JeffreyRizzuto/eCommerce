<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
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
                ?>
            </ul>
            <?php echo" <form name='search' action='".$_SERVER['PHP_SELF']."' method='post'> "?>
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

    $searchString = $_POST['search'];
    $_SESSION['search'] = $searchString;

    $results = search($searchString);
    if($results !== NULL) {
    /*
    foreach ($results as $r) {
        echo "
        <img src=".$r['pic']."><br>
        ISBN:           ".$r['isbn']."<br>
        Title:          ".$r['title']."<br>
        Author:         ".$r['author']."<br>
        Edition:        ".$r['edition']."<br>
        Type:           ".$r['type']."<br>
        Publisher:      ".$r['publisher']."<br>
        Price:          $".$r['price']."<br>
        Course:         ".$r['course']."<br>
        Category:       ".$r['category']."<br>
        Quantity:       ".$r['qty']."<br>
        Details:        ".$r['details']."<br><hr>
        ";
    }//end of foreach loop
    */
    //send them to the search results page
    header("Location: models/item_page.php");
    die();
    } else {
        echo "No products match your search: $searchString<br>";
    }
}//end of POST

?>