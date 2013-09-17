<?php
require	'../common.php';
require_authentication();
?>

<head>
<?php require '../head.html';?>

	<script type="text/javascript" src="../js/XRegExp.js"></script>
	<script type="text/javascript" src="../js/shCore.js"></script>
	<script type="text/javascript" src="../js/shBrushPhp.js"></script>
	<link type="text/css" rel="stylesheet" href="../js/shCoreDefault.css"/>
	<script type="text/javascript">SyntaxHighlighter.all();</script>

</head>

<?php require '../div_nav.html'; ?>
<body id="vdmwiki">
<p>Model</p>
<div id="codes">
<pre class="brush: php;">
/******abstract class Model******
*abstract class Model
*    path:../data/classes/Model.php
*    fucntion: setDatabaseConnection(DB connection);
**********************************/
abstract class Model {
    ...
    public function __construct() {}

    /*all model classes will inherit setDatabaseConnection*/
    public function setDatabaseConnection($databaseConnection) 
	{...}

}
</pre>
<p>DBObject</p>
<pre class="brush: php;">
/*******DBObject******************
*class DBObject
*	path:../data/classes/DBObject.php
*	fucntion: constructor(string,string,string,string)
*		  DBconnection getDB()
**********************************/
class DBObject {
    ...
    /********constructor****************
    *constructor request four parameters
    *	String server,username,password,databasename.
    *************************************/
    public function __construct($Server, $User, $Password, $Database) 
	{...}

    /*******getDB()************
    *getDB() returns current database connection.
    *******************************/
    public function getDB() 
	{...}
}
</pre>
<pre class="brush: php;">
/************Sample to use DBObjecti,Model with PDO************
*let this call Sample.php	for example
*
***************************************************************/
class Sample extends Model{
	$query="select * from table where id=? or id=?";
	public function excuteQuery($param){	//$param usually to be an array of variables that query needs.
		try{
			$this->stmt->prepare($query);
			if ( $this->stmt->execute($param) )	//in this case $param is an array of two id
				return $this->stmt; //do things, here just return the whole rows which might be a bad example.
		}
        	catch (PDOException $e) 
        	{
				//if exception, do ....
		}
	}
}
</pre>
<pre class="brush: php;">
/************Sample to implement Sample class************
********************************************************/
require "classes/Model.php";	//keep Model required before your class.
require "classes/Sample.php";
require "classes/DBObject.php";
$DB=new DBObject(***,***,***,***);
$sampleObj=new Sample();
$sampleObj->setDatabaseConnection($DB->getDB());
$param=array();
$param[]=0;
$param[]=1;
$result=$sampleObj->execute($params);
//now you have result, fetch it, do what you want to do.
</pre>
</div>
</body>
