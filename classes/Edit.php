
<?php
class Edit extends Model
{
	CONST INSERT_NEW_CLONE= '../SQL/ps_insertNewClone.php';
	CONST CHECK_EXT_ID_EXIST='../SQL/ps_checkExtIdExist.php';
	function addNewClone($extId,$bactName,$vcid,$vector,$genotype)
	{
	    try 
	    {
	        require self::INSERT_NEW_CLONE;

	        if($this->stmt->execute(array($extId,$bactName,$vcid,$vector,$genotype)))
	        {
		echo "good";	
    	    	}
	    }
	    catch (PDOException $e) 
	    {
	        echo ".<br>".$e->getMessage(); 
	    }
	}

	function isExtIdExist($extId){
	  try{
	        
	        require self::CHECK_EXT_ID_EXIST;
	        if($this->stmt->execute(array($extId)))
	        {
			while ($row = $this->stmt->fetch())
			{
				if(((int)$row['count(*)'])>0){
					return true;
				}
			}
			return false;
    	    	}
	    }
	    catch (PDOException $e) 
	    {
	        echo ".<br>".$e->getMessage(); 
	    }

	}
}
?>
