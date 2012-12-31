<?php
//////////////////////////////////////////////
// FILE:            UnitTestable.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         This is an interface that should
//					be implemented by most classes.
// NOTES:           
//////////////////////////////////////////////
interface UnitTestable
{
	public function printTestReport();
	//public function logTestReport();
	public function startUnitTest();
}