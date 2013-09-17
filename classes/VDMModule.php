<?php
include "DBObject.php";

class VDMModule{
	protected static $DB;
	function __construct(){
		$db=new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
		self::$DB=$db->getDB();
	}
}

?>
