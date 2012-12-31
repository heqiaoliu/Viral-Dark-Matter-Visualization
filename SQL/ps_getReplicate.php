<?php

//////////////////////////////////////////////
// FILE: 		ps_getReplicate.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This prepared statement is called from the 
// 				Parser class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare(  "SELECT pm_exp.replicate_num
									FROM pm_exp 
									JOIN bacteria 
									ON (pm_exp.bacteria_id = bacteria.bacteria_id) 
									WHERE bacteria.bact_external_id = ? 
									ORDER BY replicate_num DESC; ");

?>
