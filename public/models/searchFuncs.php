<?php

function correctCategoryString($str) {
	return ucfirst(strtolower($str));
}//end of correctCategoryString

function searchByCategories($row) {
	foreach($row as $category) {
		$arr[] = searchByCategory(correctCategoryString($category));
	}
	return $arr;
}//end of searchByCategories

function searchByCategory($category) {
	global $myQuery;

	$stmt = $myQuery->prepare("SELECT * FROM `books` WHERE `category` = ?");
	$stmt->bind_param("s", correctCategoryString($category));
	$stmt->execute();
	$stmt->bind_result($isbn, $title, $author, $edition, $type, $details, $publisher, $price, $course, $cat, $quantity);
	while($stmt->fetch()) {
		$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.gif');
	}
	$stmt->close();

	return $row;
}//end of searchByCategory

function searchByISBN($isbn) {
	global $myQuery;

	$stmt = $myQuery->prepare("SELECT * FROM `books` WHERE `isbn` = ?");
	$stmt->bind_param("s", $isbn);
	$stmt->execute();
	$stmt->bind_result($isbn, $title, $author, $edition, $type, $details, $publisher, $price, $course, $cat, $quantity);
	while($stmt->fetch()) {
		$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.gif');
	}
	$stmt->close();

	return $row;
}//end of searchByISBN

function searchByCourse($course) {
	global $myQuery;

	//make sure the search string is correct
	if( !startsWith('CS',$course) || startsWith('cs',$course) ) {
		$search = 'CS';
	}
	$matches = array();
	$cnum = preg_match("/[0-9]+/", $course, $matches);
	$search .= $matches[0];

	$stmt = $myQuery->prepare("SELECT * FROM `books` WHERE `course` = ?");
	$stmt->bind_param("s", $search);
	$stmt->execute();
	$stmt->bind_result($isbn, $title, $author, $edition, $type, $details, $publisher, $price, $course, $cat, $quantity);
	while($stmt->fetch()) {
		$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.gif');
	}
	$stmt->close();

	return $row;
}//end of searchByCourse

function searchByAuthor($searchAuthor) {
	global $myQuery;

	$stmt = $myQuery->prepare("SELECT * FROM `books`");
	$stmt->execute();
	$stmt->bind_result($isbn, $title, $author, $edition, $type, $details, $publisher, $price, $course, $cat, $quantity);
	while($stmt->fetch()) {
		if(strpos($searchAuthor, $author) !== false) {
			$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.gif');
		}
	}
	$stmt->close();


}//end of searchByAuthor



?>