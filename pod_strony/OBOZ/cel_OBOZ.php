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
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/OBOZ/cel_OBOZ.php&IDM='.$IDM."&IDW=".$IDW);
}

$css="cel_oboz.css";
require(DR.'/view/v_head_cel.php');
require(DR.'/class/database.php');

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
/* END CHECK PERMISSION */
define('ACT_URL',APP_URL.'/pod_strony/OBOZ/');
define('PAGE_URL',APP_URL.'/pod_strony/OBOZ/cel_OBOZ.php?IDM='.$IDM);
define('OBOZ_'.$IDM.$IDW,'y');

require(DR.'/pod_strony/_funkcje_/resize_image_new_v3.php');
require(DR.'/pod_strony/_funkcje_/check_len.php');

$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');

?>
<body onLoad="init('e')">
<div id="loading" style=" width:100%; text-align:left; top:0px; left:0px; height:20px;">
<img src="<?=APP_URL?>/images/loadProgressBar.gif" border="0" width="128px" height="15px"></div>
<?php
$windL ='<script type="text/javascript">document.writeln(leftS);</script>';
$windT ='<script type="text/javascript">document.writeln(topS);</script>';
$resWidth ='<script type="text/javascript">document.writeln(getWidth());</script>';
$resHeight ='<script type="text/javascript">document.writeln(getHeight());</script>';
echo "<p class=\"P_INFO\">Rozdziekczosc ekranu użytkownka - width:<span class=\"S_INFO\">$resWidth</span> height:<span class=\"S_INFO\">$resHeight</span></p>";
echo "<p class=\"P_INFO\">Pozycja okna - left:<span class=\"S_INFO\">$windL</span> top:<span class=\"S_INFO\">$windT</span></p>";


switch ($IDW):
                        default: // WYSWIETL OBOZY
                        case 9:
			case 4: 
			case 5:
			case 0: 
				include(DR."/pod_strony/OBOZ/showAll.php");
				break;
                        case 1: // USTAW TYP DODAJ OBOZ	
                                if(filter_input(INPUT_GET,'TYP_DODAJ')!='')
                                {
                                    include(DR."/pod_strony/OBOZ/add.php");
                                }
                                else
                                {
                                    include(DR."/pod_strony/OBOZ/set_type_of_add.php");
                                }
                                
                                break;
                        case 2: // EDYTUJ OBOZ	
                                if($ID!='')
                                {
                                    include(DR."/pod_strony/OBOZ/edytuj_o.php");
                                }
                                else
                                {
                                    include(DR."/pod_strony/OBOZ/edit.php");
                                }
				
				break;
                        case 3: // USUN					
				include(DR."/pod_strony/OBOZ/delete.php");
				break;
                        case 6: // WIDOCZNOSC OBOZU
				include(DR."/pod_strony/OBOZ/visibility.php");
				break;						
                        case 8: // POKAZ ZDJECIA	
                                echo "<DIV class=\"DIV_MAIN\">";
                                echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_OBOZ.php?IDW=0&IDM='.$_GET['IDM'].'">Anuluj</a></p>';
                               include(DR."/pod_strony/OBOZ/show_images.php");                          
				break;  
			endswitch;	
echo "<p class=\"P_INFO_OP\">Strona załadowana w czasie : ".round((floatval(microtime(true))-$start_time_page),2)." s.</p>";?>		
</body>
</html>