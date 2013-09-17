<?php
//Connects to mysql and databse.
$user = "root";
$pass = "loonatic07";
try {
	$conn = new PDO('mysql:host=localhost;dbname=my_db',$user,$pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//checks connection
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}			 

//Uploads file from server to Perl script.
$target_path = "uploads/";
$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
    " has been uploaded</br>";
} else{
    echo "There was an error uploading the file, please try again!</br>";
}
$uploadFilePath = getcwd()."\\".$target_path;
$uploadFilePath = str_replace("\\","/",$uploadFilePath);
$cmd = "C:/xampp/perl/bin/perl.exe C:/Users/Owner/Desktop/metabolomics/parse_xls2table.pl $uploadFilePath";
shell_exec($cmd);

//Sends the output of perl script to be loaded into database tables.
$outEI = $_FILES['uploadedfile']['name'];
loadToEI($conn,$outEI);

$outSIfilePath = getcwd()."\\".$target_path.".SampleInfo";
$outSIfilePath = str_replace("\\","/",$outSIfilePath);
loadToSI($conn, $outSIfilePath);

$outBBfilePath = getcwd()."\\".$target_path.".BinBase";
$outBBfilePath = str_replace("\\","/",$outBBfilePath);
loadToBB($conn, $outBBfilePath);

$outMSfilePath = getcwd()."\\".$target_path.".MSdata";
$outMSfilePath = str_replace("\\","/",$outMSfilePath);
loadToMS($conn,$outMSfilePath,$outEI);

function loadToEI($conn,$outEI){
	$conn->query( "DELETE FROM experimentInfo"); //DELETE DATA IN exp. table.
	$conn->query("INSERT INTO experimentInfo (Exp_ID) VALUES('$outEI')") ;  
	echo "File '$outEI' uploaded! <br/>";						

	//Retrieve all the data from table 'experimentInfo' found in database
	$result = $conn->query("SELECT * FROM experimentInfo");

	//Prints out table with uploaded data
	echo "<table border = '1'>";
	echo "<tr><th>EXP_ID</th></tr>";						
	while($row = $result->fetch(PDO::FETCH_BOTH )) { 
		// Print out the contents of each row into a table
		echo "<tr><td>"; 
		echo $row['Exp_ID'];
		echo "</td></tr>"; 
	}
	echo "</table>"; 
}

function loadToMS($conn, $outMSfilePath,$outEI){
	$conn->query( "DELETE FROM msdata");	//DELETES DATA IN msdata table
	$original = fopen($outMSfilePath, 'r');	//opens .MSdata output file
	$attribute = explode("\t",trim(fgets($original))); //reads in first line (header). Saves to array.
	
	while(!feof($original)){
	  $attValues = [NULL,NULL,NULL,NULL];	//Default file header has no attributes
	  $currline = trim(fgets($original));	//reads in next line of .MSdata file
	 
	  if($currline == '')
	  {	  break; }							//checks end of file if line read is empty
	  $currline = explode("\t",$currline);	//line read is saved into array.
	  $cmd = $conn->prepare("INSERT INTO msdata (Exp_ID, BB_ID, File_ID, Abundancy) VALUES (?,?,?,?)");
	  
	  //Checks which attributes are in the header 
	  for($i=0; $i < count($attribute); $i++){
		$attValues[0]=$outEI;
		if($attribute[$i] == 'BB_ID'){
			$attValues[1]=$currline[$i];
		}
		if($attribute[$i] == 'File_ID'){
			$attValues[2]=$currline[$i];
		}
		if($attribute[$i] == 'Abundancy'){
			$attValues[3]=$currline[$i];
		}
	  }
	  $cmd->bindParam(1,$attValues[0]);		//binds attribute value to 'Exp_ID'
	  $cmd->bindParam(2,$attValues[1]);		//binds attribute value to 'BB_ID'
	  $cmd->bindParam(3,$attValues[2]);		//binds attribute value to 'File_ID'
	  $cmd->bindParam(4,$attValues[3]);		//binds attribute value to 'Abundancy'
	  $cmd->execute(); 						//executes insertion
	  }
	echo "File '$outMSfilePath' uploaded! <br/>";				

	//Retrieve all the data from table 'MSdata' found in database
	$result = $conn->query("SELECT * FROM msdata");

	//Prints out table with uploaded data
	echo "<table border = '1'>";
	echo "<tr><th>BB_ID</th><th>File_ID</th><th>Abundancy</th><th>EXP_ID</th></tr>";						//seperated by tabs 
	// keeps getting the next row until there are no more to get
	while($row = $result->fetch(PDO::FETCH_BOTH )) {
		// Print out the contents of each row into a table
		echo "<tr><td>"; 
		echo $row['BB_ID'];
		echo "</td><td>"; 
		echo $row['File_ID'];
		echo "</td><td>"; 
		echo $row['Abundancy'];
		echo "</td><td>"; 
		echo $row['Exp_ID'];
		echo "</td></tr>"; 
	}
	echo "</table>"; 
}

function loadToSI($conn,$outSIfilePath){
	$conn->query("DELETE FROM sampleInfo");	//DELETES data from sampleInfo table.

	$original = fopen($outSIfilePath, 'r');	//opens .SampleInfo output file
	$attribute = explode("\t",trim(fgets($original))); //reads in first line (header). Saves to array.
	
	while(!feof($original)){
	  $attValues = [NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL];	//Default file header has no attributes
	  $currline = trim(fgets($original));	//reads in next line of .SampleInfo file
	 
	  if($currline == '')
	  {	  break; }							//checks end of file if line read is empty
	  $currline = explode("\t",$currline);	//line read is saved into array.
	  $cmd = $conn->prepare("INSERT INTO sampleInfo (ID, Rep, Reactor_ID, Sample_ID,Genotype,Vector,Specie,Date,File_ID,Comment) VALUES (?,?,?,?,?,?,?,?,?,?)");
	  
	  //Checks which attributes are in the header 
	  for($i=0; $i < count($attribute); $i++){
		if($attribute[$i] == 'ID'){
			$attValues[0]=$currline[$i];
		}
		if($attribute[$i] == 'Rep'){
			$attValues[1]=$currline[$i];
		}
		if($attribute[$i] == 'Reactor_ID'){
			$attValues[2]=$currline[$i];
		}
		if($attribute[$i] == 'Sample_ID'){
			$attValues[3]=$currline[$i];
		}
		if($attribute[$i] == 'Genotype'){
			$attValues[4]=$currline[$i];
		}
		if($attribute[$i] == 'Vector'){
			$attValues[5]=$currline[$i];
		}
		if($attribute[$i] == 'Specie'){
			$attValues[6]=$currline[$i];
		}
		if($attribute[$i] == 'Date'){
			$attValues[7]=$currline[$i];
		}
		if($attribute[$i] == 'File_ID'){
			$attValues[8]=$currline[$i];
		}
		if($attribute[$i] == 'Comment'){
			$attValues[9]=$currline[$i];
		}
	  }
	  $cmd->bindParam(1,$attValues[0]);		//binds attribute value to 'ID'
	  $cmd->bindParam(2,$attValues[1]);		//binds attribute value to 'Rep'
	  $cmd->bindParam(3,$attValues[2]);		//binds attribute value to 'Reactor_ID'
	  $cmd->bindParam(4,$attValues[3]);		//binds attribute value to 'Sample_ID'
	  $cmd->bindParam(5,$attValues[4]);		//binds attribute value to 'Genotype'
	  $cmd->bindParam(6,$attValues[5]);		//binds attribute value to 'Vector'
	  $cmd->bindParam(7,$attValues[6]);		//binds attribute value to 'Specie'
	  $cmd->bindParam(8,$attValues[7]);		//binds attribute value to 'Date'
	  $cmd->bindParam(9,$attValues[8]);		//binds attribute value to 'File_ID'
	  $cmd->bindParam(10,$attValues[9]);	//binds attribute value to 'Comment'
	  $cmd->execute(); 						//executes insertion
	  }
	echo "File '$outSIfilePath' uploaded! <br/>";				

	//Retrieve all the data from table 'sampleInfo' found in database
	$result = $conn->query("SELECT * FROM sampleInfo");

	//Prints a table with of uploaded data
	echo "<table border = '1'>";
	echo "<tr><th>ID</th><th>Rep</th><th>Reactor_ID</th><th>Sample_ID</th><th>Genotype</th><th>Vector</th><th>Specie</th><th>Date</th><th>File_ID</th><th>Comment</th></tr>";
	// keeps getting the next row until there are no more to get
	while($row = $result->fetch(PDO::FETCH_BOTH )) {
		// Print out the contents of each row into a table
		echo "<tr><td>"; 
		echo $row['ID'];
		echo "</td><td>"; 
		echo $row['Rep'];
		echo "</td><td>"; 
		echo $row['Reactor_ID'];
		echo "</td><td>"; 
		echo $row['Sample_ID'];
		echo "</td><td>"; 
		echo $row['Genotype'];
		echo "</td><td>"; 
		echo $row['Vector'];
		echo "</td><td>"; 
		echo $row['Specie'];
		echo "</td><td>"; 
		echo $row['Date'];
		echo "</td><td>"; 
		echo $row['File_ID'];
		echo "</td><td>"; 
		echo $row['Comment'];
		echo "</td></tr>"; 
	}
	echo "</table>";
}
function loadToBB($conn,$outBBfilePath){
	$conn->query("DELETE FROM binBase");	//DELETES data from sampleInfo table.

	$original = fopen($outBBfilePath, 'r');	//opens .BinBase output file
	$attribute = explode("\t",trim(fgets($original))); //reads in first line (header). Saves to array.
	
	while(!feof($original)){
	  $attValues = [NULL,NULL,NULL,NULL,NULL,NULL,NULL];	//Default file header has no attributes
	  $currline = trim(fgets($original));	//reads in next line of .BinBase file
	 
	  if($currline == '')
	  {	  break; }							//checks end of file if line read is empty
	  $currline = explode("\t",$currline);	//line read is saved into array.
	  $cmd = $conn->prepare("INSERT INTO binBase (BB_ID, BB_Name, Kegg_ID, PubChem_ID, Ret_Index, Quant_mz, Mass_spec) VALUES (?,?,?,?,?,?,?)");
	  
	  //Checks which attributes are in the header 
	  for($i=0; $i < count($attribute); $i++){
		if($attribute[$i] == 'BB_ID'){
			$attValues[0]=$currline[$i];
		}
		if($attribute[$i] == 'BB_Name'){
			$attValues[1]=$currline[$i];
		}
		if($attribute[$i] == 'Kegg_ID'){
			$attValues[2]=$currline[$i];
		}
		if($attribute[$i] == 'PubChem_ID'){
			$attValues[3]=$currline[$i];
		}
		if($attribute[$i] == 'Ret_Index'){
			$attValues[4]=$currline[$i];
		}
		if($attribute[$i] == 'Quant_mz'){
			$attValues[5]=$currline[$i];
		}
		if($attribute[$i] == 'Mass_spec'){
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
	echo "<tr><th>BB_ID</th><th>BB_Name</th><th>Kegg_ID</th><th>PubChem_ID</th><th>Ret_Index</th><th>Quant_mz</th><th>Mass_spec</th></tr>";					
	// keeps getting the next row until there are no more to get
	while($row = $result->fetch(PDO::FETCH_BOTH )) {
		// Print out the contents of each row into a table
		echo "<tr><td>"; 
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
?>