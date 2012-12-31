<?php 

//////////////////////////////////////////////
// FILE:            InputChecker.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         The Input Checker object confirms all
//                  user input before passing the file to 
//                  the uploader.
// NOTES:           
//////////////////////////////////////////////

class InputChecker extends FileHandler 
{
    const MAX_NAME_LENGTH = 255;
    const MAX_INFO_LENGTH = 1023;


    protected $name = NULL;
    protected $plate = NULL;
    protected $additionalInfo = NULL;
    protected $overwrite = NULL;
    protected $fileType = NULL; // single or multiplate reader
    protected $bactid = NULL;
    protected $vcid = NULL;

    public function __construct() {}

    //////////////////////////////////////////////
    // FUNCTION:   setInputsFromPOST()
    // PARAMETERS: None
    // RETURN:     None
    // NOTES:      sets name, plate, additionalInfo, overwrite from POST
    //////////////////////////////////////////////
    public function setInputsFromPOST() 
    {
        $this->name = $_POST['name'];
        $this->plate = $_POST['plate'];
        $this->additionalInfo = $_POST['additionalInfo'];
        $this->overwrite = $_POST['overwrite'];
        parent::setFileFromPOST();

        if (empty($_POST['bactid']) && empty($_POST['vcid'])) 
        {
            // Should never happen due to JS on client side.
            $errorMessage = "Error: VCID and Bacteria External ID not specified.  \n";
            $destination = "http://vdm.sdsu.edu/data/input/input_test.php";
            parent::reportErrorFatal($errorMessage, $destination);
        } 
        elseif (isset($_POST['bactid'])) 
        {
            $this->bactid = $_POST['bactid'];
            $this->vcid = $_POST['other'];
        } 
        elseif (isset($_POST['vcid'])) 
        {
            $this->vcid = $_POST['vcid'];
            $this->bactid = $_POST['other'];
        } 
    }

    public function getProperties() 
    {
        return array(
            "name" => $this->name,
            "plate" => $this->plate,
            "additionalInfo" => $this->additionalInfo,
            "overwrite" => $this->overwrite,
            "fileType" => $this->fileType,
            "bactid" => $this->bactid,
            "vcid" => $this->vcid,
        );
    }

    public function checkProperties() 
    {
        try
        {
            $this->checkName();
            //$this->checkPlate();
            $this->checkAdditionalInfo();
            //$this->checkOverwrite();     
        }
        catch (Exception $e)
        {
            $errorMessage = $e->getMessage(). "\n";
            $destination = "http://vdm.sdsu.edu/data/input/input_test.php";
            parent::reportErrorFatal($errorMessage, $destination);
        }


    }

    public function checkName() 
    {
        $len = strlen($this->name);
        // Check length
        if ( $len >  self::MAX_NAME_LENGTH )
        {
            throw new Exception('Your name is too long.  It must be less than 256 characters.');
        }
        else if (!$len)
        {
            throw new Exception('You must enter a name.');
        }
    }
/*
    public function checkPlate()
    {

    }
*/
    public function checkAdditionalInfo()
    {
        $len = strlen($this->additionalInfo);
        // Check length
        if ( $len >  self::MAX_INFO_LENGTH )
        {
            throw new Exception('Your additional info section is too long.  It must be less than 1000 characters');
        }

    }
/*
    public function checkOverwrite()
    {

    }
*/
}

?>