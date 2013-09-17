<?php
class PMExperience extends Model{
	function getAll(){
		try{
			$this->stmt=$this->db->prepare("select concat('{\"exp_id\":',pe.exp_id,',\"experience_date\":\"',pf.exp_date,'\",\"plate_id\":',p.plate_id,',\"plate_name\":\"',p.plate_name,'\",\"replicate_id\":',pe.replicate_num,',\"bacteria_id\":',pe.bacteria_id,',\"bacteria_external_id\":\"',b.bact_external_id,'\",\"file\":\"',pf.file_name,'\"}') as data from pm_exp pe, plate p, pm_files pf,bacteria b where pf.file_id=pe.file_id and pe.plate_id=p.plate_id and pe.bacteria_id=b.bacteria_id");
			if($this->stmt->execute()){
				$temp=array();
				while($row=$this->stmt->fetch()){
					$temp[]=json_decode($row['data'],true);
				}
				return $temp;
			}
		}
		catch(PDOException $e){
			throw new Exception('Unable to connect VDM database.');
		}
	}

	function getByBacteria($bact_ext_id){
		try{
			$this->stmt=$this->db->prepare("select concat('{\"exp_id\":',pe.exp_id,',\"experience_date\":\"',pf.exp_date,'\",\"plate_id\":',p.plate_id,',\"plate_name\":\"',p.plate_name,'\",\"replicate_id\":',pe.replicate_num,',\"bacteria_id\":',pe.bacteria_id,',\"bacteria_external_id\":\"',b.bact_external_id,'\",\"file\":\"',pf.file_name,'\"}') as data from pm_exp pe, plate p, pm_files pf,bacteria b where b.bact_external_id=? and pf.file_id=pe.file_id and pe.plate_id=p.plate_id and pe.bacteria_id=b.bacteria_id");
			if($this->stmt->execute(array($bact_ext_id))){
				$temp=array();
				while($row=$this->stmt->fetch()){
					$temp[]=json_decode($row['data'],true);
				}
				return $temp;
			}
		}
		catch(PDOException $e){
			throw new Exception('Unable to connect VDM database.');
		}
	}
	function getByBacteriaID($bact_id){
		try{
			$this->stmt=$this->db->prepare("select concat('{\"exp_id\":',pe.exp_id,',\"experience_date\":\"',pf.exp_date,'\",\"plate_id\":',p.plate_id,',\"plate_name\":\"',p.plate_name,'\",\"replicate_id\":',pe.replicate_num,',\"bacteria_id\":',pe.bacteria_id,',\"bacteria_external_id\":\"',b.bact_external_id,'\",\"file\":\"',pf.file_name,'\"}') as data from pm_exp pe, plate p, pm_files pf,bacteria b where pe.bacteria_id=? and pf.file_id=pe.file_id and pe.plate_id=p.plate_id and pe.bacteria_id=b.bacteria_id");
			if($this->stmt->execute(array($bact_id))){
				$temp=array();
				while($row=$this->stmt->fetch()){
					$temp[]=json_decode($row['data'],true);
				}
				return $temp;
			}
		}
		catch(PDOException $e){
			throw new Exception('Unable to connect VDM database.');
		}
	}

	function getByExpID($exp_id){
		try{
			$this->stmt=$this->db->prepare("select concat('{\"exp_id\":',pe.exp_id,',\"experience_date\":\"',pf.exp_date,'\",\"plate_id\":',p.plate_id,',\"plate_name\":\"',p.plate_name,'\",\"replicate_id\":',pe.replicate_num,',\"bacteria_id\":',pe.bacteria_id,',\"bacteria_external_id\":\"',b.bact_external_id,'\",\"file\":\"',pf.file_name,'\"}') as data from pm_exp pe, plate p, pm_files pf,bacteria b where pe.exp_id=? and pf.file_id=pe.file_id and pe.plate_id=p.plate_id and pe.bacteria_id=b.bacteria_id");
			if($this->stmt->execute(array($exp_id))){
				while($row=$this->stmt->fetch()){
					return json_decode($row['data'],true);
				}
			}
		}
		catch(PDOException $e){
			throw new Exception('Unable to connect VDM database.');
		}
	}
}
?>
