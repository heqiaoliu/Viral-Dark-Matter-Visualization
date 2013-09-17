<?php
$host="blast.ncbi.nlm.nih.gov";
$blastConn=fsockopen($host,80);
if($_GET['query']==NULL)
	echo "?Error";
else if(!$blastConn)
	echo "Cannot connect to NCBI.";
else{
	$query="CMD=Put&DATABASE=nr&PROGRAM=blastp&QUERY=".$_GET['query'];
	$post="POST /blast/Blast.cgi HTTP/1.0\r\n";
	$post.="User-Agent: Hi_there\r\n";
	$post.="Content-type: application/x-www-form-urlencoded\r\n";
	$post.="Content-Length: ".strlen($query)."\r\n";
	$post.="Connection: close\r\n\r\n";
	fputs($blastConn,$post."".$query);
	$result="";
	while(!feof($bastConn)){
		$result=fgets($blastConn);
		if(stripos($result,"rid")>0)
			break;
	}
	$result = urldecode($result);
	$buffer=explode("&",$result);
	$rid;
	foreach($buffer as $col){
		if(strpos($col,"RID")!==false){
			$rid=substr($col,4);
			echo $rid;
			break;
		}
	}
	fclose($blastConn);
	$get="CMD=Get&FORMAT_OBJECT=SearchInfo&RID=".$rid;
	$post="POST /blast/Blast.cgi HTTP/1.0\r\n";
	$post.="User-Agent: Hi_there\r\n";
	$post.="Content-type: application/x-www-form-urlencoded\r\n";
	$post.="Content-Length: ".strlen($get)."\r\n";
	$post.="Connection: close\r\n\r\n";
	while(true){
		sleep(2);
		$blastConn=fsockopen($host,80);
		fputs($blastConn,$post."".$get);
		while(!feof($blastConn)){
			$result=fgets($blastConn);
			if(stripos($result,"status")>0)
				break;
		}
		echo $result;
		if(stripos($result,"ready")>0)
			break;
		fclose($blastConn);	
	}

	$final="CMD=Get&FORMAT_TYPE=Text&RID=".$rid;
	$post="POST /blast/Blast.cgi HTTP/1.0\r\n";
	$post.="User-Agent: Hi_there\r\n";
	$post.="Content-type: application/x-www-form-urlencoded\r\n";
	$post.="Content-Length: ".strlen($final)."\r\n";
	$post.="Connection: close\r\n\r\n";
		$blastConn=fsockopen($host,80);
		fputs($blastConn,$post."".$final);
		while(!feof($blastConn))
			echo fgets($blastConn);
		fclose($blastConn);
}
?>
