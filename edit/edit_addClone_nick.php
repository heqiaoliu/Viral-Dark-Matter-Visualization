<?php  
//////////////////////////////////////////////
// FILE:            edit_addClone.php //
// ORIGINAL AUTHOR: HQ
// PURPOSE:         Allow inserting new bacteria to bacteria table
// NOTES:           None
//////////////////////////////////////////////
require "initialize.php";
require "../classes/Edit.php";
// user input variables: html form -> uploader.php -> parse.pl -> MySQL DB


$bact_external_id=$_POST['bact_external_id'];
$bact_name=$_POST['bact_name'];
$vc_id=$_POST['vc_id'];
$vector = $_POST['vector']; 
$genotype = $_POST['genotype']; 

$dbo = new DBObject("localhost", "nturner", "LOB4steR", "viral_dark_matter");
$db = $dbo->getDB();
$Bact = Container::makeBacter();
$Bact->setDatabaseConnection($db);
echo $Bact->createBacterium($bact_external_id, $bact_name, $vc_id, $vector, $genotype);

?>

