<?php
require_once("models/config.php");
if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

echo "Testing the searching functions :D";

$results = searchByCategory(correctSearchString('office'));

foreach ($results as $r) {
	echo "
	".$r['pic']."<br>
	ISBN:			".$r['isbn']."<br>
	Title:			".$r['title']."<br>
	Author:			".$r['author']."<br>
	Edition:		".$r['edition']."<br>
	Type:			".$r['type']."<br>
	Publisher:		".$r['publisher']."<br>
	Price:			$".$r['price']."<br>
	Course:			".$r['course']."<br>
	Category:		".$r['cat']."<br>
	Quantity:		".$r['qty']."<br>
	Details:		".$r['details']."<br><hr>
	";
}//end of foreach loop

?>