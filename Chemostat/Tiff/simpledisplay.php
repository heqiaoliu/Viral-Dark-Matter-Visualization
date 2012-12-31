<html>
<body>

<?php
include_once "login.php";
 
$query="SELECT b.BB_ID, s.File_ID, s.Reactor_ID, s.bact_external_id, s.vc_id, s.Rep, c.Abundance "
	."FROM binBase b,sample_info s, chemo_data c, experiment_info e "
	."WHERE c.Bin_ID=b.Bin_ID AND c.Samp_ID=s.Samp_ID AND e.Exp_name='miniX77923'";
	#."WHERE c.Bin_ID=b.Bin_ID AND c.Samp_ID=s.Samp_ID AND e.Exp_ID=1";
$result= $mysqli -> prepare($query);
$result->execute() or die ("There is a problem with the database");
$result->bind_result($bbid, $fileid, $reactorid, $edtid, $vcid, $rep, $abundance);

?>
<table border="1" cellspacing="2" cellpadding="2" align="center">

<?php
$reactor = array();
$edt = array();
$replicate = array();
while ($result->fetch()) {

	$reactor{$fileid} = $reactorid;
	if ($edtid == "NULL") {
		$edt{$fileid} = "VCID".$vcid;
	} else {
		$edt{$fileid} = $edtid;
	}
	$replicate{$fileid} = $rep;

	$abun{$bbid}{$fileid} = $abundance;

}

$csv = "";
$tout = "";
$tout .= "<tr><td>EDT/VCID</td>";
$csv .= "EDT/VCID\t";
foreach ($edt as $fid => $ev) {
	$tout .= "<td>$ev</td>";
	$csv .= "$ev\t";
}
$tout .= "</tr>";
$csv .= "\n";

$tout .= "<tr><td>Reactor</td>";
$csv .= "Reactor\t";
foreach ($reactor as $fid => $ev) {
	$tout .= "<td>$ev</td>";
	$csv .= "$ev\t";
}
$tout .= "</tr>";
$csv .= "\n";

$tout .= "<tr><td>Rep</td>";
$csv .= "Rep\t";
foreach ($replicate as $fid => $ev) {
	$tout .= "<td>$ev</td>";
	$csv .= "$ev\t";
}
$tout .= "</tr>";
$csv .= "\n";

foreach ($abun as $bb => $value ) {
	$tout .= "<tr><td>$bb</td>";
	$csv .= "$bb\t";
	foreach ($value as $fid => $ab) {
		$tout .= "<td>$ab</td>";
		$csv .= "ab\t";
	}
	$tout .= "</tr>";
	$csv .= "\n";
}
?>
<form method="POST" action="csv_creator.php">
<input type="hidden" name="results" value="<?php echo $csv ?>">
<input type="submit" value="Get CVS">
</form>
<?
echo $tout;
?>
</table>


</body>
</html>

