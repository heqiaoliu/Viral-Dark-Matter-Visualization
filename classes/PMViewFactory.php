<?php
require '../classes/Model.php';
require '../classes/VDMModule.php';
require '../classes/PMExperience.php';
require '../classes/PMPlate.php';
require '../classes/PMGrowth.php';
class PMViewFactory extends VDMModule{
	public function getPMExperience(){
		$obj=new PMExperience();
		$obj->setDatabaseConnection(parent::$DB);
		return $obj; 
	}

	public function getPMPlate(){
		$obj=new PMPlate();
		$obj->setDatabaseConnection(parent::$DB);
		return $obj; 
	}
	
	public function getPMGrowth(){
		$obj=new PMGrowth();
		$obj->setDatabaseConnection(parent::$DB);
		return $obj; 
	}
}

?>
