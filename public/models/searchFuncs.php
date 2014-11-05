<?php

function correctCategoryString($str) {
	return ucfirst(strtolower($str));
}//end of correctCategoryString

function returnValueCheck($row) {
	if(empty($row)) {
		return NULL;
	} else {
		return $row;
	}
}//end of returnValueCheck

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
	$stmt->bind_result($course, $cat, $isbn, $title, $edition, $author, $type, $price, $details, $publisher, $quantity);
	while($stmt->fetch()) {
		$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.jpg');
	}
	$stmt->close();

	return returnValueCheck($row);
}//end of searchByCategory

function searchByISBN($isbn) {
	global $myQuery;

	$stmt = $myQuery->prepare("SELECT * FROM `books` WHERE `isbn` = ?");
	$stmt->bind_param("s", $isbn);
	$stmt->execute();
	$stmt->bind_result($course, $cat, $isbn, $title, $edition, $author, $type, $price, $details, $publisher, $quantity);
	while($stmt->fetch()) {
		$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.jpg');
	}
	$stmt->close();

	return returnValueCheck($row);
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
	$stmt->bind_result($course, $cat, $isbn, $title, $edition, $author, $type, $price, $details, $publisher, $quantity);
	while($stmt->fetch()) {
		$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.jpg');
	}
	$stmt->close();

	return returnValueCheck($row);
}//end of searchByCourse

function searchByAuthor($searchAuthor) {
	global $myQuery;

	$stmt = $myQuery->prepare("SELECT * FROM `books`");
	$stmt->execute();
	$stmt->bind_result($course, $cat, $isbn, $title, $edition, $author, $type, $price, $details, $publisher, $quantity);
	while($stmt->fetch()) {
		if(strpos($searchAuthor, $author) !== false) {
			$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.jpg');
		}
	}
	$stmt->close();

	return returnValueCheck($row);
}//end of searchByAuthor

function searchByTitle($searchString) {
	global $myQuery;

	$stmt = $myQuery->prepare("SELECT * FROM `books` WHERE `title` = ?");
	$smtm->bind_param("s",$searchString);
	$stmt->execute();
	$stmt->bind_result($course, $cat, $isbn, $title, $edition, $author, $type, $price, $details, $publisher, $quantity);
	while($stmt->fetch()) {
		$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.jpg');
	}
	$stmt->close();

	return returnValueCheck($row);
}//end of searchByTitle


function search($searchString) {
	global $myQuery;

	//first see if we can determine if the search string can be applied to a defined function
	$matches = array();
	$cnum = preg_match("/[0-9]+/", $searchString, $matches);
	$search = $matches[0];
	if(strlen($search) == 4) {
		//assume its a course number search
		return searchByCourse($search);
	} elseif ( strlen($search) > 4) {
		//assume its an isbn search because it is a number and is greater than length 4
		return searchByISBN($search);
	} else {
		$row = searchByCategory($searchString);

		if(is_null($row)) {
			$row = searchByAuthor($searchString);

			if(is_null($row)) {
				$row = searchByTitle($searchString);
			}
		}

		return $row;
	}
}//end of search

function getAllBooks() {
	global $myQuery;

	$stmt = $myQuery->prepare("SELECT * FROM `books`");
	$stmt->execute();
	$stmt->bind_result($course, $cat, $isbn, $title, $edition, $author, $type, $price, $details, $publisher, $quantity);
	while($stmt->fetch()) {
		$row[] = array('isbn' => $isbn, 'title' => $title, 'author' => $author, 'edition' => $edition, 'type' => $type, 'details' => $details,
						'publisher' => $publisher, 'price' => $price, 'course' => $course, 'category' => $cat, 'qty' => $quantity, 'pic' => '../images/'.$isbn.'.jpg');
	}
	$stmt->close();

	return returnValueCheck($row);
}//end of getAllBooks

function getPrice($isbn) {
	global $myQuery;
	$stmt = $myQuery->prepare("SELECT `price` FROM `books` WHERE `isbn` = ?");
	$stmt->bind_param("s", $isbn);
	$stmt->execute();
	$stmt->bind_result($price);
	$stmt->fetch();
	$stmt->close();

	return $price;
}//end of getPrice

?>