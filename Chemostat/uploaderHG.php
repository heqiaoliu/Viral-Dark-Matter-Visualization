<?php
/*
Chemo_Data Table:
	ChemoData output file from perl script has abundancy only.
	Samp_ID, Bin_ID and Exp_ID are autho increments from respective tables.
ExperimentInfo table:
	Exp_name is the file name.
	Comment is gathered from from the user through _POST.
BinBase Table:
	BB_ID, BB_Name, Kegg_ID, PubChem_ID, Ret_Index, Quant_mz, Mass_spec are
		gathered from binBase output file from perl script.
SampleInfo Table:
	Reactor_ID, Sample_ID, bact_external_id, vc_id, Ret, Genotype, Vector, Specie, ms_date, File_ID, Comment,
*/

error_reporting(E_ALL);
ini_set('display_errors','On');

//Connects to mysql and databse.
$user = "garrido";
$pass = "loonatic07";
try {
	$conn = new PDO('mysql:host=localhost;dbname=my_db',$user,$pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//checks connection
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}		

$commentText = $_POST['commentBox'];
$target = getcwd()."/uploads/";
if (!is_dir($target)) {
    mkdir($target); //create the directory
}
//Uploads file from server to Perl script.
$target_path = "uploads/";
$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);
if (!file_exists(getcwd()."/".$target_path)) {
	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
		echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
			" has been uploaded</br>";
	} else{
		echo "There was an error uploading the file, please try again!</br>";
	}
} 
else{
	echo "File Already Exists";
}
$uploadFilePath = getcwd()."/".$target_path;
$cmd = "perl parse_xls2table.pl $uploadFilePath";
$output = shell_exec($cmd);

if (preg_match("/^ERROR:/i",$output)){
	header("Location: http://vdm.sdsu.edu/data/Chemostat/uploadButtonHG.php?ERROR='TRUE'");
    exit;
}

//Sends the output of perl script to be loaded into database tables.
$outEI = $_FILES['uploadedfile']['name'];
loadToEI($conn,$outEI,$commentText);

$outSIfilePath = getcwd()."/".$target_path.".SampleInfo";
loadToSI($conn, $outSIfilePath);

$outBBfilePath = getcwd()."/".$target_path.".BinBase";
// $outBBfilePath = str_replace("\\","/",$outBBfilePath);
loadToBB($conn, $outBBfilePath);

$outCDfilePath = getcwd()."/".$target_path.".ChemoData";
loadToCD($conn,$outCDfilePath,$outEI);

//unlink($uploadFilePath);
//unlink($outSIfilePath);
//unlink($outBBfilePath);
//unlink($outCDfilePath);

function loadToEI($conn,$outEI,$commentText){
	$conn->query("TRUNCATE TABLE experimentInfo"); //DELETE DATA IN exp. table.
	$conn->query("INSERT INTO experimentInfo (Exp_name,Comment) VALUES('$outEI','$commentText')") ;  
	echo "File '$outEI' uploaded! <br/>";						

	//Retrieve all the data from table 'experimentInfo' found in database
	$result = $conn->query("SELECT * FROM experimentInfo");

	//Prints out table with uploaded data
	echo "<table border = '1'>";
	echo "<tr><th>Exp_ID</th><th>Exp_Name</th><th>Comment</th></tr>";						
	while($row = $result->fetch(PDO::FETCH_BOTH )) { 
		// Print out the contents of each row into a table
		echo "<tr><td>"; 
		echo $row['Exp_ID'];
		echo "</td><td>"; 
		echo $row['Exp_name'];
		echo "</td><td>"; 
		echo $row['Comment'];
		echo "</td></tr>";  
	}
	echo "</table>"; 
}

function loadToSI($conn,$outSIfilePath){
	$conn->query("TRUNCATE TABLE sampleInfo");	//DELETES data from sampleInfo table.

	$original = fopen($outSIfilePath, 'r');	//opens .SampleInfo output file
	$attribute = explode("\t",trim(fgets($original))); //reads in first line (header). Saves to array.
	
	while(!feof($original)){
	  //$attValues = [NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL];	//Default file header has no attributes
	  $attValues = array(NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
	  $currline = trim(fgets($original));	//reads in next line of .SampleInfo file
	 
	  if($currline == '')
	  {	  break; }							//checks end of file if line read is empty
	  $currline = explode("\t",$currline);	//line read is saved into array.
	  $cmd = $conn->prepare("INSERT INTO sampleInfo (Reactor_ID,Sample_ID,Bact_external_id,Vc_id,Rep,Genotype,Vector,Specie,Ms_date,File_ID,Comment) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
	  
	  //Checks which attributes are in the header 
	  for($i=0; $i < count($attribute); $i++){
	  	if(preg_match("/Reactor_ID/i", $attribute[$i])){
			$attValues[0]=$currline[$i];
		}
		if(preg_match("/Sample_ID/i",$attribute[$i])){
			$attValues[1]=$currline[$i];
		}
		if(preg_match("/Bact_external_id/i",$attribute[$i])){
			$attValues[2]=$currline[$i];
		}
		if(preg_match("/vc_id/i",$attribute[$i])){
			$attValues[3]=$currline[$i];
		}
		if(preg_match("/Rep/i",$attribute[$i])){
			$attValues[4]=$currline[$i];
		}
		if(preg_match("/Genotype/i",$attribute[$i])){
			$attValues[5]=$currline[$i];
		}
		if(preg_match("/Vector/i",$attribute[$i])){
			$attValues[6]=$currline[$i];
		}
		if(preg_match("/Specie/i",$attribute[$i])){
			$attValues[7]=$currline[$i];
		}
		if(preg_match("/date/i",$attribute[$i])){
			$attValues[8]=$currline[$i];
		}
		if(preg_match("/File_ID/i",$attribute[$i])){
			$attValues[9]=$currline[$i];
		}
		if(preg_match("/Comment/i",$attribute[$i])){
			$attValues[10]=$currline[$i];
		}
	  }
	  $cmd->bindParam(1,$attValues[0]);		//binds attribute value to 'Reactor_ID'
	  $cmd->bindParam(2,$attValues[1]);		//binds attribute value to 'Sample_ID'
	  $cmd->bindParam(3,$attValues[2]);		//binds attribute value to 'Bact_external_id'
	  $cmd->bindParam(4,$attValues[3]);		//binds attribute value to 'Vc_id'
	  $cmd->bindParam(5,$attValues[4]);		//binds attribute value to 'Rep'
	  $cmd->bindParam(6,$attValues[5]);		//binds attribute value to 'Genotype'
	  $cmd->bindParam(7,$attValues[6]);		//binds attribute value to 'Vector'
	  $cmd->bindParam(8,$attValues[7]);		//binds attribute value to 'Specie'
	  $cmd->bindParam(9,$attValues[8]);		//binds attribute value to 'Ms_date'
	  $cmd->bindParam(10,$attValues[9]);		//binds attribute value to 'File_ID'
	  $cmd->bindParam(11,$attValues[10]);	//binds attribute value to 'Comment'
	  $cmd->execute(); 						//executes insertion
	  }
	echo "File '$outSIfilePath' uploaded! <br/>";				

	//Retrieve all the data from table 'sampleInfo' found in database
	$result = $conn->query("SELECT * FROM sampleInfo");

	//Prints a table with of uploaded data
	echo "<table border = '1'>";
	echo "<tr><th>Samp_ID</th><th>Reactor_ID</th><th>Sample_ID</th><th>bact_external_id</th><th>vc_id</th><th>Rep</th><th>Genotype</th><th>Vector</th><th>Specie</th><th>ms_date</th><th>File_ID</th><th>Comment</th></tr>";
	// keeps getting the next row until there are no more to get
	while($row = $result->fetch(PDO::FETCH_BOTH )) {
		// Print out the contents of each row into a table
		echo "<tr><td>"; 
		echo $row['Samp_ID'];
		echo "</td><td>"; 
		echo $row['Reactor_ID'];
		echo "</td><td>"; 
		echo $row['Sample_ID'];
		echo "</td><td>"; 
		echo $row['Bact_external_id'];
		echo "</td><td>"; 
		echo $row['Vc_id'];
		echo "</td><td>"; 
		echo $row['Rep'];
		echo "</td><td>"; 
		echo $row['Genotype'];
		echo "</td><td>"; 
		echo $row['Vector'];
		echo "</td><td>"; 
		echo $row['Specie'];
		echo "</td><td>"; 
		echo $row['Ms_date'];
		echo "</td><td>"; 
		echo $row['File_ID'];
		echo "</td><td>"; 
		echo $row['Comment'];
		echo "</td></tr>"; 
	}
	echo "</table>";
}
function loadToBB($conn,$outBBfilePath){
	$conn->query("TRUNCATE TABLE binBase");	//DELETES data from sampleInfo table.

	$original = fopen($outBBfilePath, 'r');	//opens .BinBase output file
	$attribute = explode("\t",trim(fgets($original))); //reads in first line (header). Saves to array.
	
	while(!feof($original)){
	  $attValues = array(NULL,NULL,NULL,NULL,NULL,NULL,NULL);	//Default file header has no attributes
	  $currline = trim(fgets($original));	//reads in next line of .BinBase file
	 
	  if($currline == '')
	  {	  break; }							//checks end of file if line read is empty
	  $currline = explode("\t",$currline);	//line read is saved into array.
	  $cmd = $conn->prepare("INSERT INTO binBase (BB_ID, BB_Name, Kegg_ID, PubChem_ID, Ret_Index, Quant_mz, Mass_spec) VALUES (?,?,?,?,?,?,?)");
	  
	  //Checks which attributes are in the header 
	  for($i=0; $i < count($attribute); $i++){
		if(preg_match("/BB_ID/i",$attribute[$i])){
			$attValues[0]=$currline[$i];
		}
		if(preg_match("/BB_Name/i",$attribute[$i])){
			$attValues[1]=$currline[$i];
		}
		if(preg_match("/Kegg_ID/i",$attribute[$i])){
			$attValues[2]=$currline[$i];
		}
		if(preg_match("/PubChem_ID/i",$attribute[$i])){
			$attValues[3]=$currline[$i];
		}
		if(preg_match("/Ret_Index/i",$attribute[$i])){
			$attValues[4]=$currline[$i];
		}
		if(preg_match("/Quant_mz/i",$attribute[$i])){
			$attValues[5]=$currline[$i];
		}
		if(preg_match("/Mass_spec/i",$attribute[$i])){
			$attValues[6]=$currline[$i];
		}
	  }
	  $cmd->bindParam(1,$attValues[0]);		//binds attribute value to 'BB_ID'
	  $cmd->bindParam(2,$attValues[1]);		//binds attribute value to 'BB_Name'
	  $cmd->bindParam(3,$attValues[2]);		//binds attribute value to 'Kegg_ID'
	  $cmd->bindParam(4,$attValues[3]);		//binds attribute value to 'PubChem_ID'
	  $cmd->bindParam(5,$attValues[4]);		//binds attribute value to 'Ret_Index'
	  $cmd->bindParam(6,$attValues[5]);		//binds attribute value to 'Quant_mz'
	  $cmd->bindParam(7,$attValues[6]);		//binds attribute value to 'Mass_spec'
	  $cmd->execute(); 						//executes insertion
	  }
	echo "File '$outBBfilePath' uploaded! <br/>";				

	//Retrieve all the data from table 'binBase' found in database
	$result = $conn->query("SELECT * FROM binBase");

	//Prints out table with uploaded data
	echo "<table border = '1'>";
	echo "<tr><th>Bin_ID</th><th>BB_ID</th><th>BB_Name</th><th>Kegg_ID</th><th>PubChem_ID</th><th>Ret_Index</th><th>Quant_mz</th><th>Mass_spec</th></tr>";					
	// keeps getting the next row until there are no more to get
	while($row = $result->fetch(PDO::FETCH_BOTH )) {
		// Print out the contents of each row into a table
		echo "<tr><td>"; 
		echo $row['Bin_ID'];
		echo "</td><td>"; 
		echo $row['BB_ID'];
		echo "</td><td>"; 
		echo $row['BB_Name'];
		echo "</td><td>"; 
		echo $row['Kegg_ID'];
		echo "</td><td>"; 
		echo $row['PubChem_ID'];
		echo "</td><td>"; 
		echo $row['Ret_Index'];
		echo "</td><td>"; 
		echo $row['Quant_mz'];
		echo "</td><td>"; 
		echo $row['Mass_spec'];
		echo "</td></tr>"; 
	}
	echo "</table>"; 
}

function loadToCD($conn, $outCDfilePath, $outEI){
	$conn->query( "TRUNCATE TABLE chemo_data");	//DELETES DATA IN Chemo_data table
	$original = fopen($outCDfilePath, 'r');	//opens .ChemoData output file
	$attribute = explode("\t",trim(fgets($original))); //reads in first line (header). Saves to array.
	
	while(!feof($original)){
	  $attValues = array(NULL,NULL,NULL);	//Default file header has no attributes
	  $currline = trim(fgets($original));	//reads in next line of .ChemoData file
	 
	  if($currline == '')
	  {	  break; }							//checks end of file if line read is empty
	  $currline = explode("\t",$currline);	//line read is saved into array.
	  
	  $cmd4 = $conn->prepare("INSERT INTO chemo_data (Samp_ID, Bin_ID, Exp_ID, Abundancy) VALUES (?,?,?,?)");
	  
	  //Checks which attributes are in the header 
	  for($i=0; $i < count($attribute); $i++){
		//$attValues[0]=$outEI;
		if(preg_match("/File_ID/i",$attribute[$i])){
			$attValues[0]=$currline[$i];
		}
		if(preg_match("/BB_ID/i",$attribute[$i])){
			$attValues[1]=$currline[$i];
		}
		if(preg_match("/Abundancy/i",$attribute[$i])){
			$attValues[2]=$currline[$i];
		}
	  }
	  
	  $cmd1 = $conn->prepare("SELECT Samp_ID FROM sampleInfo WHERE File_ID = '".$attValues[0]."'");
	  $cmd2 = $conn->prepare("SELECT Exp_ID FROM experimentInfo WHERE Exp_name = '".$outEI."'");
	  $cmd3 = $conn->prepare("SELECT Bin_ID FROM binBase WHERE BB_ID = ".$attValues[1]."");
	  $cmd1->execute();
	  $cmd2->execute();
	  $cmd3->execute();
	  
	  $Samp = $cmd1->fetch(PDO::FETCH_ASSOC);
	  $Exp = $cmd2->fetch(PDO::FETCH_ASSOC);
	  $Bin = $cmd3->fetch(PDO::FETCH_ASSOC);
	  
	  $cmd4->bindParam(1,$Samp['Samp_ID']);		//binds attribute value to 'samp_id'
	  $cmd4->bindParam(2,$Bin['Bin_ID']);		//binds attribute value to 'Bin_ID'
	  $cmd4->bindParam(3,$Exp['Exp_ID']);		//binds attribute value to 'exp_ID'
	  $cmd4->bindParam(4,$attValues[2]);		//binds attribute value to 'Abundancy'
	  $cmd4->execute(); 						//executes insertion
	  }
	echo "File '$outCDfilePath' uploaded! <br/>";				

	//Retrieve all the data from table 'ChemoData' found in database
	$result = $conn->query("SELECT * FROM chemo_data");

	//Prints out table with uploaded data
	echo "<table border = '1'>";
	echo "<tr><th>Chemo_ID</th><th>Samp_ID</th><th>EXP_ID</th><th>Bin_ID</th><th>Abundance</th></tr>";						//seperated by tabs 
	// keeps getting the next row until there are no more to get
	while($row = $result->fetch(PDO::FETCH_BOTH )) {
		// Print out the contents of each row into a table
		echo "<tr><td>"; 
		echo $row['Chemo_ID'];
		echo "</td><td>"; 
		echo $row['Samp_ID'];
		echo "</td><td>"; 
		echo $row['Exp_ID'];
		echo "</td><td>"; 
		echo $row['Bin_ID'];
		echo "</td><td>"; 
		echo $row['Abundancy'];
		echo "</td></tr>"; 
	}
	echo "</table>"; 
}
?>