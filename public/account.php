<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/master_page.php");
?>

    <div class="container center-block">
        <div id='top'><div id='logo'></div></div>
            <div id='content'>
                <h2>Account</h2>
            </div>
             <div id='main'>
                 <?php
                echo"Hey, $loggedInUser->displayname. This is an example secure page designed to
                demonstrate some of the basic features of UserCake. Just so you know,
                your title at the moment is $loggedInUser->title, and that can be
                changed in the admin panel."
                ?>
            </div>
    </div>

    <!-- Footer-->
    <?php   require 'models/footer.php';  ?>