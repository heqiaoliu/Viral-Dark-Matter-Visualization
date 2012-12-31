<?php
	$temp=$_GET['filename'];	//get filename for download;
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: private");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=yourselect.csv");//file name for user is always yourselect.csv
        header("Accept-Ranges: bytes");
        readfile($temp);	//start download;
	unlink($temp);
?>
