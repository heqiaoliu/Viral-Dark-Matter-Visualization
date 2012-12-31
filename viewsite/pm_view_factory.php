<?php
require '/var/www/vdm/data/classes/Model.php';
require '/var/www/vdm/data/classes/View.php';
require '/var/www/vdm/data/classes/GrowthLevel.php';
require '/var/www/vdm/data/classes/DBObject.php';
require '/var/www/vdm/data/tool/JSONAssArray.php';
$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();

/***********php object declaration*********************/
$view=new View();
$view->setDatabaseConnection($databaseConnection);
/*******temp for test*********/
if($_GET!=NULL){

	if($_GET['type']=='supp_info'){
		
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
		$para=array();
		$para[]=$_GET['bacteria_id'];
		$info=$view->getPlateRep($para);
		$data=array();
		foreach($info as $row){
			if(array_key_exists($row[0],$data)){
				$temp=array();
				$temp['replicate_id']=$row[2];
				$temp['exp_id']=$row[3];
				$temp['exp_date']=$row[4];
				$temp['file_name']=$row[5];
				$data[$row[0]]['info'][]=$temp;
				
			}
			else{
				$data[$row[0]]=array();
				$data[$row[0]]['plate']=$row[1];
				$data[$row[0]]['info']=array();
				$temp=array();
				$temp['replicate_id']=$row[2];
				$temp['exp_id']=$row[3];
				$temp['exp_date']=$row[4];
				$temp['file_name']=$row[5];
				$data[$row[0]]['info'][]=$temp;
			}
		}

		echo json_encode($data);
	}
}

else if($_POST!=NULL){
	if($_POST['type']=='supp_info'){
		
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
		$result['growth_level']=$gl->getGrowthLevel($_POST['exp_id'],$_POST['well_id']);
		$result['exp_id']=$_POST['exp_id'];
		$result['well_id']=$_POST['well_id'];
		echo json_encode($result);
	}

	else if($_POST['type']=="exp_info"){
		$para=array();
		$para[]=$_POST['bacteria_id'];
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
	}


}
?>
