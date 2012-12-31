<?php

require '../classes/Model.php';
require '../classes/BasicModel.php';
require '../classes/DBObject.php';


$dbobject = new DBObject("edwards.sdsu.edu", "nturner", "LOB4steR", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();

$view = new BasicModel();

$view->setDatabaseConnection($databaseConnection);


$query="select * from vdm_exp;";


$data=$view->execute($query,null);
while($row=$data->fetch()){
				echo "<pre>". print_r($row)."</pre>";
}


?>
