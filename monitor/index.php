<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 20-8-17
 * Time: 20:20
 */
include_once "lib/common.php";
CommonFunctions::authoriseSession();

/*
 * Blocks to be shown
 *
 * key will be the id of the enclosing div
 * the value is an array with class names for the enclosing div
 *
 * the key must be the same name as the service name in the api directory
 *
 */
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
  <?php
  $l = new Linux();
  ?>

  <title><?php echo $l->getHostName(); ?></title>

  <script src="ui/js/jquery-3.2.1.min.js"></script>
  <script src="ui/js/updater.js"></script>

  <link rel="stylesheet" type="text/css" href="ui/css/layout.css">
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
          $template = "ui/template/{$id}.php";
          if (file_exists($template))
            include $template;
          echo "</div>";
        }
      ?>
  </div>

</body>
</html>