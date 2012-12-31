<?php 

//////////////////////////////////////////////
// FILE:            Controller.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         
// NOTES:           
//////////////////////////////////////////////

abstract class Controller 
{

    protected $db = NULL;
    protected $error = NULL;

    //////////////////////////////////////////////
    // FUNCTION:   reportErrorNonFatal(), reportErrorFatal()
    // PARAMETERS: None
    // RETURN:     None
    // NOTES:      abstract functions
    //////////////////////////////////////////////
    abstract protected function reportErrorNonFatal();
    abstract protected function reportErrorFatal($errorMessage, $destination = NULL);

    public function __construct() {}

    //////////////////////////////////////////////
    // FUNCTION:   setError()
    // PARAMETERS: None
    // RETURN:     None
    // NOTES:      Allows programmer to set errors, either 
    // to be displayed now or later.
    //////////////////////////////////////////////
    public function setError($error) 
    {
        $this->error = $error;
    }


    
}

?>