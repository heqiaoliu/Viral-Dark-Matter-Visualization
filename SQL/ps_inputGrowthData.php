<?php

//////////////////////////////////////////////
// FILE: 		ps_inputGrowthData.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		This prepared statement is called from the 
// 				Parser class
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("INSERT INTO pm_growth (well_id, time, growth_measurement, exp_id) VALUES (:wellNumId, :time, :growthMeasurement, :expId)");

?>
