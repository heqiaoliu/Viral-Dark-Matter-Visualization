<?php  

//////////////////////////////////////////////
// FILE:            input_initialize.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         
// NOTES:           
//////////////////////////////////////////////

require("../common.php"); 
require_authentication();

function __autoload($class_name) {
    require_once '../classes/'.$class_name . '.php';
}

function createSelect($array, $column) {
    $string = '';
    for ($i=0; $i<count($array); $i++) {
        $string .= '<option>'.$array[$i][$column].'</option>';
    }
    return $string;
}

// Create default database connection
$dbo = new DBObject("localhost", "nturner", "LOB4steR", "viral_dark_matter");
$db = $dbo->getDB();
Container::$_database = $db;

// Create extra database connection for accessing joomla database
$dbo_joom = new DBObject("localhost", "nturner", "LOB4steR", "vdm_joomla");
$db_joom = $dbo_joom->getDB();

?>
