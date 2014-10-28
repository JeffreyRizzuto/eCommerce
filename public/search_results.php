<?php
require_once("models/config.php");
require_once("models/master_page.php");

$searchString = $_SESSION['search'];

$results = search($searchString);
if($results !== NULL) {
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
        echo "
        <form name='cart' action='".$_SERVER['PHP_SELF']."' method='post'>
            <input type='hidden' id='isbn' name='isbn' value='".$r['isbn']."'/>
            <input type='Submit' value='Add to Cart' />
        </form>";
    }//end of foreach loop
}

//form posted
if(!empty($_POST)) {

    $isbn = $_POST['isbn']

    $esuer = $_SESSION['euser'];
    $euser->addtoCart($isbn);
}//end of POST

?>
