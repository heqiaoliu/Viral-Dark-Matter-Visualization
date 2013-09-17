<?php
class PMPlate extends Model{
	public function getPlateFullInfo($pid){
		try{
			$this->stmt=$this->db->prepare("select concat('{\"well\": \"',pwn.well_num,'\",\"well_id\":',pwn.well_id,',\"control_name\":\"',pmc.medium_ctrl_name,'\",\"supplement\":\"',ms.medium_supplement_name,'\",\"conc\":',ppc.supplement_conc,'}') as data from pm_plate_compound ppc, pm_medium_control pmc, medium_supplement ms,plate p,pm_well_num pwn where ppc.plate_id=? and ppc.plate_id=p.plate_id and ppc.medium_control_id=pmc.medium_ctrl_id and ppc.medium_supplement_id=ms.medium_supplement_id and pwn.well_id=ppc.well_id order by pwn.well_id");
			if($this->stmt->execute(array($pid))){
				$temp=array();
				while($row=$this->stmt->fetch())
					$temp[]=json_decode($row['data'],true);
				return $temp;
			}		
		}catch(PDOException $e){
			//echo $e->getMessage(); 
			throw new Exception('Unable to connect VDM database.');	
		}	
	}
}

?>
