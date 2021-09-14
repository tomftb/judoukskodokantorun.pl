<?php
if(!isset($DR))
{
    $DR=filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
}
// ADD DB
require_once($DR."/.cfg/konfiguracja.php");

if ($_SESSION["id_user"]==1)
{
	echo '<p CLASS="P_INFO">Uruchomiono procedurę pobierania parametrów z bazy !</p>';
};

$ZAP_SQL="select ID,ID_GROUP,N_OPCJ,NAZWA,WART,WSK_D FROM PARM WHERE WSK_U=0 AND WSK_V=1 AND ID_MODUL=$_GET[IDM] order by ID_GROUP";
$SEL_PARM = $db->query($ZAP_SQL);
if ($SEL_PARM)
{
    if ($_SESSION["id_user"]==1) echo "<p class=\"P_SQL_OK\">Poprawnie wykonano zapytanie SQL (<span class=\"S2_SQL\">\$ZAP_SQL</span>) - <span class=\"S_SQL\">$ZAP_SQL</span></p>";
} 
else
{
    if ($_SESSION["id_user"]==1)
    {
	echo "<p class=\"P_SQL_ERR\">Błąd zapytania SQL  (<span class=\"S2_SQL\">\$ZAP_SQL</span>) -<span class=\"S_SQL\"> $ZAP_SQL ".mysql_error()."</span></p>"; 
    };
    die("<p class=\"P_SQL_ERR\">Błąd w zapytaniu SQL !<span class=\"S_SQL\"> Skontaktuj się z Administratorem !</span></p>");
};
$i_poz=0;
$i_poz_r=0;
$i_kol=0;
$i_css=0;
$i_while=0;
$css=array(0,0,0);
$kolor_font="";
$domyślny_kom=" (domyślny)";
$max_width=array("0","0");
$max_height=array("0","0");
while($REK_PARM = mysqli_fetch_array($SEL_PARM))
{
    if ($_SESSION["id_user"]==1)
    {
	echo '<p CLASS="P_INFO">ID_GROUP [<span class="S_INFO">'.$REK_PARM[1].'</span>]';
	echo '[<span class="S_INFO">'.$REK_PARM[2].'</span>] : <span class="S_INFO">'.$REK_PARM[4].'</span></p>';
    };
    switch($REK_PARM[1]):
			case 0: // POZYCJA TEKSTU
                        	//if (strpos($REK_PARM[2],"_AKT_TYT")) { //echo "_AKT_TYT";
																																			$poz_id[$i_poz]=$REK_PARM[5];
																																			$poz_nazwa[$i_poz]=$REK_PARM[3];
																																			$poz_wart[$i_poz]=$REK_PARM[4];
																										//}
																										/*
																										else if (strpos($REK_PARM[2],"_AKT_TRE")){//echo "_AKT_TRE";
																																					$poz_id[$i_poz]=$REK_PARM[5];
																																					$poz_nazwa[$i_poz]=$REK_PARM[3];
																																					$poz_wart[$i_poz]=$REK_PARM[4];
																										} 
																										else {
																												echo "BŁĄD - skontaktuj się z Administratorem !";
																										}
																										*/
																										if ($_SESSION["id_user"]==1){
																																	echo '<p CLASS="P_INFO">'.$i_poz;
																																	echo 'POZYCJA : [<span class="S_INFO">'.$poz_nazwa[$i_poz].' | '.$poz_wart[$i_poz].' | '.$poz_id[$i_poz].'</span>]</p>';
																										};
																										$i_poz++;
																									break;
																								case 1: //CSS
																										$i_while=0;
																										$db->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																										$ZAP_SQL="select c.ID_OPCJ,c.NAZWA FROM CSS c WHERE c.ID_GROUP=0 ORDER BY c.ID";
																										$SEL_CSS=$db->query($ZAP_SQL);
																										if ($SEL_CSS) {
																														if ($_SESSION["id_user"]==1) echo "<p class=\"P_SQL_OK\">Poprawnie wykonano zapytanie SQL (<span class=\"S2_SQL\">\$SEL_CSS</span>) - <span class=\"S_SQL\">$ZAP_SQL</span></p>";
																										} 
																										else {
																												if ($_SESSION["id_user"]==1) {
																																				echo "<p class=\"P_SQL_ERR\">Błąd zapytania SQL  (<span class=\"S2_SQL\">\$ZAP_SQL</span>) -<span class=\"S_SQL\"> $ZAP_SQL ".mysql_error()."</span></p>"; 
																											};
																												die("<p class=\"P_SQL_ERR\">Błąd w zapytaniu SQL !<span class=\"S_SQL\"> Skontaktuj się z Administratorem !</span></p>");
																										};
																										while($REK_CSS = mysqli_fetch_array($SEL_CSS))
																										{
																											$db->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																											$ZAP_SQL_1="select pc.ID,pc.WSK_V FROM PARM_CSS pc, PARM pm WHERE pc.ID_PARM=pm.ID AND pm.ID_MODUL='$_GET[IDM]' AND pc.ID_CSS='$REK_CSS[0]' AND pm.ID='$REK_PARM[0]' LIMIT 1";
																											$SEL_P_CSS = $db->query($ZAP_SQL_1);
																											if ($SEL_P_CSS)
																											{
																												if ($_SESSION["id_user"]==1) echo "<p class=\"P_SQL_OK\">Poprawnie wykonano zapytanie SQL (<span class=\"S2_SQL\">\$ZAP_SQL_1</span>) - <span class=\"S_SQL\">$ZAP_SQL_1</span></p>";
																											} 
																											else
																											{
																												if ($_SESSION["id_user"]==1)
																												{
																													echo "<p class=\"P_SQL_ERR\">Błąd zapytania SQL  (<span class=\"S2_SQL\">\$ZAP_SQL_1</span>) -<span class=\"S_SQL\"> $ZAP_SQL_1 ".mysql_error()."</span></p>"; 
																												};
																												die("<p class=\"P_SQL_ERR\">Błąd w zapytaniu SQL !<span class=\"S_SQL\"> Skontaktuj się z Administratorem !</span></p>");
																											};
																											$REK_P_CSS = mysqli_fetch_array($SEL_P_CSS);
																											if($REK_P_CSS[1]=="0" ||$REK_P_CSS[1]=="") $css[$i_while]=0; 
																											else if ($REK_P_CSS[1]=="1") $css[$i_while]=1;
																											$i_while++;
																										};
																										$css_tab[$i_css]=$css;
																										if ($_SESSION["id_user"]==1){
																																	echo '<p CLASS="P_INFO">'.$i_css.' |';
																																	echo '<span class="S_INFO">Wartość CSS_TAB :'.$css_tab[$i_css][0].$css_tab[$i_css][1].$css_tab[$i_css][2].'</span></p>';
																										};
																										$css=array(0,0,0);
																										$i_while=0;
																										$i_css++;
																									break;
																								case 2: // ROZMIAR
																										$rozmiar[$i_poz_r]=$REK_PARM[4];
																										if ($_SESSION["id_user"]==1){
																																	echo '<p CLASS="P_INFO">'.$i_poz_r.' |';
																																	echo '<span class="S_INFO">Wartość rozmiar tekstu :'.$rozmiar[$i_poz_r].'</span></p>';
																										};
																										$i_poz_r++;
																									break;
																								case 3: // KOLOR TEKSTU
																										$kolor_id[$i_kol]=$REK_PARM[5];
																										$kolor_nazwa[$i_kol]=$REK_PARM[3];
																										$kolor_hex[$i_kol]=$REK_PARM[4];	
																										if ($REK_PARM[4]=='#000000') {
																																	$kolor_font[$i_kol]='#FFFFFF';
																										}
																										else {
																											$kolor_font[$i_kol]='#000000';
																										};
																										if ($_SESSION["id_user"]==1){
																																	echo '<p CLASS="P_INFO">Wartość : |';
																																	echo ' kolor_id - <span class="S_INFO">'.$kolor_id[$i_kol].'</span>';
																																	echo ' kolor_nazwa - <span class="S_INFO">'.$kolor_nazwa[$i_kol].'</span>'; 
																																	echo ' kolor_hex - <span class="S_INFO">'.$kolor_hex[$i_kol].'</span></p>';
																										};
																										$i_kol++;
																									break;
																								case 4: // ROZMIAR ZDJECIE
																											//--------------------------------------------------------SQL-SELECT-IMG-MAX-------------------------------------------------------------------
																											
																											if (strpos($REK_PARM[2],"_H_MIN"))
                                                                                                                                                                                                                        {
                                                                                                                                                                                                                            $max_height[1]=$REK_PARM[4]; 
																											}
																											else if(strpos($REK_PARM[2],"_H_MAX"))
                                                                                                                                                                                                                        {
                                                                                                                                                                                                                            $max_height[0]=$REK_PARM[4]; 
																											}
																											else if (strpos($REK_PARM[2],"_W_MIN"))
                                                                                                                                                                                                                        {
                                                                                                                                                                                                                            $max_width[1]=$REK_PARM[4];
																											} 
																											else if (strpos($REK_PARM[2],"_W_MAX"))
                                                                                                                                                                                                                        {
                                                                                                                                                                                                                            $max_width[0]=$REK_PARM[4];
																											} 
																											else
                                                                                                                                                                                                                        {
                                                                                                                                                                                                                            echo "Nic nie pasuje ! Skontaktuj się z Administratorem</br>";
																											};
																											
																											//--------------------------------------------------------KONIEC-SQL-SELECT-IMG-MAX------------------------------------------------------------
																											
																									break;
																								case 5: // Wskażnik widoczności
																										if (isset($_POST["dodaj"])) {
																																	if (strpos($REK_PARM[2],"_PO_DODANIU")) {
																																											$WSK_V=$REK_PARM[4];
																																	}
																																	else {
																																			echo "Nic nie pasuje ! Skontaktuj się z Administratorem</br>";
																																	};
																										}
																											else if (isset($_POST["edytuj"])){
																																			if(strpos($REK_PARM[2],"_PO_EDYCJI")){
																																													$WSK_V=$REK_PARM[4]; 
																																			} 
																																			else {
																																					echo "Nic nie pasuje ! Skontaktuj się z Administratorem</br>";
																																			};
																										};
																									break;
																								case 6:
																										$ILE_ID=$REK_PARM[4];
																									break;
																								case 7: // FONT FAMILY
																										$font_f_id=$REK_PARM[5];
																										$font_f_wart=$REK_PARM[4];
																									break;
																								default:
																									break;
																			endswitch;
																};
?>