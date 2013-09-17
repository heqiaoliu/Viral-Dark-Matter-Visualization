<?php  
/*precsv.php functionally generate an temp csv file for user to download.

 */
	$max_length=0;

    $temp=tempnam("../data/csv","csv");	//tempnam is a php functio that create random temp file
										//then return file name.
										//$temp is the file name 0f the temp file;
	$buffer=$_POST['data'];
	$dataset=json_decode( str_replace("\\","",$buffer),true);
	foreach( $dataset as $expid=>$wellset){
		foreach($wellset as $wellid=>$growthset){
			if(count($growthset)>$max_length){
				$max_length=count($growthset);
			}
		}
	}
	echo exec("chmod 755 $temp");       //make sure the file can be r/w
    $file=fopen($temp,"w+");

	for($i=0;$i<$max_length+2;$i++){
		foreach( $dataset as $expid=>$wellset){
			foreach($wellset as $wellid=>$growthset){
				$cursize=count($growthset);
					if($i==0)
						fwrite($file,$expid.",");
					else if($i== 1)
						fwrite($file,$wellid.",");
					else if($i>$cursize+1)
							fwrite($file,",");
					else
							fwrite($file,$growthset[$i-2][1].",");
			}
		}
		fwrite($file,"\r\n");
	}
	/*						fwrite($file,$indexSet[$j]);
							fwrite($file,$rowOneTwo[0].",");}
							fwrite($file,",");
								fwrite($file,$rowOneTwo[1].",");
								fwrite($file,",");
		fwrite($file,"\r\n");
*/
	fclose($file);
	echo json_encode($temp);

?>
