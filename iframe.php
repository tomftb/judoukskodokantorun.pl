<?php 
session_start(); 
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
require(DR."/.cfg/konfiguracja.php");
require(DR."/class/logToFile.php");
require(DR."/class/database.php");

$db= NEW database();
$db->connect(dbUser,dbPass,database,dbHost);

require(DR.'/view_public/iframeHead.php');
?>
<body bgcolor="WHITE" onLoad="init('e')">
<div id="loading" style=" width:100%; text-align:left; top:0px; left:0px; height:20px;">
<img src="<?=APP_URL;?>/pod_strony/Loading_napis.gif" border="0" width="128px" height="15px"></div>
<?php
/* GLOBAL VAR */

require(DR."/class/checkGlobalVar.php");
$checkVar=NEW checkGlobalVar();

$checkVar->checkOnlyGet($IDW,'IDW');
$checkVar->checkOnlyGet($IDS,'IDS');
$checkVar->checkOnlyGet($IDM,'IDM');

$log=NEW logToFile();

/* CSS */

require(DR.'/class/parseCSS.php');
$parseC=NEW parseCSS();

$HTTP_HOST=filter_input(INPUT_SERVER,"HTTP_HOST");

$PAGE_URL=APP_URL.'/iframe.php?';

// INLCUDE BROWSER CHECK
include(DR."/pod_strony/_funkcje_/przegladarka.php");
$userAgent=getBrowser();
$mobilePlatform=array(
                        'iPhone',
                        'iPod',
                        'iPad',
                        'Android',
                        'BlackBerry',
                        'Mobile'
);

/*
 * IF IDM NOT EXIST SET 1
 */
//echo "IDM => ".$IDM."<br/>";
if($IDM==='') { $IDM=1; }

$REK_MODUL=mysqli_fetch_assoc($db->query("select `ID`,`SKROT`,`NAZWA`,`TABELA` FROM `MODUL` WHERE `NR_M`='".$IDM."' LIMIT 1"));
$log->log(0,"[".__FILE__.'::'.__LINE__.'] DATABASE TABLE => '.$REK_MODUL['TABELA']);

echo '<p class="P_TITLE">'.$REK_MODUL['NAZWA'].'</p>';
/* PAGINATION */
    require(DR.'/class/page.php');
    $page=NEW page();
    $page->setDbRec("select COUNT(*) FROM `".$REK_MODUL['TABELA']."` WHERE `WSK_U`=0 AND WSK_V=1");
    $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
    $page->setPages($PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
    $IDS=$page->getIDS();
    echo $page->getPageLink('s');
/* END PAGINATION */
    require_once(DR.'/view_public/'.$REK_MODUL['SKROT'].'.php');  
echo $page->getPageLink('e');
?>
</body>
</html>