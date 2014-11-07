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
    if(!preg_match('/^[0-9]+$/',$phonenumber))
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
<div class="container">

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<?php echo"
		<form name='newUser' action='".$_SERVER['PHP_SELF']."' method='post'>" ?>
			<h2>Please Sign Up <small>It's free and always will be.</small></h2>
			<hr class="colorgraph">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
					<input type="text" name="firstname" id="firstname" class="form-control input-lg" placeholder="First Name">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="text" name="lastname" id="lastname" class="form-control input-lg" placeholder="Last Name">
					</div>
				</div>
			</div>
			<div class="form-group">
				<input type="text" name="username" id="username" class="form-control input-lg" placeholder="UserName">
			</div>
			<div class="form-group">
				<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address">
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="passwordc" id="passwordc" class="form-control input-lg" placeholder="Confirm Password">
					</div>
				</div>
			</div>
			
			<!-- ADDRESS -->
			<!-- Street -->
			<div class="form-group">
				<input type="text" name="street" id="street" class="form-control input-lg" placeholder="Street">
			</div>
			
			<!-- CITY, ST, ZIP -->
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="text" name="city" id="city" class="form-control input-lg" placeholder="City">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="text" name="state" id="state" class="form-control input-lg" placeholder="State">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="text" name="zipcode" id="zipcode" class="form-control input-lg" placeholder="Zip">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="text" name="phonenumber" id="phonenumber" class="form-control input-lg" placeholder="Phone">
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-4 col-sm-3 col-md-3">
					<span class="button-checkbox">
						<button type="button" class="btn" data-color="info"> Same As Billing?</button>
						<input type="checkbox" name="both" id="t_and_c" class="hidden" value="1">
					</span>
				</div>
			</div>
			
			<div class="row"></div>
			
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Security Code: <img src='models/captcha.php'></label>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<div class="controls">
						    <input type="text"  name ='captcha' placeholder="Security Code" class="input-xlarge">
						</div>
					</div>
				</div>
			</div>
			
			<hr class="colorgraph">
			<div class="row">
				<div class="col-xs-12 col-md-6"><button class="btn btn-success">Register</button></div>
				<div class="col-xs-12 col-md-6"><a href="login.php" class="btn btn-success btn-block btn-lg">Sign In</a></div>
			</div>
		</form>
	</div>
</div>

</div>
<!-- Footer-->
<?php   require 'models/footer.php';    ?>
    
<script type="text/javascript">
	$(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i>');
            }
        }
        init();
    });
});
</script>
