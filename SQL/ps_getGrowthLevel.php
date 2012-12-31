<?php
$this->stmt = $this->db->prepare("SELECT growth_level from pm_growth_level where exp_id=? and well_id=?");
?>
