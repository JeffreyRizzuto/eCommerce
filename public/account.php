<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/master_page.php");

$euser = $_SESSION['euser'];
?>

    <div class="container center-block">
        <div id='top'><div id='logo'></div></div>
            <div id='content'>
                <h2>Account</h2>
            </div>
             <div id='main'>
                 <?php
                require_once("models/account-nav.php");
                echo"Hello, ".$euser->getFName().". This is your account page. Here you can view your past
                orders and change your account information.";
                ?>
            </div>
    </div>

    <!-- Footer-->
    <?php   require 'models/footer.php';  ?>