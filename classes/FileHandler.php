<?php 
//////////////////////////////////////////////
// FILE:            Filehandler.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         
// NOTES:           
//////////////////////////////////////////////

class FileHandler extends Controller 
{

    protected $file = NULL;
    protected $fileError = NULL;
    protected $path = NULL;

    public function __construct() {}

    protected function reportErrorNonFatal()
    {

    }

    //////////////////////////////////////////////
    // FUNCTION:    reportErrorFatal()
    // PARAMETERS:  $errorMessage : the message to sent along to the destination.  
    //              The destination should have some way of displaying $_POST
    //              or $_REQUEST variables, preferably so user can see the error
    //              $destination = NULL :  the hyper link that the php header will
    //              go to.
    // RETURN:      None
    // NOTES:       Allows programmer to set file
    //////////////////////////////////////////////
    protected function reportErrorFatal($errorMessage, $destination = NULL)
    {

        if (isset($destination))
        { 
            header('Location: '.$destination . '?inputError='.$errorMessage.' If this problem persists, contact an administrator.');
        }
        else 
        {
            die($errorMessage);
        }
    }

    //////////////////////////////////////////////
    // FUNCTION:   setFile()
    // PARAMETERS: $file
    // RETURN:     None
    // NOTES:      Allows programmer to set file
    //////////////////////////////////////////////
    public function setFile($file) 
    {
        $this->file = $file;
    }
    //////////////////////////////////////////////
    // FUNCTION:   getFile()
    // PARAMETERS: $file
    // RETURN:     None
    // NOTES:      Allows programmer to set file
    //////////////////////////////////////////////
    public function getFile() 
    {
        return $this->file;
    }

    //////////////////////////////////////////////
    // FUNCTION:   setPath()
    // PARAMETERS: $path
    // RETURN:     None
    // NOTES:      Sets the path to the file 
    //////////////////////////////////////////////
    public function setPath($path) 
    {
        $this->path = $path;
    }
    //////////////////////////////////////////////
    // FUNCTION:   getPath()
    // PARAMETERS: $path
    // RETURN:     None
    // NOTES:      Sets the path to the file 
    //////////////////////////////////////////////
    public function getPath() 
    {
        return $this->path;
    }

    //////////////////////////////////////////////
    // FUNCTION:   setFileFromPOST()
    // PARAMETERS: None
    // RETURN:     None
    // NOTES:      
    //////////////////////////////////////////////
    public function setFileFromPOST() 
    {
        $this->file = basename($_FILES['uploadedfile']['name']);
    }

    //////////////////////////////////////////////
    // FUNCTION:   uploadFileFromPOST()
    // PARAMETERS: None
    // RETURN:     None
    // NOTES:      
    //////////////////////////////////////////////
    public function uploadFileFromPOST() 
    {
        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $this->path.$this->file)) 
        {
            // Success
            return $this->path.$this->file;

        } 
        else 
        {
            // Fail
            $this->fileError = "File upload failure. Error: " . $_FILES['uploadedfile']['error'].". ";
            $destination = "http://vdm.sdsu.edu/data/input/input_test.php";
            self::reportErrorFatal($this->fileError, $destination);
        }
    }

    //////////////////////////////////////////////
    // FUNCTION:    openFile()
    // PARAMETERS:  $mode : takes any of the file 
    //              modes that fopen accepts, including
    //              r, r+, w, w+, a, a+, x, x+, c, c+
    // RETURN:      $handle: a usable handle to the file
    // NOTES:       Allows programmer to open the 
    //              Filehandler objects file
    //////////////////////////////////////////////
    public function openFile($mode)
    {
        try 
        {
            $handle = fopen($this->path.$this->file, $mode);
        } 
        catch (Exception $e) 
        {
            $this->fileError = "File read failure. Error: " . $e.". ";
            $destination = "http://vdm.sdsu.edu/data/input/input_test.php";
            self::reportErrorFatal($this->fileError, $destination);
        }
        return $handle;
            
    }
    //////////////////////////////////////////////
    // FUNCTION:    closeFile()
    // PARAMETERS:  none
    // RETURN:      none
    // NOTES:       Closes the Filehandlers file
    //////////////////////////////////////////////
    public function closeFile()
    {
        try 
        {
            fclose($this->path.$this->file);
        } 
        catch (Exception $e) 
        {
            $this->fileError = "File close failure. Error: " . $e.". ";
            $destination = "http://vdm.sdsu.edu/data/input/input_test.php";
            self::reportErrorFatal($this->fileError, $destination);
        }
            
    }


}

?>