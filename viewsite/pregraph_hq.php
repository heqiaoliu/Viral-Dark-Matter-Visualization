<?php


require '../classes/DBObject.php';
require '../viewsite/JSONAssArray.php';
$dbobject = new DBObject("edwards.sdsu.edu", "nturner", "LOB4steR", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();
/***********
this is for temp test
***************/

$json=new JSONAssArray(0,3);

echo $json->toJsonString();


?>
