<?php
include "VDMModule.php";

class Bacteria extends VDMModule{
	private	$stmt;
	public function selectAll(){
		try{
			$this->stmt = $this->DB->prepare("SELECT distinct bacteria_id,bact_external_id FROM bacteria");
			$data=array();
			if( $this->stmt->execute())
			{
				while ($row = $this->stmt->fetch())
					$data[]=$row;
			}
			return $data;
    	}
    	catch (PDOException $e) 
    	{
    	    echo ".<br>".$e->getMessage(); 
    	}
	}
	
	public function selectById($bid){
		try{
			$this->stmt = $this->DB->prepare("SELECT * from bacteria where bacteria_id=?");
			if( $this->stmt->execute(array($bid)))
			{
				while ($row = $this->stmt->fetch())
					return $row;
			}
			return null;
    	}
    	catch (PDOException $e) 
    	{
    	    echo ".<br>".$e->getMessage(); 
    	}
	}
	
	public function selectByExtId($eid){
		try{
			$this->stmt = $this->DB->prepare("SELECT * from bacteria where bact_external_id=?");
			if( $this->stmt->execute(array($eid)))
			{
				while ($row = $this->stmt->fetch())
					return $row;
			}
			return null;
    	}
    	catch (PDOException $e) 
    	{
    	    echo ".<br>".$e->getMessage(); 
    	}
	}

	public function selectbyVcId($vc_id){
		try{
			$this->stmt = $this->DB->prepare("SELECT * from bacteria where vc_id=?");
			if( $this->stmt->execute(array($vc_id)))
			{
				while ($row = $this->stmt->fetch())
					return $row;
			}
			return null;
    	}
    	catch (PDOException $e) 
    	{
    	    echo ".<br>".$e->getMessage(); 
    	}
	}
}
?>
