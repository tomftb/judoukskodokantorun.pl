<?php if(!defined('AKT21')) { exit('NO PERMISSION');}?>
<div class="DIV_MAIN">
<?php
require(DR.'/pod_strony/_funkcje_/upload_file.php');
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0"><p class="P_HREF_BACK">Powrót</p></a>';						
//------------------------------------------------------------------POBIERANIE-PARM-Z-BAZY------------------------------------------------------
if(file_exists(DR."/pod_strony/_include_/pobr_parm.php")) include(DR."/pod_strony/_include_/pobr_parm.php"); else echo "Nie można załadować potrzebnego pliku - pobr_parm.php . Skontaktuj się z Adminstratorem!";
//------------------------------------------------------------------KONIEC-POBIERANIE-PARM-Z-BAZY------------------------------------------------------

$status_dodaj=0;
if (isset($_POST["podglad_artykul"])) {			
								//echo "Wcisnieto podglad !</br>";
}
								if (isset($_POST["artykul"])) { # Sprawdz czy tablica o indeksie artykul , nie jest pusta
																# Ustaw wartosci poczatkowe testow
																$IMG_NEW=FALSE;
																$checked_img=TRUE;
																$checked=TRUE;
																$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ -_,*.'!:\r\n]+$/";
																$err=array('','');
								for($ch_dane=0;$ch_dane<2;$ch_dane++)
								{	# Sprawdz wprowadzone dane tekstowe
									/* turn of change to htmlentities 
									if(!preg_match($string_exp,$_POST["dane".$ch_dane]))
									{
										$err[$ch_dane]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; 
										$checked=FALSE;
									};
									*/
									if($ch_dane==1) $ch_max=8000; else $ch_max=2000;
									check_len($checked, $_POST["dane".$ch_dane],$ch_max,$err[$ch_dane],'<span class="S_ERR_DANE">Pole za długie (maksymalna ilość znaków - <span CLASS="S_ERR_DANE2">'.$ch_max.'</span>)</span>',5,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - <span style="color:black;font-size:14px;">5</span>)</span>');
								};
								for( $y = 1; $y<5; $y++ ) { # Sprawdz zawarte pliki  
															if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"])) {
																if ($checked_img==TRUE) { # Sprawdz, czy plik posiada dozwolone rozszerzenie
																	if(($_FILES["obraz".$y]["type"] == 'image/gif') || 
																				 ($_FILES["obraz".$y]["type"] == 'image/jpeg') || 
																				  ($_FILES["obraz".$y]["type"] == 'image/jpg') || 
																				   ($_FILES["obraz".$y]["type"] == 'image/png') || 
																				   ($_FILES["obraz".$y]["type"] == 'image/bmp') || 
																				   ($_FILES["obraz".$y]["type"] == 'image/pjpeg')) 
																					{
																					$max_rozmiar = 8388608;
																						if ($_FILES["obraz".$y]["size"] < $max_rozmiar){ # Sprawdz, czy plik ma dozwolona wielkosc
																						$checked_img=TRUE;
																						$IMG_NEW=TRUE;
																						if ($_SESSION['id_user']==1) echo "<p class=\"P_INFO\">IMG_ZMIANA = <span class=\"S_INFO\">".$IMG_ZMIANA."</span></p>";
																						$err_obraz_tab[$y]="<span class=\"S_INFO_OK\">Proszę wczytać jeszcze raz zdjęcie.</span>";
																						}
																						else {
																								$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Za duży rozmiar obrazu</span> !";
																								$checked_img=FALSE;
																					}
																					} 
																					else { 
																					$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Wskazany plik nie jest obrazem </span>!";
																					$checked_img=FALSE;
																					}
																} else { $err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Błąd poprzedniego zdjęcia </span>!";}												
															//} else { $err_obraz_tab[$y]='<span style="color:red;">Nie wskazano obrazu.</span>'; };
															} else { $err_obraz_tab[$y]="<span class=\"S_INFO_OK\">Nie wskazano obrazu.</span>"; }; // zmiana na FALSE uniemoliwia dodanie zawodnika bez zdjecia
									}; // KONIEC petla FOR dla plików
									if (($checked==FALSE) || ($checked_img==FALSE)) $status_dodaj=0; else $status_dodaj=1;
};
if ($status_dodaj==0){
													echo '<form action="" method="POST" ENCTYPE="multipart/form-data" name="form1" id="form1" >';//target="_blank"
													//echo '<a form action="javascript:pokazDANE(&#39;pokaz_tresc.php&#39;,300,200)" method="POST" ENCTYPE="multipart/form-data" target="_blank">';
							for ($tr=0;$tr<2;$tr++){
													switch($tr):
																case 0: 
																		echo $naglowek_switch_IDW;
																		$naglowek="Tytuł";
																		$rows=1;
																		$ID_OPCJ_KOL=5;
																		$ID_OPCJ_ROZ=7;
																		$pozycja_d=5;
																		$pozycja_s=5;
																	break;
																case 1:
																		echo $naglowek_switch_IDW;
																		$naglowek="Treść";
																		$rows=20;
																		$ID_OPCJ_KOL=6;
																		$ID_OPCJ_ROZ=8;
																		$pozycja_d=7;
																		$pozycja_s=7;
																	break;
																case 2:
																	break;
																default:
																	break;
													endswitch;
							echo '<p class="NG_DANE">';
							if($_SESSION['id_user']==1) echo "TR - ".$tr." | ";
							echo $naglowek.' <span class="S_NG_DANE">*</span>: '.$err[$tr].'</p>';
							echo '<div class="DIV_DODAJ">';
							//----------------------------------------------------------------------------------POZYCJA-TEXT-NAGŁWEK---------------------------------------------------------------
							if($_POST["pozycja".$tr]!='') {
															list($poz_id[$tr],$poz_wart[$tr],$poz_nazwa[$tr]) = explode('|', $_POST["pozycja".$tr]);
															$domyślny_kom=' (ustawiony)';
							};
							//----------------------------------------------------------------------------------KONIEC-POZYCJA-TEXT-NAGŁWEK---------------------------------------------------------------
							//----------------------------------------------------------------------------------KOLOR-Tytul/Treść-NAGŁ--------------------------------------------------------
							if($_POST["kolor".$tr]!='') {
														list($kolor_id[$tr],$kolor_hex[$tr],$kolor_nazwa[$tr]) = explode('|', $_POST["kolor".$tr]);
														if ($kolor_hex[$tr]=='#000000') {
																					$kolor_font[$tr]='#FFFFFF';
														}
														else {
																$kolor_font[$tr]='#000000';
														};
														$domyślny_kom=' (ustawiony)';
							if ($_SESSION["id_user"]==1){
														echo '<p CLASS="P_INFO">POST[kolor'.$tr.'] - ';
														echo '<span class="S_INFO">'.$kolor_id[$tr].' '.$kolor_nazwa[$tr].' '.$kolor_hex[$tr].'</span></p>';
							};
							};
							//----------------------------------------------------------------------------------KONIEC-KOLOR-Tytul/Treść-NAGŁ---------------------------------------------
							//----------------------------------------------------------------------------------CSS-NAGLOWEK--------
							$i_wh_css=0;
							$CSS_POKAZ="";
							
							$SEL_CSS= $db->query("select NAZWA,WSK_V FROM CSS WHERE WSK_V=1 AND ID_GROUP=0 ORDER BY ID") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_CSS".mysqli_error($polaczenie)."</span></p>");
							while($REK_CSS = mysqli_fetch_array($SEL_CSS)){
																			if ($css_tab[$tr][$i_wh_css]==$REK_CSS[1]) {
																														$domyslny='checked="checked"';
																														$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(domyślny)</span>';
																														if ($i_wh_css==0) $font_weight='font-weight:bold;';
																														if ($i_wh_css==1) $font_style='font-style:italic;';
																														if ($i_wh_css==2) $text_dec='text-decoration: underline;'; 
																			}
														else if ($_POST["CSS_".$tr."_".$i_wh_css]!="") {
																					$domyslny='checked="checked"'; 
																					$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																					if ($i_wh_css==0) $font_weight='font-weight:bold;'; 
																					if ($i_wh_css==1) $font_style='font-style:italic;'; 
																					if ($i_wh_css==2) $text_dec='text-decoration: underline;'; 
																					} 
														else {
																$domyslny=''; 
																$domyslny_kom='';
																if ($i_wh_css==0) $font_weight='font-weight:normal;';
																	if ($i_wh_css==1) $font_style='font-style: normal;';
																		if ($i_wh_css==2) $text_dec='';
															};
														$CSS_POKAZ.='<input type="checkbox" name="CSS_'.$tr.'_'.$i_wh_css.'" value="'.$REK_CSS[1].'" '.$domyslny.' class="CSS_CHBOX"  /><span class="S_CSS">'.$REK_CSS[0].'</span> '.$domyslny_kom.'<br/>';
							$i_wh_css++;
							};	// Koniec petla WHILE
							//$i_wh_css=0;
							//if ($_POST["CSS_".$tr."_".$i_wh_css]!="") 
									//if ($css_tab[$tr][0]==1) $font_weight='font-weight:bold;'; else $font_weight='font-weight:normal;';
									//if ($css_tab[$tr][1]==1) $font_style='font-style:italic;'; else $font_style='font-style: normal;';
									//if ($css_tab[$tr][2]==1) $text_dec='text-decoration: underline;'; $text_dec='';
									
							//----------
							//echo '<form action="pokaz_tresc.php" method="POST" target="_blank" >';
							
							echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA" style="color:'.$kolor_hex[$tr].'; text-align:'.$poz_wart[$tr].';'.$font_weight.$font_style.$text_dec.';">'; // na przyszlosc style="font-size:'.rozmair[$tr].'px;" 
							if (isset($_POST["dane".$tr])) {
															echo $_POST["dane".$tr];
															$podglad=$_POST["dane".$tr];
							}
							/*
							else { 
									echo $_POST["dane".$tr]; 
							};										
							*/
							echo '</textarea>';
							
							//echo '<p>';
							//echo '<a HREF="javascript:pokazDANE("pokaz_tresc.php?TRESC=".$_POST["dane".$tr].",800,300)">';
							//echo "<a HREF=\"javascript:displayWindow('pokaz_tresc.php?TRESC=$podglad',800,300)\">";
							//echo "POKAZ";
							//echo '</a></p>';
							echo '<table><tr><td class="TD_L">';
							//----------------------------------------------------------------------------------KOLOR-Tytul/Treść----------------------------------------------
							echo '<p CLASS="P_CSS_NG_L">Kolor tekstu : ';
							echo '<select name="kolor'.$tr.'" class="SELECT">';
							echo '<optgroup label="Aktualny :" class="OPTGROUP">';
							echo '<option value="'.$kolor_id[$tr].'|'.$kolor_hex[$tr].'|'.$kolor_nazwa[$tr].'" style="font-weight:bold; color:'.$kolor_font[$tr].';background: none repeat scroll 0%  0% '.$kolor_hex[$tr].';">';
							echo $kolor_nazwa[$tr].' '.$domyślny_kom;
							echo '</option>';
							echo '</optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
							
							$SEL_COLOR = $db->query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 AND ID!='$kolor_id[$tr]'order by ID");
							while($r_kolor = mysqli_fetch_array($SEL_COLOR)){
																			if ($r_kolor[2]=='#000000') {
																										$kolor_font[$tr]='#FFFFFF';
																			}
																			else {
																					$kolor_font[$tr]='#000000';
																			}
																			echo '<option value="'.$r_kolor[0].'|'.$r_kolor[2].'|'.$r_kolor[1].'" style="color:'.$kolor_font[$tr].'; background: none repeat scroll 0%  0% '.$r_kolor[2].';">';
																			echo $r_kolor[1].'</option>';
							};
							echo '</optgroup></select></p>';
							//----------------------------------------------------------------------------------KONIEC-KOLOR------------------------------------------------------------------------------
							//----------------------------------------------------------------------------------POZYCJA-TEXT----------------------------------------------------------------
							echo '<p CLASS="P_CSS_NG_L">Pozycja tekstu : ';
							echo '<select name="pozycja'.$tr.'" class="SELECT">';
							echo '<optgroup label="Aktualny :" class="OPTGROUP">';
							echo '<option value="'.$poz_id[$tr].'|'.$poz_wart[$tr].'|'.$poz_nazwa[$tr].'" class="OPTION">'.$poz_nazwa[$tr].' '.$domyślny_kom.'</option>';
							echo '</optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
							
							$SEL_POZ_ALL = $db->query("select ID,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=1 AND ID_OPCJ!='$poz_id[$tr]'  order by ID_OPCJ");
							while($r_pozycja = mysqli_fetch_array($SEL_POZ_ALL)){
																				echo '<option value="'.$r_pozycja[0].'|'.$r_pozycja[2].'|'.$r_pozycja[1].'" class="OPTION">';
																				echo $r_pozycja[1].'</option>';						
							};
							echo '</optgroup></select></p>';
							//-----------------------------------------------------------------------------------KONIEC-POZYCJA-TEXT---------------------------------------------------------
							//-----------------------------------------------------------------------------------ROZMIAR-------------------------------------------------------------------------------
							echo '<p CLASS="P_CSS_NG_L">Rozmiar czcionki : ';
							if($_POST["rozmiar".$tr]!='') {
															$rozmiar[$tr]=$_POST["rozmiar".$tr];
															$domyślny_kom=' (ustawiona)';
							}
							echo '<select name="rozmiar'.$tr.'" class="SELECT">';
							echo '<optgroup label="Aktualny :" class="OPTGROUP">';
							echo '<option value="'.$rozmiar[$tr].'" class="OPTION">';
							echo $rozmiar[$tr].' '.$domyślny_kom;
							echo '</option></optgroup>';
							echo '<optgroup label="Dostępne :" class="OPTGROUP">';
							for( $i = 12; $i<33;) {
													if ($rozmiar[$tr]!=$i) echo '<option value="'.$i.'" class="OPTION">'.$i.'</option>';
							$i=$i+2; 
							};
							echo '</optgroup></select></p>';
							//-----------------------------------------------------------------------------------KONIEC-ROZMIAR------------------------------------------------------------------
							//-----------------------------------------------------------------------------------STYLE-TEXT--------------------------------------------------------------------------------
							echo '</td><td class="TD_R">';
							echo '<p CLASS="P_CSS_NG_R">Wskaż opcję formatowania :</p>';
							echo $CSS_POKAZ;
							//----------------------------------------------------------------------------------KONIEC-STYLE-TEXT-Imie/Nazwisko----------------------------------------
							echo '</td></tr></table>';
							echo '<input type="hidden" name="CSS_DEF_'.$tr.'" value="1" />';	
							echo '</div>';
							};
							/*echo '<p class="NG_DANE">Poniżej wprowadź adres URL do filmu który chcesz załączyć:</p>';
							echo '<div class="DIV_DODAJ">';
							echo '<textarea name="adres" rows="1" cols="85" class="TEXTAREA">';
							if (isset($_POST["adres"])) echo $_POST["adres"]; else echo $_POST["adres"];
							echo '</textarea></br>';
							echo '</div>';
							*/
							echo '<p class="NG_DANE">Część dodawadodawania zdjęć (<span CLASS="S_NG_DANE">Max 4</span>):</p>';
							echo '<div class="DIV_DODAJ">';
							for( $x = 1; $x<5; $x++ ) {
														echo '<p class="P_INPUT">Zdjęcie nr : ';
														echo '<span class="S_NG_DANE">'.$x.' </span>';
														echo '<input type="file" name="obraz'.$x.'"/> ';
														echo $err_obraz_tab[$x].'</p>';
							};
							echo '</div>';
							echo '<DIV style="width:800px; height:30px; ">'; // DIV BUTTON DODAJ border: 1px solid green;
							//echo '<button type="submit" formaction="pokaz_tresc.php">Podglad</button>'; //javascript:pokazDANE(\' \',800,200)
							//echo "dane0".$_POST["dane0"]."</br>";
							//echo '<input type="hidden" name="dane1" value="100" />';
							echo '<input class="button" type="submit" value="Dodaj" name="artykul">';
							echo '<input class="button" type="submit" value="Podgląd" name="podglad_artykul">';
							
							echo '</FORM>';
							
							//-------------PODGLAD---
							//echo '<form action="javascript:pokazDANE(\'pokaz_tresc.php?dane0='.$_POST["dane0"].'&dane1=123\',800,200)" method="GET" ENCTYPE="multipart/form-data" target="_blank">';
							//echo '<input type="hidden" name="dane0" value="100" />';
							//echo '<input type="hidden" name="dane1" value="100" />';
							//ECHO "<span CLASS=\"SPAN_BUT\"><input class=\"btn\" type=\"submit\" value=\"PODGLĄD\" name=\"PODGLAD\"/></span><form>";
							
							//-------------KONIEC-PODGLAD---
							echo '</DIV>';
							if (isset($_POST["podglad_artykul"])){
							//	echo '<button type="submit" formaction="javascript:pokazDANE(\'pokaz_tresc.php?dane0='.$_POST["dane0"].'\',800,200)">Podglad</button>'; //javascript:pokazDANE(\' \',800,200)
							//
							//echo "Podgląd</br>";
							//echo '<br /><iframe id="upload_frame" name="upload_frame" frameborder="2" border="2" src="" scrolling="no" scrollbar="no" height="200" width="800"> </iframe><br />';
							};
							//---------------------------------------------------------------LEGENDA----------------------------
							echo '<DIV style="width:800px; ">'; // DIV LEGEND DODAJ 	border: 1px solid green;
							$tab_legenda=array("pola z GWIAZDKĄ (<span class=\"S_LEG_INFO\">*</span>) wymagane;","TYTUŁ musi zawierać min (<span class=\"S_LEG_INFO\">5</span>) znaków;","TYTUŁ może zawierać max (<span class=\"S_LEG_INFO\">200</span>) znaków;","TREŚĆ musi zawierać min (<span class=\"S_LEG_INFO\">5</span>) znaków;","TREŚĆ może zawierać max (<span class=\"S_LEG_INFO\">4000</span>) znaków;","ZDJĘCIA nie są (<span class=\"S_LEG_INFO\">wymagane</span>);","ZDJĘCIA, dozwolony TYP : (<span class=\"S_LEG_INFO\">JPG JPEG PNG BMP GIF</span>);","ZDJĘCIE,  <span class=\"S_LEG_INFO\">MAX</span> Rozmiar ~ <span class=\"S_LEG_INFO\">8 MB</span> ;");
							echo '<p class="P_LEG">Legenda :</p>';
							echo "<ul class=\"UL_LEG\">";
							foreach ($tab_legenda as $key => $value){
								echo "<li class=\"LI_LEG\">$value</li>";
							};
							echo "</ul>";
							echo '</DIV>';
							//---------------------------------------------------------------KONIEC-LEGENDA----------------------------
							//echo '</center></div>';
							//echo '</div>';
							} 
							else if ($status_dodaj==1){
														echo '<p class="P_MAIN">Twój artykuł został dodany</p>';
														$CSS_dane= array(0,0);
														for ($css_1=0;$css_1<2;$css_1++){
																						if ($_POST["CSS_".$css_1."_0"]!='') {
																															$CSS_dane[$css_1]=$_POST["CSS_".$css_1."_0"];
																															if ($_SESSION['id_user']==1){
																																						echo '<p CLASS="P_INFO">CSS_<span class="S_INFO">'.$css_1.'</span>_0 - <span class="S_INFO">'.$CSS_dane[$css_1].'</span></p>';
																															};
																						}
																						else { 
																							$CSS_dane[$css_1]=0;
																							if ($_SESSION['id_user']==1){
																														echo '<p CLASS="P_INFO">CSS_<span class="S_INFO">'.$css_1.'</span>_0 - <span class="S_INFO">'.$CSS_dane[$css_1].'</span></p>';
																							};
																						};
														};
														$css_b=array(0,0);
														$css_i=array(0,0);
														$css_u=array(0,0);
														$dane = array('','');
														$kol_hex= array('','');
														$kol_id= array('','');
														$pozyzja_id=array('','');
														$pozycja_wart=	array('','');
														$INS_IMG_NEWS=TRUE;
														for ($d_ins=0;$d_ins<2;$d_ins++){	
																						for( $css_licz=1; $css_licz<3; $css_licz++ ) {
																																		if(isset($_POST["CSS_".$d_ins."_".$css_licz])) {
																																														$CSS_dane[$d_ins]=$CSS_dane[$d_ins].'|'.$_POST["CSS_".$d_ins."_".$css_licz];
																																		}
																																		else {
																																				$CSS_dane[$d_ins]=$CSS_dane[$d_ins].'|0';
																																		};
																																		if ($_SESSION['id_user']==1){
																																									echo '<p CLASS="P_INFO">CSS_<span class="S_INFO">'.$d_ins.'</span>_<span class="S_INFO">'.$css_licz.'</span> - <span class="S_INFO">'.$CSS_dane[$d_ins].'</span></p>';
																																		};
																						};
														if ($d_ins==0) $nagl_wysw="Tytuł"; else if ($d_ins==1) $nagl_wysw="Treść";
														if ($_SESSION['id_user']==1){ 
																					echo '<p CLASS="P_INFO">CSS <span class=\"S_INFO\">'.$nagl_wysw.'</span> - <span class=\"S_INFO\">'.$CSS_dane[$d_ins].'</span></p>';
														};
														list($css_b[$d_ins],$css_i[$d_ins],$css_u[$d_ins]) = explode('|', $CSS_dane[$d_ins]);
														if ($css_b[$d_ins]==0) $font_weight='font-weight:normal;'; else $font_weight='font-weight:bold;';
														if ($css_i[$d_ins]==0) $font_style='font-style: normal;'; else $font_style='font-style:italic;';
														if ($css_u[$d_ins]==0) $text_dec=''; else $text_dec='text-decoration: underline;';	
														//$dane_ins[$d_ins] = nl2br($_POST["dane".$d_ins]);
														$dane_ins[$d_ins]=htmlspecialchars(nl2br($_POST["dane".$d_ins]),ENT_QUOTES);
														
														list($kol_id[$d_ins],$kol_hex[$d_ins]) = explode('|', $_POST["kolor".$d_ins]);
														list($pozyzja_id[$d_ins],$pozycja_wart[$d_ins]) = explode('|', $_POST["pozycja".$d_ins]);
														
														echo '<p class="NG_DANE">'.$nagl_wysw.' : </p>';
														echo '<div class="DIV_DODAJ">';
														echo '<p style="background:white;margin-left:10px;margin-right:10px;color:'.$kol_hex[$d_ins].';font-size:'.$_POST["rozmiar".$d_ins].'; text-align:'.$pozycja_wart[$d_ins].';'.$font_weight.$font_style.$text_dec.'">'.$dane_ins[$d_ins].'</p>';
														echo '</div>';
														};
														//--------------------------------------------------------SQL-INSERT-NEW-NEWS---------------------------------------------------------------
														$db->query("INSERT INTO `NEWS` (TYTUL,TRESC,ADRES,ID_PERS,K_TYTUL,R_TYTUL,K_TRESC,R_TRESC,DAT_UTW,K_TYTUL_N,K_TRESC_N,UID,ID_COL_TYT,ID_COL_TRE,P_TYT,ID_P_TYT,P_TRE,ID_P_TRE,CSS_TYT,CSS_TRE,VER,WSK_V) VALUES ('$dane_ins[0]','$dane_ins[1]','$_POST[adres]','$_SESSION[id_user]','$kol_hex[0]','$_POST[rozmiar0]','$kol_hex[1]','$_POST[rozmiar1]',NOW(),'$kol_hex[0]','$kol_hex[1]','$_SESSION[uid]','$kol_id[0]','$kol_id[1]','$pozycja_wart[0]','$pozyzja_id[0]','$pozycja_wart[1]','$pozyzja_id[1]','$CSS_dane[0]','$CSS_dane[1]',4,'$WSK_V')");
														$ID_NEWS=$db->last();
                                                                                                                $db->insDbLog($_GET["IDM"],"DODAJ ARTYKUŁ - dodano ID : ".$ID_NEWS);
														if ($IMG_NEW==TRUE){
														$tworz_kat=0;
														$ILE_ZD=0;	
														$NR_IMG=1;
														for( $y = 1; $y<5; $y++ ) {
														if (is_uploaded_file($_FILES['obraz'.$y]['tmp_name'])) {
																					
																					if ($_SESSION['id_user']==1){ 
																												echo "<p CLASS=\"P_INFO\">Odebrano obraz : ";
																												echo "<span class=\"S_INFO\">".$_FILES['obraz'.$y]['name'].'</span></p>';
																					};
																if ($tworz_kat==0){
																					$dir=opendir(DR."/zdjecia/artykul");
																					$katalog=1; 
																					while($plik=readdir($dir)){ 
																												if($plik!="." && $plik!="..")
																												$katalog++; 
																												};
																					mkdir(DR.'/zdjecia/artykul/'.$katalog, 0777);
																					closedir($dir);
																					if ($_SESSION['id_user']==1){ echo "Utworzono katalog - ".$katalog."<br/>";};
																					$tworz_kat=1;															
																					};
																$uploads_dir = DR.'/zdjecia/artykul/'.$katalog.'/';
																//--------------UPLOAD-FILE----------
																$wynik_upload_file=upload_file($_FILES['obraz'.$y]['name'],$_FILES['obraz'.$y]['tmp_name'],$uploads_dir,"_news",$max_width[0],$max_height[0],$max_width[1],$max_height[1]);
																$filename=$_FILES['obraz'.$y]['name'];
																//--------------KONIEC-UPLOAD-FILE---
																//$INSERT_O="INSERT INTO OUR_PHOTO (NAZWA_O,NAZWA_I,ID_PERS,DAT_UTW,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT,WSK_V) VALUES ('$filename','$wynik_upload_file[2]','$ID_PERS',NOW(),'$wynik_upload_file[0]','$wynik_upload_file[1]','$wynik_upload_file[5]','$wynik_upload_file[3]','$wynik_upload_file[4]','$WSK_V')";
																$db->query("INSERT INTO `NEWS_IMG`(ID_NEWS,KATALOG,NAZWA_O,NAZWA_I,ID_PERS,DAT_UTW,NR_IMG,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT) VALUES ('$ID_NEWS','$katalog','$filename','$wynik_upload_file[2]','$_SESSION[id_user]',NOW(),'$NR_IMG','$wynik_upload_file[0]','$wynik_upload_file[1]','$wynik_upload_file[5]','$wynik_upload_file[3]','$wynik_upload_file[4]')");
																$ID_NEWS_IMG=$db->last();
                                                                                                                                $db->insDbLog($_GET["IDM"],"DODAJ ARTYKUŁ - dodano IMG ID : ".$ID_NEWS_IMG);
																echo '<DIV CLASS="DIV_IMG" style="float:LEFT;">';
																echo '<a HREF="javascript:displayWindow(&#39;'.APP_URL.'/zdjecia/artykul/'.$katalog.'/'.$wynik_upload_file[2].'&#39;,'.$wynik_upload_file[0].','.$wynik_upload_file[1].')">';
																echo '<img src="'.APP_URL.'/zdjecia/artykul/'.$katalog.'/'.$wynik_upload_file[5].'" alt="ARTYKUŁ" >';
																echo '</a>';
																echo '</DIV>';
														$ILE_ZD++;
														$NR_IMG++;
														} 
														else {   
															echo "<p class=\"P_INFO\">Nie wskazano obrazu nr : <span class=\"S_INFO\">".$y."</span></p>"; 
														}
									}
									}
									
									$db->query("UPDATE `NEWS` SET `ILOZD`='$ILE_ZD', `ID_KATALOG`='$katalog' WHERE `ID`='$ID_NEWS' ");
                                                                        $db->insDbLog($_GET["IDM"],"DODAJ ARTYKUŁ - uaktualniono ILE_ZD ".$ILE_ZD." ID : ".$ID_NEWS);
																
									echo '<a href="'.PAGE_URL.'&IDW=0#ID'.$ID_NEWS.'"><p style="text-align:center; font-size:20px; margin:0px;">Powrót do MENU - ARTYKUŁY</p></a>';
									
									foreach ($_POST as $key => $value) { UNSET($_POST[$key]);}
									
									}																			