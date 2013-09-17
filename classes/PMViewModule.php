<?php
class PMViewModule extends VDMModule{
	protected $pgc=null;
	protected $pgi=null;
	protected $pf=null;
	protected $pp=null;
	protected $pe=null;
	public function __construct(){
		parent::__construct();
		$this->pgc=new PMGrowthCurve();
		$this->pgi=new PMGrowthIndex();
		$this->pf=new PMFile();
		$this->pp=new PMPlate();
		$this->pe=new PMExperience();
		$this->pgc->setDatabaseConnection($this->DB);
		$this->pgi->setDatabaseConnection($this->DB);
		$this->pf->setDatabaseConnection($this->DB);
		$this->pp->setDatabaseConnection($this->DB);
		$this->pe->setDatabaseConnection($this->DB);
		
	}

	public function getPMCurveByGID($gid){
			return $this->pgc->getCurveByGID($gid);
	}

	public function getPMCurvesByGIDs($gids){
		$data=array();
		foreach($gids as $gid)
			$data[$gid]=$this->pgc->getCurveByGID($gid);
		return $data;	
	}
	
	public function getPMCurveByExpWell($expid,$well_id){
		return $this->pgc->getCurveByExpWell($expid,$well_id);
	}

	public function getIndexes($expids){
		$data=array();
		foreach($expids as $expid)
			$data[$expid]=$this->pgi->getGIDsOfEXPID($expid);
		return $data;	
	}

	public function getIndexOf($expid,$wellnum){
		return $this->pgi->getGIDByWellnum($expid,$wellnum);
	}
	
	public function getIndexesOf($expid,$wellnums){
		$data=array();
		foreach($wellnums as $wellnum)
			$data[$expid][$wellnum]=$this->pgi->getGIDByWellnum($expid,$wellnum);
		return $data;	
	}	

	public function getFilesByBEID($beid){
		return $this->pf->getFilesByBEID($beid);
	}

	public function getPlateInfo($pid){
		return $this->pp->getPlateFullInfo($pid);
	}
	public function getExpByBact($bactid){
		return $this->pe->getByBacteria($bactid);
	}
	
	public function getAllExps(){
		return $this->pe->getAll();
	}
	
	public function groupBy($dataset,$groupI){
		$resemble=array();
		foreach($dataset as $data)
			$resemble[$data[$groupI]]=$data;
		return $resemble;
	}	
}
?>
