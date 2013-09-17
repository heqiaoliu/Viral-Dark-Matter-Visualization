<?php

//////////////////////////////////////////////
// FILE: 		ps_getPlateIdFromPlateName.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This prepared statement is called from the 
// 				Plate class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("SELECT plate_id FROM plate WHERE plate_name = ?");

?>