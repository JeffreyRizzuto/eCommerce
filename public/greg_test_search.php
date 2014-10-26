<?php
require_once("models/config.php");

echo "Hardcoded test of seach function searchByCategory<br>";

//Single category search
$results = searchByCategory('office');

foreach ($results as $r) {
	echo "
	<img src=".$r['pic']."><br>
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

//Multiple category search
//will have to add each category to an array
$cats = array('office', 'culture');
$results = searchByCategories($cats);
foreach ($results as $result) {
	foreach ($result as $r) {
		echo "
		<img src=".$r['pic']."><br>
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
}//end of outher foreach loop

?>