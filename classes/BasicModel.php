<?php
class BasicModel extends Model
{
	function execute($query,$params)
	{

	    try 
	    {
	    	$this->stmt=$this->db->prepare($query);
	        if ( $this->stmt->execute($params) )
	        {
			return $this->stmt;
    	    	}
	    }
	    catch (PDOException $e) 
	    {
	        echo ".<br>".$e->getMessage(); 
	    }
	}

	function insertIntoTable($tablename, $colName, $colValue){
		try
		{
			$PDOarray=array();
			array_merge$($PDOarray,(array)$tablename,$colName,$colValue);
			if($colName==null)
				$query="insert into $tablename values(".implode(",",$colValue).")";
			echo $query;
			
		}
		catch(PDOException $e)
		{
		}
	}
}
?>
