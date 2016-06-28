<?php
$content = isset($_GET["u"]) ? base64_decode($_GET["u"]) : '';
session_start();
session_destroy();
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<title>Untitled Document</title>
</head>

<body>
<div style="width:860px; margin:90px auto; border:1px solid #0CF; background-color:#F2F2F2">
	<div style="padding:25px;">
		<div style="width:170px; float:left"><img src="../../themes/client/images/1349786289_important.png" /></div>
		<div style="width:600px; float:left;">
			<span><h1>Something bad heppened !</h1></span>
			<span>Internal Server Error <br />
			The server encountered an internal error or misconfiguration and was unable to complete your request.
			'.$content.'
			</span>
		</div>
		
		<div style="clear:both"></div>
	</div>

</div>
</body>
</html>';?>