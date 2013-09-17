<?php 

//////////////////////////////////////////////
// FILE:            Parser.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         The Parser takes in text files that
//					contain phenotype microarray data
// NOTES:           This class is a very specific data 
//					parsing class that relies on data
//					formatted from a specific phenotype 
//					micro-array reader.
//
//					This class is a subclass of controller,
//					and it has database access (it does not
//					go through a model, think MVC architecture)
//					It inputs into the growth table. 
//					TODO: add experiment date functionality
//////////////////////////////////////////////

class Parser extends FileHandler 
{

	////////////////////////////////////////////
	// This constant reflects what units
	// we expect from the data, currently 
	// optical density readings.  
	const EXPECTED_UNITS = "O.D.";
	const PS_INPUT_EXP_DATA = '../SQL/ps_inputExpData.php';
	const PS_INPUT_GROWTH_DATA = '../SQL/ps_inputGrowthData.php';
	const PS_GET_EXP_ID = "../SQL/ps_getExpId.php";
	////////////////////////////////////////////

	////////////////////////////////////////////
	// This variable will be set to true during 
	// a unit test
	protected $unittest = FALSE;
	// These variables are passed in from the previous step, 
	// originally from an html form (not counting unit tests)
    protected $name = NULL; //e.g. Nick Turner
    protected $plate = NULL; //e.g. anca plate or PM1
    protected $additionalInfo = NULL; // paragraph
    protected $overwrite = NULL; // yes or no
    protected $fileType = NULL; // single or multiplate reader
    protected $bactid = NULL;
    protected $vcid = NULL;
    // $file is defined in parent
    // $path is defined in parent
    
	// This is how we skip BG subtracted data
	protected $dataType = NULL;
	// Has an instance of Bacter, Plate, File
	public $Bacteria = NULL;
	public $Plate = NULL;
	public $File = NULL;
    // Has a database connection even though it is a controller 
    // (acts as a model for vdm_growth table)... This will 
    // be a foolish move if we decide we want more than just 
    // THIS class to be able to input in the vdm_growth table
    protected $db = NULL;
    ////////////////////////////////////////////

    public function __construct() {}

    public function setDatabaseConnection($databaseConnection) {
        $this->db = $databaseConnection;
    }

    public function setInputsFromArray($inputArray) 
    {
    	//echo '<pre>'.print_r($inputArray).'</pre><br />';
		$this->name           = $inputArray['name'];
		$this->plate          = $inputArray['plate'];
		$this->additionalInfo = $inputArray['additionalInfo'];
		$this->overwrite      = $inputArray['overwrite'];
		$this->file           = $inputArray['file'];
		$this->path           = $inputArray['path'];
		$this->fileType       = $inputArray['fileType'];
		$this->bactid         = $inputArray['bactid'];
		$this->vcid           = $inputArray['vcid'];
    }

    public function parseFile() 
    {
		$handle           = parent::openFile("r");
		$lineNumber       = 0;
		$prevHr           = 0;
		$prevMin          = 0;
		$prevSec          = 0;
		$minutesFromStart = 0;


		$firstLine = fgets($handle);
		$expDate = strtotime(self::findDate($firstLine));
		rewind($handle);
		
    	// Insert initial data into database
		$replicateNum = self::getReplicate($this->bactid);
    	// get IDs from other tables
		$bacteriaId = self::getBacteriaId($this->bactid);
		$plateId    = self::getPlateId($this->plate);
		$fileId     = self::inputFile($this->file, $this->name, $expDate, $bacteriaId, $this->additionalInfo);
	    	$expId      = self::inputExpData($bacteriaId, $plateId, $replicateNum, $fileId);
    	// Iterate through file and parse one line at a time
    	require self::PS_INPUT_GROWTH_DATA;
    	while(!feof($handle))
    	{
    		$line = fgets($handle);
    		// Parse the current line, passing variables that by reference (see parseLine)
    		// that will allow information to be saved beyond the scope of each line
    		self::parseLine($line, $lineNumber, $prevHr, $prevMin, $prevSec, $minutesFromStart, $expId);
    		$lineNumber++;
    	}
    	parent::closeFile($firstLine);
    }

    protected function findDate($firstLine) 
    {
    	if (preg_match("`([\d/]+)\s(.{11})\s+`", $firstLine, $matches)) 
    	{
    		return $matches[1];
    	} 
    	else 
    	{
    		// Wrong file?
    		echo "wrong file?";
    	}

    } 

    //////////////////////////////////////////////
    // FUNCTION:    parseLine()
    // PARAMETERS:  $line: the current line of the input file to be parsed
    // RETURN:      none
    // NOTES:       YOU (the developer) SHOULD INSPECT THE FILE THIS WILL BE PARSING FOR
    //				THIS FUNCTION TO MAKE ANY SENSE.
    //////////////////////////////////////////////
    protected function parseLine($line, $lineNumber, &$prevHr, &$prevMin, &$prevSec, &$minutesFromStart, $expId) 
    {
    	// Matches the 'Units: '... row
		// $matches[1] == 'O.D.'
		// This if is a test for scary units that are NOT what we are expecting (at the time of 
		// writing it is optical density or O.D.)
		if ( ($lineNumber == 4) && preg_match("/^Units\:\s+([\w\.]+)/", $line, $matches) )
		{
			// If the following condition is met, then we are getting a file with units that
			// we are not expecting. 
			if(strcmp($matches[1], self::EXPECTED_UNITS) != 0)
			{
				// We are not getting the units we expect, lets abort and alert user.
			} 
		}

    	// Matches any row with data in it in the format: 
    	// 		A1     0.300554
    	// There can be any amount of whitespace between A1 and 0.300
    	// $matches[1] == 'A1'
    	// $matches[2] == '0.300554'
	    if ((strcmp($this->dataType, 'ABS DATA') == 0) && preg_match("/^([A-H]\d+)\s+([\d.]+)/", $line, $matches)) 
		{	
			$wellNumId = self::lookupWellNumId($matches[1]);
			self::inputGrowthData($wellNumId, $minutesFromStart, $matches[2], $expId);
		}

		// $matches[1] == 'ABS DATA' or 'BACKGROUND SUBTRACTED DATA'
		elseif ( preg_match("/^Data\:\s+([\w\s]+)/", $line, $matches)  )
		{
			$this->dataType = trim($matches[1]);
		}

		// $matches[1] == '12/09/2011'
		// $matches[2] == '06:07:04 PM'
		elseif ( preg_match("`([\d/]+)\s(.{11})\s+`", $line, $matches) )
		{
			self::parseAndCalculateTime($matches[2], $prevHr, $prevMin, $prevSec, $minutesFromStart);
		}

	}

    //////////////////////////////////////////////
    // FUNCTION:    parseAndCalculateTime()
    // PARAMETERS:  
    // RETURN:      
    // NOTES:       
    //////////////////////////////////////////////
	protected function parseAndCalculateTime($time, &$prevHr, &$prevMin, &$prevSec, &$minutesFromStart)
	{
		preg_match("/(\d\d):(\d\d):(\d\d)\s([AP])/", $time, $matches);
		$currentHr  = intval($matches[1]);
		$currentMin = intval($matches[2]);
		$currentSec = intval($matches[3]);
		$tempMin = 0;
		echo $time . "<br>";
		if ($prevHr != 0)
		{ 

			// We are at 1:00
			if ($currentHr < $prevHr)
			{
				$hDiff = ($currentHr + 12) - $prevHr;
			}
			else //if ($currentHr == $prevHr)
			{
				$hDiff = $currentHr - $prevHr;
			}
			$tempMin = $hDiff * 60;
			

			$mDiff = $currentMin - $prevMin;

			$tempMin = $tempMin + $mDiff;
		}

		$prevMin = $currentMin;
		$prevHr = $currentHr;


		$minutesFromStart += $tempMin;
		
		//return $minutesFromStart;

	}
    //////////////////////////////////////////////
    // FUNCTION:    inputGrowthData()
    // PARAMETERS:  
    // RETURN:      
    // NOTES:       This function uses the prepared statement 
    //				defined in ps_inputGrowthData.  That ps file is 
    //				required in the function Parser->parseFile()
    //////////////////////////////////////////////
	protected function inputGrowthData($wellNumId, $minutesFromStart, $growthData, $expId)
	{
        try 
        {
            // Require inputGrowthData() prepared statement, which
        	// uses VALUES (:wellNumId, :time, :growthMeasurement, :expId)
            $this->stmt->bindParam(':wellNumId', $wellNumId);
            $this->stmt->bindParam(':time', $minutesFromStart);
            $this->stmt->bindParam(':growthMeasurement', $growthData);
            $this->stmt->bindParam(':expId', $expId);
            $this->stmt->execute();
        }
        catch (PDOException $e) 
        {
            echo "There was a system error inputting growth data.  The database for this file may be corrupt.  Contact your system administrator.<br>".$e->getMessage(); 
        }
        //return $name;
	}

    //////////////////////////////////////////////
    // FUNCTION:    inputExpData()
    // PARAMETERS:  
    // RETURN:      
    // NOTES:       This function uses the prepared statement 
    //				defined in ps_inputExpData.  That ps file is 
    //				required in the function Parser->parseFile()
    //////////////////////////////////////////////
	protected function inputExpData($bacteriaId, $plateId, $replicateNum, $fileId) 
	{
        try 
        {
        	require self::PS_INPUT_EXP_DATA;
            // Require inputExpData() prepared statement, which
        	// uses VALUES (:bacteriaId, :plateId, :replicateNum, :fileId)
            $this->stmt->bindParam(':bacteriaId', $bacteriaId);
            $this->stmt->bindParam(':plateId', $plateId);
            $this->stmt->bindParam(':replicateNum', $replicateNum);
            $this->stmt->bindParam(':fileId', $fileId);
            $this->stmt->execute();

            require self::PS_GET_EXP_ID;

            $this->stmt->bindParam(':bacteriaId', $bacteriaId);
            $this->stmt->bindParam(':plateId', $plateId);
            $this->stmt->bindParam(':replicateNum', $replicateNum);
            $this->stmt->bindParam(':fileId', $fileId);
            $this->stmt->execute();
            $row = $this->stmt->fetch();
            return $row['exp_id'];

        }
        catch (PDOException $e) 
        {
            echo "There was a system error inputting experiment data.  The database for this file may be corrupt.  Contact your system administrator.<br>".$e->getMessage(); 
        }
	}

	protected function getReplicate($bactid) 
	{
		if ($this->File)
		{
			$return = $this->File->getReplicate($bactid);
			echo 'return is: '. $return;
			return $return ? $return + 1 : 1;
		}
		else
		{
			// error
		}
	}

	protected function getBacteriaId($bactid)
	{
		if ($this->Bacteria)
		{
			return $this->Bacteria->getBacteriaIdFromExternalId($bactid);	
		}
		else
		{
			// Error
		}
		
	}

	protected function getPlateId($plate)
	{
		if ($this->Plate)
		{
			return $this->Plate->getPlateIdFromPlateName($plate);	
		}
		else
		{
			// Error
		}
	}

	protected function inputFile($fileName, $name, $exp_date, $bacteria_id, $notes)
	{
		if ($this->File)
		{
			return $this->File->inputFile($fileName, $name, $exp_date, $bacteria_id, $notes);	
		}
		else
		{
			// Error
		}
	}



    //////////////////////////////////////////////
    // FUNCTION:    lookupWellNumId()
    // PARAMETERS:  
    // RETURN:      
    // NOTES:       
    //////////////////////////////////////////////
	protected function lookupWellNumId($wellNum)
	{

		$wellNumArray = array(
			"A1"  => 1,
			"A2"  => 2,
			"A3"  => 3,
			"A4"  => 4,
			"A5"  => 5,
			"A6"  => 6,
			"A7"  => 7,
			"A8"  => 8,
			"A9"  => 9,
			"A10" => 10,
			"A11" => 11,
			"A12" => 12,
		

			'B1'  => 13,
			'B2'  => 14,
			'B3'  => 15,
			'B4'  => 16,
			'B5'  => 17,
			'B6'  => 18,
			'B7'  => 19,
			'B8'  => 20,
			'B9'  => 21,
			'B10' => 22,
			'B11' => 23,
			'B12' => 24,

			'C1'  => 25,
			'C2'  => 26,
			'C3'  => 27,
			'C4'  => 28,
			'C5'  => 29,
			'C6'  => 30,
			'C7'  => 31,
			'C8'  => 32,
			'C9'  => 33,
			'C10' => 34,
			'C11' => 35,
			'C12' => 36,

			'D1'  => 37,
			'D2'  => 38,
			'D3'  => 39,
			'D4'  => 40,
			'D5'  => 41,
			'D6'  => 42,
			'D7'  => 43,
			'D8'  => 44,
			'D9'  => 45,
			'D10' => 46,
			'D11' => 47,
			'D12' => 48,

			'E1'  => 49,
			'E2'  => 50,
			'E3'  => 51,
			'E4'  => 52,
			'E5'  => 53,
			'E6'  => 54,
			'E7'  => 55,
			'E8'  => 56,
			'E9'  => 57,
			'E10' => 58,
			'E11' => 59,
			'E12' => 60,

			'F1'  => 61,
			'F2'  => 62,
			'F3'  => 63,
			'F4'  => 64,
			'F5'  => 65,
			'F6'  => 66,
			'F7'  => 67,
			'F8'  => 68,
			'F9'  => 69,
			'F10' => 70,
			'F11' => 71,
			'F12' => 72,

			'G1'  => 73,
			'G2'  => 74,
			'G3'  => 75,
			'G4'  => 76,
			'G5'  => 77,
			'G6'  => 78,
			'G7'  => 79,
			'G8'  => 80,
			'G9'  => 81,
			'G10' => 82,
			'G11' => 83,
			'G12' => 84,

			'H1'  => 85,
			'H2'  => 86,
			'H3'  => 87,
			'H4'  => 88,
			'H5'  => 89,
			'H6'  => 90,
			'H7'  => 91,
			'H8'  => 92,
			'H9'  => 93,
			'H10' => 94,
			'H11' => 95,
			'H12' => 96,
		);
		
		return $wellNumArray[$wellNum];
	}



}

?>
