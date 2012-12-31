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
<?php
if ($_GET["ERROR"] == TRUE){
?>
	<script type="text/javascript">window.alert("ERROR: Format Error. File needs to be formatted correctly.")</script>
<?php
}
?>
<form enctype="multipart/form-data" action="uploaderHG.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="200000000" />
Choose a file to upload: <input name="uploadedfile" type="file" id = "fileInput" onChange = "checkFile()"/><br />
Comments: <br /><textarea rows="5" cols="20" name="commentBox" wrap="physical"></textarea><br /><br />
<input type="submit" value="Submit" id="submitBtn" disabled ="disabled"/><br />
</form>
</body>
</html>