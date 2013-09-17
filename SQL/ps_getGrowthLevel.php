<?php
$this->stmt = $this->db->prepare("SELECT gl.growth_level,wn.well_num from pm_growth_level gl,pm_well_num wn where gl.exp_id=? and gl.well_id=? and wn.well_id=gl.well_id");
?>
