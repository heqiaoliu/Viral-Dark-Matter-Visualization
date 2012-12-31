<?php

//////////////////////////////////////////////
// FILE: 		ps_getFileIdFromFileName.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This prepared statement is called from the 
// 				Parser class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("SELECT file_id FROM pm_files WHERE file_name = ?");

?>
