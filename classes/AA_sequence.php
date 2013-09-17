<?php
class AA_sequence extends Model{
	const PS_GET_AASEQ_BY_ID="/var/www/vdm/data/SQL/ps_getAASeqByID.php";
	const PS_GET_AASEQ="/var/www/vdm/data/SQL/ps_getAASeq.php";
	function getSequenceByID($seq_id){
		try{
	        	require self::PS_GET_AASEQ_BY_ID;
		        if ( $this->stmt->execute(array($seq_id))){
				while($row=$this->stmt->fetch()){
					return $row['amino_acid_sequence'];
				}
			}
			else
				return "no";
		}
		catch(PDOException $e){
	        	echo ".<br>".$e->getMessage(); 
		}

	}
	
	function getSequences($seq_id){
		try{
	        	require self::PS_GET_AASEQ;
		        if ( $this->stmt->execute(null)){
				while($row=$this->stmt->fetch()){
					echo $row['amino_acid_sequence']."\n";
				}
			}
			else
				return "no";
		}
		catch(PDOException $e){
	        	echo ".<br>".$e->getMessage(); 
		}

	}

}

?>
