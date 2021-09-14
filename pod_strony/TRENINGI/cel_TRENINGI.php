<?php 
session_start(); 
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
require(DR.'/.cfg/konfiguracja.php');
require(DR.'/class/logToFile.php');
require(DR.'/class/session.php');
require(DR."/class/checkGlobalVar.php");

$start_time_page = floatval(microtime(true));

$log=NEW logToFile();
$checkVar=NEW checkGlobalVar();
$session=new session();

$checkVar->checkOnlyGet($IDM,'IDM');
$checkVar->checkOnlyGet($IDW,'IDW');

if(!$session->checkSession($IDM))
{    
    $log->log(0,"[".__FILE__."] SESSION NOT EXIST => REDIRECT TO LOGIN PAGE");
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/TREN/cel_TREN.php&IDM='.$IDM."&IDW=".$IDW);
}

$css="cel_modul.css";

require(DR.'/class/database.php');
require(DR.'/view/v_head_cel.php');
require(DR.'/class/userPerm.php');
require(DR.'/pod_strony/_funkcje_/check_len.php');

$db= NEW database();
$db->loadDB();

/* CHECK PERMISSION */
if(!array_key_exists($IDW, $_SESSION['perm'][$IDM]))
{
    $db->insDbLog($IDM,'>BRAK uprawnienia IDW = '.$IDW);
    echo '<div class="DIV_MAIN"><p class="P_ERR">BRAK uprawnienia</p></div>';
    return '';
}
$db->insDbLog($IDM,'Uruchomiono funkcję - '.$_SESSION['perm'][$IDM][$IDW]);
define('ACT_PERM',$_SESSION['perm'][$IDM][$IDW]);
define('ACT_URL',APP_URL.'/pod_strony/TRENINGI/');
define('PAGE_URL',APP_URL.'/pod_strony/TRENINGI/cel_TRENINGI.php?IDM='.$IDM);
/* END CHECK PERMISSION */

$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');

?>
<body bgcolor="#FFFAFA" onLoad="init('e')">
<div id="loading" class="loadGif">
<img src="<?=APP_URL?>/images/loadProgressBar.gif" border="0">
</div>
<?php

$REK_MODUL=mysqli_fetch_assoc($db->query("select `ID`,`SKROT`,`NAZWA`,`TABELA` FROM `MODUL` WHERE `ID`='".$IDM."' LIMIT 1"));
$log->log(0,"[".__FILE__.'::'.__LINE__.'] DATABASE TABLE => '.$REK_MODUL['TABELA']);

switch ($IDW):
                    default: /* WYŚWIETL REKORDY */
                    case 0:
                            $frameTitle='Wszystkie pozycje';
                            require(DR.'/pod_strony/TRENINGI/showAll.php');
                        break;
                    case 1: /* DODAJ-TRENING */
                            $frameTitle='Dodaj pozycje';
                            require(DR.'/pod_strony/TRENINGI/add.php');
			break;
                    case 2:/* EDYTUJ */
                            if(isset($ID) && $ID!=='')
                            {
                                 require(DR.'/pod_strony/TRENINGI/edytujt_skrypt.php');
                            }
                            else
                            {
                                require(DR.'/pod_strony/TRENINGI/edit.php');
                            }
                            $frameTitle='Edytuj pozycje';
                            			
			break;							
                    case 3:	/* USUŃ */
                            $idData='';
                            $frameTitle='Usuń pozycje';
                            require(DR.'/pod_strony/DEFAULT/delete.php');								
			break;	
                    case 4: /* WIDOCZNOŚĆ */
                            $idData='';
                            $STAT_KOM='';
                            $WSKV=0;
                            $frameTitle='Widoczność pozycji';
                            require(DR.'/pod_strony/DEFAULT/visibility.php');
                            break;
                    case 5:
                        break;
endswitch;
echo "<p class=\"P_INFO_OP\">Strona załadowana w czasie : ".round((floatval(microtime(true))-$start_time_page),2)." s.</p>"; ?>
</body>