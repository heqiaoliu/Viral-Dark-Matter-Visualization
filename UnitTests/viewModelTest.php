<?php

require '../classes/Model.php';
//require '../classes/View.php';
//require '../classes/DBObject.php';
//require '../classes/PMPlateCompound.php';
//$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
//$databaseConnection = $dbobject->getDB();
//$pc=new PMPlateCompound();
//$pc->setDatabaseConnection($databaseConnection);
//$data=$pc->getAllByPlate(4);
//echo json_encode($data);

require '../classes/VDMModule.php';
require '../classes/PMGrowthIndex.php';
require '../classes/PMGrowthCurve.php';
require '../classes/PMFile.php';
require '../classes/PMPlate.php';
require '../classes/PMExperience.php';
require '../classes/PMViewModule.php';
//$pgi=new PMGrowthIndex();
//$pgc=new PMGrowthCurve();
//$a=$pgi->getGIDsOfEXPID(2);
$pvm=new PMViewModule();
//$c=$pvm->getPMCurvesByGIDs(array(1));
//$g=$pvm->getPMCurveByExpWell(31,12);
//$d=$pvm->getIndexes(array(5));
//$e=$pvm->getIndexesOf(5,array('A1','A2'));
//$f=$pvm->getFilesByBEID('EDT2231');
//$b=$pgc->getCurveByGID(105);
//echo json_encode($c);
//echo json_encode($d);
//echo json_encode($e);
//echo json_encode($f);
//echo json_encode($g);
$g=$pvm->getExpByBact(2);
echo json_encode($g);

/**
$temp=array();
for($i=0;$i<10000;$i++)
	$temp[]=9701;
echo '<br/>'.time();
for($i=0;$i<10000;$i++)
	echo $pvm->getPMCurveByExpWell(101,5);
echo '<br/>'.time();
echo $pvm->getPMCurvesByGIDs($temp);
echo '<br/>'.time();
**/	




?>
