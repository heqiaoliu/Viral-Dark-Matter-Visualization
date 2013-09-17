<?php
  if($_POST['data']!=NULL){}
  else{
	$data;
	$data['count']=10;
	for($i=0;$i<100;$i++)
		$data['sets'][]=rand(0,10);
	echo json_encode($data);	
  }
?>

