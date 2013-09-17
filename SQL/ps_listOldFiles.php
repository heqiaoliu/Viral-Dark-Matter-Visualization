<?php

//////////////////////////////////////////////
// FILE: 		ps_listOldFiles.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This was used to migrate old database to new database 9/23/2012
//////////////////////////////////////////////

$this->stmt = $this->db->prepare(  "SELECT DISTINCT f.file_id, f.file_name, f.name, f.exp_date, f.upload_date, g.plate_name, b.bact_external_id, f.replicate_num, f.notes
									FROM file f, bacteria b, growth_new g
									WHERE b.bacteria_id = f.bacteria_id AND g.file_name = f.file_name
									ORDER BY f.name DESC; ");

?>