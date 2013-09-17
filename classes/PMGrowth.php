<?php
class PMGrowth extends Model{
	function getCurveByExpWell($exp_well){
		try{
		$this->stmt=$this->db->prepare("select time-mod(time,30) as time,growth_measurement from pm_growth where exp_id=? and well_id=?");
		if($this->stmt->execute($exp_well)){
			$temp=array();
			while($row=$this->stmt->fetch()){
				$temp[$row['time']]=floatval($row['growth_measurement']);
			}
			return $temp;
		}
		}
		catch(PDOException $e){
			throw new Exception('Unable to connect VDM database.');	
		}
	}
}
?>
