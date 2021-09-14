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
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/STR_GL/cel_STR_GL.php&IDM='.$IDM."&IDW=".$IDW);
}

$css="cel_modul.css";
require(DR.'/view/v_head_cel.php');
require(DR.'/class/database.php');

$db= NEW database();
$db->loadDB();
if(!array_key_exists($IDW, $_SESSION['perm'][$IDM]) || !array_key_exists(-1, $_SESSION['perm'][$IDM]) )
{
    $db->insDbLog($IDM,'>BRAK uprawnienia IDW = '.$IDW);
    echo '<div class="DIV_MAIN"><p class="P_ERR">BRAK uprawnienia</p></div>';
    return '';
}

$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');
?>
<!-----------------------------------------------------------KONIEC-SESJA------------------------------------------------------------------------------------>
<body onLoad="init('e')">
<div id="loading">
<img src="../Loading_napis.gif" id="loading"></div>
<?php


echo '<center><div class="DIV_MAIN">';		
switch ($IDW):
					default:
                                            echo '<p class="P_MAIN">Strona w rozbudowie ['.$IDW.']</p>';
                                            break;
                                        case 7:
                                                $db->insDbLog($IDM,"Uruchomiono funkcję - DODAJ element strony głównej");
						echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_str_gl.php?IDW=0&IDM='.$IDM.'">Anuluj</a></p>';
						IF(!ISSET($_GET["submit_wybor"]))
						{
							if (file_exists(DR.'/view/v_admin_str_gl_wybor.php')) 
							{
								include(DR.'/view/v_admin_str_gl_wybor.php');
							}
							else
							{
								echo "<p class=\"P_ERROR\" style=\"color:red;\">Brak pliku - <span class=\"S_INFO\">/widok/v_admin_str_gl_wybor.php</span></p>"; 
							};
							
						}
						ELSE
						{
						switch ($_GET["TYP_DODAJ"]):
							case 0:// TESKT
									IF(ISSET($_POST['dodaj']) || ISSET($_POST["podgladT"]))
									{
										$check=FALSE;
										echo "Sprawdzam zawartosc</br>";
										include(DR.'/tb.php/m_sprawdz_dane.php');
										echo "check - $check</br>";
									};
									include(DR.'/view/v_admin_str_gl_wybor_tekst.php');
									IF(ISSET($_POST['dodaj']) && $check==TRUE)
									{
										echo "Dodaje zawartosc</br>";
										include(DR.'/tb.php/m_dodaj_dane.php');
									};
								break;
							case 1:// OBRAZ
									include(DR.'/view/v_admin_str_gl_wybor_obraz.php');
									break;
							case 2:// FILM
									include(DR.'/view/v_admin_str_gl_wybor_film.php');
									break;
							case 3:// hiperłącze
									include(DR.'/view/v_admin_str_gl_wybor_hiperlacze.php');
								break;
							default:
									echo "NIE ZDEFINIOWANY WYBÓR";
									break;
								
						endswitch;
						};
						//echo '</div>';
						//}else {echo '<div class=\"DIV_MAIN\"><p class="P_ERR">BRAK uprawnienia - Dodaj nowy obóz </p></div>';};
	break;
endswitch;	
echo "</div></center>";
echo "<p class=\"P_INFO_OP\">Strona załadowana w czasie : ".round((floatval(microtime(true))-$start_time_page),2)." s.</p>";
?>		
</body>
</html>