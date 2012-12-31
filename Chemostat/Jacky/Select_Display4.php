<!DOCTYPE html>
<html lang="en">

<head> 
  <?php require "head.html"; ?>
  <script src="input_js.js" type="text/javascript"></script>
</head>

<?php echo '<body id="input">';
require "header.html"; ?>

<nav>
  <?php  require "nav.html"; ?>
</nav>

<section id="mainarea">
  <div id="description" >
    <p>Please select from the drop down menu the EDT/VCID you would like to view.</p>
    <p id="error"></p>
    <p id="success" style="color: green;"><?php if (isset($_REQUEST['success'])) echo $_REQUEST['success']; ?></p>
  </div><!-- /#description -->
<form method="post" action="<?php echo $PHP_SELF;?>">
  <div id="leftCol">
  </br></br></br>
  <table border = "1" width="100%" cellspacing="10" cellpadding="4">
	  <tr>
		<td>
		  <input type="checkbox" name="abundance" value = "Abundance" checked disabled ="disabled" class="chemo_data" />Abundance</br>
          <input type="checkbox" name="bb_id" 	 value = "BB_ID" checked disabled ="disabled" class="binBase" />BB_ID</br>
		  <input type="checkbox" name="edt/vcid" value = "EDT/VCID" checked  disabled ="disabled" class="sample_info" />EDT_VCID</br>
		</td>
		<td>
		 <input type="checkbox" name="reactor" value = "Reactor_ID" checked disabled ="disabled" class="sample_info" />Reactor_ID</br>
		  <input type="checkbox" name="reactor"  value = "Sample_ID" checked disabled ="disabled" class="sample_info" />Sample_ID</br>
          <input type="checkbox" name="rep"	 value = "Rep" checked disabled ="disabled" class="chemo_data" />Rep</br>
		 </td>
		 <td> 
		  <input type="checkbox" name ="date"	 value = "Date" checked disabled ="disabled" class="sample_info" />Date
		</td>
      </tr>  
	</table border = "1" width="100%" cellspacing="10" cellpadding="4">
    <table>
      <tr> 
        <td valign="top"></br></br>EDT/VCID: <em>*</em></td>
        <td valign="top"></br>
        <?php
		include_once "login.php";
		
		$query ="SELECT DISTINCT bact_external_id,vc_id FROM sample_info";
		$result = $mysqli->prepare($query);
		$result->execute() or die ("There is a problem with the database");
		echo "<form method = POST><select name ='bactid[]' multiple='multiple'>";
		$result->bind_result($edt,$vcid);
		while($result->fetch()) {
			if($edt == "NULL"){
				echo "<option value='VCID".$vcid."'>"."VCID".$vcid."</option>";
			}
			else{
				echo "<option value='".$edt."'>".$edt."</option>";
			}
		}
        ?>
		</select></br><input type='submit'></form>
        </td>
	</tr>
  </table>
  </form>
  </div> <!-- /#leftCol -->

  <div id="rightCol" style="height: 350px; width: 400px; overflow: scroll;">
	<?php

	$query="SELECT b.BB_ID, s.File_ID, s.Reactor_ID, s.bact_external_id, s.vc_id, s.Rep, c.Abundance "
		."FROM binBase b,sample_info s, chemo_data c, experiment_info e "
		."WHERE c.Bin_ID=b.Bin_ID AND c.Samp_ID=s.Samp_ID AND e.Exp_ID = 1 limit 2000";
	$result= $mysqli -> prepare($query);
	$result->execute() or die ("There is a problem with the database");
	$result->bind_result($bbid, $fileid, $reactorid, $edtid, $vcid, $rep, $abundance);
	?>
	<table border="1" width="100%" cellspacing="8" cellpadding="8" align="center">

	<?php
	$reactor = array();
	$edt = array();
	$replicate = array();
	while ($result->fetch()) {

		$reactor{$fileid} = $reactorid;
		if ($edtid == "NULL") {
			$edt{$fileid} = "VCID".$vcid;
			$vc = "VCID".$vcid;
			$edtrv{$vc} .= $fileid."|";
		} else {
			$edt{$fileid} = $edtid;
			$edtrv{$edtid} .= $fileid."|";
		}
		$replicate{$fileid} = $rep;

		$abun{$bbid}{$fileid} = $abundance;
	}

	/////THIS PIECE GRABS THE SELECTED VALUES FROM DROPDOWN
	$fidarrays = array();
	if( $_POST['bactid'] != ''){ 
		$array = $_POST['bactid'];
		foreach ($array as $bactid){
			echo $bactid;
			$fids = $edtrv{$bactid};
			$fidarray = explode("|",$fids);
			array_push($fidarrays,$fidarray);
			///loops through array.
		}
	}
	foreach ($fidarrays as $i) {
	//	echo "$i\t";
	}

	////////
	$tout = "";
	$tout .= "<tr><td>EDT/VCID</td>";
	foreach ($fidarrays as $f) {
		$tout .= "<td>".$edt[$f]."</td>";	
	}
	$tout .= "</tr>";
	$tout .= "<tr><td>Reactor</td>";
	foreach ($fidarrays as $f) {
		$tout .= "<td>".$reactor[$f]."</td>";
	}
	$tout .= "</tr>";
	$tout .= "<tr><td>Replicate</td>";
	foreach ($fidarrays as $f) {
		$tout .= "<td>".$replicate[$f]."</td>";
	}
	$tout .= "</tr>";
	foreach ($abun as $bb => $value ) {
		$tout .= "<tr><td>$bb</td>";
		foreach ($fidarrays as $f) {
			$tout .= "<td>".$abun[$f]."</td>";
		}
		$tout .= "</tr>";
	}

/*
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
*/
echo "$tout";
	?>
	</table>

  </div> <!-- /#rightCol -->
  
	</section><!-- /#mainarea -->
  <footer>
    <ul>
       <!--> <li><a href="input.php" id="Ffirst">external link</a></li><!-->
    </ul>
  </footer>

</body>
</html>

