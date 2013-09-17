<?php

//////////////////////////////////////////////
// FILE: 		ps_getBacteriaIdFromExternalId.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This prepared statement is called from the 
// 				Parser class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("SELECT exp_id FROM pm_exp WHERE bacteria_id = :bacteriaId AND plate_id = :plateId AND replicate_num = :replicateNum AND file_id = :fileId LIMIT 1 ");

?>
