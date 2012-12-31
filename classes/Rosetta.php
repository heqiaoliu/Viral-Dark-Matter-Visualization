<?php
class Rosetta extends Model{
	const PS_GET_ROSETTA_DATA = '../SQL/ps_getRosettaData.php';
	public $index=array(0=>'possible_structural_protein', 1=>'source', 2=>'vc_id', 3=>'name', 4=>'gi', 5=>'length',6=>'ordered' ,7=>'cloned', 8=>'expressed', 9=>'soluble', 10 => 'purified', 11=>'crystallization_trials', 12=>'crystals' , 13 =>'diffraction', 14=>'dataset', 15=>'structure',16=>'comments');
	public $translation=array('soluble'=>'Insol','purified'=>'failed to cleave','crystallization_trials'=>'X (with Tag)');
	public function Rosetta(){
	}
	function getRosettaData()
	{
	    try 
	    {
	        require self::PS_GET_ROSETTA_DATA;

	        if ( $this->stmt->execute() )
	        {
				while ($row = $this->stmt->fetch())
				{	
					echo "<tr>";
					foreach($this->index as $col)
					{
						$feed=$row[$col];
						if($col==$this->index[0]){
			
							if($feed=='0')
								$feed='-';
							if($feed=='1')
								$feed='+';
						}	
						else{
							if($feed=='null')
								$feed='';
							if($feed=='0')
								$feed='-';
							if($feed=='1')
								$feed='X';
							if($feed=='3'){
								$feed=$this->translation[$col];
							}
						}
						if($col==$this->index[4])
							echo "<td class=\"".$col."\" text_val=\"".$feed."\">".$feed."</td>";
						else
							echo "<td class=\"".$col."\">".$feed."</td>";
					}
					echo "</tr>";
				}
    	    }
	    }
	    catch (PDOException $e) 
	    {
	        echo ".<br>".$e->getMessage(); 
	    }
	}	
}
?>
