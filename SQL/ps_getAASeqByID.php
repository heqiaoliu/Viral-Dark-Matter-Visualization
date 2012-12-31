<?php
			$this->stmt=$this->db->prepare("select distinct amino_acid_sequence from amino_acid_sequence where seq_id=? ");
?>
