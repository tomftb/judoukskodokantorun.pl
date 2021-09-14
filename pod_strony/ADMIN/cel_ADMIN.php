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
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/CEL_ADMIN/cel_ADMIN.php&IDM='.$IDM."&IDW=".$IDW);
}

$css="cel_ADMIN.css";
require(DR.'/view/v_head_cel.php');
require(DR.'/class/database.php');
$db= NEW database();
$db->loadDb();

/* CHECK PERMISSION */
/*
 * -1 => Dostęp
 */
/*
echo '<pre>';
print_r($_SESSION['perm'][$IDM]);
echo '</pre>';
 */
if(!array_key_exists($IDW, $_SESSION['perm'][$IDM]) || !array_key_exists(-1, $_SESSION['perm'][$IDM]) )
{
    $db->insDbLog($IDM,'>BRAK uprawnienia IDW = '.$IDW);
    echo '<div class="DIV_MAIN"><p class="P_ERR">BRAK uprawnienia</p></div>';
    return '';
}
$db->insDbLog($IDM,'Uruchomiono funkcję - '.$_SESSION['perm'][$IDM][$IDW]);
/* END CHECK PERMISSION */
define('PAGE_URL',APP_URL.'/pod_strony/ADMIN/cel_ADMIN.php?IDM='.$IDM);
define('ADMIN'.$IDM.$IDW,'y');
        


$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');

?>
<body onLoad="init('e')">
<div id="loading" ><img src="<?=APP_URL?>/images/loadProgressBar.gif" border="0" width="128px" height="15px"></div>
<?php
echo '<div class="DIV_MAIN">';
		
switch ($IDW):
			DEFAULT:
			case 0: /* USTAWIENIA */
                                require(DR.'/pod_strony/ADMIN/showSettings.php');
                            break;
                        case 1: /* UŻYTKOWNICY */
                                require(DR.'/pod_strony/ADMIN/showUsers.php');
                            break;
                        case 2: /* UPRAWNIENIA */
                                require(DR.'/pod_strony/ADMIN/showPermissions.php');
                            break;	
                        case 3: /* LOG-LOGOWANIA-DO-SYSTEMU */
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
                                require(DR.'/pod_strony/ADMIN/showLogs.php');
                            break;
			case 4:
				echo '<p class="P_M_NAGL">PHP info :</p>';
				phpinfo();					
                            break;		
					case 5:
//--------------------------------------------------------------------------USTAWIENIA-EDYCJA-POZYCJONOWANIE-TEKSTU-------------------------------------------------------------------------
						//if ($TAB_PRAW[5]["php INFO"]==1){
						echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'">Anuluj</a></p>';
						mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
						$INS_DZIEN="INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ($_SESSION[id_user],$_GET[IDM],'Uruchomiono funkcję - USTAWIENIA EDYCJA POZYCJONOWANIE TEKSTU',NOW())";
						mysqli_query($polaczenie,$INS_DZIEN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_DZIEN - ".mysqli_error()."</span></p>");
						if ($_SESSION['id_user']==1){
													echo "<p class=\"P_INFO\">IDW : <span class=\"S_INFO\">".$_GET["IDW"]."</span></p>";
													echo "<p class=\"P_INFO\">IDM : <span class=\"S_INFO\">".$_GET["IDM"]."</span></p>";
													echo "<p class=\"P_INFO\">IDG : <span class=\"S_INFO\">".$_GET["IDG"]."</span></p>";
													echo "<p class=\"P_INFO\">IDB : <span class=\"S_INFO\">".$_GET["IDB"]."</span></p>";
						};
						$wsk_d_poz=0;
						$naglowek="Zmiana pozycjonowania tekstu :";
						$COLUMN="NAZWA";									
						echo '<p class="P_M_NAGL"> Zmiana pozycjonowania tekstu : </p>';
						echo '<table class="GLOWNA_TAB"><tr class="NAGLOWEK_TAB">';
						echo '<td class="NAGLOWEK_TAB" width="120px"><p class="NAGLOWEK_P">Moduł : </p></td>';
						echo '<td width="260px"><p class="NAGLOWEK_P">Pole : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="80px"><p class="NAGLOWEK_P">Opcja : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="140px"><p class="NAGLOWEK_P">Domyślny :</td></tr>';
						for ($i_parm=0;$i_parm<4;$i_parm++){
						switch ($i_parm):
											case 0:
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Tytuł</span>";
													$wsk_d=1;
												break;
											case 1:
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Treść</span>";
													$wsk_d=3;
												break;
											case 2:
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Imie/Nazwisko</span>";
													$wsk_d=5;
												break;
											case 3:
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Dodatkowe informacje</span>";
													$wsk_d=7;
												break;
											default:
												break;
						endswitch;			
						mysqli_query($polaczenie,"SET NAMES `UTF8` COLLATE `UTF8_POLISH_CI`");
						$SEL_PARM = mysqli_query($polaczenie,"SELECT ID,N_OPCJ,NAZWA,WART FROM PARM WHERE WSK_U=0 AND SUBSTRING(WSK_D, '$wsk_d', 1)='1' AND ID_GROUP='$_GET[IDG]'")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_PARM : ".mysqli_error()."</span></p>");
						while($REK_PARM=mysqli_fetch_array($SEL_PARM))
								{
								echo '<tr class="TRESC_TAB">';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; color:black; font-weight:bold;margin-bottom:0px; margin-top:0px; text-align:left;">'.$modul.'</p></td>';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$opcja_modul.'</p></td>';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$REK_PARM[1].'</p></td>';
								echo '<td class="TRESC_TAB"><span style="float:left;margin-left:10px;color:purple; font-weight:bold;">'.$REK_PARM[2].'</span>';
								echo '<span style="float:right;margin-right:10px;"><b> [</b><a href="cel_ADMIN.php?IDW=10&IDM='.$_GET['IDM'].'&IDP='.$REK_PARM[0].'&IDG=0&IDS='.$wsk_d.'" style="margin:0px;" class="A_UST"> ZMIEŃ </a><b>]</b></span></td>';
								}
													};
						echo "</tr></table>";
						//} else { echo '<p class="P_ERR">BRAK uprawnienia - Wyświetl php INFO </p>';};
						break;
//--------------------------------------------------------------------------KONIEC-USTAWIENIA-EDYCJA-POZYCJONOWANIE-TEKSTU-------------------------------------------------------------------------						
					case 6:
//--------------------------------------------------------------------------USTAWIENIA-EDYCJA-FORMATOWANIA-TEKSTU-------------------------------------------------------------------------
						echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'">Anuluj</a></p>';
						mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
						$INS_DZIEN="INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ($_SESSION[id_user],$_GET[IDM],'Uruchomiono funkcję - USTAWIENIA EDYCJA FORMATOWANIA TEKSTU',NOW())";
						mysqli_query($polaczenie,$INS_DZIEN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_DZIEN - ".mysqli_error()."</span></p>");
						if ($_SESSION['id_user']==1){
													echo "<p class=\"P_INFO\">IDW : <span class=\"S_INFO\">".$_GET["IDW"]."</span></p>";
													echo "<p class=\"P_INFO\">IDM : <span class=\"S_INFO\">".$_GET["IDM"]."</span></p>";
													echo "<p class=\"P_INFO\">IDG : <span class=\"S_INFO\">".$_GET["IDG"]."</span></p>";
													echo "<p class=\"P_INFO\">IDB : <span class=\"S_INFO\">".$_GET["IDB"]."</span></p>";
						};						
						echo '<p class="P_M_NAGL"> Zmiana formatowania tekstu : </p>';
						echo '<table class="GLOWNA_TAB"><tr class="NAGLOWEK_TAB">';
						echo '<td class="NAGLOWEK_TAB" width="120px"><p class="NAGLOWEK_P">Moduł : </p></td>';
						echo '<td width="240px"><p class="NAGLOWEK_P">Pole : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="240px"><p class="NAGLOWEK_P">Domyślny :</td></tr>';
						//$IDP=array('','','');
						for ($i_parm=0;$i_parm<4;$i_parm++){
						switch ($i_parm):
											case 0:
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Tytuł</span>";
													$wsk_d=1;
												break;
											case 1:
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Treść</span>";
													$wsk_d=3;
												break;
											case 2:
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Imie/Nazwisko</span>";
													$wsk_d=5;
												break;
											case 3:
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Dodatkowe informacje</span>";
													$wsk_d=7;
												break;
											default:
												break;
						endswitch;			
						mysqli_query($polaczenie,"SET NAMES `UTF8` COLLATE `UTF8_POLISH_CI`");
								echo '<tr class="TRESC_TAB">';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; color:black; font-weight:bold;margin-bottom:0px; margin-top:0px; text-align:left;">'.$modul.'</p></td>';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$opcja_modul.'</p></td>';
								echo '<td class="TRESC_TAB">';
								//$i_dom=0;
								
								$SEL_DOM = mysqli_query($polaczenie,"SELECT ID,N_OPCJ,NAZWA,WART,SUBSTRING(WSK_D, '$wsk_d', 1) FROM PARM WHERE WSK_U=0 AND ID_GROUP='$_GET[IDG]'")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_DOM : ".mysqli_error()."</span></p>");
								while($REK_DOM=mysqli_fetch_array($SEL_DOM))
													{
													if ($REK_DOM[4]==1) {
																		$DOMYSLNY[$i_parm].=$REK_DOM[2]." ";
																		//$IDP[$i_parm].=$REK_DOM[0].'|';
																		} else {
																				//$IDP[$i_parm].='0|';
																				};
													
													//echo "i_parm w petli rek_dom : ".$i_parm."</br>";
													//echo "WARTOSC IDP = ".$IDP[$i_parm]."</br>";
													//$i_dom++;
													};
											//if ($i_dom==0) { 
											//	echo '<span style="float:left;margin-left:10px;color:red; font-weight:bold;">BRAK</span>';
											//	$IDP[$i_parm]=0;
											//	echo "i_parm w petli rek_dom : ".$i_parm."</br>";
											//	echo "WARTOSC IDP = ".$IDP[$i_parm]."</br>";
											//	} 
											//	else {
											//			echo '<span style="float:left;margin-left:10px;color:purple; font-weight:bold;">'.$DOMYSLNY[$i_parm].'</span>';
											//		};
								echo '<span style="float:left;margin-left:10px;color:purple; font-weight:bold;">'.$DOMYSLNY[$i_parm].'</span>';
								echo '<span style="float:right;margin-right:10px;"><b> [</b><a href="cel_ADMIN.php?IDW=11&IDG='.$_GET["IDG"].'&IDS='.$wsk_d.'" style="margin:0px;" class="A_UST"> ZMIEŃ </a><b>]</b></span></td>';
													};
						echo "</tr></table>";
						break;
//--------------------------------------------------------------------------KONIEC-USTAWIENIA-EDYCJA-FORMATOWANIA-TEKSTU-------------------------------------------------------------------------							
					case 7:
//--------------------------------------------------------------------------USTAWIENIA-EDYCJA-ROZMIARU-TEKSTU-------------------------------------------------------------------------
							echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'">Anuluj</a></p>';
							mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$INS_DZIEN="INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ($_SESSION[id_user],13,'Uruchomiono funkcję - USTAWIENIA EDYCJA ROZMIARU TEKSTU',NOW())";
							mysqli_query($polaczenie,$INS_DZIEN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_DZIEN - ".mysqli_error()."</span></p>");
							if ($_SESSION['id_user']==1){
													echo "<p class=\"P_INFO\">IDW : <span class=\"S_INFO\">".$_GET["IDW"]."</span></p>";
													echo "<p class=\"P_INFO\">IDM : <span class=\"S_INFO\">".$_GET["IDM"]."</span></p>";
													echo "<p class=\"P_INFO\">IDG : <span class=\"S_INFO\">".$_GET["IDG"]."</span></p>";
													echo "<p class=\"P_INFO\">IDB : <span class=\"S_INFO\">".$_GET["IDB"]."</span></p>";
							};
						$tab_width=array(0,0,0,0);
						$tab_nagl=array("","","","");
						switch ($_GET["IDG"]):
											//case 1:
											//		$modul="Strona Główna -> Trening";
											//		$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Nazwa grupy</span>";
											//		$wsk_d=1;
											//	break;
											case 2:
													$naglowek="Zmiana rozmiaru tekstu";
													$tab_width[0]=160;
													$tab_width[1]=240;
													$tab_width[2]=110;
													$tab_width[3]=90;
													$tab_nagl[1]="Pole";
												break;
											case 3:
													$naglowek="Zmiana ustawienia koloru";
													$tab_width[0]=160;
													$tab_width[1]=220;
													$tab_width[2]=80;
													$tab_width[3]=140;
													$tab_nagl[1]="Pole";
												break;
											case 4:
													$naglowek="Zmiana rozmiaru zdjęć";
													$tab_width[0]=140;
													$tab_width[1]=240;
													$tab_width[2]=110;
													$tab_width[3]=110;
													$tab_nagl[1]="Info";
												break;
											default:
													$naglowek="BŁĄD";
												break;
						endswitch;				
						echo '<p class="P_M_NAGL"> '.$naglowek.' : </p>';
						echo '<table class="GLOWNA_TAB">';
						echo '<tr class="NAGLOWEK_TAB">';
						echo '<td class="NAGLOWEK_TAB" width="'.$tab_width[0].'px"><p class="NAGLOWEK_P">Moduł : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="'.$tab_width[1].'px"><p class="NAGLOWEK_P">'.$tab_nagl[1].' : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="'.$tab_width[2].'px"><p class="NAGLOWEK_P">Opcja : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="'.$tab_width[3].'px"><p class="NAGLOWEK_P">Domyślny :</td></tr>';
						mysqli_query($polaczenie,"SET NAMES `UTF8` COLLATE `UTF8_POLISH_CI`");
						$SEL_PARM = mysqli_query($polaczenie,"SELECT ID,ID_OPCJ,N_OPCJ,NAZWA,WART FROM PARM WHERE WSK_U=0 AND ID_GROUP='$_GET[IDG]' GROUP BY N_OPCJ")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_PARM : ".mysqli_error()."</span></p>");
						while($REK_PARM=mysqli_fetch_array($SEL_PARM)){
						switch ($REK_PARM[2]):
											case "ROZ_TRE_N_G":
													$modul="Strona Główna -> Trening";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Nazwa grupy</span>";	
												break;
											case "ROZ_TRE_W":
													$modul="Strona Główna -> Trening";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Wiek</span>";
												break;
											case "ROZ_TRE_D_G":
													$modul="Strona Główna -> Trening";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Dzień godzina treningu</span>";
												break;
											case "ROZ_TRE_Z":
													$modul="Strona Główna -> Trening";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Zapisy</span>";
												break;
											case "ROZ_ART_TYT":
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Tytuł</span>";
													
												break;
											case "ROZ_ART_TRE":
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Treść</span>";
												break;
											case "ROZ_NZ_IMI":
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Imie/Nazwisko</span>";
												break;
											case "ROZ_NZ_INF":
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Dodatkowe informacje</span>";
												break;
											case "ROZ_IMG_ART_MAX":
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Maksymalny rozmiar zdjęcia</span>";
												break;
											case "ROZ_IMG_ART_MIN":
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Maksymalny rozmiar zdjęcia miniatury</span>";
												break;
											case "ROZ_IMG_N_ZD_MAX":
													$modul="Nasze zdjęcia";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Maksymalny rozmiar zdjęcia</span>";
												break;
											case "ROZ_IMG_N_ZD_MIN":
													$modul="Nasze zdjęcia";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Maksymalny rozmiar zdjęcia miniatury</span>";
												break;
											case "ROZ_IMG_NZ_MAX":
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Maksymalny rozmiar zdjęcia</span>";
												break;
											case "ROZ_IMG_NZ_MIN":
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Maksymalny rozmiar zdjęcia miniatury</span>";
												break;
											case "ROZ_IMG_OBOZ_MAX":
													$modul="Obóz";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Maksymalny rozmiar zdjęcia</span>";
												break;
											case "ROZ_IMG_OBOZ_MIN":
													$modul="Obóz";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Maksymalny rozmiar zdjęcia miniatury</span>";
												break;
											case "KOL_ART_TRE_TXT":
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Kolor tekstu - TREŚĆ</span>";
												break;
											case "KOL_ART_TYT_TXT":
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Kolor tekstu - TYTUŁ</span>";
												break;
											case "KOL_NZ_IMI_TXT":
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Kolor tekstu - Imie/Nazwisko</span>";
												break;
											case "KOL_NZ_INF_TXT":
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Kolor tekstu - Dodatkowe informacje </span>";
												break;
											case "KOL_TRE_TLO":
													$modul="Strona Główna -> Trening";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Kolor tła</span>";
												break;
											case "KOL_TRE_TXT":
													$modul="Strona Główna -> Trening";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Kolor tekstu</span>";
												break;
											default:
													$modul="<span style=\"color:red;\">BŁĄD</span>";
													$opcja_modul="<span style=\"color:red;\">BŁĄD</span>";
												break;
						endswitch;			
								echo '<tr class="TRESC_TAB">';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; color:black; font-weight:bold;margin-bottom:0px; margin-top:0px; text-align:left;">'.$modul.'</p></td>';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$opcja_modul.'</p></td>';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$REK_PARM[2].'</p></td>';
								echo '<td class="TRESC_TAB"><span style="float:left;margin-left:10px;color:purple; font-weight:bold;">'.$REK_PARM[4].'</span>';
								echo '<span style="float:right;margin-right:10px;"><b> [</b><a href="cel_ADMIN.php?IDW=12&IDMD=13&IDM='.$REK_PARM[1].'&IDP='.$REK_PARM[0].'&IDB=7&IDG='.$_GET["IDG"].'" style="margin:0px;" class="A_UST"> ZMIEŃ </a><b>]</b></span></td>';	
						};
						echo "</tr></table>";
						break;
//--------------------------------------------------------------------------KONIEC-USTAWIENIA-EDYCJA-ROZMIARU-TEKSTU-------------------------------------------------------------------------	
					case 8:
//--------------------------------------------------------------------------USTAWIENIA-EDYCJA-ROZMIARU-ZDJĘĆ-------------------------------------------------------------------------
						break;
//--------------------------------------------------------------------------KONIEC-USTAWIENIA-EDYCJA-ROZMIARU-ZDJĘĆ-------------------------------------------------------------------------	
					case 9:
//--------------------------------------------------------------------------USTAWIENIA-EDYCJA-KOLORU-------------------------------------------------------------------------
						break;
//--------------------------------------------------------------------------KONIEC-USTAWIENIA-EDYCJA-KOLORU-------------------------------------------------------------------------						
					case 10:
//--------------------------------------------------------------------------USTAWIENIA-EDYCJA-KOLORU-------------------------------------------------------------------------
						echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_ADMIN.php?IDW=5&IDM='.$_GET["IDM"].'">Powrót</a></p>';
						mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
						$INS_DZIEN="INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ($_SESSION[id_user],$_GET[IDM],'Uruchomiono funkcję - USTAWIENIA EDYCJA KOLORU',NOW())";
						mysqli_query($polaczenie,$INS_DZIEN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_DZIEN - ".mysqli_error()."</span></p>");
							// $GET["IDWP"] == 5 wartość domyślna do zmiany
							// &IDP id w tabeli
							// IDW=10 IDWyboru 
							if ($_SESSION['id_user']==1){
															echo "<p class=\"P_INFO\">IDS : <span class=\"S_INFO\">".$_GET["IDS"]."</span></p>";
														};
																					switch ($_GET["IDS"]):
																												case 1:
																														$modul="Artykuł";
																														$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Tytuł</span>";
																													break;
																												case 3:
																														$modul="Artykuł";
																														$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Treść</span>";
																													break;
																												case 5:
																														$modul="Nasi zawodnicy";
																														$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Imie/Nazwisko</span>";
																													break;
																												case 7:
																														$modul="Nasi zawodnicy";
																														$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Dodatkowe informacje</span>";
																													break;
																												default:
																														$modul="<span style=\"color:red;font-weight:bold;\">BŁĄD</span>";
																														$opcja_modul="<span style=\"color:red;font-weight:bold;\">BŁĄD</span>";
																													break;
																														
																					endswitch;
						echo '<p class="P_M_NAGL">Zmiana (<span style=\"color:black;font-weight:bold;\">wartości domyślnej</span>) pozycjonowania tekstu :</p>';
						echo '<form action="" method="GET" ENCTYPE="multipart/form-data" >';
						echo '<table class="GLOWNA_TAB"><tr class="NAGLOWEK_TAB">';
						echo '<td class="NAGLOWEK_TAB" width="160px"><p class="NAGLOWEK_P">Moduł : </p></td>';
						echo '<td width="220px"><p class="NAGLOWEK_P">Pole : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="220px"><p class="NAGLOWEK_P"></td></tr>';
								echo '<tr class="TRESC_TAB">';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; color:black; font-weight:bold;margin-bottom:0px; margin-top:0px; text-align:left;">'.$modul.'</p></td>';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$opcja_modul.'</p></td>';
								echo '<td class="TRESC_TAB">';
if($_GET["DEF_PARM"]!='') {
							list($poz_id,$poz_id_opcj) = explode('|', $_GET["DEF_PARM"]);
							mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SEL_POZYCJA = mysqli_query($polaczenie,"select NAZWA FROM PARM WHERE ID='$poz_id' AND ID_GROUP='$_GET[IDG]'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_POZYCJA".mysqli_error()."</span></p>");
							$col_poz=mysqli_fetch_row($SEL_POZYCJA);
							$poz_nazwa=$col_poz[0];
							$domyślny_kom=' (ustawiony)';
							} 
else {
		mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
		$SEL_P_DEF = mysqli_query($polaczenie,"SELECT ID,NAZWA,ID_OPCJ FROM PARM WHERE WSK_U=0 AND ID_GROUP='$_GET[IDG]' AND ID='$_GET[IDP]' AND SUBSTRING(WSK_D, '$_GET[IDS]', 1)='1'")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_P_DEF : ".mysqli_error()."</span></p>");
		$REK_P_DEF=mysqli_fetch_array($SEL_P_DEF);
		$poz_id=$REK_P_DEF[0];
		$poz_nazwa=$REK_P_DEF[1];
		$poz_id_opcj=$REK_P_DEF[2];		
		$domyślny_kom=' (aktualny)';
};
echo '<select name="DEF_PARM" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$poz_id.'|'.$poz_id_opcj.'" class="OPTION">'.$poz_nazwa.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
$SEL_POZ_ALL = mysqli_query($polaczenie,"select ID,NAZWA,ID_OPCJ FROM PARM WHERE WSK_U=0 AND ID_GROUP='$_GET[IDG]' AND ID!='$poz_id' order by ID");
while($r_pozycja = mysqli_fetch_array($SEL_POZ_ALL))
												{
												echo '<option value="'.$r_pozycja[0].'|'.$r_pozycja[2].'" class="OPTION">'.$r_pozycja[1].'</option>';						
												}
echo '</optgroup></select>';
						//echo '<span style="float:right;margin-right:10px;"><b> [</b><a href="domyslny_u.php?IDD='.$REK_P_DEF[0].'&IDDw='.$IDWz.'" style="margin:0px;" class="A_UST"> USTAW </a><b>]</b></span></td>';
						echo '<input type="hidden" name="IDW" value="10" />';
						echo '<input type="hidden" name="IDP" value="'.$_GET["IDP"].'" />'; // ID pierwotnego rekordu
						echo '<input type="hidden" name="IDG" value="'.$_GET["IDG"].'" />'; // IDG IDG group
						echo '<input type="hidden" name="IDS" value="'.$_GET["IDS"].'" />'; // IDS Substring
						echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />'; // ID Modułu
						echo '<input class="button" type="submit" value="USTAW" name="DEFAULT"/>';
						//echo '<span style="float:right;margin-right:10px;"><b> [</b><a href="cel_ADMIN.php?IDW=10&IDP='.$_GET["IDP"].'&IDG='.$_GET["IDG"].'&IDS='.$_GET["IDS"].'&DEF=T" style="margin:0px;" class="A_UST"> USTAW </a><b>]</b></span></td>';
						echo "</td></tr></table></form>";
							if ($_GET["DEFAULT"]!=''){
								list($poz_id,$poz_id_opcj) = explode('|', $_GET["DEF_PARM"]);
								if ($_SESSION['id_user']==1){
															echo "<p class=\"P_INFO\">IDP : <span class=\"S_INFO\">".$_GET["IDP"]."</span></p>";
															echo "<p class=\"P_INFO\">IDW : <span class=\"S_INFO\">".$_GET["IDW"]."</span></p>";
															echo "<p class=\"P_INFO\">IDG : <span class=\"S_INFO\">".$_GET["IDG"]."</span></p>";
															echo "<p class=\"P_INFO\">IDS (substring) : <span class=\"S_INFO\">".$_GET["IDS"]."</span></p>";
															echo "<p class=\"P_INFO\">Def parm (CAŁY): <span class=\"S_INFO\">".$_GET["DEF_PARM"]."</span></p>";
															echo "<p class=\"P_INFO\">Def parm (ID Parametru) :  <span class=\"S_INFO\">".$poz_id."</span></p>";
															echo "<p class=\"P_INFO\">Def parm (ID_OPCJ) :  <span class=\"S_INFO\">".$poz_id_opcj."</span></p>";
															};
								if ($poz_id==$_GET["IDP"]) {
															echo "<p class=\"P_SQL_ERR\">NIC nie zmieniono !</p>";
															} 
								else {
										mysqli_query($polaczenie,"SET AUTOCOMMIT=0");
										mysqli_query($polaczenie,"START TRANSACTION");								
										mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
										$SEL_P_OLD = mysqli_query($polaczenie,"SELECT  WSK_D FROM PARM WHERE WSK_U=0 AND ID_GROUP='$_GET[IDG]' AND ID='$_GET[IDP]'")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_P_DEF : ".mysqli_error()."</span></p>");
										$REK_P_OLD=mysqli_fetch_array($SEL_P_OLD);
										mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
										$SEL_P_NEW = mysqli_query($polaczenie,"SELECT  WSK_D FROM PARM WHERE WSK_U=0 AND ID_GROUP='$_GET[IDG]' AND ID='$poz_id'")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_P_DEF : ".mysqli_error()."</span></p>");
										$REK_P_NEW=mysqli_fetch_array($SEL_P_NEW);
										if ($_SESSION['id_user']==1){
																		echo "<p class=\"P_INFO\">ID (PRZED UPD_OLD) : <span class=\"S_INFO\">".$_GET["IDP"]."</span> OLD WSK_D : <span class=\"S_INFO\">".$REK_P_OLD[0]."</span></p>";
																		};
										//$WART_OLD=$REK_P_OLD[0];
										$poz_old=array(0,0,0,0);
										$poz_new=array(0,0,0,0);
										list($poz_old[0],$poz_old[1],$poz_old[2],$poz_old[3]) = explode('|', $REK_P_OLD[0]);
										list($poz_new[0],$poz_new[1],$poz_new[2],$poz_new[3]) = explode('|', $REK_P_NEW[0]);
										//$_GET["IDS"] 1 3 5 7 
										switch ($_GET["IDS"]):
														case 1:
															$poz_old[0]=0;
															$poz_new[0]=1;
															break;
														case 3:
															$poz_old[1]=0;
															$poz_new[1]=1;
															break;
														case 5:
															$poz_old[2]=0;
															$poz_new[2]=1;
															break;
														case 7:
															$poz_old[3]=0;
															$poz_new[3]=1;
															break;
													default:
														break;
										endswitch;
								mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
								$UPD_OLD = "UPDATE `PARM` SET `WSK_D`='$poz_old[0]|$poz_old[1]|$poz_old[2]|$poz_old[3]' WHERE ID_GROUP='$_GET[IDG]' AND `ID`='$_GET[IDP]'";
								mysqli_query($polaczenie,$UPD_OLD)  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> UPD_OLD : ".mysqli_error()."</span></p>");
								if ($_SESSION['id_user']==1){
															echo "<p class=\"P_INFO\">ID (PO UPD_OLD) : <span class=\"S_INFO\">".$_GET["IDP"]."</span> OLD WSK_D : <span class=\"S_INFO\">".$poz_old[0].'|'.$poz_old[1].'|'.$poz_old[2].'|'.$poz_old[3]."</span></p>";
															};
								mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
								$UPD_NEW = "UPDATE `PARM` SET `WSK_D`='$poz_new[0]|$poz_new[1]|$poz_new[2]|$poz_new[3]' WHERE ID_GROUP='$_GET[IDG]' AND `ID`='$poz_id' AND `ID_OPCJ`='$poz_id_opcj'";
								mysqli_query($polaczenie,$UPD_NEW)  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> UPD_NEW : ".mysqli_error()."</span></p>");
								if ($SEL_P_OLD) {
												mysqli_query($polaczenie,"COMMIT");
												}
								else { 
										mysqli_query($polaczenie,"ROLLBACK");
									};
								echo '<p class="P_BACK">Zmieniono domyślne ustawienia pozycjonowania !<br/><span class="S_BACK">Za chwilę zostaniesz przekierowany na <span class="S_BACK2">poprzednią stronę</span>.</span></p><script>
																																function init(){ setTimeout(\'document.location="cel_ADMIN.php?IDW=5&IDM=13"\', 1500);}
																																window.onload=init;
																																</script>';
								};
								
								};
						break;
//--------------------------------------------------------------------------KONIEC-USTAWIENIA-EDYCJA-KOLORU-------------------------------------------------------------------------
					case 11:
//--------------------------------------------------------------------------ZMIANA-USTAWIENIA-CSS-------------------------------------------------------------------------
							echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_ADMIN.php?IDW=6&IDM='.$_GET["IDM"].'">Powrót</a></p>';
							//mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							//$INS_DZIEN="INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ($_SESSION[id_user],$_GET[IDM],'Uruchomiono funkcję - ZMIANA USTAWIEŃ CSS',NOW())";
							//mysqli_query($polaczenie,$INS_DZIEN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_DZIEN - ".mysqli_error()."</span></p>");
							// $GET["IDWP"] == 5 wartość domyślna do zmiany
							// &IDP id w tabeli
							// IDW=10 IDWyboru 
							if ($_SESSION['id_user']==1){ echo "<p class=\"P_INFO\">IDS : <span class=\"S_INFO\">".$_GET["IDS"]."</span></p>";};
							switch ($_GET["IDS"]):
													case 1:
															$modul="Artykuł";
															$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Tytuł</span>";
														break;
													case 3:
															$modul="Artykuł";
															$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Treść</span>";
														break;
													case 5:
															$modul="Nasi zawodnicy";
															$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Imie/Nazwisko</span>";
														break;
													case 7:
															$modul="Nasi zawodnicy";
															$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Dodatkowe informacje</span>";
														break;
													default:
															$modul="<span style=\"color:red;font-weight:bold;\">BŁĄD</span>";
															$opcja_modul="<span style=\"color:red;font-weight:bold;\">BŁĄD</span>";
														break;
							endswitch;
							echo '<p class="P_M_NAGL">Zmiana formatowania tekstu :</p>';
							echo '<form action="" method="GET" ENCTYPE="multipart/form-data" >';
							echo '<table class="GLOWNA_TAB"><tr class="NAGLOWEK_TAB">';
							echo '<td class="NAGLOWEK_TAB" width="160px"><p class="NAGLOWEK_P">Moduł : </p></td>';
							echo '<td width="220px"><p class="NAGLOWEK_P">Pole : </p></td>';
							echo '<td class="NAGLOWEK_TAB" width="220px"><p class="NAGLOWEK_P">Dostępne opcje formatowania :</td></tr>';
							echo '<tr class="TRESC_TAB"><td class="TRESC_TAB"><p style="margin-left:10px; color:black; font-weight:bold;margin-bottom:0px; margin-top:0px; text-align:left;">'.$modul.'</p></td>';
							echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$opcja_modul.'</p></td>';
							echo '<td class="TRESC_TAB">';
								if(!isset($_GET["CSS_DEF"])) $CSS_DEF=TRUE; else $CSS_DEF=FALSE;
								if ($_SESSION['id_user']==1){ echo "CSS_DEF - ".$CSS_DEF."</br>"; };
								mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
								$SEL_CSS= mysqli_query($polaczenie,"select ID,ID_OPCJ,NAZWA,WART,SUBSTRING(WSK_D, '$_GET[IDS]', 1) FROM PARM WHERE WSK_U=0 AND ID_GROUP=1 AND ID_OPCJ IN (1,2,3) ORDER BY ID") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_CSS".mysqli_error()."</span></p>");
								while($REK_CSS = mysqli_fetch_array($SEL_CSS)){
														if ($CSS_DEF==FALSE) {
																				if (!isset($_GET["CSS_".$REK_CSS[0]])) {
																																$domyslny=''; 
																																$domyslny_kom='';
																																} 
																																else {
																																		$domyslny='checked="checked"'; 
																																		$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																																	};
																				}
														else if ($REK_CSS[4]=='0') {
																					$domyslny=''; 
																					$domyslny_kom='';
																					} 
														else {
																$domyslny='checked="checked"'; 
																$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(domyślna)</span>';
															};
														echo '<input type="checkbox" name="CSS_'.$REK_CSS[0].'" value="1" '.$domyslny.' class="CSS_CHBOX"  /><span class="S_CSS">'.$REK_CSS[2].'</span> '.$domyslny_kom.'<br/>';
													};
						echo '<input type="hidden" name="IDW" value="11" />';
						echo '<input type="hidden" name="CSS_DEF" value="1" />';
						echo '<input type="hidden" name="IDS" value="'.$_GET["IDS"].'" />'; // IDS Substring
						echo '<input class="button" type="submit" value="USTAW" name="DEFAULT"/>';
						echo "</td></tr></table></form>";
							if ($_GET["DEFAULT"]!=''){
								$ID_CSS=array('','','');
								if ($_SESSION['id_user']==1){
															echo "<p class=\"P_INFO\">IDW : <span class=\"S_INFO\">".$_GET["IDW"]."</span></p>";
															echo "<p class=\"P_INFO\">IDS (substring) : <span class=\"S_INFO\">".$_GET["IDS"]."</span></p>";
															};
								//if ($poz_id==$_GET["IDP"]) {
									//						echo "<p class=\"P_SQL_ERR\">NIC nie zmieniono !</p>";
									//						} 
								//else {
										mysqli_query($polaczenie,"SET AUTOCOMMIT=0");
										mysqli_query($polaczenie,"START TRANSACTION");
								//$i_ID=0;
								$UPD=FALSE;
								for ($i_DEF=4;$i_DEF<7;$i_DEF++) {
																	mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																	$SEL_P_CSS = mysqli_query($polaczenie,"SELECT  WSK_D FROM PARM WHERE WSK_U=0 AND ID_GROUP=1 AND ID='$i_DEF'")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_P_DEF : ".mysqli_error()."</span></p>");
																	$REK_P_CSS=mysqli_fetch_array($SEL_P_CSS);
										$poz_css=array(0,0,0,0);
										list($poz_css[0],$poz_css[1],$poz_css[2],$poz_css[3]) = explode('|', $REK_P_CSS[0]);
										//$_GET["IDS"] 1 3 5 7 
										if (isset($_GET["CSS_".$i_DEF])){
																			switch ($_GET["IDS"]):
																						case 1:
																							$poz_css[0]=1;
																							break;
																						case 3:
																							$poz_css[1]=1;
																							break;
																						case 5:
																							$poz_css[2]=1;
																							break;
																						case 7:
																							$poz_css[3]=1;
																							break;
																					default:
																						break;
																			endswitch;
										mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
										$UPD_CSS_T = "UPDATE `PARM` SET `WSK_D`='$poz_css[0]|$poz_css[1]|$poz_css[2]|$poz_css[3]' WHERE ID_GROUP=1 AND `ID`='$i_DEF'";
										mysqli_query($polaczenie,$UPD_CSS_T)  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> UPD_CSS_T : ".mysqli_error()."</span></p>");
										if ($_SESSION['id_user']==1){
																	echo "<p class=\"P_INFO\">TRUE - (CSS_".$i_DEF.") : <span class=\"S_INFO\">".$_GET["CSS_".$i_DEF]."</span></p>";
															};
															} else {
																	switch ($_GET["IDS"]):
																						case 1:
																							$poz_css[0]=0;
																							break;
																						case 3:
																							$poz_css[1]=0;
																							break;
																						case 5:
																							$poz_css[2]=0;
																							break;
																						case 7:
																							$poz_css[3]=0;
																							break;
																					default:
																						break;
																			endswitch;
																	mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																	$UPD_CSS_F = "UPDATE `PARM` SET `WSK_D`='$poz_css[0]|$poz_css[1]|$poz_css[2]|$poz_css[3]' WHERE ID_GROUP=1 AND `ID`='$i_DEF'";
																	mysqli_query($polaczenie,$UPD_CSS_F)  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> UPD_CSS_F : ".mysqli_error()."</span></p>");
																	if ($_SESSION['id_user']==1){
																								echo "<p class=\"P_INFO\">FALSE - (CSS_".$i_DEF.") : <span class=\"S_INFO\">".$_GET["CSS_".$i_DEF]."</span></p>";
																									};
															};
								//$i_ID++;
								$UPD=TRUE;
										};
										
								if ($UPD) {
											mysqli_query($polaczenie,"COMMIT");
											}
											else { 
													mysqli_query($polaczenie,"ROLLBACK");
													};
								echo '<p class="P_BACK">Zmieniono domyślne ustawienia formatowania tekstu !<br/><span class="S_BACK">Za chwilę zostaniesz przekierowany na <span class="S_BACK2">poprzednią stronę</span>.</span></p><script>
																																function init(){ setTimeout(\'document.location="cel_ADMIN.php?IDW=6"\', 1500);}
																																window.onload=init;
																																</script>';
								//};
								
								};
						break;
//--------------------------------------------------------------------------KONIEC-ZMIANA-USTAWIENIA-CSS-------------------------------------------------------------------------
					case 12:
//--------------------------------------------------------------------------ZMIANA-USTAWIENIA-CASE-12------------------------------------------------------------------------
							//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - ZMIANA USTAWIEŃ",$polaczenie);
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
							echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_ADMIN.php?IDM='.$_GET['IDMD'].'&IDW='.$_GET["IDB"].'&IDG='.$_GET["IDG"].'">Powrót</a></p>';
							$status_zmiana=0;
							// $GET["IDWP"] == 5 wartość domyślna do zmiany
							// &IDP id w tabeli
							// IDW=10 IDWyboru 
							if ($_SESSION['id_user']==1){
														echo "<p class=\"P_INFO\">IDM : <span class=\"S_INFO\">".$_GET["IDM"]."</span></p>";
														echo "<p class=\"P_INFO\">IDP : <span class=\"S_INFO\">".$_GET["IDP"]."</span></p>";
														echo "<p class=\"P_INFO\">IDB : <span class=\"S_INFO\">".$_GET["IDB"]."</span></p>";
														echo "<p class=\"P_INFO\">IDG : <span class=\"S_INFO\">".$_GET["IDG"]."</span></p>";
														};
							switch ($_GET["IDM"]):
												case 1:
														$modul="Strona Główna -> Trening";
														$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Nazwa grupy</span>";
														$wsk_d=1;
													break;
												case 2:
														$modul="Strona Główna -> Trening";
														$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Wiek</span>";
														$wsk_d=3;
													break;
												case 3:
													$modul="Strona Główna -> Trening";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Dzień godzina treningu</span>";
													$wsk_d=5;
												break;
											case 4:
													$modul="Strona Główna -> Trening";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Zapisy</span>";
													$wsk_d=7;
												break;
											case 5:
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Tytuł</span>";
													$wsk_d=1;
												break;
											case 6:
													$modul="Artykuł";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Treść</span>";
													$wsk_d=3;
												break;
											case 7:
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Imie/Nazwisko</span>";
												break;
											case 8:
													$modul="Nasi zawodnicy";
													$opcja_modul="<span style=\"color:purple;font-weight:bold;\">Dodatkowe informacje</span>";
												break;
											default:
												break;
							endswitch;
							if (isset($_GET["IDZ"])){
							$checked=TRUE;
							$err_dane="";
							$status_zmiana=1;
										switch ($_GET["IDG"]):
															case 4:
																	$string_exp = "/^[0-9]+$/";
																	if(!preg_match($string_exp,$_GET["DANE"])) { $err_dane='<span class="S_ERR_RED">Proszę usunąć niedozwolone znaki</span>'; $checked=FALSE;};
																	check_len($checked, $_GET["DANE"],4,$err_dane,'<span class="S_ERR_RED">Pole za długie (maksymalna ilość znaków - </span><span style="color:black;">4</span>)',1,'<span class="S_ERR_RED">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">1</span>)');
																	if ($checked==FALSE) $status_zmiana=0;
																break;
															default:
																break;
										endswitch;				
							};
							if ($status_zmiana==0){
								echo '<p class="P_M_NAGL">Zmiana '.$modul.' - '.$opcja_modul.' :</p>';
								mysqli_query($polaczenie,"SET NAMES `UTF8` COLLATE `UTF8_POLISH_CI`");
								$SEL_PARM = mysqli_query($polaczenie,"SELECT ID,N_OPCJ,NAZWA,WART FROM PARM WHERE WSK_U=0 AND ID_GROUP='$_GET[IDG]' AND ID='$_GET[IDP]'")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_PARM : ".mysqli_error()."</span></p>");
								$REK_PARM=mysqli_fetch_array($SEL_PARM);
								echo '<form action="" method="GET" ENCTYPE="multipart/form-data" >';
								echo '<table class="GLOWNA_TAB">';
								echo '<tr class="NAGLOWEK_TAB">';
								echo '<td class="NAGLOWEK_TAB" width="200px"><p class="NAGLOWEK_P">Moduł : </p></td>';
								echo '<td width="190px"><p class="NAGLOWEK_P">Pole : </p></td>';
								echo '<td class="NAGLOWEK_TAB" width="140px"><p class="NAGLOWEK_P">Opcja :</td>';
								echo '<td class="NAGLOWEK_TAB" width="270px"><p class="NAGLOWEK_P">Domyślny :</td></tr>';
								echo '<tr class="TRESC_TAB">';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; color:black; font-weight:bold;margin-bottom:0px; margin-top:0px; text-align:left;">'.$modul.'</p></td>';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$opcja_modul.'</p></td>';
								echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$REK_PARM[1].'</p></td>';
								echo '<td class="TRESC_TAB">';
								switch ($_GET["IDG"]):
											case 2:
													if(isset($_GET["WART"])) {
																				$rozmiar=$_GET["WART"];
																				$domyślny_kom=' (ustawiona)';
													} else {
															$rozmiar=$REK_PARM[3]; //Rozmiar
															$domyślny_kom=' (domyślna)';
													};
													echo '<select name="WART" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$rozmiar.'" class="OPTION">'.$rozmiar.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
													for( $i = 12; $i<33;) {
																			if ($rozmiar!=$i) echo '<option value="'.$i.'" class="OPTION">'.$i.'</option>';
																			$i=$i+2; 
													};
													echo '</optgroup></select>';
												break;
											case 3:
													if($_GET["WART"]!='') {
																				list($kolor_id,$kolor_hex,$kolor_nazwa) = explode('|', $_GET["WART"]);
																				//mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																				//$SEL_COL = mysqli_query($polaczenie,"select NAZWA from COLOR WHERE ID='$kolor_id' AND WSK_U=0") or die or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_COL".mysqli_error()."</span></p>");
																				//$col=mysqli_fetch_row($SEL_COL);
																				//$kolor_nazwa=$col[0];
																				if ($kolor_hex=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
																				$domyślny_kom=' (ustawiony)';
																				echo $kolor_id.' '.$kolor_nazwa.' '.$kolor_hex.'</br>';
																				echo $_GET["WART"];
													} else {
															mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
															$SEL_DOM_COL = mysqli_query($polaczenie,"select ID,NAZWA,WART FROM PARM WHERE WSK_D=1 AND ID_GROUP='$_GET[IDG]' AND ID='$_GET[IDP]'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_DOM_COL".mysqli_error()."</span></p>");
															$DOM_COL=mysqli_fetch_row($SEL_DOM_COL);
																									$kolor_id=$DOM_COL[0];
																									$kolor_nazwa=$DOM_COL[1];
																									$kolor_hex=	$DOM_COL[2];
															if ($DOM_COL[2]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
															$domyślny_kom=' (domyślny)';
															echo $kolor_id.' '.$kolor_nazwa.' '.$kolor_hex.'</br>';
															echo $_GET["WART"];
															};
													echo '<select name="WART" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$kolor_id.'|'.$kolor_hex.'|'.$kolor_nazwa.'" style="font-weight:bold; color:'.$kolor_font.';background: none repeat scroll 0%  0% '.$kolor_hex.';">'.$kolor_nazwa.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
													mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
													$wyswietl = mysqli_query($polaczenie,"select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 AND HEX!='$kolor_hex' ORDER BY ID");
													while($r_kolor = mysqli_fetch_array($wyswietl))
																									{
																									if ($r_kolor[2]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
																									echo '<option value="'.$r_kolor[0].'|'.$r_kolor[2].'|'.$r_kolor[1].'" style="color:'.$kolor_font.'; background: none repeat scroll 0%  0% '.$r_kolor[2].';">'.$r_kolor[1].'</option>';
																									}

													echo '</optgroup></select>';
												break;
											case 4:
													echo '<input type="text" name="DANE" class="TEXTAREA" value="'; if (isset($_GET["DANE"])) echo $_GET["DANE"]; echo '">';
												//							echo $_GET["WART"]; echo '">'
													//echo '<textarea name="DANE" rows="1" cols="10" class="TEXTAREA">'; // ROZMIAR ZDJECIE
													//if (isset($_GET["DANE"])) { 
												//							echo $_GET["WART"];
													//} else { 
												//			echo $_GET["DANE"]; 
												//	};										
													//echo '</textarea>';
												break;
											default:
												break;
							endswitch;
							echo '<input class="button" type="submit" value="USTAW" name="IDZ"/>';
							echo "</td></tr></table>";
							echo '<input type="hidden" name="IDMD" value="'.$_GET["IDMD"].'" />';
							echo '<input type="hidden" name="IDW" value="'.$_GET["IDW"].'" />';
							echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
							echo '<input type="hidden" name="IDP" value="'.$_GET["IDP"].'" />';
							echo '<input type="hidden" name="IDG" value="'.$_GET["IDG"].'" />'; // IDS Substring
							echo '<input type="hidden" name="IDB" value="'.$_GET["IDB"].'" />'; // IDS Substring
							echo "</form>";
							echo "</br>".$err_dane;
							} else {
								if ($_SESSION['id_user']==1){
															echo "<p class=\"P_INFO\">IDP : <span class=\"S_INFO\">".$_GET["IDP"]."</span></p>";
															};
								$UPD=FALSE;
								mysqli_query($polaczenie,"SET AUTOCOMMIT=0");
								mysqli_query($polaczenie,"START TRANSACTION");
								mysqli_query($polaczenie,"SET NAMES `UTF8` COLLATE `UTF8_POLISH_CI`");
								switch ($_GET["IDG"]):
														case 2:
																$UPD_PARM_2 = "UPDATE `PARM` SET `WART`='$_GET[WART]' WHERE `ID_GROUP`='$_GET[IDG]' AND `ID`='$_GET[IDP]'";
																mysqli_query($polaczenie,$UPD_PARM_2) or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> UPD_PARM_2 : ".mysqli_error()."</span></p>");
																$UPD=TRUE;
															break;
														case 3:
																list($kolor_id,$kolor_hex,$kolor_nazwa) = explode('|', $_GET["WART"]);
																$UPD_PARM_3="UPDATE `PARM` SET `WART`='$kolor_hex', `NAZWA`='$kolor_nazwa' WHERE `ID_GROUP`='$_GET[IDG]' AND `ID`='$_GET[IDP]'";
																mysqli_query($polaczenie,$UPD_PARM_3) or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> UPD_PARM_3 : ".mysqli_error()."</span></p>");
																$UPD=TRUE;
															break;
														case 4:
																$UPD_PARM_4 = "UPDATE `PARM` SET `WART`='$_GET[DANE]' WHERE `ID_GROUP`='$_GET[IDG]' AND `ID`='$_GET[IDP]'";
																mysqli_query($polaczenie,$UPD_PARM_4) or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> UPD_PARM_4 : ".mysqli_error()."</span></p>");
																$UPD=TRUE;
															break;
														default:
															break;
								endswitch;			
								if ($UPD) {
											mysqli_query($polaczenie,"COMMIT");
											}
											else { 
													mysqli_query($polaczenie,"ROLLBACK");
													};
								if ($_SESSION['id_user']==1){
														echo "<p class=\"P_INFO\">IDM : <span class=\"S_INFO\">".$_GET["IDM"]."</span></p>";
														echo "<p class=\"P_INFO\">IDP : <span class=\"S_INFO\">".$_GET["IDP"]."</span></p>";
														echo "<p class=\"P_INFO\">IDB : <span class=\"S_INFO\">".$_GET["IDB"]."</span></p>";
														}; 
								echo '<p class="P_BACK">Zmieniono domyślne ustawienia formatowania tekstu !<br/><span class="S_BACK">Za chwilę zostaniesz przekierowany na <span class="S_BACK2">poprzednią stronę</span>.</span></p><script>
																																function init(){ setTimeout(\'document.location=\"cel_ADMIN.php?IDW=7&IDMD='.$_GET["IDMD"].'&IDM='.$_GET["IDM"].'&IDG='.$_GET["IDG"].'"\', 1500);}
																																window.onload=init;
																																</script>';
								
								
								};
						break;
//--------------------------------------------------------------------------ZMIANA-CASE-12------------------------------------------------------------------------
					case 13:
//--------------------------------------------------------------------------ZMIANA-UZYTKOWNICY-USUNIECIE-CASE-13------------------------------------------------------------------------
							echo '<a class="A_BACK" href="cel_ADMIN.php?IDW=1&IDM='.$_GET['IDM'].'"><p class="P_HREF_BACK">Anuluj</p></a>';
							//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - USUŃ użytkownik",$polaczenie);
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
							echo '<p class="P_M_NAGL">Usuwanie użytkownika : </p>';
							if ($TAB_PRAW["Usuń"]['VAL']==1){
							$USN=FALSE;
							if ($_SESSION['id_user']==1){
														echo '<p class="P_INFO"> IDW = <span class="S_INFO">'.$_GET['IDW'].'</span></br>';	// $_GET['IDW'] IDW wybór funkcji
														echo ' IDU =  <span class="S_INFO">'.$_GET['IDU'].'</span></br>'; 					// $_GET['IDU'] ID zawodnika du usniecia
														echo ' USN =  <span class="S_INFO">'.$_GET['USN'].'</span></br>'; 				// $_GET['USN'] Usunac ? T/N
														echo ' IDM =  <span class="S_INFO">'.$_GET['IDM'].'</span></p>'; 				// $_GET['IDM'] ID modułu
														};
							if (isset($_GET['IDU'])){
													echo '<p class="P_NG_INF">Napewno chcesz usunąć użytkownika nr <span class="S_MAIN_NG">[</span>'.$_GET['IDU'].'<span class="S_MAIN_NG">]</span> ?</p>';
													echo '<div class="DIV_OPCJ">';
													echo '<p class="P_POZ_BUT"><a href="cel_ADMIN.php?IDW=1&IDM='.$_GET["IDM"].'"><span class="s_button">NIE</span></a>';
													echo '<a href="cel_ADMIN.php?IDW='.$_GET["IDW"].'&IDM='.$_GET["IDM"].'&IDU='.$_GET["IDU"].'&USN=T"><span class="s_button">TAK</span></a>';
													echo '</p></div>';
													if (isset($_GET["USN"])){
																			$USN=TRUE;
													};
							} 
							else {					
									echo '<div class="DIV_OPCJ">';
									echo '<form Name="USUN" action="" method="POST" >';
									echo '<p class="P_NG_INF">Podaj numer ID użytkownika : ';
									echo '<input type="text" name="IDU" size="5" value="'.$_POST['id'].'" maxlength="5" /></p>';
									echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
									echo '<input class="button" type="submit" value="Usuń" name="USUN"/></form>';
									echo '</div>';
							if(isset($_POST['USUN']))
							{
								if (($_POST['IDU']!=''))
								{
									mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
									$SEL_NEWS = mysqli_query($polaczenie,"select ID from PERS where ID='$_POST[IDU]'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_NEWS - ".mysqli_error()."</span></p>");
									$istnieje_rekord = mysqli_num_rows($SEL_NEWS);
								if ($istnieje_rekord == 0) echo '<p style="color:red">Nie znaleziono żadnego użytkownika o wpisanym <span style="color:black;">ID</span>!</p>';
								else {
														mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
														$query = mysqli_query($polaczenie,"select ID from PERS where ID='$_POST[IDU]' AND WSK_U=1") or die(mysqli_error());
														$istnieje_rekord = mysqli_num_rows($query);
														if ($_SESSION['id_user']==1) echo "Status UŻYTKOWNIKA usunięty ? - ".$istnieje_rekord."<br/>";
								if ($istnieje_rekord==0){				
														$USN=TRUE;
														}
									else echo '<p style="color:red">Istnieje użytkownik o podanym <span style="color:black;">ID</span> ale został ono już prędzej usunięty !</p>';
									}
								} else echo '<p style="color:red;">Nie podałeś <span style="color:black;">ID</span> użytkownika !</p>';
							}
							};
							if($USN==TRUE){
											if($_POST["IDU"]!='') $ID_USN=$_POST["IDU"]; else if ($_GET["IDU"]!='')  $ID_USN=$_GET["IDU"];
											$UPD_USN = "UPDATE `PERS` SET `WSK_U`='1',`DAT_USN`=NOW() WHERE `PERS`.`ID`=$ID_USN";
											mysqli_query($polaczenie,$UPD_USN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">UPD_USN_POST - ".mysqli_error()."</span></p>");
											//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
											INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"USUŃ UŻYTKOWNIK - usunięto ID : ".$ID_USN,$polaczenie);
											//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------	
											echo '<p class="P_BACK">Twój użytkownik został usunięty !<br/><span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span><span class="S_BACK2">MENU - Użytkownicy.</span></p><script>
																																function init(){ setTimeout(\'document.location="cel_ADMIN.php?IDW=1&IDM='.$_GET['IDM'].'"\', 500);}
																																window.onload=init;
																																</script>';
							};
							} else { echo '<p class="P_ERR">BRAK uprawnienia - Usuń użytkownika </p>';};
//--------------------------------------------------------------------------KONIEC-ZMIANA-UZYTKOWNICY-USUNIECIE-CASE-13------------------------------------------------------------------------		
						break;
					case 14: /*DIARY*/
                                                require(DR.'/pod_strony/ADMIN/v_admin_dziennik.php');
						break;
					case 15:
//--------------------------------------------------------------------------USTAWIENIE-WIDOCZNOŚCI-CASE-15------------------------------------------------------------------------						
							echo '<a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'"><p class="P_HREF_BACK">Anuluj</p></a>';
							echo '<p class="P_M_NAGL">Zmiana statusu widoczności użytkownika : </p>';
							//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - AKTYWNOŚĆ użytkownika o ID : ".$_GET["ID"],$polaczenie);
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
							if ($TAB_PRAW["ZMIEŃ"]['VAL']==1){
							$AKTWNY="<span style=\"color:blue;font-weight:bold;\"> AKTYWNY </span>";
							$NIEAKTYWNY="<span style=\"color:red;font-weight:bold;\"> NIEAKTYWNY</span>";
							if ($_GET["WSKV"]==1){
												$STAT_KOM =$AKTWNY;
							} 
							else {
									$STAT_KOM=$NIEAKTYWNY;
							};
							if($_SESSION['id_user']==1) echo '<p style="text-align:left;margin-left:20px;">ID użytkownika - '.$_GET["ID"].'</p>';
							echo '<p class="P_NG_INF">Użytkownik o nr ID <span class="S_MAIN_NG">[</span>'.$_GET['ID'].'<span class="S_MAIN_NG">]</span> ?</p>';
							echo '<div class="DIV_OPCJ">';
							echo '<p class="P_POZ_BUT"><a href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'"><span class="s_button">NIE</span></a>';
							echo '<a href="cel_ADMIN.php?IDW=15&IDB='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'&ID='.$_GET["ID"].'&WSKV='.$_GET["WSKV"].'&UPDV=T"><span class="s_button">TAK</span></a>';
							echo '</p></div>';
							echo '<p style="font-size:14px;text-align:left;margin-left:20px;">(Aktualny status - '.$STAT_KOM.')</p>';
							if (isset($_GET["UPDV"])){
														if ($_GET["WSKV"]==0) {
																					$WIDOK=1;
																					$STAT_KOM =$AKTWNY;
														} 
														else {
																$WIDOK=0;
																$STAT_KOM=$NIEAKTYWNY;
														};
							mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$UPD_WSKV = "UPDATE `PERS` SET `WSK_V`=$WIDOK WHERE `PERS`.`ID`=$_GET[ID]";
							if(mysqli_query($polaczenie,$UPD_WSKV))
							{
								mysqli_query($polaczenie,"COMMIT");
							} 
							else
							{
								"<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">UPD_WSKV - ".mysqli_error()."</span></p>";
								mysqli_query($polaczenie,"ROLLBACK");
							};
							echo '<p class="P_BACK">Status twojego użytkownika został zmieniony na - '.$STAT_KOM.'<br/>';
							echo '<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
							echo '<a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'">MENU - Użytkownicy.</a></p>';
							echo '<script>function init(){ setTimeout(\'document.location="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'"\', 1500);}window.onload=init;</script>';
							};
							} else { echo '<p class="P_ERR">BRAK uprawnienia - Zmiana aktywności użytkownika </p>';};
//--------------------------------------------------------------------------KONIEC-USTAWIENIE-WIDOCZNOŚCI-CASE-15------------------------------------------------------------------------						
						break;
					case 16:
//--------------------------------------------------------------------------DODAJ-UZYTKOWNIK-------CASE-16------------------------------------------------------------------------						
						echo '<a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'"><p class="P_HREF_BACK">Anuluj</p></a>';
						//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
						INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - DODAJ użytkownika",$polaczenie);
						//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
						if ($TAB_PRAW["Dodaj"]['VAL']==1){
																	$status_dodaj==0;
																	if (isset($_POST["NEW_USER"])) { // Sprawdz, czy wcisnieto przycisk DODAJ
																		// USTAW poczatkowe parametry 
																		$checked=TRUE; 
																		$blad=FALSE;
																		$admin_info="";
																		$err = array ("Login"=>"", "Hasło"=>"", "Imię"=>"", "Nazwisko"=>"", "Ulica"=>"", "Kod-Pocztowy"=>"", "Miasto"=>"");
																	foreach ($_POST as $klucz => $wartosc) { // SPRAWDZ i ustaw warunki dla wszystkich elemntow tablicy POST
																	if ($_SESSION["id_user"]==1){
																	$admin_info=$admin_info."Wartosc [".$klucz."] :  ".$wartosc."</br>";
																	};
																							$ch_min=3;
																							$ch_max=20;
																							switch ($klucz):
																											case "Login" :
																													$ch_min=6;
																													//echo $klucz." - ".$wartosc." ".$ch_min."/".$ch_max."</br>";
																												break;
																											case "Hasło";
																													$ch_min=8;
																													//echo $klucz." - ".$wartosc." ".$ch_min."/".$ch_max."</br>";
																												break;
																											case "Imię":
																											case "Nazwisko";
																											case "Ulica":
																											case "Miasto";
																													$ch_max=30;
																													//echo $klucz." - ".$wartosc." ".$ch_min."/".$ch_max."</br>";
																												break;
																											case "Kod-Pocztowy";
																													$ch_max=6;
																													$ch_min=6;
																													//echo $klucz." - ".$wartosc." ".$ch_min."/".$ch_max."</br>";
																												break;
																											default:
																												break;
																							endswitch;
																							if ($klucz=="Hasło"){ // URUCHOM dodatkowa funkcje sprawdzajaca pole HASLO
																									list($checked, $err[$klucz]) = sprawdz_haslo($wartosc,$klucz,$ch_min,$ch_max);
																							} else { 
																								list($checked, $err[$klucz]) = sprawdz_napis($wartosc,$klucz,$ch_min,$ch_max);
																								if (($checked==TRUE) &&($klucz=="Imię" || $klucz=="Nazwisko" || $klucz=="Miasto" || $klucz=="Ulica") ) {
																													list($new_napis, $err[$klucz]) = duza_litera($wartosc,$klucz,$ch_min); // Ustaw duza pierwsza litere
																													$_POST[$klucz]=$new_napis;
																								};
																							};
																	if ($checked==FALSE) $blad=TRUE;									
																	};
																	if ($_SESSION["id_user"]==1){ echo $admin_info; };
						//----------------------------------------------------------------SPRAWDZ-LOGIN---------------------------------
						if ($_POST["Login"]!=""){	
												if ($_SESSION["id_user"]==1){
																				echo "Klucz : ".$_POST["Login"]."</br>";
												};
												mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
												$SEL_LOGIN_USER = mysqli_query($polaczenie,"SELECT UCASE(NAZWA) FROM PERS")
												or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_LOGIN_USER : ".mysqli_error()."</span></p>");
												while($REK_LOGIN_USER = mysqli_fetch_array($SEL_LOGIN_USER)){
																					$str = strtoupper($_POST["Login"]);
																					echo "REK_LOGIN_USER".$REK_LOGIN_USER[0]." | Login : ".$_POST["Login"]."</br>";
																					IF($REK_LOGIN_USER[0]==$str) {$blad=TRUE;
																					$err["Login"]='<span class="S_ERR_RED">Istnieje już użytkownik z takim loginem !</span>'; 
																					};
						};
						};
						//----------------------------------------------------------------KONIEC-SPRAWDZ-LOGIN--------------------------								
						if ($blad==TRUE) $status_dodaj=0; else $status_dodaj=1;;
						};
						if ($status_dodaj==0){
						echo '<p CLASS="P_MAIN_CEL">Dodajesz nowego użytkownika : </p>';
						echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
						echo '<div class="DIV_DODAJ">';
						echo '<table CLASS="TAB_NEW_USER">';
						$gwiazdka="<span class=\"S_NG_DANE\">*</span>";
						$typ_input="text";
						$TAB_DANE = array ("Login"=>"", "Hasło"=>"", "Imię"=>"", "Nazwisko"=>"", "Ulica"=>"", "Kod-Pocztowy"=>"", "Miasto"=>"");
							//for ($tr=0;$tr<7;$tr++){
							foreach ($TAB_DANE as $klucz => $wartosc) {
							if ($klucz=="Login" || $klucz=="Hasło" || $klucz=="Imię" || $klucz=="Nazwisko"){
								$naglowek=$gwiazdka.$klucz;
							}
							else {
								$naglowek=$klucz;
							};
							//if ($klucz=="Hasło") $typ_input="password"; else $typ_input="text";
							echo '<tr height="0px"><td class="TD_L">';
							echo '<p class="NG_DANE_2">'.$naglowek." :</p></td>";
							echo '<td class="TD_R"> ';
							echo '<input type="'.$typ_input.'" name="'.$klucz.'" class="INPUT_TXT" value="';
							if (isset($_POST[$klucz])) echo $_POST[$klucz];									
							echo '"> '.$err[$klucz].'</td></tr>';
							};
							echo '</table>';
							echo '</div>';
							echo '<input class="button" type="submit" value="Dodaj" name="NEW_USER"></FORM>';
							//---------------------------------------------------------------LEGENDA----------------------------
							echo '<p class="P_LEG">Legenda :</p><p class="P_LEG_INFO">';
							echo '- pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane;<br/>';
							echo '- LOGIN,IMIĘ,NAZWISKO musi zawierać min (<span class="S_LEG_INFO">3</span>) znaki; <br/> ';
							echo '- TYTUŁ może zawierać max (<span class="S_LEG_INFO">200</span>) znaków;<br/> ';
							echo '- TREŚĆ musi zawierać min (<span class="S_LEG_INFO">5</span>) znaków;<br/> ';
							echo '- TREŚĆ może zawierać max (<span class="S_LEG_INFO">2000</span>) znaków;<br/> ';
							echo '- ZDJĘCIA i FILM nie jest (<span class="S_LEG_INFO">wymagany</span>);<br/> ';
							echo '- ZDJĘCIA, dozwolony TYP : (<span class="S_LEG_INFO">JPG JPEG PNG BMP GIF</span>);<br/> ';
							//---------------------------------------------------------------KONIEC-LEGENDA----------------------------
							echo '</p></center>';
							}
							else if ($status_dodaj==1){
							$kod=$_POST["Kod-Pocztowy"];
							$md5=md5($_POST["Hasło"]);
							echo '<p class="P_MAIN">Twój użytkownik został dodany!</p>';
							mysqli_query($polaczenie,"SET AUTOCOMMIT=0");
							mysqli_query($polaczenie,"START TRANSACTION");	
							mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$INS_NEW_USER="INSERT INTO PERS (NAZWA,HASLO,IMIE,NAZWISKO,ULICA,KOD,MIASTO,DAT_UTW) VALUES ('$_POST[Login]','$md5','$_POST[Imię]','$_POST[Nazwisko]','$_POST[Ulica]','$kod','$_POST[Miasto]',NOW())";
							mysqli_query($polaczenie,$INS_NEW_USER) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_NEW_USER".mysqli_error()."</span></p>"); 		
							if ($INS_NEW_USER) {
												mysqli_query($polaczenie,"COMMIT");
												//$INS_MYSQL=TRUE;
							}
							else{
									//Delete($katalog);
									//$INS_MYSQL=FALSE;
									mysqli_query($polaczenie,"ROLLBACK");
							};
							foreach ($_POST as $key => $value) { UNSET($_POST[$key]);};
									if ($_SESSION['id_user']==1){
																echo "POST : ".$_POST[$key].$_POST[$value]."</br>";
									};
							echo '<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span><span class="S_BACK2">MENU - Użytkownicy.</span></p><script>
																																function init(){ setTimeout(\'document.location="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'"\', 1500);}
																																window.onload=init;
																																</script>';
							};
							} else { echo '<p class="P_ERR">BRAK uprawnienia - Edycja danych użytkownika </p>';};
//--------------------------------------------------------------------------KONIEC-DODAJ-UZYTKOWNIK-CASE-16-----------------------------------------------------------------------						
						break;
					case 17:
//--------------------------------------------------------------------------EDYTUJ-UZYTKOWNIK-CASE-17-----------------------------------------------------------------------------	
							echo '<a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET['IDM'].'"><p class="P_HREF_BACK">Anuluj</p></a>';
							echo '<p class="P_M_NAGL">Edycja użytkownika : </p>';
							//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - EDYTUJ użytkownik",$polaczenie);
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
							//$POST_EDIT=FALSE;
							if ($TAB_PRAW["Edytuj"]['VAL']==1){
							$EDIT=FALSE;
							if (isset($_POST["EDIT_USER"])) {
														$checked=TRUE;
														$err = array ("Login"=>"", "Hasło"=>"", "Imię"=>"", "Nazwisko"=>"", "Ulica"=>"", "Kod-Pocztowy"=>"", "Miasto"=>"");
														foreach ($_POST as $klucz => $wartosc) {
																							echo "Wartosc [".$klucz."] :  ".$wartosc."</br>";
																							$ch_min=2;
																							$ch_max=20;
																							switch ($klucz):
																											case "Login" :
																													$ch_min=6;
																													echo $klucz." - ".$wartosc." ".$ch_min."/".$ch_max."</br>";
																												break;
																											case "Hasło";
																													$ch_min=8;
																													echo $klucz." - ".$wartosc." ".$ch_min."/".$ch_max."</br>";
																												break;
																											case "Imię":
																											case "Nazwisko";
																											case "Ulica":
																											case "Miasto";
																													$ch_max=30;
																													echo $klucz." - ".$wartosc." ".$ch_min."/".$ch_max."</br>";
																												break;
																											case "Kod-Pocztowy";
																													$ch_max=6;
																													$ch_min=6;
																													echo $klucz." - ".$wartosc." ".$ch_min."/".$ch_max."</br>";
																												break;
																											default:
																												break;
																							endswitch;
																							if ($klucz=="Hasło"){
																									list($checked, $err[$klucz]) = sprawdz_haslo($wartosc,$klucz,$ch_min,$ch_max);
																							} else {
																								list($checked, $err[$klucz]) = sprawdz_napis($wartosc,$klucz,$ch_min,$ch_max);
																								if (($checked==TRUE) &&($klucz=="Imię" || $klucz=="Nazwisko" || $klucz=="Miasto" || $klucz=="Ulica") ) {
																													list($new_napis, $err[$klucz]) = duza_litera($wartosc,$klucz,$ch_min);
																													$_POST[$klucz]=$new_napis;
																								};
																							};
														if ($checked==FALSE) $blad=TRUE;									
														};
						//----------------------------------------------------------------SPRAWDZ-LOGIN---------------------------------
						if ($_POST["Login"]!=""){	
												echo "Klucz : ".$_POST["Login"]."</br>";
												echo "ID : ".$_POST["ID"]."</br>";
												mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
												$SEL_LOGIN_USER = mysqli_query($polaczenie,"SELECT UCASE(NAZWA) FROM PERS WHERE ID!='$_POST[ID]'")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_LOGIN_USER : ".mysqli_error()."</span></p>");
												while($REK_LOGIN_USER = mysqli_fetch_array($SEL_LOGIN_USER)){
																					$str = strtoupper($_POST["Login"]);
																					echo "REK_LOGIN_USER : ".$REK_LOGIN_USER[0]." | Login : ".$_POST["Login"]."</br>";
																					IF($REK_LOGIN_USER[0]==$str) {
																												$checked=FALSE;
																												echo "checked : ".$checked."</br>";
																												$err["Login"]='<span class="S_ERR_RED">Istnieje już użytkownik z takim loginem !</span>'; 
																					};
												};
						};
						//----------------------------------------------------------------KONIEC-SPRAWDZ-LOGIN--------------------------								
						echo "EDIT : ".$EDIT."</br>";
						if ($checked==FALSE) $EDIT=FALSE; else $EDIT=TRUE;
						};
							if ($_SESSION['id_user']==1){
														echo '<p class="P_INFO"> IDW = <span class="S_INFO">'.$_GET['IDW'].'</span></br>';	// $_GET['IDW'] IDW wybór funkcji
														echo ' IDE =  <span class="S_INFO">'.$_GET['IDE'].'</span></br>'; 					// $_GET['IDU'] ID zawodnika du usniecia
														echo ' IDB =  <span class="S_INFO">'.$_GET['IDB'].'</span></br>'; 				// $_GET['USN'] Usunac ? T/N
														echo ' IDM =  <span class="S_INFO">'.$_GET['IDM'].'</span></p>'; 				// $_GET['IDM'] ID modułu
														};
							
							echo '<div class="DIV_OPCJ">';
							if ((isset($_GET["IDE"])) && ($EDIT==FALSE)){
																		mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																		$SEL_EDIT_USER = mysqli_query($polaczenie,"select NAZWA,IMIE,NAZWISKO,ULICA,KOD,MIASTO,ID FROM PERS where ID='$_GET[IDE]'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_USER - ".mysqli_error()."</span></p>");
																		$REK_USER = mysqli_fetch_array($SEL_EDIT_USER);
																		echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
																		echo '<div class="DIV_DODAJ">';
																		echo '<table CLASS="TAB_NEW_USER">';
																		$gwiazdka="<span class=\"S_NG_DANE\">*</span>";
																		$typ_input="text";
																		$i=0;
																		$TAB_DANE = array ("Login"=>"","Imię"=>"", "Nazwisko"=>"", "Ulica"=>"", "Kod-Pocztowy"=>"", "Miasto"=>"");
																			//for ($tr=0;$tr<7;$tr++){
																			foreach ($TAB_DANE as $klucz => $wartosc) {
																			if ($klucz=="Login" || $klucz=="Hasło" || $klucz=="Imię" || $klucz=="Nazwisko"){
																				$naglowek=$gwiazdka.$klucz;
																			}
																			else {
																				$naglowek=$klucz;
																			};
																			//if ($klucz=="Hasło") $typ_input="password"; else $typ_input="text";
																			echo '<tr height="0px"><td class="TD_L">';
																			echo '<p class="NG_DANE_2">'.$naglowek." :</p></td>";
																			echo '<td class="TD_R"> ';
																			echo '<input type="'.$typ_input.'" name="'.$klucz.'" class="INPUT_TXT" value="';
																			if (isset($_POST[$klucz])) echo $_POST[$klucz];	else echo $REK_USER[$i];								
																			echo '"> '.$err[$klucz].'</td></tr>';
																			$i++;
																			};
																			echo '</table>';
																			echo '</div>';
																			echo '<input type="hidden" name="ID" value="'.$_GET["IDE"].'" />';
																			echo '<input class="button" type="submit" value="Edytuj" name="EDIT_USER"></FORM>';
							}
							else if((!isset($_GET["IDE"])) && ($EDIT==FALSE)) {	
														echo '<form Name="EDYTUJ" action="" method="POST" >';
														echo '<p class="P_NG_INF">Podaj numer ID użytkownika : ';
														echo '<input type="text" name="IDE" size="5" value="'.$_GET["IDE"].'" maxlength="5" /></p>';
														echo '<input type="hidden" name="IDW" value="'.$_GET["IDW"].'" />';
														echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
														echo '<input type="hidden" name="IDB" value="'.$_GET["IDB"].'" />';								
														echo '<input class="button" type="submit" value="Edytuj" name="EDYTUJ"/></form>';
														
							if(isset($_POST["EDYTUJ"]))
							{
								if (($_POST["IDE"]!=''))
								{
									mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
									$SEL_USER = mysqli_query($polaczenie,"select ID from PERS where ID='$_POST[IDE]'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_USER - ".mysqli_error()."</span></p>");
									$istnieje_rekord = mysqli_num_rows($SEL_USER);
								if ($istnieje_rekord == 0) echo '<p style="color:red">Nie znaleziono żadnego użytkownika o wpisanym <span style="color:black;">ID</span>!</p>';
								else {
														mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
														$SEL_USER_USN = mysqli_query($polaczenie,"select ID from PERS where ID='$_POST[IDE]' AND WSK_U=1")  or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_USER_USN - ".mysqli_error()."</span></p>");
														$istnieje_rekord = mysqli_num_rows($SEL_USER_USN);
														if ($_SESSION['id_user']==1) echo "Status UŻYTKOWNIKA usunięty ? - ".$istnieje_rekord."<br/>";
								if ($istnieje_rekord==0){
														//$POST_EDIT=TRUE;
														echo '<script>function init(){ setTimeout(\'document.location="cel_ADMIN.php?IDW='.$_POST["IDW"].'&IDB='.$_POST["IDB"].'&IDE='.$_POST["IDE"].'&IDM='.$_POST['IDM'].'"\', 500);}
															window.onload=init;</script>';
														}
									else echo '<p style="color:red">Istnieje użytkownik o podanym <span style="color:black;">ID</span> ale został ono już prędzej usunięty !</p>';
									}
								} else echo '<p style="color:red;">Nie podałeś <span style="color:black;">ID</span> użytkownika !</p>';
							}
							} else if ($EDIT==TRUE){
									//echo "Uakrualniles zawodnika !";
											$kod=$_POST["Kod-Pocztowy"];
											$login=trim($_POST["Login"]);
											$UPD_USER = "UPDATE `PERS` SET `NAZWA`='$login',`IMIE`='$_POST[Imię]',`NAZWISKO`='$_POST[Nazwisko]',`ULICA`='$_POST[Ulica]',`KOD`='$kod',`MIASTO`='$_POST[Miasto]',`WSK_K`=WSK_K+1,`DAT_KOR`=NOW() WHERE `PERS`.`ID`=$_GET[IDE]";
											mysqli_query($polaczenie,$UPD_USER) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">UPD_USER - ".mysqli_error()."</span></p>");
											//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
											INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"EDYTUJ UŻYTKOWNIK - uaktualniono ID : ".$_GET["IDE"],$polaczenie);
											//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
									echo '<p class="P_BACK">Uaktualniono użytkownika !<br/><span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
									echo '<a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'">MENU - Użytkownicy.</a></p><script>';
									echo 'function init(){ setTimeout(\'document.location="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'"\', 1500);}window.onload=init;</script>';
							};
							echo '</div>';
							} else { echo '<p class="P_ERR">BRAK uprawnienia - Edycja danych użytkownika </p>';};
//--------------------------------------------------------------------------KONIEC-EDYTUJ-UZYTKOWNIK-CASE17-----------------------------------------------------------------------											
						break;
					case 18:
//--------------------------------------------------------------------------WIĘCEJ-DANE-UZYTKOWNIK-CASE-18------------------------------------------------------------------------											
							//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - WIĘCEJ użytkownik",$polaczenie);
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
							echo '<a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET['IDM'].'"><p class="P_HREF_BACK">Powrót</p></a>';
							echo '<p class="P_M_NAGL">Dane użytkownika o nr ID [ '.$_GET["ID"].' ] </p>';
							if ($TAB_PRAW["WIĘCEJ"]['VAL']==1){
							echo '<div class="DIV_DODAJ">';
							echo '<table CLASS="TAB_NEW_USER">';
							$i=0;
							mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SEL_EDIT_USER = mysqli_query($polaczenie,"select NAZWA,IMIE,NAZWISKO,ULICA,KOD,MIASTO FROM PERS where ID='$_GET[ID]'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_USER - ".mysqli_error()."</span></p>");
							$REK_USER = mysqli_fetch_array($SEL_EDIT_USER);
							$TAB_DANE = array ("Login"=>"","Imię"=>"", "Nazwisko"=>"", "Ulica"=>"", "Kod-Pocztowy"=>"", "Miasto"=>"");
																		
							foreach ($TAB_DANE as $klucz => $wartosc) {
																		echo '<tr height="0px"><td class="TD_L">';
																		echo '<p class="NG_DANE_2">'.$klucz." :</p></td>";
																		echo '<td class="TD_R"> '.$REK_USER[$i].'</td></tr>';
							$i++;
							};
							echo '</table>';
							echo '</div>';
							} else { echo '<p class="P_ERR">BRAK uprawnienia - Wyświetl dane użytkownika </p>';};
//--------------------------------------------------------------------------WIĘCEJ-DANE-UZYTKOWNIK-CASE-18------------------------------------------------------------------------																			
						break;
					case 19:
//--------------------------------------------------------------------------HASŁO-UZYTKOWNIK-CASE-19------------------------------------------------------------------------------
							//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - HASŁO użytkownik",$polaczenie);
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
							echo '<a class="A_BACK" href="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET['IDM'].'"><p class="P_HREF_BACK">Anuluj</p></a>';
							
							if ($TAB_PRAW["HASŁO"]['VAL']==1)
							{
								include("../_funkcje_/sprawdz_XML.php");
								if ($_GET["ID"]!="") $ID=$_GET["ID"]; 
								//else if ($_POST["ID"]!="") $ID=$_POST["ID"]; 
							else echo "Błąd skontaktuj się z Administratorem</br>";
							mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SEL_PASS_USER = mysqli_query($polaczenie,"select IMIE,NAZWISKO,HASLO FROM PERS where ID='$ID'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_USER - ".mysqli_error()."</span></p>");
							$REK_PASS_USER = mysqli_fetch_array($SEL_PASS_USER);
							echo '<p class="P_M_NAGL">Zmiana hasła użytkownika [ <span class="S_LEG_INFO">'.$REK_PASS_USER[0].' '.$REK_PASS_USER[1].' </span>] </p>';
							$zmiana_haslo=0;
							if (isset($_GET["HASLO"]))
							{
														$checked=TRUE;
														$err="";
														list($checked, $err) = sprawdz_haslo($_GET["PASS"],"PASS",8,20);
						//----------------------------------------------------------------SPRAWDZ-PASS---------------------------------
						if ($_POST["PASS"]!="" && $checked==TRUE)
						{	
												echo "PASS : ".$_POST["PASS"]."</br>";
												mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
												$SEL_PASS_USER = mysqli_query($polaczenie,"SELECT HASLO FROM PERS WHERE ID='$_POST[ID]'")  or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> SEL_PASS_USER : ".mysqli_error()."</span></p>");
												while($REK_PASS_USER = mysqli_fetch_array($SEL_PASS_USER)){
																					$pass_md5 = md5($_POST["PASS"]);
																					echo "REK_PASS_USER".$REK_PASS_USER[0]." | PASS : ".$pass_md5."</br>";
																					IF($REK_PASS_USER[0]==$pass_md5) {$checked=FALSE;
																					$err='<span class="S_ERR_RED">WPisane hasło jest takie same jak aktualne !</span>'; 
																					};
						};
						};
						//----------------------------------------------------------------KONIEC-SPRAWDZ-PASS---------------------------								
						if (($checked==FALSE) ) $zmiana_haslo=0; else $zmiana_haslo=1;
						} else {
								//IF($_SESSION["id_user"]==1) echo "Nie wcisnieto ZMIEN</br>";
						};
						
							if ($zmiana_haslo==0)
							{ // ==0
								echo '<form Name="EDYTUJ" action="" method="GET" >';
								echo '<div class="DIV_DODAJ">';
								echo '<p class="NG_DANE_PASS">Wpisz nowe hasło : ';
								echo '<input type="text" name="PASS" class="INPUT_TXT" value="';
								if (isset($_GET["PASS"])) echo $_GET["PASS"];									
								echo '"> '.$err."</p>";
								echo '<input type="hidden" name="IDW" value="'.$_GET["IDW"].'" />';
								echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
								echo '<input type="hidden" name="IDB" value="'.$_GET["IDB"].'" />';	
								echo '<input type="hidden" name="ID" value="'.$_GET["ID"].'" />';	
								echo "</div>";							
								echo '<input class="button" type="submit" value="Zmień" name="HASLO"/></form>';
							//---------------------------------------------------------------LEGENDA----------------------------
							echo '<p class="P_LEG">Legenda :</p><p class="P_LEG_INFO">';
							$tab_legenda=array("musi zawierać min (<span class=\"S_LEG_INFO\">8</span>) znaków;","może zawierać max (<span class=\"S_LEG_INFO\">20</span>) znaków;","musi zawierać min (<span class=\"S_LEG_INFO\">1</span>) znak specjalny;","musi zawierać min (<span class=\"S_LEG_INFO\">1</span>) cyfrę;");
							foreach($tab_legenda as &$wartosc)
							{
								echo "- Hasło ".$wartosc."</br>";
							}
							//---------------------------------------------------------------KONIEC-LEGENDA----------------------------
							echo '</p>';
							} 
							else
							{
								$pass_trim=trim($_GET["PASS"]);
								$pass_md5=md5($pass_trim);
								$UPD_USER = "UPDATE `PERS` SET `HASLO`='$pass_md5' WHERE `PERS`.`ID`=$_GET[ID]";
								mysqli_query($polaczenie,$UPD_USER) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">UPD_USER - ".mysqli_error()."</span></p>");
								//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
								INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"HASLO UŻYTKOWNIK - uaktualniono ID : ".$_GET["ID"],$polaczenie);
								//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
								echo '<p class="P_BACK">Uaktualniono hasło użytkownika !<br/><span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span><span class="S_BACK2">MENU - Użytkownicy.</span></p><script>
																																function init(){ setTimeout(\'document.location="cel_ADMIN.php?IDW='.$_GET["IDB"].'&IDM='.$_GET["IDM"].'"\', 500);}
																																window.onload=init;
																																</script>';
																																
							};
							} else { echo '<p class="P_ERR">BRAK uprawnienia - Zmiana hasła użytkownika </p>';};
//--------------------------------------------------------------------------KONIEC-HASŁO-UŻYTKOWNIK-CASE-19-----------------------------------------------------------------------						
						break;
					case 20:
							echo '<p class="P_M_NAGL">Aktualne ustawienia w systemie (OLD v.) :</p>';
							//--------------------------------------------------------SQL-INSERT-DZIENNIK--------------------------------------------------------------------
							INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - AKTUALNE ustawienia w systemie",$polaczenie);
							//--------------------------------------------------------KONIEC-INSERT-DZIENNIK------------------------------------------------------------------
							if ($TAB_PRAW["USTAWIENIA OLD"]['VAL']==1){
							for($tab=0;$tab<5;$tab++){
							$w_td_right="460px";
							$td_col_2='';
							$w_td_left="320px"; // WIDTH TABLE 780px
														switch ($tab):
																	case 0:
																		$naglowek="Ustawienia pozycjonowania tekstu";
																		$COLUMN="NAZWA";
																		$ID_GROUP=0; 
																		$IDWP=5;
																		break;
																	case 1:
																		$naglowek="Ustawienia formatowania tekstu";
																		$COLUMN="NAZWA";
																		$ID_GROUP=1;
																		$IDWP=6;
																		break;
																	case 2:
																		$naglowek="Ustawienia rozmiaru tekstu";
																		$COLUMN="WART";
																		$ID_GROUP=2;
																		$IDWP=7;
																		$td_col_2='<span style="color:purple;">px</span>';
																		break;
																	case 3:
																		$naglowek="Ustawienia rozmiaru zdjęć";
																		$COLUMN="WART";
																		$ID_GROUP=4;
																		$IDWP=7;
																		$td_col_2='<span style="color:purple;">px</span>';
																		break;
																	case 4:
																		$naglowek="Ustawienia koloru ";
																		$COLUMN="NAZWA";
																		$ID_GROUP=3;
																		$IDWP=7;
																	default:
																		break;
														endswitch;
							echo '<p style="text-align:left; font-size:18px; font-weight:bold;margin-left:10px;">'.$naglowek.' [<a href="cel_ADMIN.php?IDM='.$_GET["IDM"].'&IDW='.$IDWP.'&IDG='.$ID_GROUP.'&IDB=0" class="A_UST"> ZMIEŃ </a>] :</p>';
							echo "<table ID=\"UST\">";
							mysqli_query($polaczenie,"SET NAMES `UTF8` COLLATE `UTF8_POLISH_CI`");
							$DEF_POZ = mysqli_query($polaczenie,"SELECT N_OPCJ,$COLUMN FROM PARM WHERE WSK_U=0 AND ID_GROUP='$ID_GROUP'") or die (mysqli_error());
							while($REK_POZ=mysqli_fetch_array($DEF_POZ))
								{
								echo '<tr ID="UST"><td ID="UST" width="'.$w_td_left.'"><span style="margin-bottom:0px; margin-top:0px; float:right;">'.$REK_POZ[0].'</span></td>';
								echo '<td ID="UST" width="'.$w_td_right.'"><span style="font-weight:bold;float:left;"> : '.$REK_POZ[1].'</span>'.$td_col_2.'</td></tr>';
								}
							echo "</table>";
							};
							} else { echo '<p class="P_ERR">BRAK uprawnienia - Wyświetl USTAWIENIA OLD </p>';};
//-------------------------------------------------------------------------------------------KONIEC-AKTUALNE-USTAWIENIA-W-SYSTEMIE--------------------------------------------------------------------
						break;
                                            case 21: /* ZMIANA USTAWIEŃ */
                                                require(DR.'/pod_strony/ADMIN/changeSettings.php');
						break;
					/*default:
							echo '<p class="P_WORK">Strona w rozbudowie</p>';
							//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - DEFAULT");
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
					*/
                                       case 22: /* Odwiedziny strony judoukskodokantorun.pl*/
                                            require(DR.'/pod_strony/ADMIN/showVisit.php');
					break;
                                               case 99:
                                                   echo "<a href=\"http://judoukskodokantorun.pl/phpmyadmin/index.php\" target=_blank>phpMyAdmin</a>";
                                                break;
endswitch;
echo "</div></center></body>";
?>		
</body>
<?php
$end_time_page = round(((time()) - $start_time_page),5);
echo "<p class=\"P_INFO_OP\">Strona załadowana w czasie : $end_time_page s.</p>";