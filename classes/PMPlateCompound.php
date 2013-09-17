<?php
class PMPlateCompound extends Model{
	const PS_GET_BY_IDS = '/var/www/vdm/data/SQL/ps_getPlateCompoundByIDs.php';
	const PS_GET_BY_ID_NUM = '/var/www/vdm/data/SQL/ps_getPlateCompByIDNum.php';
	const PS_GET_BY_NAME_ID = '/var/www/vdm/data/SQL/ps_getPlateCompByNameID.php';
	const PS_GET_BY_NAME_NUM = '/var/www/vdm/data/SQL/ps_getPlateCompByNameNum.php';
	function getByIDs($plate_id, $well_id){
	  	require self::PS_GET_BY_IDS;
		try{
			$ids=array();
			$ids[]=$plate_id;
			$ids[]=$well_id;
			$result=array();
			if($this->stmt->execute($ids)){
				while($row =$this->stmt->fetch()){
					return $row;
				}
			}
		}
		catch(PDOException $e){
	        echo ".<br>".$e->getMessage(); 
		}
	}
	function getByIDNum($plate_id, $well_num){
	  	require self::PS_GET_BY_ID_NUM;
		try{
			$params=array();
			$params[]=$plate_id;
			$params[]=$well_num;
			$result=array();
			if($this->stmt->execute($params)){
				while($row =$this->stmt->fetch()){
					return $row;
				}
			}
		}
		catch(PDOException $e){
	        echo ".<br>".$e->getMessage(); 
		}
	}

	function getByNameID($plate_name, $well_id){
	  	require self::PS_GET_BY_NAME_ID;
		try{
			$params=array();
			$params[]=$plate_name;
			$params[]=$well_id;
			$result=array();
			if($this->stmt->execute($params)){
				while($row =$this->stmt->fetch()){
					return $row;
				}
			}
		}
		catch(PDOException $e){
	        echo ".<br>".$e->getMessage(); 
		}
	}
	function getByNameNum($plate_name, $well_num){
	  	require self::PS_GET_BY_NAME_NUM;
		try{
			$params=array();
			$params[]=$plate_name;
			$params[]=$well_num;
			$result=array();
			if($this->stmt->execute($params)){
				while($row =$this->stmt->fetch()){
					return $row;
				}
			}
		}
		catch(PDOException $e){
	        echo ".<br>".$e->getMessage(); 
		}
	}
	function getAllByPlate($plate_id){
		$this->stmt=$this->db->prepare("select mc.medium_ctrl_name,c.well_id,c.supplement_conc,s.medium_supplement_name from pm_plate_compound c left join medium_supplement s on c.medium_supplement_id=s.medium_supplement_id left join pm_medium_control mc on c.medium_control_id=mc.medium_ctrl_id where c.plate_id=?");
		try{
			$data=array();
			$data['plate_id']=$plate_id;
			if($this->stmt->execute(array($plate_id))){
				while($row =$this->stmt->fetch()){
					$data['supp_info'][$row['well_id']]=array('medium_ctrl_name'=>$row['medium_ctrl_name'],'supplement'=>$row['medium_supplement_name'],'supplement_conc'=>$row['supplement_conc']);
				}
			return $data;
			}	
		}
		catch(PDOException $e){	
	        echo ".<br>".$e->getMessage(); 
		}
	}
}
?>
