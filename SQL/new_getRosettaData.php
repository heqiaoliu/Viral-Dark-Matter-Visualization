
<?php

//////////////////////////////////////////////
// FILE: 		ps_getRosettaData.php
// PARAMETERS: 	
// RETURN: 		
// NOTES: 		
//////////////////////////////////////////////

$this->stmt = $this->db->prepare("SELECT v.*,r.*  FROM rosetta r left join vc_id v on r.vc_id=v.vc_id ORDER BY r_id");



?>
