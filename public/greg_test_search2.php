<?php
require_once("models/config.php");

echo "Testing a general search by keyword or whatnot<br>";

//form posted
if(!emtpy($_POST)) {

	$searchString = $_POST['search'];
	$errors[] = array();
	$debug[] = array();

	$debug =  "The search string is: $searchString<br>";

}//end of POST

?>

<?php   echo resultBlock($errors,$debug);   ?>

<?php echo" <form name='searchTest' action='".$_SERVER['PHP_SELF']."' method='post'> "?>
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