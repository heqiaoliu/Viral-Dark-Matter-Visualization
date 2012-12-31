
<html lang="en">
<head>
<?php require "../head.html";?>
<script type="text/javascript">
$(document).ready(function(){
    $.ajax({
        type:"POST",
        url:"test.php",
        dataType:"json",
        success:function(dataObj){
          console.log(dataObj);
        }
});
});
</script>
</head>
</html>
<?php
?>
