
<?php
require '../classes/Model.php';
require '../classes/GrowthLevel.php';
require '../classes/DBObject.php';
//require '../classes/Edit.php';
//require '../classes/Bacteria.php';
//require '../viewsite/JSONAssArray.php';
$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();
$gl = new GrowthLevel();
$gl->setDatabaseConnection($databaseConnection);

//$bm=new BasicModel();
//$bm->setDatabaseConnection($databaseConnection);
//$colval=array();
//$colval[]='test';
//$colval[]=3;
//$aas=new AA_sequence();
//$aas->setDatabaseConnection($databaseConnection);
//$var="6683_4_1";
//$aas->getSequences();
$num= $gl->getGrowthLevel(137,1);
echo $num;

//echo "good";
/*
$pairs=array();
$pair=array();
$pair[0]=39;
$pair[1]=1;
$pairs[]=$pair;
$pair[0]=39;
$pair[1]=2;
$pairs[]=$pair;
$pair[0]=39;
$pair[1]=3;
$pairs[]=$pair;
$pair[0]=40;
$pair[1]=2;
$pairs[]=$pair;
$pair[0]=40;
$pair[1]=3;
$pairs[]=$pair;
echo json_encode($pairs);

//$view->getGrowthDatas($pairs);

$bact_id=array();
$bact_id[]=35;
$data=$view->getPlateRep($bact_id);

$type=array();
$type[]=3;
_1_$type[]=2;
$bact->getBactExtId();
foreach($data as $row)
	$json->add($row,$type);
$test=array();
$test["temp"]="1";
echo $json->toJsonString();
*/


?>
