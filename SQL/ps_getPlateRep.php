<?php

$this->stmt = $this->db->prepare("SELECT v.exp_id,v.plate_id,v.replicate_num,p.plate_name,f.exp_date,f.file_name  FROM pm_exp v, plate p, pm_files f
							where v.bacteria_id=? and  v.plate_id=p.plate_id and f.file_id=v.file_id");
?>
