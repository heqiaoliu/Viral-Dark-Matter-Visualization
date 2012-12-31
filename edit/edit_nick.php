<?
require "initialize.php";
require("edit_helpers.php");
require "../head.html"; 
?>
<!DOCTYPE html>
<html lang="en">
<head> 
<script type="text/javascript">

//////////////////////////////////////////////
// FUNCTION:   $(".edit_tr").click(function())
// PARAMETERS: None
// RETURN:     None
// NOTES:      Anonymous javascript function that allows
//             editing the bacteria table
//////////////////////////////////////////////

	$(function() { 
		$(".edit_tr").click(function() {
			var ID=$(this).attr('id');
			$("#bei_"+ID).hide();
			$("#bn_"+ID).hide();
			$("#vi_"+ID).hide();
			$("#v_"+ID).hide();
			$("#bei_input_"+ID).show();
			$("#bn_input_"+ID).show();
			$("#vi_input_"+ID).show();
			$("#v_input_"+ID).show();
			console.log("#bei_input_"+ID);
		}).change(function() {
			var ID=$(this).attr('id');
			var bei=$("#bei_input_"+ID).val();
			var bn=$("#bn_input_"+ID).val();
			var vi=$("#vi_input_"+ID).val();
			var v=$("#v_input_"+ID).val();
			var dataString = 'id='+ID +'&bei='+bei +'&bn='+bn +'&vi='+vi +'&v='+v;
			console.log(dataString);
			$("#bei_"+ID).html('Loading');
			if(bei.length>0 && bn.length>0 && vi.length>0 && v.length>0) {
				$.ajax({
					type: "POST",
					url: "edit_update_bact.php",
					data: dataString,
					cache: false,
					success: function(html) {
						$("#bei_"+ID).html(bei);
						$("#bn_"+ID).html(bn);
						$("#vi_"+ID).html(vi);
						$("#v_"+ID).html(v);
						console.log(html);
					}
				});
			} else {
				alert('Enter something.');
			}
		});

		// Edit input box click action
		$(".editbox").mouseup(function() {
			return false;
		});

		// Outside click action
		$(document).mouseup(function() {
			$(".editbox").hide();
			$(".text").show();
		});

		// Add clone
		$("#addclone").click(function() {
			var bact_external_id=$("#bact_external_id").val();
			var bact_name=$("#bact_name").val();
			var vc_id=$("#vc_id").val();
			var vector=$("#vector").val();
			var genotype=$("#genotype").val();
			var clonedata = 'bact_external_id='+bact_external_id +'&bact_name='+bact_name +'&vc_id='+vc_id +'&vector='+vector +'&genotype='+genotype;
			console.log(clonedata);
			if(bact_external_id.length>0 && bact_name.length>0 && vc_id.length>0 && vector.length>0) {
				alert('good');
				if (!genotype) {
					genotype = 'empty';
				}

				$.ajax({
					type: "POST",
					url: "edit_addClone.php",
					data: clonedata,
					success: function(ret) {
						console.log(ret);
						alert('success');
					}
				});
			} else {
				alert('Enter something in the required fields: 1) Bacteria External ID, 2) Bacteria Name 3) VC ID and 4) Vector.');
			}
		});
	});
</script>

</head>
<body id="edit">
<?php require "../header.html";?>
<nav>
<?php require "../nav.html"; ?>
</nav>
<section id="mainarea">
	<?php 

		$bacter = Container::makeBacter();
		$bacter->setDatabaseConnection($db); 
		$bactArr = $bacter->readBacteria(); 

		echo createEditSelect('bacteriaLive', 600, 400, array('bei', 'bn', 'vi', 'v'), $bactArr); 
	?>
	<div id="description">
		<p>Insert a bacteria here.</p>
	</div>
	<form name="addclone" action="">
		<div id="leftcol">
			<table width="400">
				<colgroup>
					<col class="col1">
				</colgroup>
				<tbody>
					<tr>
						<td>
							<p id="error"> </p>	
						</td>
					</tr>
					<tr>
						<td>
							<p class="inputTitle">Bacterial External ID <em>*</em> </p>
						</td>
						<td>
							<input placeholder="e.g. EDT0000" name="bact_external_id" id="bact_external_id">
						</td>
					</tr>
					<tr>
						<td>
							<p class="inputTitle">Bacterial Name:</p>
						</td>
						<td>
							<input placeholder="e.g. Escherichia coli" name="bact_name" id="bact_name">
						</td>
					</tr>
					<tr>
						<td>
							<p class="inputTitle">VCID</p>
						</td>
						<td>
							<input placeholder="e.g. 5432" name="vc_id" id="vc_id">
						</td>
					</tr>
					<tr>
						<td>
							<p class="inputTitle">Vector</p>   
						</td>
						<td>
							<input placeholder="e.g. pEMB11" name="vector" id="vector">
						</td>
					</tr>
					<tr>
						<td>
							<p class="inputTitle">Genotype</p>
						</td>
						<td>
							<input name="genotype" id="genotype">
						</td>
					</tr>
					<tr>
						<td>
							<input id="addclone" type="submit" value="Add clone">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>	
	
</section>
<footer></footer>
</body>
</html>
