<?php

//////////////////////////////////////////////
// FILE: 		ps_getGrowthData.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("SELECT well_num_id, time, growth_measurement, exp_id
								  FROM vdm_growth 
								  WHERE exp_id = ? AND well_num_id = ? ");



?>
