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
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/CEL_AKT/cel_AKT.php&IDM='.$IDM."&IDW=".$IDW);
}

$css="cel_akt.css";
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
define('PAGE_URL',APP_URL.'/pod_strony/AKT/cel_AKT.php?IDM='.$IDM);
define('AKT'.$IDM.$IDW,'y');
        



require(DR.'/pod_strony/_funkcje_/resize_image_new_v3.php');
require(DR.'/pod_strony/_funkcje_/check_len.php');

$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');

?>
<!----------------------------------------------------------------------------------------------------------------------------------->
<body onLoad="init('e')">
<center>
<!---------------------------------------------------------------------------------LOADING-NAPIS----------->
<div id="loading" style=" width:100%; text-align:left; top:0px; left:0px; display:block;">
<img src="../Loading_napis.gif" border="0" width="128px" height="15px"></div>
 <!--------------------------------------------------------------------------KONIEC-LOADING-NAPIS----------->
<?php
switch ($IDW):
                    
                    case 0: /* WYŚWIETL WSZYSTKIE ARTYKUŁY */
    			require(DR.'/pod_strony/AKT/showAll.php');
			break;
                    case 1: /* DODAJ--ARTYKUŁ */
                            require("dodaj_a.php");
			break;
                    case 2: /* EDYTUJ ARTYKUŁ */	
                                            if($ID!='')
                                            {
                                                 require(DR.'/pod_strony/AKT/edytuja.php');
                                                
                                            }
                                            else
                                            {
                                               
                                                require(DR.'/pod_strony/AKT/find.php');
                                            }
                                            break;						
			case 3: /*USUN ARTUKUŁ*/
                                                
                                                require_once(DR.'/pod_strony/AKT/usun_a.php');
						break;		
					case 4: 
						break;
					case 6: /* WIDOCZNOŚĆ-ARTYKUŁ */
                                                require(DR.'/pod_strony/AKT/visibility.php');
                                            break;
                    default:
                        echo "WRONG IDW";
                        break;
//---------------------------------------------------------------------KONIEC-WIDOCZNOŚĆ-ARTYKUŁ-------------------------------------------------------------------		
endswitch;	
?>
</center>	
<?php echo "<p class=\"P_INFO_OP\">Strona załadowana w czasie : ".round((floatval(microtime(true))-$start_time_page),2)." s.</p>"; ?>
</body>
</html>
																			