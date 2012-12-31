<?php

/************************************************
// FILE:            Model.php
// object type:     abstract class
// ORIGINAL AUTHOR: Nick Turner , Heqiao Liu
// PURPOSE:         Model is an abstract class that define few necessary functions of database models.
//                  For each class extends Model, it inherits:
//			* void fucntion setDatabaseConnection(DBObject)
//			  param type:DBObject
//			  The function set database connection as the DBObject.
//
// NOTES:           Error reporting needed
// Last Motified by HQ Liu: 10/17/2012
***************************************************/
abstract class Model {
    protected $db = NULL;

    // Common methods

    public function __construct() {}

    public function setDatabaseConnection($databaseConnection) {
        $this->db = $databaseConnection;
    }

}

?>
