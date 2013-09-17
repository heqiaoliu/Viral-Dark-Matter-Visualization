<?php
include "VDMModule.php";

class PMFiles extends VDMModule{
	private	$stmt;
	public function selectAll(){
		try{
			$this->stmt = $this->DB->prepare("SELECT * FROM pm_files");
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
	
	public function selectById($fid){
		try{
			$this->stmt = $this->DB->prepare("SELECT * from pm_files where file_id=?");
			if( $this->stmt->execute(array($fid)))
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
	
	public function selectByBactId($bid){
		try{
			$this->stmt = $this->DB->prepare("SELECT * from bacteria where bacteria_id=?");
			if( $this->stmt->execute(array($bid)))
			{
				$data=array();
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

}
?>
