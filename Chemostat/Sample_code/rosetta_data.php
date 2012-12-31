<?php

$index=array(0=>'possible_structural_protein', 1=>'source', 2=>'vc_id', 3=>'name', 4=>'gi', 5=>'length',6=>'ordered' ,7=>'cloned', 8=>'expressed', 9=>'soluble', 10 => 'purified', 11=>'crystallization_trials', 12=>'crystals' , 13 =>'diffraction', 14=>'dataset', 15=>'structure',16=>'comments');
$translation=array('soluble'=>'Insol','purified'=>'failed to cleave','crystallization_trials'=>'X (with Tag)');
require '../classes/Model.php';
require '../classes/Rosetta_new.php';
require '../classes/DBObject.php';
$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();

$ros=new Rosetta_new();
$ros->setDatabaseConnection($databaseConnection);
$data=$ros->getRosettaData();
foreach($data as $row)
	{	
	echo "<tr>";
	foreach($index as $col)
	{
		$feed=$row[$col];
		if($col==$index[0]){
			if($feed=='0')
				$feed='-';
			if($feed=='1')
				$feed='+';
		}	
		else{
			if($feed=='null')
				$feed='';
			if($feed=='0')
				$feed='-';
			if($feed=='1')
				$feed='X';
			if($feed=='3')
				$feed=$translation[$col];
		}
		if($col==$index[4])
			echo "<td class=\"".$col."\" text_val=\"".$feed."\">".$feed."</td>";
		else if($col==$index[2])
			echo "<td class=\"".$col."\" value=\"".$feed."\">".$feed."</td>";
		else
			echo "<td class=\"".$col."\">".$feed."</td>";
	}
	echo "</tr>";
}
?>
