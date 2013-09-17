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
    <p>Please select from the drop down menu the EDT/VCID you would like to view.</br></br></br></br></p>
    <p id="error"></p>
    <p id="success" style="color: green;"><?php if (isset($_REQUEST['success'])) echo $_REQUEST['success']; ?></p>
  </div><!-- /#description -->
   <form method="post" action="<?php echo $PHP_SELF;?>">
  <div id="leftCol">
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
		  <input type="checkbox" name ="date"	 value = "Date" checked disabled ="disabled" class="sample_info" />Date</br>
		  <input type="checkbox" name ="comment"	 value = "Comment"   class="experiment_info" />Comment
		</td>
      </tr>  
	</table>
    <table>
      <tr> 
        <td><p class="title" >EDT/VCID: <em>*</em></p></td>
        <td>
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
        </td>
	</tr>
  </table>
  </div> <!-- /#leftCol -->

  <div id="rightCol">
	<table>
	</table>
  </div> <!-- /#rightCol -->
  
  </form>
	</section><!-- /#mainarea -->

  <footer>
    <ul>
       <li><a href="input.php" id="Ffirst">external link</a></li>
    </ul>
  </footer>

</body>
</html>

