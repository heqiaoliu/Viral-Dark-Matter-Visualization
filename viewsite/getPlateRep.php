<?php
require '/var/www/vdm/data/classes/Model.php';
require '/var/www/vdm/data/classes/View.php';
require '/var/www/vdm/data/classes/DBObject.php';
require '/var/www/vdm/data/tool/JSONAssArray.php';
$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();

/***********php object declaration*********************/
$view=new View();
$view->setDatabaseConnection($databaseConnection);
/*******temp for test*********/
$para=array();
$para[]=$_POST['bacteria_id'];
//echo json_encode($view->getPlateRep($para));
$info=$view->getPlateRep($para);
$data=array();
foreach($info as $row){
	if(array_key_exists($row[0],$data)){
		$temp=array();
		$temp[]=$row[2];
		$temp[]=$row[3];
		$temp[]=$row[4];
		$temp[]=$row[5];
		$data[$row[0]]['data'][]=$temp;
		
	}
	else{
		$data[$row[0]]=array();
		$data[$row[0]]['name']=$row[1];
		$data[$row[0]]['data']=array();
		$temp=array();
		$temp[]=$row[2];
		$temp[]=$row[3];
		$temp[]=$row[4];
		$temp[]=$row[5];
		$data[$row[0]]['data'][]=$temp;
	}
}

echo json_encode($data);
?>
