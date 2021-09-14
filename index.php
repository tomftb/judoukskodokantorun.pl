<?php 
session_start();
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));

require(DR."/.cfg/konfiguracja.php");
require(DR."/class/logToFile.php");
require(DR."/class/database.php");
require(DR."/pod_strony/_funkcje_/licznik_odwiedzin.php");

$db= NEW database();
$db->connect(dbUser,dbPass,database,dbHost)

?>
<!DOCTYPE>
<HTML>
<head>
<title>JUDO UKS Kodokan Toruń</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
<meta name="keywords" content="judo, uks, sport" >
<meta name="description" content="Strona całkowicie poświęcona klubowi UKS KODOKAN Toruń" >
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="<?=APP_URL;?>/css/normalize.min.css">
<link rel="stylesheet" href="<?=APP_URL;?>/css/jquery.cookiepolicy.css">
<link rel="stylesheet" href="<?=APP_URL;?>/css/main.css">
<link rel="stylesheet" href="<?=APP_URL;?>/css/index.css?<?=UID;?>" type="text/css">
<script src="<?=APP_URL;?>/js/vendor/modernizr-2.6.2.min.js"></script>
<script src="<?=APP_URL;?>/js/ciastko.js?<?=UID;?>"></script>
<script src="<?=APP_URL;?>/js/ciastko.js?<?=UID;?>"></script>
<script src="<?=APP_URL;?>/js/displayWindowIndex.js?<?=UID;?>"></script>
<!-- <meta http-equiv="refresh" content="30"> -->
<link rel="shortcut icon" href="<?=APP_URL; ?>/images/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?=APP_URL;?>/images/favicon.ico" type="image/x-icon">
</head>
<body>
<!-- COOKIE POLICY -->
<?php include(DR.'/view_public/cookie.html');?>
<script type="text/javascript" src="js/cookie.js"></script>
<script type="text/javascript" src="js/cookie.script.js"></script>
<!-- END COOKIE POLICY -->
<center>
<div class="caleokno">
	<div class="naglowek">
<?php
$now = time();
$login_wartosc="Logowanie";
if ((ISSET($_SESSION["id_user"])) AND ($now < $_SESSION["expire"]))
{
    $login_wartosc="Przejdz do CMS";	
}
else
{
    $login_wartosc="Logowanie";
}
echo "<p class=\"P_LOGIN\"><a class=\"LOGIN\" target=_blank href=\"".APP_URL."/logowanie.php\" >".$login_wartosc."</a></p>";
?>
</div><!-- .naglowek-->
<DIV CLASS="LEWA_KRAWEDZ" >
<ul class="nawigacja_ul">
<?php
// LINKI do pod stron
include(DR."/view_public/mainHref.php");
?>
</ul>
</DIV>
<DIV  CLASS="SRODEK"  >
    <iframe name="iframe" src="iframe.php" class="FRAME"></iframe>
</DIV> <!-- .SRODEK -->
<DIV CLASS="PRAWA_KRAWEDZ" ></DIV><!-- .PRAWA_KRAWEDZ -->
<div class="stopka">	
</div><!-- .STOPKA -->
</div><!-- DIV.caleokno -->
</center>
</body>
</html>