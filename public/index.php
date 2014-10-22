<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/master_page.php");
?>

<?php   require 'homepage.php';  ?>

<!-- Footer-->
<?php   require 'models/footer.php';  ?>