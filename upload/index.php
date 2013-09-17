<?php
$i = 0;
$thelist = " ";
 if ($handle = opendir('.')) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != ".." && $file != "index.php")
	  {
          	$thelist .= '<br />'.$i.'.  <a href="'.$file.'">'.$file.'</a>';
		$i++;
          }
       }
  closedir($handle);
  }
?>
<p>List of files:</p>
<?php echo $thelist ?>

