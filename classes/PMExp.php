<?php
class PMExp extends Model{
	private	$stmt;
	public function selectAll(){
		try{
			$this->stmt = $this->db->prepare("SELECT * FROM pm_exp");
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
    	    $data['msg']=$e->getMessage(); 
			$data['error']=true;
			return $data;
    	}
	}
	
	public function selectById($eid){
		try{
			$this->stmt = $this->db->prepare("SELECT * from pm_exp where exp_id=?");
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
	
	public function selectByBactId($bid){
		try{
			$this->stmt = $this->db->prepare("SELECT * from bacteria where bacteria_id=?");
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
