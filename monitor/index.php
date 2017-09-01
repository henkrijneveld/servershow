<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 20-8-17
 * Time: 20:20
 */
include_once "lib/common.php";
CommonFunctions::authoriseSession();

$blocks = array(
  "summary" => array("superbox"),
  "dummy1" => array("largebox"),
  "dummy2" => array("smallbox")
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Monitor POC</title>

  <script src="js/jquery-3.2.1.js"></script>
  <script src="js/updater.js"></script>

  <link rel="stylesheet" type="text/css" href="css/layout.css">
</head>
<body>
  <h1>Test monitor</h1>
  <div class="mainbox">
      <?php
        foreach($blocks as $id => $classes) {
          echo "<div id='{$id}'";
          if (is_array($classes)) {
            echo " class='";
            echo implode(" ", $classes);
            echo "'";
          }
          echo ">Dit is een dingetje</div>";
        }
      ?>
  </div>

</body>
</html>