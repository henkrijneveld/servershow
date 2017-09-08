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
  "monitor" => array("superbox"),
  "resources" => array("largebox"),
  "services" => array("smallbox"),
  "operatingsystem" => array("smallbox"),
  "hardware" => array("smallbox"),
  "network" => array("smallbox")
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
  <div class="mainbox">
      <?php
        foreach($blocks as $id => $classes) {
          echo "<div id='{$id}'";
          if (is_array($classes)) {
            echo " class='";
            echo implode(" ", $classes);
            echo "'";
          }
          echo ">";
          $template = "template/{$id}.php";
          if (file_exists($template))
            include $template;
          echo "</div>";
        }
      ?>
  </div>

</body>
</html>