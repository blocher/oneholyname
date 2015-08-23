<?php


/**
 * This is called with AJAX; Used Mysql custom class function getSomeMapPoints
 *
 */

?>

<?php

require_once('config.php');
require_once('Mysql.php');
$db = new Mysql();

$potential_new_points = $db->getSomeMapPoints($_POST['south'],$_POST['west'],$_POST['north'],$_POST['east']);
//$potential_new_points = $db->getAllMapPoints($_POST['south'],$_POST['west'],$_POST['north'],$_POST['east']);

$potential_new_points = json_encode ($potential_new_points);

echo $potential_new_points;


?>