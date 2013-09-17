<?php
class PMFile extends Model{
	function getFilesByBEID($beid){
		try{
			$this->stmt=$this->db->prepare("select concat('{\"file_name\":\"',pf.file_name,'\",\"upload_by\":\"',pf.name,'\",\"experience_date\":\"',pf.exp_date,'\"}') as data from pm_exp pe left join pm_files pf on pe.file_id=pf.file_id left join bacteria b on pe.bacteria_id=b.bacteria_id where b.bact_external_id=?");

			if($this->stmt->execute(array($beid))){
				$temp=array();
				while($row=$this->stmt->fetch())
					$temp[$beid][]=json_decode($row['data']);
				return $temp;
			}		
		}catch(PDOException $e)
		{
			echo $e->getMessage(); 
		}	
	}

}
?>
