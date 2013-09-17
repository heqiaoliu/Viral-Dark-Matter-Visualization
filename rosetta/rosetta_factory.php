<?php
require '../classes/Model.php';
require '../classes/DBObject.php';
require '../classes/AA_sequence.php';
require '../classes/Bacteria.php';
$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();
$seq=new AA_sequence();
$seq->setDatabaseConnection($databaseConnection);
if($_GET['seq_id']!=null){
	$sequence=$seq->getSequenceByID($_GET['seq_id']);
	$seqstrs=str_split($sequence, 70);
	echo "<p style=\"font-size:11px\"> ". $_GET['seq_id']."</p>";
	foreach($seqstrs as $seqstr)
		echo "<p style=\"font-size:11px\">".$seqstr."</p>";
}
else if($_POST['seq_id']!=null){
	echo json_encode($seq->getSequenceByID($_POST['seq_id']));
}
else if($_GET['vcid']!=null) {
	$bact=new Bacteria();
	$list=$bact->getBactByVCID($_GET['vcid']);
}

?>
