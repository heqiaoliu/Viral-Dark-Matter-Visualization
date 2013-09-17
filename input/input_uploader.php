<?php  

//////////////////////////////////////////////
// FILE:            input_uploader_test2.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         
// NOTES: This page is the inbetween for input_parser.pl and input.php.
// The POST data from input.php is turned into meaningful database information, i.e. some SELECT statements must be used 
// to determine table IDs that will speed up input into the database.  
// This page also checks to see if an overwrite is necessary, (only happens when a file of the same name is being uploaded for the second time),
// in which case the applicable information will be deleted from file, exp and growth tables       
//////////////////////////////////////////////

// Initialization of classes and DB
function __autoload($class_name) 
{
    require_once '../classes/'.$class_name . '.php';
}
$dbo = new DBObject("localhost", "heqiaol", "LHQk666!", "viral_dark_matter");
$db = $dbo->getDB();
$exception = "None";
Container::$_database = $db;


$InputChecker = Container::makeInputChecker();
$Uploader = Container::makeUploader();
$Parser = Container::makeParser();

// Capture user input
$InputChecker->setInputsFromPOST();
$InputChecker->checkProperties();

// Upload File
$Uploader->setPath("../upload/");
$Uploader->setFile($InputChecker->getFile());
$file = $Uploader->uploadFileFromPOST();

// Set up input for parser
$inputArray = $InputChecker->getProperties();
$inputArray['file'] = $Uploader->getFile();
$inputArray['path'] = $Uploader->getPath();

//Parse data
$Parser->setInputsFromArray($inputArray);
$Parser->parseFile();
?>
