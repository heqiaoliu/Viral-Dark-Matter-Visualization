<?php
$this->stmt = $this->db->prepare("SELECT bact_external_id,bact_name,vc_id,vector,genotype,ATCC_ID,other_ids from bacteria where vc_id=?");
?>
