<?php

//////////////////////////////////////////////
// FILE: 		ps_inputGrowthData.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This prepared statement is called from the 
// 				Parser class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("INSERT INTO pm_exp (bacteria_id, plate_id, replicate_num, file_id) VALUES (:bacteriaId, :plateId, :replicateNum, :fileId)");

?>
