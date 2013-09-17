<?php
//////////////////////////////////////////////
// FILE:            ut_Parser.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         THis class unit tests the Parser, which
//					takes in text files that
//					contain phenotype microarray data
// NOTES:           
//////////////////////////////////////////////

require '../classes/Controller.php';
require '../classes/FileHandler.php';
require '../classes/Parser.php';
require '../classes/UnitTestable.php';

Class ut_Parser extends Parser implements UnitTestable
{
	private $pass = TRUE;
	
	public function printTestReport() 
	{
		return;
	}

	public function startUnitTest() 
	{
		ob_start();
		self::unittest01();
		return ob_get_clean();
	}

	public function logTestReport()
	{
		return;
	}

	///////////////////////
	// Unit tests
	///////////////////////
	protected function unittest01()
	{
		// Testing difference in minutes from 
		// 02:30:56 PM -> 06:07:04 PM = 03:36:08, 04:36:08 taking
		// the 60 minutes from start into account
		$matches[2] = '06:07:04 PM';
		$prevHr = 02;
		$prevMin = 30;
		$prevSec = 56;
		$minutesFromStart = 60;

		$time = parent::parseAndCalculateTime($matches[2], $prevHr, $prevMin, $prevSec, $minutesFromStart);

		if ( ($time - 276.13333) < 1)
		{
			echo 'TEST01 PASS.';
		}
		else
		{
			echo 'TEST01 FAIL';
		}
	}



}

$UTP = new ut_Parser();
echo $UTP->startUnitTest();


?>