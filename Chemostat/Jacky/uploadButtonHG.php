<?php
if ($_GET["ERROR"] == TRUE){
?>
	<script type="text/javascript">window.alert("ERROR: Format Error. File needs to be formatted correctly.")</script>
<?php
}
if ($_GET["SUCCESS"] == TRUE){
?>
	<script type="text/javascript">window.alert("Upload to VDM database successfull.")</script>
<?php
}
if ($_GET["UPLOAD"] == 1){
?>
	<script type="text/javascript">window.alert("File Upload was unsuccessfull. Please try again.")</script>
<?php
}
if ($_GET["AlreadyExists"] == TRUE){
?>
	<script type="text/javascript">window.alert("File Already Exists. Please select a different file.")</script>
<?php
}
?>
<?php		
require "head.html"; 				
require "header.html"; 		//white line break
?>	
<nav>						
<?php require "nav.html"; 	//tabs		
?>
</nav>
<section id="mainarea">
<article id="description" >
<html>
<head>
<script type="text/javascript">
      function checkFile()
      {
          btn = document.getElementById("submitBtn");
          btn.disabled = "";
      }
    </script>
</head>
<body>
<form enctype="multipart/form-data" action="uploaderHG.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="200000000" />
Choose a file to upload: <input name="uploadedfile" type="file" id = "fileInput" onChange = "checkFile()"/><br />
Comments: <br /><textarea rows="5" cols="20" name="commentBox" wrap="physical"></textarea><br /><br />
<input type="submit" value="Submit" id="submitBtn" disabled ="disabled"/><br />
</form>
<!	External Link & also calls out the white box background>
</section><!-- /#mainarea -->
    <footer>
        <ul>
            <li><a href="index.php" id="Ffirst">external link</a></li>
        </ul>
    </footer>
    </body>
</html>
