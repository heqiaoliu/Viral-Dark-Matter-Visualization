<?php

//////////////////////////////////////////////
// FILE: 		ps_getCurrentName.php
// PARAMETERS: 	username - the current users username 
// 				from the php $_SESSION variable
// RETURN: 		The users actual name
// NOTES: 		This prepared statement is called from the 
// 				USER class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("SELECT name FROM vdm_users WHERE vdm_users.username = ?");

?>