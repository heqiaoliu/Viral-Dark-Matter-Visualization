<?php
$this->stmt = $this->db->prepare("SELECT p.plate_id,pl.plate_name,p.well_id,pw.well_num,p.medium_supplement_id,m.medium_supplement_name,p.supplement_conc from pm_plate_compound p,medium_supplement m,plate pl, pm_well_num pw where m.medium_supplement_id=p.medium_supplement_id and p.plate_id=? and pl.plate_id=p.plate_id and pw.well_id=p.well_id and pw.well_num=?");
?>
