<?php
require("../common.php"); 
require_authentication(); ?>

<html lang="en">
<body id="rosetta">
<head>
<?php require "../head.html";?>
<script src="rosetta_js.js" type="text/javascript" ></script>
</head>

<div id="ncbi_box" class="dialog"><iframe id="dialog_frame" src="" width="100%"></iframe><a id="external_ncbi" target="_blank" href="">click to see more on NCBI</a>
<a id="blast_result" href=""></a>
</div>
<div id="div_nav">
  <?php  require "../nav.html"; ?>
</div>

<div id="table_bar" style="width:340px"></div>
<div id="search_box"><input id="search_input"/></div>
<div id="description" ></div>
<div id="button"></div>
<div id="table_head">
<table border="1">
<tr id="head_index">
</tr>
</table>
</div>
<div id="rosetta_table" style="overflow: scrollbar">

<table border="1">
<tr id="rosettaTableIndex">
<td class="psp">Possible Structural Protein</td>
<td class="src">Source</td>
<td class="vc">VCID</td>
<td class="n">Name</td>
<td class="gi">gi</td>
<td class="l">Length</td>
<td class="ord">Ordered</td>
<td class="clnd">Cloned</td>
<td class="expd">Expressed</td>
<td class="slbl">Soluble</td>
<td class="pfd">Purified</td>
<td class="ct">Crystallization Trials</td>
<td class="cts">Crystals</td>
<td class="dft">Diffraction</td>
<td class="dt">Data Set</td>
<td class="str">Structure</td>
<td class="cmt">comments</td>
</tr>
<?php 
require 'rosetta_data.php';
?>
</table>
</div>
<footer>
    <ul>
        <li><a href="input.php" id="Ffirst">external link</a></li>
    </ul>
</footer>
</body>
</html>
