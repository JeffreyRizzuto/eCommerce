<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/master_page.php");


$searchString = $_POST['search'];

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
    }//end of foreach loop


?>