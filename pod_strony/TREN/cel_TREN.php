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

$css="cel_trener.css";
require(DR.'/view/v_head_cel.php');
require(DR.'/class/database.php');
$db= NEW database();
$db->loadDb();

/* CHECK PERMISSION */
/*
 * -1 => Dostęp
 */
if(!array_key_exists($IDW, $_SESSION['perm'][$IDM]) || !array_key_exists(-1, $_SESSION['perm'][$IDM]) )
{
    $db->insDbLog($IDM,'>BRAK uprawnienia IDW = '.$IDW);
    echo '<div class="DIV_MAIN"><p class="P_ERR">BRAK uprawnienia</p></div>';
    return '';
}
$db->insDbLog($IDM,'Uruchomiono funkcję - '.$_SESSION['perm'][$IDM][$IDW]);
/* END CHECK PERMISSION */
define('PAGE_URL',APP_URL.'/pod_strony/TREN/cel_TREN.php?IDM='.$IDM);
define('TREN'.$IDM.$IDW,'y');

require(DR.'/pod_strony/_funkcje_/resize_image_new2.php');
require(DR.'/pod_strony/_funkcje_/check_len.php');

$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');

?>
<body onLoad="init('e')">
<div id="loading" style=" width:100%; text-align:left; top:0px; left:0px; height:20px;">
<img src="<?=APP_URL?>/images/loadProgressBar.gif" border="0" width="128px" height="15px"></div>
<?php
echo "<center>";
switch ($_GET['IDW']):
					default:
					case 0: /* WYŚWIETL-TRENER */				
							require_once(DR."/pod_strony/TREN/show_all.php");
							break;
					case 1: /* DODAJ-TRENER */				
							require_once(DR."/pod_strony/TREN/add.php");
						break;
					case 2: /* EDYTUJ-TRENER */
                                                if($ID!='')
                                                {
                                                    require_once(DR."/pod_strony/TREN/edytuj.php");
                                                }
                                                else
                                                {
                                                    require_once(DR."/pod_strony/TREN/edit.php");
                                                }	
						break;						
					case 3: /* USUN-TRENER */							
							require_once(DR."/pod_strony/TREN/delete.php");
						break;	
					case 4: 
							break;
					case 5: 
							break;
					case 6: /* WIDOCZNOŚĆ-TRENER */
						require_once(DR."/pod_strony/TREN/visibility.php");
						break;		
endswitch;	
?>		
</center>
<?php echo "<p class=\"P_INFO_OP\">Strona załadowana w czasie : ".round((floatval(microtime(true))-$start_time_page),2)." s.</p>"; ?>		
</body>
</html>