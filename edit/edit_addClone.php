<?php  
//////////////////////////////////////////////
// FILE:            edit_addClone.php //
// ORIGINAL AUTHOR: Nick,HQ
// PURPOSE:         Allow inserting new bacteria to bacteria table
// NOTES:           None
//////////////////////////////////////////////
//require "initialize.php";
require '../classes/Model.php';
require "../classes/Edit.php";
require '../classes/DBObject.php';
// user input variables: html form -> uploader.php -> parse.pl -> MySQL DB


$bact_external_id=$_POST['bact_external_id'];

//write a function for this later
$bact_name=$_POST['bact_name'];
if($bact_name=="")
	$bact_name=null;

$vc_id=$_POST['vc_id'];
if($vc_id=="")
	$vc_id=null;

$vector = $_POST['vector']; 
if($vector=="")
	$vector=null;

$genotype = $_POST['genotype']; 

$dbo = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$db = $dbo->getDB();


$edit=new Edit();
$edit->setDatabaseConnection($db);
if(!$edit->isExtIdExist($bact_external_id))
	echo $edit->addNewClone($bact_external_id,$bact_name,$vc_id,$vector,$genotype);
else
	echo "Bacteria External ID exists.";
?>
