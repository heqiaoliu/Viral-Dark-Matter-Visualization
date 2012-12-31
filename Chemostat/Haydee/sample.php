<?php
		$server   = "localhost";
		$user     = "nturner";
		$password = "LOB4steR";

		trim($server);
		trim($user);
		trim($password);

		function error( $msg ){
			print( "<h2>ERROR: $msg</h2>\n" );
			exit();}
		$db = mysql_connect($server, $user, $password );
		if ( ! $db ){
			error( "Cannot open connection to $user@$server" );}

		if (!mysql_select_db( "viral_dark_matter", $db )){
			error( "Cannot select database 'viral_dark_matter'." );}
		$result = mysql_query("SELECT DISTINCT bact_external_id,vc_id FROM sample_info");
		echo "<select name ='bactid'>";
		while($row = mysql_fetch_array($result)) {
			$edt = $row["bact_external_id"];
			$vcid = $row["vc_id"];
			echo $edt;
			if($edt == "NULL"){
				echo "<option value='".$vcid."'>".$vcid."</option>";
			}
			else{
				echo "<option value='".$edt."'>".$edt."</option>";
			}
		}
?>