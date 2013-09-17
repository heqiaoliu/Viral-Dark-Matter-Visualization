<?php
require '../classes/PMViewFactory.php';
$pvf=new PMViewFactory();
$request=$_SERVER['REQUEST_METHOD'];
$url_query=$request==='POST'?$_POST:($request==='GET'?$_GET:null);
if($url_query===null)
	return;
$type=$url_query['type'];
$param=json_decode(str_replace("\\","",$url_query['param']));

if($type=='PMExpsByBact'){
	$pm=$pvf->getPMExperience();
	$data=array();
	$counter=0;
	try{
		foreach($param as $bact){
			$temp=$pm->getByBacteria($bact);
			$data['data'][$bact]=$temp;
			$counter++;
		}
		$data['count']=$counter;
		$data['success']=true;
		$data['msg']=null;
		echo json_encode($data);	
	}
	catch(Exception $e){
		echo '{"success":false,"msg":'.$e.getMessage().'}';
	}
}

if($type=='PMExpsByBacteriaID'){
	$pm=$pvf->getPMExperience();
	$data=array();
	$counter=0;
	try{	
		foreach($param as $bact){
			$temp=$pm->getByBacteriaID($bact);
			$data['data'][$bact]=$temp;
			$counter++;
		}
		$data['count']=$counter;
		$data['success']=true;
		$data['msg']=null;
		echo json_encode($data);	
	}
	catch(Exception $e){
		echo '{"success":false,"msg":'.$e.getMessage().'}';
	}
}
if($type=='PMPlateByPlateID'){
	$pm=$pvf->getPMPlate();
	$data=array();
	foreach($param as $plate){
		$data[$plate]=$pm->getPlateFullInfo($plate);
	}
	echo json_encode($data);
}

if($type=='PMGrowthByExpWell'){
	$pm=$pvf->getPMGrowth();
	$data=array();
	foreach($param as $sets){
		$data[$sets[0]][$sets[1]]=$pm->getCurveByExpWell($sets);
	}
	echo json_encode($data);
}

?>
