<?php
/*
* @constant TYPE='type'
* @constant PARAM='param'
*/

define('TYPE','type');
define('PARAM','param');
define('ERROR_T','Missing Type of the request');
define('ERROR_P','Missing Variables of the request');
function parseQuery($query){
	$pmPARAM;
	parse_str($query,$pmParam);
	if(!array_key_exists(TYPE,$pmParam)){
		throw new Exception(ERROR_T);
		return;
	}
	if(!array_key_exists(PARAM,$pmParam)){
		throw new Exception(ERROR_P);
		return;
	}
	return $pmParam;
}

function checkParam($paramStr){
	$msg;
	$code;
	$paramArr=json_decode($paramStr);
	switch(json_last_error()){
		case JSON_ERROR_NONE:
			break;
		default:
			$msg='';
			$code=1;
		throw new Exception($msg,$code);
	}
	return $paramArr;	
}
?>
