<?php

//////////////////////////////////////////////
// FILE: 		ps_getBacteriaIdFromExternalId.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This prepared statement is called from the 
// 				Parser class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("SELECT bacteria_id FROM bacteria WHERE bact_external_id = ?");

?>