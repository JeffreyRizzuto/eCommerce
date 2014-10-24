<?php

function correctSearchString($str) {
	return ucfirst(strtolower($str));
}//end of correctSearchString

function searchByCategory($category) {
	global $myQuery;

	$stmt = $myQuery->prepare("SELECT * FROM `books` WHERE `category` = ?");
	$stmt->bind_param("s", $category);
	$stmt->execute();
	$stmt->bind_result($isbn, $title, $author, $edition, $type, $details, $publisher, $price, $course, $cat, $quantity);
	while($stmt->fetch()) {
		$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.gif');
	}
	$stmt->close();

	return $row;
}//end of searchByCategory



?>