<?php 
session_start(); 
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
define('RA',filter_input(INPUT_SERVER,"REMOTE_ADDR"));

require(DR.'/.cfg/konfiguracja.php');
require(DR.'/class/logToFile.php');
require(DR.'/class/session.php');
require(DR."/class/checkGlobalVar.php");

$start_time_page = floatval(microtime(true));
$css="cel_DEFAULT.css";


$log=NEW logToFile();
$checkVar=NEW checkGlobalVar();
$session=new session();

$checkVar->checkOnlyGet($IDM,'IDM');
$checkVar->checkOnlyGet($IDW,'IDW');

if(!$session->checkSession($IDM))
{    
    $log->log(0,"[".__FILE__."] SESSION NOT EXIST => REDIRECT TO LOGIN PAGE");
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/DEFAULT/iframe.php&IDM='.$IDM."&IDW=".$IDW);
}

require(DR.'/class/database.php');
$db= NEW database();
$db->loadDb();

require(DR.'/view/v_head_cel.php');

/* CHECK PERMISSION */
if(!array_key_exists($IDW, $_SESSION['perm'][$IDM]))
{
    $db->insDbLog($IDM,'>BRAK uprawnienia IDW = '.$IDW);
    echo '<div class="DIV_MAIN"><p class="P_ERR">BRAK uprawnienia</p></div>';
    return '';
}
$db->insDbLog($IDM,'Uruchomiono funkcję - '.$_SESSION['perm'][$IDM][$IDW]);
/* END CHECK PERMISSION */
define('ACT_URL',APP_URL.'/pod_strony/TRENINGI/');
define('PAGE_URL',APP_URL.'/pod_strony/TRENINGI/cel_TRENINGI.php?IDM='.$IDM);
define('DEFAULT_'.$IDW,'y');

require(DR.'/class/parseCSS.php');


/* CSS PARSER */

$parseC=NEW parseCSS();

$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');

$REK_MODUL=mysqli_fetch_assoc($db->query("select `ID`,`SKROT`,`NAZWA`,`TABELA` FROM `MODUL` WHERE `ID`='".$IDM."' LIMIT 1"));
$log->log(0,"[".__FILE__.'::'.__LINE__.'] DATABASE TABLE => '.$REK_MODUL['TABELA']);

?>
<body onLoad="init('e')">
<div id="loading" style=" width:100%; text-align:left; top:0px; left:0px;display:block">
<img src="<?=APP_URL?>/images/loadProgressBar.gif" border="0" width="128px" height="15px"></div>
<center>
<?php
switch ($IDW):
        case 0: /* WYSWIETL-WSZYSTKIE-POZYCJE */
                require(DR.'/pod_strony/DEFAULT/showall.php');
            break;
        case 1: /* DODAJ-POZYCJĘ */				
                require(DR.'/pod_strony/DEFAULT/add.php');	
            break;
	case 2:	/* FIND */	
                $frameTitle='Eydytowanie pozycji';
                require(DR.'/pod_strony/DEFAULT/find.php');
            break;							
        case 3: /* USUŃ */
                if (trim($ID)!='')
                {
                $result=$db->query("select `WSK_V`,`TRESC`,`KOLOR_HEX`,`ROZMIAR`,`POZYCJA`,`CSS` FROM `".$REK_MODUL['TABELA']."` WHERE `ID`='".$ID."'");
                $d = mysqli_fetch_assoc($result);
                $parseC->setCSS($d['CSS']);
                $idData='<p style="color:'.$d['KOLOR_HEX'].';font-size:'.$d['ROZMIAR'].'px; text-align:'.$d['POZYCJA'].';'. $parseC->getFontWeight().$parseC->getFontStyle().$parseC->getTextDecoration().'">'.$d['TRESC'].'</p>';
                }
                else
                {
                    $idData='';
                }
                require(DR.'/pod_strony/DEFAULT/delete.php');	
            break;		
        case 4: 
               require(DR.'/pod_strony/DEFAULT/edit.php');
            break;
        case 5: 
            break;
        case 6: /* WIDOCZNOŚĆ */
                if (trim($ID)!='')
                {
                    $result=$db->query("select `WSK_V`,`TRESC`,`KOLOR_HEX`,`ROZMIAR`,`POZYCJA`,`CSS` FROM `".$REK_MODUL['TABELA']."` WHERE ID='".$ID."'");
                    $d = mysqli_fetch_assoc($result);
                    $parseC->setCSS($d['CSS']);
                    if ($d['WSK_V']==1)
                    {
                        $STAT_KOM ='<font style="color:blue;font-weight:bold;"> WIDOCZNY </font>';
                    } 
                    else
                    {
                        $STAT_KOM='<font style="color:red;font-weight:bold;"> NIEWIDOCZNY</font>';
                    }
                    $WSKV=$d['WSK_V'];
                    $idData='<p style="color:'.$d['KOLOR_HEX'].';font-size:'.$d['ROZMIAR'].'px; text-align:'.$d['POZYCJA'].';'. $parseC->getFontWeight().$parseC->getFontStyle().$parseC->getTextDecoration().'">'.$d['TRESC'].'</p>';
                }
                else
                {
                    $idData='';
                }
                require(DR.'/pod_strony/DEFAULT/visibility.php');
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
