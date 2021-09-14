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
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/cel_NASI_ZA/cel_NASI_ZA.php&IDM='.$IDM."&IDW=".$IDW);
}
$css="cel_modul.css";
require(DR.'/view/v_head_cel.php');
require(DR.'/class/database.php');

$db= NEW database();
$db->loadDb();

/* CHECK PERMISSION */
if(!array_key_exists($IDW, $_SESSION['perm'][$IDM]))
{
    $db->insDbLog($IDM,'>BRAK uprawnienia IDW = '.$IDW);
    echo '<div class="DIV_MAIN"><p class="P_ERR">BRAK uprawnienia</p></div>';
    return '';
}
$db->insDbLog($IDM,'Uruchomiono funkcję - '.$_SESSION['perm'][$IDM][$IDW]);
define('ACT_PERM',$_SESSION['perm'][$IDM][$IDW]);
/* END CHECK PERMISSION */
define('ACT_URL',APP_URL.'/pod_strony/NASI_ZA/');
define('PAGE_URL',APP_URL.'/pod_strony/NASI_ZA/cel_NASI_ZA.php?IDM='.$IDM);


require(DR.'/pod_strony/_funkcje_/resize_image_new_v3.php');
require(DR.'/pod_strony/_funkcje_/upload_file.php');
require(DR.'/pod_strony/_funkcje_/check_len.php');

$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');
$REK_MODUL=mysqli_fetch_assoc($db->query("select `ID`,`SKROT`,`NAZWA`,`TABELA` FROM `MODUL` WHERE `ID`='".$IDM."' LIMIT 1"));

?>
<body onLoad="init('e')">
<div id="loading" >
<img src="<?=APP_URL?>/images/loadProgressBar.gif" border="0" width="128px" height="15px"></div>
<?php
echo "<body><center>";
switch ($IDW):
					
                                        case 0: /* WYŚWIETL-ZAWODNIKA */
//--------------------------------------------------------------------------------------------------------------------------------------------					
						require(DR."/pod_strony/NASI_ZA/showAll.php");
                                            break;								
					case 1:/* DODAJ-ZAWODNIKA */				
                                                require(DR."/pod_strony/NASI_ZA/add.php");
						break;
                                        case 2: /* EDYTUJ-ZAWODNIK */
                                                if($ID!='')
                                                {
                                                    require(DR.'/pod_strony/NASI_ZA/edit_2.php');
                                                }
                                                else
                                                {
                                                    require(DR."/pod_strony/NASI_ZA/edit.php");
                                                }
						break;			
                                        case 3: /* USUN-ZAWODNIK */
                                                require(DR."/pod_strony/NASI_ZA/delete.php");
                                            break;	 
                                        case 6: /* WIDOCZNOŚĆ-ZAWODNIKA */
                                                require(DR.'/pod_strony/NASI_ZA/visibility.php');
                                            break;
					case 7: /* PIORYTET-ZAWODNIKA */
                                                require(DR.'/pod_strony/NASI_ZA/priority.php');
                                            break;
                                        default:
                                                echo "WRONG IDW<br/>";
                                            break;
endswitch;	
?>		
</center>
<?php echo "<p class=\"P_INFO_OP\">Strona załadowana w czasie : ".round((floatval(microtime(true))-$start_time_page),2)." s.</p>";?>
</body>
</html>