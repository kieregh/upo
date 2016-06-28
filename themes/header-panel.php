<div id="header" class="header_fixed_top">
<?php
	if(isset($_GET["mod"]) && $_GET["mod"]!="")
	{
		$modheader = $_GET["mod"];
	}
	else{
		$modheader='';
	}
?>
	<?php echo $objHome->headerPanel($_SESSION["catselectId"],$modheader);?> 

</div>
