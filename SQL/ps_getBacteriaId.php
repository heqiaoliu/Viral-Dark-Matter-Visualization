<?php

//////////////////////////////////////////////
// FILE: 		ps_getFileIdFromFileName.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This prepared statement is called from the 
// 				Parser class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("SELECT bact_external_id FROM bacteria");

?>