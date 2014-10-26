<?php
require_once("models/config.php");
require_once("models/master_page.php");

echo "Testing a general search by keyword or whatnot<br>";

//form posted
if(!empty($_POST)) {

	$searchString = $_POST['search'];
	$errors = array();
	$debug = array();

	$debug[] =  "The search string is: $searchString<br>";

}//end of POST

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