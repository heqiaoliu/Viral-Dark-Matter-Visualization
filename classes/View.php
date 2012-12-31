<?php

class View extends Model
{
	const PS_GET_GROWTH_DATA = '/var/www/vdm/data/SQL/ps_getGrowthData.php';
	const PS_GET_PLATE_REP='/var/www/vdm/data/SQL/ps_getPlateRep.php';
	const PS_GET_BACT_EXT_ID = '/var/www/vdm/data/SQL/ps_getBactExtId.php';
	function getPlateRep($bactId){
	    require self::PS_GET_PLATE_REP;
		try{
			$value=array();
			if($this->stmt->execute($bactId)){
				while($row =$this->stmt->fetch()){
						$line=array();
						$line[]=$row['plate_id'];
						$line[]=$row['plate_name'];
						$line[]=$row['replicate_num'];
						$line[]=$row['exp_id'];
						$line[]=$row['exp_date'];
						$line[]=$row['file_name'];
						$value[]=$line;
				}
			}		
				return $value;
		}
		catch(PDOException $e){
	        echo ".<br>".$e->getMessage(); 
		}
	}

	function getGrowthDatas($pairs)
	{
	    try 
	    {

//		require "/var/www/vdm/data/tool/JSONAssArray.php";
	        // Require ps_getBacteriaIdFromExternalId() current procudure
		$growthDatas=new JSONAssArray(0,3);
		$types=array();
		$types[]=3;
		$types[]=2;
		$i=0;
		$query="select exp_id, well_num_id,time,growth_measurement from pm_growth where ";

		foreach($pairs as $pair){
			if($i!=0)
				$query.=" or ";
			else
				$i=1;
			$query.="(exp_id=$pair[0] and well_num_id=$pair[1])";
		}
		$query.=" order by time;";
		$this->stmt=$this->db->prepare($query);
	        if ( $this->stmt->execute() )
	        {
			while ($row = $this->stmt->fetch())
			{
				$line=array();
				$line[]=$row['exp_id'];
				$line[]=$row['well_num_id'];
				$line[]=(int)$row['time'];
				$line[]=(float)$row['growth_measurement'];
				$growthDatas->add($line,$types);
			}
    	    	}
		return $growthDatas->toJsonString();
	    }
	    catch (PDOException $e) 
	    {
	        echo ".<br>".$e->getMessage(); 
	    }
	}

	function getBactExtId(){
	    try
	    {
		$this->stmt = $this->db->prepare("SELECT distinct e.bacteria_id,b.bact_external_id FROM pm_exp e left join bacteria b on e.bacteria_id=b.bacteria_id");
		$data=array();
		if( $this->stmt->execute())
		{
			while ($row = $this->stmt->fetch())
			{
				$data[]=$row;
				
			}
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
