<?php

$this->stmt = $this->db->prepare("select distinct b.bacteria_id,b.bact_external_id from bacteria b inner join vdm_exp v on b.bacteria_id=v.bacteria_id");

?>
