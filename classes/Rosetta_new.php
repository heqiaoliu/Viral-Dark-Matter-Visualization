<?php
class Rosetta_new extends Model{
	const PS_GET_ROSETTA_DATA = '../SQL/new_getRosettaData.php';
	public $index=array(0=>'possible_structural_protein', 1=>'source', 2=>'vc_id', 3=>'name', 4=>'gi', 5=>'length',6=>'ordered' ,7=>'cloned', 8=>'expressed', 9=>'soluble', 10 => 'purified', 11=>'crystallization_trials', 12=>'crystals' , 13 =>'diffraction', 14=>'dataset', 15=>'structure',16=>'comments');
	public $translation=array('soluble'=>'Insol','purified'=>'failed to cleave','crystallization_trials'=>'X (with Tag)');
	public function Rosetta_new(){
	}
	function getRosettaData()
	{
	    $data=array();
	    try 
	    {
	        require self::PS_GET_ROSETTA_DATA;

	        if ( $this->stmt->execute() )
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
