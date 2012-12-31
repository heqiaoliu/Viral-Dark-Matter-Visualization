<?php
class GrowthLevel extends Model{
	const PS_GET_GROWTH_LEVEL = '/var/www/vdm/data/SQL/ps_getGrowthLevel.php';
	function getGrowthLevel($exp_id, $well_id){
	  	require self::PS_GET_GROWTH_LEVEL;
		try{
			$ids=array();
			$ids[]=$exp_id;
			$ids[]=$well_id;
			if($this->stmt->execute($ids)){
				while($row =$this->stmt->fetch()){
					return (int)$row['growth_level'];
				}
			}
		}
		catch(PDOException $e){
	        echo ".<br>".$e->getMessage(); 
		}
	}
}
?>
