<?php

$this->stmt = $this->db->prepare("insert into bacteria(bact_external_id,bact_name,vc_id,vector,genotype) values(?,?,?,?,?)");
//$this->stmt = $this->db->prepare("update bacteria set bact_external_id=?,bact_name=?,vc_id=?,vector=?,genotype=? where bacteria_id=38");
?>
