<?php
require '/var/www/vdm/data/classes/Model.php';
require '/var/www/vdm/data/classes/View.php';
require '/var/www/vdm/data/classes/DBObject.php';
require '/var/www/vdm/data/tool/JSONAssArray.php';
$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();

$view=new View();
$view->setDatabaseConnection($databaseConnection);

$request_data=json_decode($_POST['data']);
$result=$view->getGrowthDatas($request_data);
echo $result;
?>
