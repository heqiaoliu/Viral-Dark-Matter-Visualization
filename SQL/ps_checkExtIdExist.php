<?php

$this->stmt = $this->db->prepare("select count(*) from bacteria where bact_external_id=?");
?>
