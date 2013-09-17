<?php 
class Container {
//////////////////////////////////////////////
// FILE:            Container.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         The Container class is a type of factory method.
//					These methods allow for any configuration to be
//					kept out of the view and allow for clearer code.
// NOTES:           
//////////////////////////////////////////////
	
	public static $_database;

	public static function makeBacter() 
	{
		$Bacter = new Bacter();
		$Bacter->setDatabaseConnection(self::$_database);
		return $Bacter;
	}

	public static function makeSupplement() 
	{
		$Supplement = new Supplement();
		$Supplement->setDatabaseConnection(self::$_database);
		return $Supplement;
	}

	public static function makeFile() 
	{
		$File = new File();
		$File->setDatabaseConnection(self::$_database);
		return $File;
	}

	public static function makePlate() 
	{
		$Plate = new Plate();
		$Plate->setDatabaseConnection(self::$_database);
		return $Plate;
	}

	public static function makeRosetta() 
	{
		$Rosetta = new Rosetta();
		$Rosetta->setDatabaseConnection(self::$_database);
		return $Rosetta;
	}

	public static function makeUser() 
	{
		$User = new User();
		$User->setDatabaseConnection(self::$_database);
		return $User;
	}

	public static function makeInputChecker() 
	{
		$InputChecker = new InputChecker();
		return $InputChecker;
	}

	public static function makeUploader()
	{
		$Uploader = new Uploader();
		return $Uploader;
	}

	public static function makeParser()
	{
		$Parser = new Parser();
		$Parser->setDatabaseConnection(self::$_database);
		$Parser->Bacteria = self::makeBacter();
		$Parser->Plate = self::makePlate();
		$Parser->File = self::makeFile();
		return $Parser;
	}
}

 ?>