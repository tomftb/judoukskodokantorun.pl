<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<title>Nasze zdjęcia - <?php echo $_GET["ID_IMG"] ?></title>
</head>
<body><center>
<?php echo "<img src=\"/zdjecia/nasze/$_GET[ID_IMG]\" style=\"width:$_GET[w_img]px; height:$_GET[h_img]px; border:0px;margin:0px;padding:0px;\"></img>";?>
</center></body></html>