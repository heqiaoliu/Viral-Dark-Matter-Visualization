
<head> 
  <?php require "../head.html"; ?>
<script src="../js/jquery.form.js" type="text/javascript"></script>
<script type="text/javascript" >
	
$(function(){
  $("p#go").click(function(){
    var targetstring="&data=1,9,8,9,0,1,1,4";
    $.ajax({
        type:"POST",
        url:"testCall.php",
        data:targetstring,
        dataType:"json",
        //here dataObj="&filename="+dataObj; pass to downCsv.php
        success:function(dataObj){
          console.log(dataObj);
		}
	});
	});
});

</script>
</head>
<?php echo '<body id="rosetta">';
require "../header.html"; ?>
<nav>
  <?php  require "../nav.html"; ?>
</nav>
<section id="mainarea">
  <div id="description" >
  </div>

<body>
<p id="go">let's go</p>
</body>
