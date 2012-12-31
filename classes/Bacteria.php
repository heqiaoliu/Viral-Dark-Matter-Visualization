
<?php
class Bacteria extends Model{
	const PS_GET_BACT_EXT_ID = '/var/www/vdm/data/SQL/ps_getBactExtId.php';
	public function Bacteria(){}

	function getBactExtId(){
	    try
	    {
//		require self::PS_GET_BACT_EXT_ID;
		$this->stmt = $this->db->prepare("SELECT distinct e.bacteria_id,b.bact_external_id FROM vdm_exp e left join bacteria b on e.bacteria_id=b.bacteria_id");
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

	function temp(){
	    try
	    {
		$this->stmt = $this->db->prepare("SELECT distinct e.bacteria_id,b.bact_external_id FROM vdm_exp e left join bacteria b on e.bacteria_id=b.bacteria_id");
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
