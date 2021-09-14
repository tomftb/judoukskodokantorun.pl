<?php 
session_start(); 
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));

require(DR.'/.cfg/konfiguracja.php');
require(DR.'/class/logToFile.php');
require(DR.'/class/session.php');
require(DR."/class/checkGlobalVar.php");

$start_time_page = floatval(microtime(true));
$css="cel_oboz.css";

$log=NEW logToFile();
$checkVar=NEW checkGlobalVar();
$session=new session();

$checkVar->checkOnlyGet($IDM,'IDM');
$checkVar->checkOnlyGet($IDW,'IDW');

if(!$session->checkSession($IDM))
{    
    $log->log(0,"[".__FILE__."] SESSION NOT EXIST => REDIRECT TO LOGIN PAGE");
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/KL_SP/cel_KL_SP.php&IDM='.$IDM."&IDW=".$IDW);
}
require(DR.'/class/database.php');
require(DR.'/view/v_head_cel.php');
$db= NEW database();
$db->loadDb();

/* CHECK PERMISSION */
if(!array_key_exists($IDW, $_SESSION['perm'][$IDM]))
{
    $db->insDbLog($IDM,'>BRAK uprawnienia IDW = '.$IDW);
    echo '<div class="DIV_MAIN"><p class="P_ERR">BRAK uprawnienia IDW - '.$IDW.'</p></div>';
    return '';
}
echo "<pre>";
//print_r($_SESSION['perm']);
echo "</pre>";
$db->insDbLog($IDM,'Uruchomiono funkcję - '.$_SESSION['perm'][$IDM][$IDW]);
define('ACT_PERM',$_SESSION['perm'][$IDM][$IDW]);
/* END CHECK PERMISSION */
define('ACT_URL',APP_URL.'/pod_strony/KL_SP/');
define('PAGE_URL',APP_URL.'/pod_strony/KL_SP/cel_KL_SP.php?IDM='.$IDM);

require(DR.'/pod_strony/_funkcje_/resize_image_new.php');
require(DR.'/pod_strony/_funkcje_/upload_file.php');
require(DR.'/pod_strony/_funkcje_/check_len.php');

$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');

?>
<body onLoad="init('e')">
<div id="loading" style=" width:100%; text-align:left; top:0px; left:0px; height:20px;">
<img src="../Loading_napis.gif" border="0" width="128px" height="15px"></div>
<?php

$REK_MODUL=mysqli_fetch_assoc($db->query("select `ID`,`SKROT`,`NAZWA`,`TABELA` FROM `MODUL` WHERE `ID`='".$IDM."' LIMIT 1"));
$log->log(0,"[".__FILE__.'::'.__LINE__.'] DATABASE TABLE => '.$REK_MODUL['TABELA']);

echo "<center>";
switch ($_GET["IDW"]):
					
					case 0: 
                                                $frameTitle='Wszyskie Klasy SP 6 : ';
                                                require(DR.'/pod_strony/KL_SP/showAll.php');
                                            break;						
					case 1:
                                                $frameTitle='Dodaj Klasę Sportową :';
                                                require_once(DR.'/pod_strony/KL_SP/add.php');
                                            break;
                                        case 2: /* EDYTUJ */
                                                if($ID!='')
                                                {
                                                    require(DR.'/pod_strony/KL_SP/edytuj_kl.php');
                                                }
                                                else
                                                {
                                                    $frameTitle='Edycja Klasy Sportowej :';
                                                require_once(DR.'/pod_strony/KL_SP/edit.php');
                                                }
                                                
                                            break;
                                        case 3:/* USUŃ */
                                                $idData='';
                                                $frameTitle='Usuń pozycje';
                                                require(DR.'/pod_strony/DEFAULT/delete.php');
                                            break;
                                        case 6: /* WIDOCZNOŚĆ-KLASY */	
                                                $idData='';
                                                $STAT_KOM='';
                                                $WSKV=0;
                                                $frameTitle='Widoczność pozycji';
                                                require(DR.'/pod_strony/DEFAULT/visibility.php');
                                            break;
					default:
                                                echo "WRONG IDW<br/>";
                                            break;
endswitch;
echo "<p class=\"P_INFO_OP\">Strona załadowana w czasie : ".round((floatval(microtime(true))-$start_time_page),2)." s.</p>";
?>
<body>
</html>