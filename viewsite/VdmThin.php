<?php
require '../classes/Model.php';
require '../classes/VDMModule.php';
require '../classes/PMExperience.php';
require '../classes/PMPlate.php';
require '../classes/PMGrowth.php';
class VdmThin extends VDMModule{
	static $growth;
	static $exp;
	static $curves;
	static $expInfo;
	static $plate;
	static $plates;
	static $maxLen;
	static $maxE;
	static $maxW;
	function __construct(){
		parent::__construct();
		self::$growth=new PMGrowth();
		self::$exp=new PMExperience();
		self::$plate=new PMPlate();
		self::$growth->setDatabaseConnection(parent::$DB);
		self::$exp->setDatabaseConnection(parent::$DB);
		self::$plate->setDatabaseConnection(parent::$DB);
		self::$maxLen=0;
	}
	
	function getCurves($expID,$sets){
		try{
			foreach($sets as $set){
				$data=self::$growth->getCurveByExpWell(array($expID,$set));
				self::$curves[$expID][$set]=$data;
				if(self::$maxLen<count($data)){
					self::$maxLen=count($data);
					self::$maxE=$expID;
					self::$maxW=$set;
				}
			}
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}

	function getNames($param){
		try{
			foreach($param as $expID)
				self::$expInfo[$expID]=self::$exp->getByExpID($expID);
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}

	function getPlate($plateID){
		try{
			self::$plates[$plateID]=self::$plate->getPlateFullInfo($plateID);
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}
}
?>
