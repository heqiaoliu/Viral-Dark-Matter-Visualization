<?php
require 'VdmThin.php';
if($_GET['param']==null)
	return;
define('BEID','bacteria_external_id');
define('REPLICATE','replicate_id');
define('PLATE','plate_id');
$vdm=new VdmThin();
$param=json_decode(str_replace("\\","",$_GET['param']),true);
$exps=array_keys($param);
$vdm->getNames($exps);
foreach($exps as $key){
	$vdm->getCurves($key,$param[$key]);
	if($vdm::$plates[$key]==null)
		$vdm->getPlate($vdm::$expInfo[$key][PLATE]);
}
$tempFile=tempnam("../csv","csv");	//tempnam is a php functio that create random temp file
exec("chmod 644 $tempFile");       //make sure the file can be r/w
$file=fopen($tempFile,"w+");
$lineOne=BEID.',';
$lineTwo=REPLICATE.',';
$lineThree='well,';
$max=0;
$maxKey;
$maxWell;
$line='';
foreach($exps as $key){
	foreach($param[$key] as $well){
		/*
		$lineOne.=$vdm::$expInfo[$key][BEID].',';
		$lineTwo.=$vdm::$expInfo[$key][REPLICATE].',';
		$lineThree.=$vdm::$plates[$vdm::$expInfo[$key]['plate_id']][(int)$well-1]['well'].',';
		*/
		$line.=$vdm::$expInfo[$key][BEID].','.$vdm::$expInfo[$key]['file'].','.$vdm::$expInfo[$key][REPLICATE].','.$vdm::$plates[$vdm::$expInfo[$key]['plate_id']][(int)$well-1]['well'].',';
		for($i=0;$i<$vdm::$maxLen;$i++)
			$line.=$vdm::$curves[$key][$well][$i*30].',';
		$line.="\r\n";
	}
}
$times=array_keys($vdm::$curves[$vdm::$maxE][$vdm::$maxW]);
$subline='bacteria,file,replicate,well,';
foreach($times as $time)
	$subline.=$time.',';
$line=$subline."\r\n".$line;
/*
fwrite($file,$lineOne."\r\n");
fwrite($file,$lineTwo."\r\n");
fwrite($file,$lineThree."\r\n");
*/
fwrite($file,$line);
fclose($file);
echo json_encode($tempFile);
?>
