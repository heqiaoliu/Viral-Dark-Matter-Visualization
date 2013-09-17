<?php
require '/var/www/vdm/data/classes/Model.php';
require '/var/www/vdm/data/classes/View.php';
require '/var/www/vdm/data/classes/GrowthLevel.php';
require '/var/www/vdm/data/classes/Well.php';
require '/var/www/vdm/data/classes/PMPlateCompound.php';
require '/var/www/vdm/data/classes/PMExp.php';
require '/var/www/vdm/data/classes/DBObject.php';
require '/var/www/vdm/data/tool/JSONAssArray.php';
$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();

/***********php object declaration*********************/
$view=new View();
$well=new Well();
$view->setDatabaseConnection($databaseConnection);
/*******temp for test*********/
if($_GET!=NULL){
	if($_GET['type']=='growth_data'){
		$request_data=json_decode($_GET['data']);
		$result=$view->getGrowthDatas($request_data);
		echo json_encode($result);
	}
	if($_GET['type']=='supp_info'){
		echo getSuppInfo($_GET,$databaseConnection);
	}
	else if($_GET['type']=='growth_level'){
		if($_GET['exp_id']==NULL){
			echo "exp_id is required";
			return;
		}
		if($_GET['well_id']==NULL){
			echo "well_id is required";
			return;
		}	
		$gl=new GrowthLevel();
		$gl->setDatabaseConnection($databaseConnection);
		$result=array();
		$result['growth_level']=$gl->getGrowthLevel($_GET['exp_id'],$_GET['well_id']);
		$result['exp_id']=$_GET['exp_id'];
		$result['well_id']=$_GET['well_id'];
		echo json_encode($result);
	}
	else if($_GET['type']=="exp_info"){
		if($_GET['bacteria_id']!=null){
			$para=array();
			$para[]=$_GET['bacteria_id'];
			echo getExpInfo($para,$view);
		}
		else{
			$databaseConnection = $dbobject->getDB();
			$pe=new PMExp();
			$pe->setDatabaseConnection($databaseConnection);
			$data=$pe->selectByID($_GET['exp_id']);
			$data['exp_id']=$_GET['exp_id'];
			echo json_encode($data);
		}
	}
	else if($_GET['type']=='plate_info'){
		$databaseConnection = $dbobject->getDB();
		$pc=new PMPlateCompound();
		$pc->setDatabaseConnection($databaseConnection);
		if($_GET['plate_id']!=null)
			$data=$pc->getAllByPlate($_GET['plate_id']);
		else if($_GET['exp_id']!=null){
			$pe=new PMExp();
			$pe->setDatabaseConnection($databaseConnection);
			$data=$pe->selectByID($_GET['exp_id']);
			$data=$pc->getAllByPlate($data['plate_id']);
			$data['exp_id']=$_GET['exp_id'];
		}
		echo json_encode($data);
	}
}

else if($_POST!=NULL){
	if($_POST['type']=='supp_info'){
		 echo getSuppInfo($_GET,$databaseConnection);
	}
	else if($_POST['type']=='growth_level'){
		if($_POST['exp_id']==NULL){
			echo "exp_id is required";
			return;
		}
		if($_POST['well_id']==NULL){
			echo "well_id is required";
			return;
		}	
		$gl=new GrowthLevel();
		$gl->setDatabaseConnection($databaseConnection);
		$result=array();
		$tempRes=$gl->getGrowthLevel($_POST['exp_id'],$_POST['well_id']);
		$result['growth_level']=$tempRes['growth_level'];
		$result['well_num']=$tempRes['well_num'];
		$result['exp_id']=$_POST['exp_id'];
		$result['well_id']=$_POST['well_id'];
		echo json_encode($result);
	}

	else if($_POST['type']=="exp_info"){
		$para=array();
		$para[]=$_POST['bacteria_id'];
		echo getExpInfo($para,$view);
	}
	if($_POST['type']=='growth_data'){
		$request_data=json_decode($_POST['data']);
		$result=$view->getGrowthDatas($request_data);
		echo $result;
	}


}

function getExpInfo($para,$view){
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

		return json_encode($data);
}

function getSuppInfo($para,$databaseConnection){
		if($para['plate_name']==NULL&&$para['plate_id']==NULL){
			echo "filed 'plate_name' or 'plate_id' is required";
			return;
		}
		if($para['well_id']==NULL&&$para['well_num']==NULL){
			echo "filed 'well_id' or 'well_num' is required";
			return;
		}
		$ppc=new PMPlateCompound();
		$ppc->setDatabaseConnection($databaseConnection);
		$jsonObj=array();
		if($para['plate_name']==NULL&&$para['well_num']==NULL)
			$result=$ppc->getByIDs($para['plate_id'],$para['well_id']);
		else if($para['plate_name']==NULL&&$para['well_id']==NULL)
			$result=$ppc->getByIDNum($para['plate_id'],$para['well_num']);
		else if($para['plate_id']==NULL&&$para['well_num']==NULL)
			$result=$ppc->getByNameID($para['plate_name'],$para['well_id']);
		else if($_GET['plate_id']==NULL&&$para['well_id']==NULL)
			$result=$ppc->getByNameNum($para['plate_name'],$para['well_num']);
		$jsonObj['plate_id']=(int)$result['plate_id'];
		$jsonObj['plate_name']=$result['plate_name'];
		$jsonObj['well_id']=(int)$result['well_id'];
		$jsonObj['well_num']=$result['well_num'];
		$jsonObj['supplement_id']=(int)$result['supplement'];
		$jsonObj['supplement_name']=$result['medium_supplement_name'];
		$jsonObj['supplement_conc']=(double)$result['supplement_conc'];
		return $result;
}
?>
