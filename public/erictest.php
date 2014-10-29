<?php

require("models/config.php");
require("models/master_page.php");

$booklist = getAllBooks();

foreach($booklist as $book)
{
    var_dump($book);
    echo "<br><br>";
}

?>