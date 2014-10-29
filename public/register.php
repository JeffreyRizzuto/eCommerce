<?php

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/master_page.php");

//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: account.php"); die(); }

//Forms posted
if(!empty($_POST))
{
	$errors = array();
	$email = trim($_POST["email"]);
	$username = trim($_POST["username"]);
	$displayname = trim($_POST["displayname"]);
	$password = trim($_POST["password"]);
	$confirm_pass = trim($_POST["passwordc"]);
    $street = trim($_POST["street"]);
    $streetnumber = trim($_POST["streetnumber"]);
    $city = trim($_POST["city"]);
    $state = trim($_POST["state"]);
    $zipcode = trim($_POST["zipcode"]);
    $both = trim($_POST["both"]);
    $phonenumber = trim($_POST["phonenumber"]);
	$captcha = md5($_POST["captcha"]);
	
	
	if ($captcha != $_SESSION['captcha'])
	{
		$errors[] = lang("CAPTCHA_FAIL");
	}
	if(minMaxRange(5,25,$username))
	{
		$errors[] = lang("ACCOUNT_USER_CHAR_LIMIT",array(5,25));
	}
	if(!ctype_alnum($username)){
		$errors[] = lang("ACCOUNT_USER_INVALID_CHARACTERS");
	}
	if(minMaxRange(5,25,$displayname))
	{
		$errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
	}
	if(!ctype_alnum($displayname)){
		$errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
	}
	if(minMaxRange(8,50,$password) && minMaxRange(8,50,$confirm_pass))
	{
		$errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
	}
	else if($password != $confirm_pass)
	{
		$errors[] = lang("ACCOUNT_PASS_MISMATCH");
	}
	if(!isValidEmail($email))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}

    //address validation

    //street
    if(!ctype_alnum($street))
    {
        $errors[] = lang("ACCOUNT_STREET_INVALID");
    }
    //street number
    if(!ctype_digit($streetnumber))
    {
        $errors[] = lang("ACCOUNT_STREET_NUMBER_INVALID");
    }
    //city
    if(!ctype_alpha($city))
    {
        $errors[] = lang("ACCOUNT_CITY_INVALID");
    }
    //state
    if(!ctype_alpha($state))
    {
        $errors[] = lang("ACCOUNT_STATE_INVALID");
    }
    //zipcode
    if(!ctype_digit($zipcode))
    {
        $errors[] = lang("ACCOUNT_INVALID_EMAIL");
    }

	//End data validation

	if(count($errors) == 0)
	{	
		//Construct a user object
		$user = new User($username,$displayname,$password,$email);
        $euser = new EUser($username, $password, $email, "FTest", "LTest", 2108675309);
//        $st = 'UTSA Cirlce';
//        $no = 1;
//        $city = 'satown';
//        $state = 'TX';
//        $zip = 78249;
        //new
        $euser->addAddress($both, $street, $streetnumber, $city, $state, $zipcode);

        //old
        //$euser->addAddress('both', $st, $no, $city, $state, $zip);
		
		//Checking this flag tells us whether there were any errors such as possible data duplication occured
		if(!$user->status)
		{
			if($user->username_taken) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
			if($user->displayname_taken) $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
			if($user->email_taken) 	  $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));		
		}
		else
		{
			//Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
			if(!$user->userCakeAddUser())
			{
                $euser->addEUser();
				if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
				if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
			}
		}
	}
	if(count($errors) == 0) {
		$successes[] = $user->success;
	}

}
?>
    <div id='wrapper'>
    <div id='top'><div id='logo'></div></div>
    <div id='content'>

    <div id='main'>
    <?php    echo resultBlock($errors,$successes);    ?>

    <div id='regbox' class="panel panel-default">
    <?php echo"
        <form name='newUser' action='".$_SERVER['PHP_SELF']."' method='post'>" ?>
            <fieldset>
                <div id="legend">
                    <legend class="">Register</legend>
                </div>
                <div class="col-xs-12 col-sm-3 col-md-3">
                    <div class="form-group">
                        <!-- Username -->
                        <label class="control-label"  for="username">Username</label>
                        <div class="controls">
                            <input type="text" name="username" placeholder="" class="input-xlarge">
                            <p class="help-block">Username can contain any letters or numbers, without spaces</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 col-md-3">
                    <div class="form-group">
                        <!-- Username -->
                        <label class="control-label"  for="username">Display Name</label>
                        <div class="controls">
                            <input type="text"  name="displayname" placeholder="" class="input-xlarge">
                            <p class="help-block">Display names must not already be taken</p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <!-- E-mail -->
                    <label class="control-label" for="email">E-mail</label>
                    <div class="controls">
                        <input type="text"  name="email" placeholder="" class="input-xlarge">
                        <p class="help-block">Please provide your E-mail</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 col-md-3">
                    <div class="form-group">
                        <!-- Password-->
                        <label class="control-label" for="password">Password</label>
                        <div class="controls">
                            <input type="password"  name="password" placeholder="" class="input-xlarge">
                            <p class="help-block">Password should be at least 8 characters</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 col-md-3">
                    <div class="form-group">
                        <!-- Password -->
                        <label class="control-label"  for="password_confirm">Password (Confirm)</label>
                        <div class="controls">
                            <input type="password"  name="passwordc" placeholder="" class="input-xlarge">
                            <p class="help-block">Please confirm password</p>
                        </div>
                    </div>
                </div>
                </div>
                <div class="form-group">
                    <!-- Password -->
                    <p>
                        <label>Security Code:</label>
                        <img src='models/captcha.php'>
                    </p>
                    <label class="control-label"  for="captcha">Enter Security Code</label>
                    <div class="controls">
                        <input type="text"  name ='captcha' placeholder="" class="input-xlarge">
                    </div>
                </div>

                <hr class="colorgraph">

                <div class="form-group">
                    <!-- Button -->
                    <div class="controls">
                        <button class="btn btn-success">Register</button>
                    </div>
                </div>
            </fieldset>
        </form><!-- End Form -->
    </div><!-- End registration box -->
    <!-- Footer-->
    <?php   require 'models/footer.php';    ?>
