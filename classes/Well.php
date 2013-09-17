
<?php
class Well extends Model{
	public function Well(){}

	function getWells(){
	    try
	    {
		$this->stmt = $this->db->prepare("select * from pm_well_num");
		$data=array();
		if( $this->stmt->execute())
		{
			while ($row = $this->stmt->fetch())
			{
				$data[$row['well_id']]=$row['well_num'];
			}
			return $data;
		}
	    }
	    catch (PDOException $e) 
	    {
	        echo ".<br>".$e->getMessage(); 
	    }
	}

}
