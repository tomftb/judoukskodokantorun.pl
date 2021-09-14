<?php  if(!defined('ACT_PERM')) { exit('NO PERMISSION');} ?>
<?php 
echo '<div class="DIV_MAIN">';
echo '<a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#'.$ID.'" class="A_BACK"><p class="P_HREF_BACK">Powrót</p></a>';
$status_edytuj=0;
$checked=TRUE;

if (isset($_GET["trening"]))
{
    $string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ .,*'!:-_\r\n]+$/";
for ($tab_ch=0;$tab_ch<4;$tab_ch++){							   
							  if(!preg_match($string_exp,$_GET["dane".$tab_ch])) { $err_[$tab_ch]="Proszę usunąć niedozwolone znaki"; $checked=FALSE;};
};
for ($tab_ch=0;$tab_ch<4;$tab_ch++){
									$size_max=200;
									if ($tab_ch==3) $size_max=400;
									check_len($checked, $_GET["dane".$tab_ch],$size_max,$err_[$tab_ch],'MAX znaków - (<span class="S_ERROR">'.$size_max.'</span>)',5,'MIN znaków - (<span style="color:black;">5</span>)');
									};
if ($checked==TRUE) $status_edytuj=1;
};
if ($status_edytuj==0){
						// $_GET['IDE']; ID do modyfikacji
						
						
						
						$zap_trening = $db->query("select * from TRENING WHERE ID='".$_GET['ID']."';");
						$rekord = mysqli_fetch_array($zap_trening);

echo '<p class="P_MAIN">Edytowanie treningu <span class="S_MAIN_NG">[</span>'.$_GET["ID"].'<span class="S_MAIN_NG">]</span></p>';
echo '<form action="" method="GET" ENCTYPE="multipart/form-data" name="TRENING">';
echo '<input type="hidden" name="IDW" value="2" />';
echo '<input type="hidden" name="IDM" value="'.$IDM.'" />';
echo '<input type="hidden" name="ID" value="'.$ID.'" />';
echo '<table class="TAB_TREN"><tr>';
//----------------------------------------------------------------------------------DANE---------------------------------------------------------
//$col_width= Array(190, 150, 220, 220); //zmienne tablicowe indeksowane sa od 0
$col_width= Array(100,100,200,200); //zmienne tablicowe indeksowane sa od 0 
$col_textarea=Array(100,150,220,220); // //$cols_textarea=9; 14 24 24
$poz_id=Array($rekord[19],$rekord[21],$rekord[23],$rekord[25]);
$poz_wart=Array($rekord[18],$rekord[20],$rekord[22],$rekord[24]);
$rozmiar=array($rekord[6],$rekord[7],$rekord[8],$rekord[9]);
$kolor_hex=array($rekord[5],$rekord[10]);
$pozycja_css=Array($rekord[26],$rekord[27],$rekord[28],$rekord[29]);
$tabl_nagl=Array("Nazwa grupy","Wiek","Dzień godzina treningu","Zapisy");
$gwiazdka="<span class=\"S_LEG_INFO\">*</span>";
$domyślny_kom=' (aktualny)';
for ($tab=0;$tab<4;$tab++){
							echo '<td width="'.$col_width[$tab].'px" valign="TOP"><p class="P_TD">'.$tabl_nagl[$tab].' '.$gwiazdka.' :</p>';
							//----------------------------FORMATOWANIE-TESKTU-CSS-NAGLOWEK--------------------------------------
							$i_wh_css=0;
							$CSS_POKAZ_ALL[$tab]="";
							$CSS_pokaz="";
							
							$SEL_CSS= $db->query("select NAZWA,WSK_V FROM CSS WHERE WSK_V=1 AND ID_GROUP=0 ORDER BY ID");
							while($REK_CSS = mysqli_fetch_array($SEL_CSS)){
																			if ($rekord[17]==2){
																								list($css_tab[$tab][0],$css_tab[$tab][1],$css_tab[$tab][2],$css_tab[$tab][3]) = explode('|', $pozycja_css[$tab]);
																			};
																			if ((!isset($_GET["CSS"])) && ($css_tab[$tab][$i_wh_css]==$REK_CSS[1])) {
																														$domyslny='checked="checked"';
																														$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(aktualny)</span>';
																														if ($i_wh_css==0) $CSS_pokaz.=' font-weight:bold; ';
																														if ($i_wh_css==1) $CSS_pokaz=' font-style:italic; ';
																														if ($i_wh_css==2) $CSS_pokaz=' text-decoration: underline; '; 
																			}
																			else if (isset($_GET["CSS_".$tab."_".$i_wh_css])) {
																										$domyslny='checked="checked"'; 
																										$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																										if ($i_wh_css==0) $CSS_pokaz.=' font-weight:bold; '; 
																										if ($i_wh_css==1) $CSS_pokaz.=' font-style:italic; '; 
																										if ($i_wh_css==2)$CSS_pokaz.=' text-decoration: underline; '; 
																										} 
																			else {
																					$domyslny=''; 
																					$domyslny_kom='';
																					if ($i_wh_css==0) $CSS_pokaz.=' font-weight:normal; ';
																						if ($i_wh_css==1) $CSS_pokaz.=' font-style: normal; '; //'.$domyslny.'  class="CSS_CHBOX" 
																							if ($i_wh_css==2) $CSS_pokaz.='';
																				};
							$CSS_POKAZ_ALL[$tab].='<input type="checkbox" name="CSS_'.$tab.'_'.$i_wh_css.'" value="'.$REK_CSS[1].'" '.$domyslny.' /><span class="S_CSS">'.$REK_CSS[0].'</span> '.$domyslny_kom.'<br/>';
							$i_wh_css++;
							};	// Koniec petla WHILE
							//----------------------------KONIEC-FORMATOWANIE-TESKTU-CSS-NAGLOWEK--------------------------------------
							//----------------------------KOLOR-NAGLOWEK--------------------------------------
							if (isset($_GET["kolor0"])){
																list($kolor_hex[0]) = explode('|', $_GET["kolor0"]);
																list($kolor_hex[1]) = explode('|', $_GET["kolor1"]);
							};
							//----------------------------KONIEC-KOLOR-NAGLOWEK--------------------------------------
							//----------------------------ROZMIAR-TEKSTU-NAGLOWEK-----------------------------------
							if(isset($_GET["rozmiar".$tab])) {
																	$rozmiar[$tab]=$_GET["rozmiar".$tab];
																	$domyślny_kom=' (ustawiona)';
							};
							//----------------------------KONIEC-ROZMIAR-TEKSTU-NAGLOWEK--------------------------
							//----------------------------WYRÓWNANIE-TEKSTU-NAGLOWEK-----------------------------------
							if (isset($_GET["pozycja".$tab])){
																list($poz_id[$tab],$poz_wart[$tab]) = explode('|', $_GET["pozycja".$tab]);
							};
							//----------------------------KONIEC-WYRÓWNANIE-TEKSTU-NAGLOWEK-----------------------------------
							echo '<center><textarea name="dane'.$tab.'" rows="20"  class="TEXTAREA_TRENING" style="width:'.$col_textarea[$tab].'px ; font-size:'.$rozmiar[$tab].'px; background:'.$kolor_hex[0].'; color:'.$kolor_hex[1].'; text-align:'.$poz_wart[$tab].';'.$CSS_pokaz.';">';
								if (isset($_GET["dane".$tab]))	{
																	echo $_GET["dane".$tab];
								} 
								else  {
										$tab++;							//$konv = $rekord[$tab]; 						//echo '$_rekord[dane'.$tab.'] = '.$rekord[$tab];
										$zmieniony = str_replace("<br />","",$rekord[$tab]); //- w tym przypadku znak "+" zostanie zastąpiony wyrazem "plus"	
										echo $zmieniony; $tab--;
																				//echo '$_GET["dane"$tab] = '.$_GET["dane".$tab];
								};
								echo '</textarea></center>';
							echo '<p class="P_ERROR">'.$err_[$tab].'</p></td>';
};
//----------------------------------------------------------------------------------KONIEC-DANE------------------------------------------------------							
echo '</tr><tr>';
//----------------------------------------------------------------------------------ROZMIAR-CIALO------------------------------------------------
for ($tab_roz=0;$tab_roz<4;$tab_roz++){
										echo '<td valign="TOP"><p class="P_TD">Rozmiar czcionki : </p>';
										echo '<center><p class="P_CSS_NG_L"><select name="rozmiar'.$tab_roz.'" class="SELECT">';
										echo '<optgroup label="Aktualny">';
										echo '<option value="'.$rozmiar[$tab_roz].'" class="OPTION_R">'.$rozmiar[$tab_roz].' '.$domyślny_kom.'</option>';
										echo '</optgroup><optgroup label="Dostępne">';
										for( $i = 12; $i<33;) {
																if ($rozmiar[$tab_roz]!=$i) echo '<option value="'.$i.'" class="OPTION_R">'.$i.'</option>';
																$i=$i+2; 
										};
										echo '</optgroup></select></p></center></td>';								
};
//----------------------------------------------------------------------------------KONIEC-ROZMIAR-CIALO----------------------------------------------
//----------------------------------------------------------------------------------WYRÓWNANIE-TESKTU---------------------------------------------------------
echo '</tr><tr>';
for ($tab_roz=0;$tab_roz<4;$tab_roz++){
										if(isset($_GET["pozycja".$tab_roz])) {
																			list($poz_id[$tab_roz],$poz_wart[$tab_roz],$poz_nazwa[$tab_roz]) = explode('|', $_GET["pozycja".$tab_roz]);
																			$domyślny_kom=' (ustawiony)';
										} else {
												if ($rekord[17]==1){
																	if ($tab_roz<3){
																					$poz_id[$tab_roz]=3;
																					$poz_wart[$tab_roz]="LEFT";
																					$poz_nazwa[$tab_roz]="Lewo";
																	}
																	else {
																			$poz_id[$tab_roz]=1;
																			$poz_wart[$tab_roz]="CENTER";
																			$poz_nazwa[$tab_roz]="Wyśrodkuj";
																	}
												} 
												else {
														//echo "ID_POZ - $pozycja_t</br>";
																			
																			
																			$r_pozycja = mysqli_fetch_row($db->query("select ID_OPCJ,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=1 AND ID_OPCJ='".$poz_id[$tab_roz]."';"));
																			$poz_id[$tab_roz]=$rekord[19];
																			$poz_wart[$tab_roz]=$r_pozycja[2];
																			$poz_nazwa[$tab_roz]=$r_pozycja[1];
												};
																	
																};
										echo '<td width="'.$col_width[$tab_width].'" valign="TOP">';
										echo '<p class="P_TD">Pozycja tekstu: </p><center>';
										echo '<p class="P_CSS_NG_L"><select name="pozycja'.$tab_roz.'" class="SELECT">';
										echo '<optgroup label="Aktualny :" class="OPTGROUP">';
										echo '<option value="'.$poz_id[$tab_roz].'|'.$poz_wart[$tab_roz].'|'.$poz_nazwa[$tab_roz].'" class="OPTION">'.$poz_nazwa[$tab_roz].' '.$domyślny_kom.'</option>';
										echo '</optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
										
										$SEL_POZ_ALL = $db->query("select ID_OPCJ,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=1 AND ID_OPCJ!='$poz_id[$tab_roz]'  order by ID_OPCJ");
										while($r_pozycja = mysqli_fetch_array($SEL_POZ_ALL)){
																							echo '<option value="'.$r_pozycja[0].'|'.$r_pozycja[2].'|'.$r_pozycja[1].'" class="OPTION">';
																							echo $r_pozycja[1].'</option>';						
										};
										echo '</optgroup></select></p></center>';
										echo '</td>';								
};
echo '</tr><tr>';
//----------------------------------------------------------------------------------KONIEC-WYRÓWNANIE-TESKTU---------------------------------------------------------
//----------------------------------------------------------------------------------FORMATOWANIE-TESKTU---------------------------------------------------------
echo '</tr><tr>';
for ($tab_roz=0;$tab_roz<4;$tab_roz++){
										echo '<td width="'.$col_width[$tab_width].'" valign="TOP">';
										echo '<p class="P_TD">Formatowanie tekstu: </p><p class="P_CSS_NG_L">';
										echo $CSS_POKAZ_ALL[$tab_roz];
										echo '</p></td>';								
};
echo '</tr><tr>';
//----------------------------------------------------------------------------------KONIEC-FORMATOWANIE-TESKTU---------------------------------------------------------
//----------------------------------------------------------------------------------KOLOR----------------------------------------------------
for($tab_col=0;$tab_col<2;$tab_col++){
										if ($tab_col==0) $naglowek_info="tła"; else if ($tab_col==1) $naglowek_info="tekstu";
										echo '<td colspan="2" valign="TOP"><p class="P_TD">Kolor '.$naglowek_info.' : </p><center>';
										if($_GET["kolor".$tab_col]!='') {
																	list($kolor_tekst,$kolor_tek_e_id) = explode('|', $_GET["kolor".$tab_col]);
																	
																	
																	$col=mysqli_fetch_row($db->query("select ID,NAZWA,HEX from COLOR WHERE ID='$kolor_tek_e_id' AND WSK_U=0"));
																	$kolor_tek_e_id=$col[0];
																	$kolor_tek_e_n=$col[1];
																	$kolor_tek_hex=$col[2];
																	if ($col[2]=='#000000') $kolor_tek_font='#FFFFFF'; else  $kolor_tek_font='#000000';
																	$domyślny_kom=' (ustawiony)';
																	} 
										else {
												if ($tab_col==0) $HEX=$rekord[5]; else if ($tab_col==1) $HEX=$rekord[10]; // HEX == 5 rekord KOLOR TLO 5  HEX == 10 rekord 10 KOLOR TEKST
												
												
												
												$DOM_COL=mysqli_fetch_row($db->query("select c.ID,c.NAZWA,c.HEX FROM COLOR c WHERE HEX='$HEX'"));
												$kolor_tek_e_id=$DOM_COL[0];
												$kolor_tek_e_n=	$DOM_COL[1];
												$kolor_tek_hex=	$DOM_COL[2];
												if ($DOM_COL[2]=='#000000') $kolor_tek_font='#FFFFFF'; else  $kolor_tek_font='#000000';
												$domyślny_kom=' (aktualny)';
										};
										echo '<p class="P_SEL"><select name="kolor'.$tab_col.'" class="SELECT"><optgroup label="Aktualny">';
										echo '<option value="'.$kolor_tek_hex.'|'.$kolor_tek_e_id.'" style="font-weight:bold; color:'.$kolor_tek_font.';background: none repeat scroll 0%  0% '.$kolor_tek_hex.';">'.$kolor_tek_e_n.' '.$domyślny_kom;
										echo '</option></optgroup><optgroup label="Pozostałe">';
										
										$wyswietl = $db->query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 AND ID!='$kolor_tek_e_id' order by ID");
										while($rekord_col = mysqli_fetch_array($wyswietl))
																						{
																						if ($rekord_col[2]=='#000000') $kolor_tek_font='#FFFFFF'; else  $kolor_tek_font='#000000';
																						echo '<option value="'.$rekord_col[2].'|'.$rekord_col[0].'" style="color:'.$kolor_tek_font.'; background: none repeat scroll 0%  0% '.$rekord_col[2].';">'.$rekord_col[1].'</option>';
																						}
										echo '</optgroup></select></p></center></td>';
};
//----------------------------------------------------------------------------------KONIEC-KOLOR----------------------------------------------------
echo '</tr></table>';
//echo '<p class="P_SUBMIT"><input type="submit" value="Edytuj" name="trening" class="inp_button"/></p>
echo '<input type="hidden" name="CSS" value="1" />';
echo '<input class="button_dodaj" type="submit" value="Edytuj" name="trening">';
echo '<input class="button_dodaj" type="submit" value="Podgląd" name="podglad">';
echo '</form>';
echo '<p class="P_LEG">Legenda :</p>';
echo '<p class="P_LEG_INFO">';
echo '- pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane;<br/>';
echo '- Pola NAZWA GRUPY, WIEK, DZIEŃ GODZINA TRENINGU,ZAPISY muszą zawierać MIN (<span class="S_LEG_INFO">5</span>) znaków; <br/> ';
echo '- Pola NAZWA GRUPY, WIEK, DZIEŃ GODZINA TRENINGU, mogą zawierać MAX (<span class="S_LEG_INFO">200</span>) znaków;<br/> ';
echo '- Pole ZAPISY może zawierać MAX (<span class="S_LEG_INFO">400</span>) znaków;<br/> ';
}
else if ($status_edytuj==1){
$pozyzja_id=array('','','','');
$pozycja_wart=	array('','','','');
$CSS_dane= array(0,0,0,0);
for ($css_1=0;$css_1<4;$css_1++){
																						if ($_GET["CSS_".$css_1."_0"]!='') {
																															$CSS_dane[$css_1]=$_GET["CSS_".$css_1."_0"];
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
for ($d_ins=0;$d_ins<4;$d_ins++){
									$dane_ins[$d_ins] = nl2br($_GET["dane".$d_ins]);
									for( $css_licz=1; $css_licz<3; $css_licz++ ) {
																																		if(isset($_GET["CSS_".$d_ins."_".$css_licz])) {
																																														$CSS_dane[$d_ins]=$CSS_dane[$d_ins].'|'.$_GET["CSS_".$d_ins."_".$css_licz];
																																		}
																																		else {
																																				$CSS_dane[$d_ins]=$CSS_dane[$d_ins].'|0';
																																		};
																																		if ($_SESSION['id_user']==1){
																																									echo '<p CLASS="P_INFO">CSS_<span class="S_INFO">'.$d_ins.'</span>_<span class="S_INFO">'.$css_licz.'</span> - <span class="S_INFO">'.$CSS_dane[$d_ins].'</span></p>';
																																		};
																						};
									list($pozyzja_id[$d_ins],$pozycja_wart[$d_ins]) = explode('|', $_GET["pozycja".$d_ins]);	
};				
for ($tab_ins=0;$tab_ins<4;$tab_ins++){
										$dane_ins[$tab_ins] = nl2br($_GET["dane".$tab_ins]);
										};
list($kolor_tlo_hex) = explode('|', $_GET["kolor0"]);
list($kolor_tekst_hex) = explode('|', $_GET["kolor1"]);
							
							$db->query("UPDATE `TRENING` SET `NAZWA_GRUPY`='$dane_ins[0]',`ROK`='$dane_ins[1]',`DZIEN_GODZINA`='$dane_ins[2]',`OPIS`='$dane_ins[3]', `KOLOR_TLA`='$kolor_tlo_hex',`N_G_ROZMIAR`='".$_GET['rozmiar0']."', `W_ROZMIAR`='".$_GET['rozmiar1']."',`D_G_ROZMIAR`='".$_GET['rozmiar2']."',`Z_ROZMIAR`='".$_GET['rozmiar3']."',`KOLOR_TEKST`='$kolor_tekst_hex',`ID_PERS`='".$_SESSION['id_user']."',`DAT_K`=NOW(),`WSK_K`=WSK_K+1,`VER`=2,`P_N_G`='$pozycja_wart[0]',`ID_P_N_G`='$pozyzja_id[0]',`P_W`='$pozycja_wart[1]',`ID_P_W`='$pozyzja_id[1]',`P_D_G`='$pozycja_wart[2]',`ID_P_D_G`='$pozyzja_id[2]',`P_Z`='$pozycja_wart[3]',`ID_P_Z`='$pozyzja_id[3]',`CSS_N_G`='$CSS_dane[0]',`CSS_W`='$CSS_dane[1]',`CSS_D_G`='$CSS_dane[2]',`CSS_Z`='$CSS_dane[3]' WHERE `ID`='".$_GET['ID']."'");
							echo '<p class="P_MAIN">Twój trening został uaktualniony</p>';
							
							
							echo '<center><div style="margin-left:50px;"><table style="background-color:#E6E6FA; width:710px; border:1px solid white; border-collapse: collapse;"><tr>';
												$col_width[0]=190;
												$col_width[1]=150;
												$col_width[2]=220;
												$col_width[3]=220;
														$r=6;
														for ($i=0;$i<4;$i++){
																			echo '<td style="background-color:'.$kolor_tlo_hex.';width:'.$col_width[$i].'px;"><span style="color:'.$kolor_tekst_hex.';font-size:'.$_GET["rozmiar".$r].';">'.$_GET["dane".$i].'</span></td>';
																			$r++;
																			};
														echo '</tr></table></div></center>';
									
		echo '<a class="A_BACK" href="cel_TRENINGI.php?IDW=0&IDM=15"><p class="P_HREF_BACK2">Powrót do MENU - TRENINGI</a></p>';
};
echo "</div></center></body>";