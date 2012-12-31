<?php

require '../classes/Model.php';
require '../classes/View.php';
require '../classes/DBObject.php';


$dbobject = new DBObject("edwards.sdsu.edu", "nturner", "LOB4steR", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();

$view = new View();

$view->setDatabaseConnection($databaseConnection);


$expid = 39;
$well = 3;



$view->getGrowthData($expid, $well);


?>