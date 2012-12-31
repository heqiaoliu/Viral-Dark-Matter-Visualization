<html>
<body>
<?php
/*
$username="nturner";
$password="LOB4steR";
$database="viral_dark_matter";
$host = "localhost";

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli -> connect_errno) {
	die ("Failed to connect to MySQL: (".
		$mysqli->connect_errno, ") ",
		$mysqli->connect_error);
}
 */

include_once "login.php";
 
//mysql_connect(localhost,$username,$password);
//@mysql_select_db($database) or die( "Unable to select database");
$query="SELECT b.BB_ID, s.File_ID, s.Reactor_ID, s.bact_external_id, s.vc_id, s.Rep, c.Abundance "
	."FROM binBase b,sample_info s, chemo_data c, experiment_info e "
	."WHERE c.Bin_ID=b.Bin_ID AND c.Samp_ID=s.Samp_ID AND e.Exp_ID = 1 limit 2000";
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
//	echo $fileid."\t".$reactor{$fileid}."\t".$edt{$fileid}."\t".$replicate{$fileid}."\t".$abun{$bbid}{$fileid}."<br />";
}


/*
	$f4=mysql_result($result,$i,"vc_id");
	$f5=mysql_result($result,$i,"Rep");
	$f6=mysql_result($result,$i,"Abundance");
*/


echo "<tr><td>EDT/VCID</td>";
foreach ($edt as $fid => $ev) {
	echo "<td>$ev</td>";
}
echo "</tr>";

echo "<tr><td>Reactor</td>";
foreach ($reactor as $fid => $ev) {
	echo "<td>$ev</td>";
}
echo "</tr>";

echo "<tr><td>Rep</td>";
foreach ($replicate as $fid => $ev) {
	echo "<td>$ev</td>";
}
echo "</tr>";

foreach ($abun as $bb => $value ) {
	echo "<tr><td>$bb</td>";
	foreach ($value as $fid => $ab) {
		echo "<td>$ab</td>";
	}
	echo "</tr>";
}
?>
</table>
<tr>
<td><font face="Arial, Helvetica, sans-serif"><?php #echo ""; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php #echo ""; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php #if ($f3 == "NULL") {echo "VCID".$f4;}else {echo $f3;} ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php #echo $f5; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php #echo $f6; ?></font></td>
</tr>

<?php
	#$i++;
#}
?>
</body>
</html>

