<?php
require_once("models/config.php");
require_once("models/master_page.php");

echo "Testing search<br>";

?>

<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
</div>
<div class="row" id='main'>
<?php   echo resultBlock($errors,$debug);   ?>

<div class="col-md-1"></div>
<div class="col-md-10">
	<hr>
</div>
<div class="col-md-1"></div>

<div class="col-md-4"></div>
<div class="col-md-4" id='regbox'>

<?php echo" <form name='searchTest' action='".$_SERVER['PHP_SELF']."' method='post'> "; ?>
    <fieldset>
        <div id="legend">
            <h3>Search below</h3>
        </div>
        <div class="control-group">
            <!-- input box-->
            <label class="control-label"  for="search">Search</label>
            <div class="controls">
                <input type="text" id="search" name="search" placeholder="Search" class="input-xlarge">
            </div>
        </div>

        <div class="control-group">
            <!-- Button -->
            <div class="controls">
                <button class="btn btn-success">Submit</button>
            </div>
        </div>
    </fieldset>
</form>
</div>
<div class="col-md-4"></div>
</div>

<?php

//form posted
if(!empty($_POST)) {

	$searchString = $_POST['search'];
	$errors = array();
	$debug = array();

	$debug[] =  "The search string is: $searchString<br>";

	$results = search($searchString);
	if($results !== NULL) {
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
	} else {
		echo "No products match your search: $searchString<br>";
	}

}//end of POST

?>