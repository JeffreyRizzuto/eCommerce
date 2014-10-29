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
	//$displayname = trim($_POST["displayname"]);
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
	$password = trim($_POST["password"]);
	$confirm_pass = trim($_POST["passwordc"]);
    $telephone = trim($_POST["telephone"]);
    $street = trim($_POST["street"]);
    $apartmentnumber = trim($_POST["streetnumber"]);
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
//	if(minMaxRange(5,25,$displayname))
//	{
//		$errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
//	}
//	if(!ctype_alnum($displayname)){
//		$errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
//	}
    if(!ctype_alnum($firstname)){
        $errors[] = lang("ACCOUNT_FIRST_NAME_INVALID_CHARACTERS");
    }
    if(!ctype_alnum($lastname)){
        $errors[] = lang("ACCOUNT_LAST_NAME_INVALID_CHARACTERS");
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
    if(!is_string($street))
    {
        $errors[] = lang("ACCOUNT_STREET_INVALID");
    }
    //street number
    if(!ctype_digit($apartmentnumber))
    {
        $apartmentnumber = 0;
        //$errors[] = lang("ACCOUNT_APARTMENT_NUMBER_INVALID");
    }
    //city
    if(!is_string($city))
    {
        $errors[] = lang("ACCOUNT_CITY_INVALID");
    }
    //state
    if(!is_string($state))
    {
        $errors[] = lang("ACCOUNT_STATE_INVALID");
    }
    //zipcode
    if(!ctype_digit($zipcode))
    {
        $errors[] = lang("ACCOUNT_INVALID_ZIP");
    }
    if(!preg_match('/^[0-9]+$/',$telephone))
    {
        $errors[] = lang("ACCOUNT_INVALID_TELEPHONE");
    }
    if(!$both)
    {
        $both = "current";
    } else {
        $both = "both";
    }

	//End data validation

	if(count($errors) == 0)
	{	
		//Construct a user object
		$user = new User($username,"Customer",$password,$email);
        $euser = new EUser($username, $password, $email, $firstname, $lastname, $telephone);
        $euser->addAddress($both, $street, $apartmentnumber, $city, $state, $zipcode);

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

    <div id='regbox'>
    <?php echo"
        <form name='newUser' action='".$_SERVER['PHP_SELF']."' method='post'>" ?>
            <fieldset>
                <div id="legend">
                    <legend class="">Account</legend>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <!-- Username -->
                            <label class="control-label">Username</label>
                            <div class="controls">
                                <input type="text" name="username" placeholder="" class="input-xlarge">
                                <p class="help-block">Contains only letters or numbers</p>
                            </div>
                        </div>
                    </div>
<!--                    <div class="col-xs-12 col-sm-1 col-md-1">-->
<!--                        <div class="form-group">-->
                                <!-- Display Bane -->
<!--                            <label class="control-label">Display Name</label>-->
<!--                            <div class="controls">-->
<!--                                <input type="text"  name="displayname" placeholder="" class="input-xlarge">-->
<!--                                <p class="help-block">Must not already be taken</p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <!-- Username -->
                            <label class="control-label">First Name</label>
                            <div class="controls">
                                <input type="text" name="firstname" placeholder="" class="input-xlarge">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <!-- Display Bane -->
                            <label class="control-label">Last Name</label>
                            <div class="controls">
                                <input type="text"  name="lastname" placeholder="" class="input-xlarge">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                            <!-- E-mail -->
                            <label class="control-label">E-mail</label>
                            <div class="controls">
                                <input type="email"  name="email" placeholder="" class="input-xlarge">
                                <p class="help-block">Please provide your E-mail</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <!-- Password-->
                            <label class="control-label">Password</label>
                            <div class="controls">
                                <input type="password"  name="password" placeholder="" class="input-xlarge">
                                <p class="help-block">At least 8 characters</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                            <!-- Password -->
                            <label class="control-label">Password (Confirm)</label>
                            <div class="controls">
                                <input type="password"  name="passwordc" placeholder="" class="input-xlarge">
                                <p class="help-block">Confirm password</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Address form -->
                <div id="legend">
                    <legend class="">Address</legend>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <!-- Username -->
                            <label class="control-label">Street</label>
                            <div class="controls">
                                <input type="text" name="street" placeholder="" class="input-xlarge">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                            <!-- Display Name -->
                            <label class="control-label">*Apartment Number</label>
                            <div class="controls">
                                <input type="text"  name="apartmentnumber" placeholder="" class="input-xlarge">
                                <p class="help-block">Optional Field</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <!-- City -->
                            <label class="control-label">City</label>
                            <div class="controls">
                                <input type="text" name="city" placeholder="" class="input-xlarge">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <label class="control-label">State</label>
                            <select name="state" class="form-control">
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="DC">District Of Columbia</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
                            </select>
                    </div>
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <!-- Zipcode -->
                            <label class="control-label">Zip Code</label>
                            <div class="controls">
                                <input type="text"  name="zipcode" placeholder="" class="input-xlarge">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                            <!-- Telephone -->
                            <label class="control-label">Telephone Number</label>
                            <div class="controls">
                                <input type="text"  name="phonenumber" placeholder="" class="input-xlarge">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                            <!-- Use as Billing? -->
                            <label class="control-label">Use As Billing?</label>
                            <div class="controls">
                                <input type="checkbox"  name="both" placeholder="" class="input-xlarge">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- register button -->
                <div id="legend">
                    <legend class="">Register</legend>
                </div>
                <div class="form-group">
                    <!-- captcha-->
                    <p>
                        <label>Security Code:</label>
                        <img src='models/captcha.php'>
                    </p>
                    <label class="control-label"  for="captcha">Enter Security Code</label>
                    <div class="controls">
                        <input type="text"  name ='captcha' placeholder="" class="input-xlarge">
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <!-- Button -->
                            <div class="controls">
                                <button class="btn btn-success">Register</button>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form><!-- End Form -->
    </div><!-- End registration box -->
    <!-- Footer-->
    <?php   require 'models/footer.php';    ?>
