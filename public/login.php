<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/master_page.php");

//Prevent the user visiting the logged in page if he/she is already logged in
//if(isUserLoggedIn()) { header("Location: account.php"); die(); }

//Forms posted
if(!empty($_POST))
{
	$errors = array();
	$username = sanitize(trim($_POST["username"]));
	$password = trim($_POST["password"]);
	
	//Perform some validation
	//Feel free to edit / change as required
	if($username == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
	}
	if($password == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
	}

	if(count($errors) == 0)
	{
		//A security note here, never tell the user which credential was incorrect
		if(!usernameExists($username))
		{
			$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
		}
		else
		{
			$userdetails = fetchUserDetails($username);
			//See if the user's account is activated
			if($userdetails["active"]==0)
			{
				$errors[] = lang("ACCOUNT_INACTIVE");
			}
			else
			{
				//Hash the password and use the salt from the database to compare the password.
				$entered_pass = generateHash($password,$userdetails["password"]);
				
				if($entered_pass != $userdetails["password"])
				{
					//Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
					$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
				}
				else
				{
					//Passwords match! we're good to go'
					
					//Construct a new logged in user object
					//Transfer some db data to the session object
					$loggedInUser = new loggedInUser();
					$loggedInUser->email = $userdetails["email"];
					$loggedInUser->user_id = $userdetails["id"];
					$loggedInUser->hash_pw = $userdetails["password"];
					$loggedInUser->title = $userdetails["title"];
					$loggedInUser->displayname = $userdetails["display_name"];
					$loggedInUser->username = $userdetails["user_name"];
					
					//Update last sign in
					$loggedInUser->updateLastSignIn();
					$_SESSION["userCakeUser"] = $loggedInUser;

					//construct new euser and populate it
					$euserdetails = fetchEUserDetails($username);

					$euser = new EUser($username, $password, $userdetails["email"], $euserdetails['fname'], $euserdetails['lname'], $euserdetails['phone_num']);
					$euser->setuid();
					$euser->addAddress('both', $euserdetails['cur_address_st'], $euserdetails['cur_address_no'], $euserdetails['cur_address_c'], 
										$euserdetails['cur_address_st'], $euserdetails['cur_address_zip']);
					$euser->getCart();

					$_SESSION['euser'] = $euser;
					
					//Redirect to user account page
					header("Location: index.php");
					die();
				}
			}
		}
	}
}

?>

<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
</div>
<div class="row" id='main'>
<?php   echo resultBlock($errors,$successes);   ?>

<div class="col-md-1"></div>
<div class="col-md-10">
	<hr>
</div>
<div class="col-md-1"></div>

<div class="col-md-4"></div>
<div class="col-md-4" id='regbox'>
<?php echo" <form name='login' action='".$_SERVER['PHP_SELF']."' method='post'> "?>
    <fieldset>
        <div id="legend">
            <h3>Login</h3>
        </div>
        <div class="control-group">
            <!-- Username -->
            <label class="control-label"  for="username">Username</label>
            <div class="controls">
                <input type="text" id="username" name="username" placeholder="" class="input-xlarge">
            </div>
        </div>

        <div class="control-group">
            <!-- Password-->
            <label class="control-label" for="password">Password</label>
            <div class="controls">
                <input type="password" id="password" name="password" placeholder="" class="input-xlarge">
                <a href='../forgot-password.php'>Forgot Password?</a>
            </div>
        </div>

        <div class="control-group">
            <!-- Button -->
            <div class="controls">
                <button class="btn btn-success">Login</button>
            </div>
        </div>
    </fieldset>
</form>
</div>
<div class="col-md-4"></div>
</div>
    <!-- Footer-->
    <?php   require 'models/footer.php';    ?>
