<?php

//////////////////////////////////////////////
// FILE: 		ps_inputFile.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This prepared statement is called from the 
// 				Parser class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("INSERT INTO pm_files (file_name, name, exp_date, bacteria_id, notes) VALUES (:fileName, :name, FROM_UNIXTIME(:expDate), :bacteriaId, :notes)");

?>
