<?php  if(!defined('ACT_PERM')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<a href="'.PAGE_URL.'&IDW=0" class="A_BACK"><p class="P_HREF_BACK">Anuluj</p></a>';
echo '<p CLASS="P_MAIN_CEL">'.$frameTitle.'</p>';
//------------------------------------------------------------------POBIERANIE-PARM-Z-BAZY------------------------------------------------------
if(file_exists(DR."/pod_strony/_include_/pobr_parm.php")) include(DR."/pod_strony/_include_/pobr_parm.php"); else echo "Nie można załadować potrzebnego pliku - pobr_parm.php . Skontaktuj się z Adminstratorem!";
//------------------------------------------------------------------KONIEC-POBIERANIE-PARM-Z-BAZY------------------------------------------------------
													
							$checked=FALSE;;
							$status_dodaj=0;
                                                        $err=array();
							if (isset($_POST["trening"]))
                                                        {
															$checked=TRUE;
															$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ ,*.'!:-_\r\n]+$/";
								for ($tab_ch=0;$tab_ch<4;$tab_ch++){							   
															  if(!preg_match($string_exp,$_POST["dane".$tab_ch])) { 
																													$err[$tab_ch]="Proszę usunąć niedozwolone znaki"; 
																													$checked=FALSE;
																};
								};
								for ($tab_ch=0;$tab_ch<4;$tab_ch++){
																	$size_max=200;
																	if ($tab_ch==4) $size_max=400;
																	check_len($checked, $_POST["dane".$tab_ch],$size_max,$err[$tab_ch],'<span class="S_ERR_DANE">MAX znaków - <span CLASS="S_ERR_DANE2">'.$size_max.'</span></span>',5,'<span class="S_ERR_DANE">MIN znaków - <span CLASS="S_ERR_DANE2">5</span></span>');
																	};
							};
								if ($checked==TRUE) $status_dodaj=1;
								if ($_SESSION['id_user']==1) {
																echo "<p class=\"P_INFO\">Status dodaj - <span class=\"S_INFO\">".$status_dodaj."</span></p>";
																echo "<p class=\"P_INFO\">Status check - <span class=\"S_INFO\">".$checked."</span></p>";
																};
								if ($status_dodaj==0){	
														
														
														echo '<form action="" method="POST" ENCTYPE="multipart/form-data" name="TRENING">';
														echo '<table class="TAB_TREN"><tr>';
//----------------------------------------------------------------------------------DANE---------------------------------------------------------
								$col_width= Array(100,100,200,200); //zmienne tablicowe indeksowane sa od 0 
								$col_textarea=Array(100,150,220,220); // //$cols_textarea=9; 14 24 24
								$col_naglowek=Array("Nazwa grupy","Wiek","Dzień godzina treningu","Zapisy");
								$rozmiar_podstawowy=14;
								$gwiazdka="<span class=\"S_LEG_INFO\">*</span>";
								//-------------------------------------------------------------------FONT-FAMILY-TEKSTU-pozycja naglowek
								if(filter_input(INPUT_POST,'font_family')!='')
								{
									list($font_f_id,$font_f_wart) = explode('|', $_POST["font_family"]);
									$domyślny_kom=' (ustawiony)';
								}
								//-------------------------------------------------------------------KONIEC-FONT-FAMILY-TEKSTU-pozycja naglowek
								for ($tab=0;$tab<4;$tab++){
															$CSS_pokaz="";
															$CSS_POKAZ_ALL="";
															echo '<td style="WIDTH: '.$col_width[$tab].'px;" valign="TOP"><p class="P_TD">'.$col_naglowek[$tab].' '.$gwiazdka.' : </p>'; //WIDTH : '.$col_width.'   border: 1px solid black; 
															//-------------------------------------------------------------------naglowek-rozmiar
															$parm_roz=1;
                                                                                                                        if(filter_input(INPUT_POST,"rozmiar".$tab)!=''){
															
																	$rozmiar[$tab]=$_POST["rozmiar".$tab];
																	$domyślny_kom=' (ustawiona)';
															}
															//-------------------------------------------------------------------kiniec-naglowek-rozmiar
															//-------------------------------------------------------------------WYRÓWNANIE-TEKSTU-pozycja naglowek
															if(filter_input(INPUT_POST,"pozycja".$tab)!=''){
                                                                                                                        
																								list($poz_id[$tab],$poz_wart[$tab],$poz_nazwa[$tab]) = explode('|', $_POST["pozycja".$tab]);
																								$domyślny_kom=' (ustawiony)';
															}
															//-------------------------------------------------------------------KONIEC-WYRÓWNANIE-TEKSTU-pozycja naglowek
															
															
															//----kolor-naglowek--------------------------------------
															if ($tab<2){
                                                                                                                            if(filter_input(INPUT_POST,"kolor".$tab)!=''){
																		
																				list($kolor_tekst[$tab],$kolor_tek_e_id[$tab]) = explode('|', $_POST["kolor".$tab]);
																				
																				
																				$col=mysqli_fetch_row($db->query("select ID,NAZWA,HEX from COLOR WHERE ID='$kolor_tek_e_id[$tab]' AND WSK_U=0"));
																				$kolor_tek_e_id[$tab]=$col[0];
																				$kolor_tek_e_n[$tab]=$col[1];
																				$kolor_tek_hex[$tab]=$col[2];
																				if ($col[2]=='#000000') $kolor_tek_font[$tab]='#FFFFFF'; else  $kolor_tek_font[$tab]='#000000';
																				$domyślny_kom_kol[$tab]=' (ustawiony)';
																				} 
																			else {
																					
																					
																					$DOM_COL=mysqli_fetch_row($db->query("select c.ID,p.NAZWA,p.WART FROM PARM p,COLOR c WHERE p.WART=c.HEX and p.WSK_D=1 AND p.ID_GROUP=3 AND p.ID_OPCJ='$tab'"));
																					$kolor_tek_e_id[$tab]=$DOM_COL[0];
																					$kolor_tek_e_n[$tab]=$DOM_COL[1];
																					$kolor_tek_hex[$tab]=$DOM_COL[2];
																					if ($DOM_COL[2]=='#000000') $kolor_tek_font[$tab]='#FFFFFF'; else  $kolor_tek_font[$tab]='#000000';
																					$domyślny_kom_kol[$tab]=' (domyślny)';
																			};
															};
															//--koniec-kolor-naglowek
															//--------------------------------FORMATOWANIE-CSS-TESKTU---------------------------------------------------------
															$i_wh_css=0;
															
															$SEL_CSS= $db->query("select NAZWA,WSK_V FROM CSS WHERE WSK_V=1 AND ID_GROUP=0 ORDER BY ID");
															while($REK_CSS = mysqli_fetch_array($SEL_CSS)){
																											//echo 'CSS_ '.$_POST["CSS_".$tab."_".$i_wh_css].'</br>';
																											if((!isset($_POST["CSS"])) && ($css_tab[$tab][$i_wh_css]==$REK_CSS[1])){
																																												$domyslny='checked="checked"';
																																												$domyslny_kom='<span CLASS="S_DOM">(domyślny)</span>';
																																												if ($i_wh_css==0) $CSS_pokaz.=' font-weight:bold; '; 
																																												else if ($i_wh_css==1) $CSS_pokaz.=' font-style:italic; '; 
																																												else if ($i_wh_css==2) $CSS_pokaz.=' text-decoration: underline; '; 
																											}
																											else if (isset($_POST["CSS_".$tab."_".$i_wh_css])) {
																																		$domyslny='checked="checked"'; 
																																		$domyslny_kom='<span CLASS="S_DOM">(ustawiony)</span>';
																																		if ($i_wh_css==0) $CSS_pokaz.=' font-weight:bold; '; 
																																		else if ($i_wh_css==1) $CSS_pokaz.=' font-style:italic; '; 
																																		else if ($i_wh_css==2) $CSS_pokaz.=' text-decoration: underline; '; 
																											} else {
																													$domyslny=""; 
																													$domyslny_kom="";
																													$CSS_pokaz.="";
																											};
																											 
															$CSS_POKAZ_ALL.='<input type="checkbox" name="CSS_'.$tab.'_'.$i_wh_css.'" value="'.$REK_CSS[1].'" '.$domyslny.' /><span class="S_CSS">'.$REK_CSS[0].'</span> '.$domyslny_kom.'<br/>';
															//echo "$i_wh_css - $CSS_POKAZ[$tab] </br>";
															$i_wh_css++;
															};	// Koniec petla WHILE
															//echo "Ostatecznie - $CSS_POKAZ[$tab] </br>";
															//--------------------------------KONIEC-FORMATOWANIE-CSS-TESKTU---------------------------------------------------------
															//cols="'.$cols_textarea.'"  rows="20"
															echo '<center><textarea name="dane'.$tab.'"  class="TEXTAREA_TRENING" style="font-family:'.$font_f_wart.'; width:'.$col_textarea[$tab].'px ;font-size:'.$rozmiar[$tab].'px; background:'.$kolor_tek_hex[0].'; color:'.$kolor_tek_hex[1].'; text-align:'.$poz_wart[$tab].';'.$CSS_pokaz.';">';
															
                                                                                                                        if (filter_input(INPUT_POST,"dane".$tab)!=''){
                                                                                                                       
																		echo $_POST["dane".$tab];
															}			
															echo '</textarea></center>';
															echo '<p class="P_ERROR">';
                                                                                                                        if(array_key_exists($tab, $err))
                                                                                                                        {
                                                                                                                            echo $err[$tab];
                                                                                                                        }
                                                                                                                        echo '</p>';
															//----------------------------------------------------------------------------------ROZMIAR---------------------------------------------------------
																	echo '<p class="P_TD">Rozmiar czcionki : </p><center>';
																	echo '<center><p class="P_CSS_NG_C"><select name="rozmiar'.$tab.'" class="SELECT_M">';
																	echo '<optgroup label="Aktualny" class="OPTGROUP">';
																	echo '<option value="'.$rozmiar[$tab].'" class="OPTION_L">'.$rozmiar[$tab].' '.$domyślny_kom.'</option>';
																	echo '</optgroup><optgroup label="Dostępne" class="OPTGROUP">';
																	for( $i = 12; $i<33;) {
																								if ($rozmiar[$tab]!=$i) echo '<option value="'.$i.'" class="OPTION_L">'.$i.'</option>';
																								$i=$i+2; 
																								};
																	echo '</optgroup></select></p></center>';
															//----------------------------------------------------------------------------------KONIEC-ROZMIAR----------------------------------------------------
															//----------------------------------------------------------------------------------WYRÓWNANIE-TESKTU---------------------------------------------------------
															echo '<p class="P_TD">Pozycja tekstu :</p><center>'; //<p class="P_TD"> </p>
															echo '<p class="P_CSS_NG_C"><select name="pozycja'.$tab.'" class="SELECT_M">';
															echo '<optgroup label="Aktualny :" class="OPTGROUP">';
															echo '<option value="'.$poz_id[$tab].'|'.$poz_wart[$tab].'|'.$poz_nazwa[$tab].'" class="OPTION">'.$poz_nazwa[$tab].' '.$domyślny_kom.'</option>';
															echo '</optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
															
															$SEL_POZ_ALL = $db->query("select ID_OPCJ,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=1 AND ID_OPCJ!='$poz_id[$tab]'  order by ID_OPCJ");
															while($r_pozycja = mysqli_fetch_array($SEL_POZ_ALL)){
																												echo '<option value="'.$r_pozycja[0].'|'.$r_pozycja[2].'|'.$r_pozycja[1].'" class="OPTION">';
																												echo $r_pozycja[1].'</option>';						
															};
															echo '</optgroup></select></p></center>';
															//----------------------------------------------------------------------------------KONIEC-WYRÓWNANIE-TESKTU---------------------------------------------------------
															//----------------------------------------------------------------------------------FORMATOWANIE-TESKTU---------------------------------------------------------
															echo '<p class="P_TD">Formatowanie tekstu : </p>';
															echo '<p class="P_CSS_NG_L">'; // <p class="P_TD"></p>
															echo $CSS_POKAZ_ALL; //$tab_css.' - '.
															echo "</p>";
															//----------------------------------------------------------------------------------KONIEC-FORMATOWANIE-TESKTU---------------------------------------------------------
															echo '</td>';
									}; // KONIEC PETLi $tab
//----------------------------------------------------------------------------------KONIEC-DANE------------------------------------------------------							
									echo '</tr><tr>';
//----------------------------------------------------------------------------------KOLOR-TLO/TEKST--------------------------------------------------
										echo '<td colspan="2" valign="TOP">';
										for($tab_col=0;$tab_col<2;$tab_col++){
										if ($tab_col==0) $naglowek_info="tła"; else if ($tab_col==1) $naglowek_info="tekstu";
										echo '<p class="P_TD">Kolor '.$naglowek_info.' : </p>';
										echo '<p class="P_CSS_NG_C">'; // <p class="P_TD"></p>
										echo '<select name="kolor'.$tab_col.'" class="SELECT"><optgroup label="Aktualny" class="OPTGROUP">';
										echo '<option value="'.$kolor_tek_hex[$tab_col].'|'.$kolor_tek_e_id[$tab_col].'" style="font-weight:bold; color:'.$kolor_tek_font[$tab_col].';background: none repeat scroll 0%  0% '.$kolor_tek_hex[$tab_col].';">'.$kolor_tek_e_n[$tab_col].' '.$domyślny_kom_kol[$tab_col];
										echo '</option></optgroup><optgroup label="Pozostałe" class="OPTGROUP">';
										
										$wyswietl = $db->query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 AND ID!='$kolor_tek_e_id[$tab_col]' order by ID");
										while($rekord_col = mysqli_fetch_array($wyswietl))
																						{
																						if ($rekord_col[2]=='#000000') $kolor_tek_font[$tab_col]='#FFFFFF'; else  $kolor_tek_font[$tab_col]='#000000';
																						echo '<option value="'.$rekord_col[2].'|'.$rekord_col[0].'" style="color:'.$kolor_tek_font[$tab_col].'; background: none repeat scroll 0%  0% '.$rekord_col[2].';">'.$rekord_col[1].'</option>';
																						}
										echo '</optgroup></select></p>';
										};
										echo '</td>';
//----------------------------------------------------------------------------------KONIEC-KOLOR-TLO/TEKST--------------------------------------------------
//----------------------------------------------------------------------------------FONT-FAMILY-CIAŁO--------------------------------------------------
echo '<td colspan="2" valign="TOP">';
										echo '<p class="P_TD">Rodzina czcionki : </p>';
										echo '<p class="P_CSS_NG_C">'; // <p class="P_TD"></p>
										echo '<select name="font_family" class="SELECT"><optgroup label="Aktualna" class="OPTGROUP">';
										echo '<option value="'.$font_f_id.'|'.$font_f_wart.'" style="font-weight:normal; font-size:14px; font-family: '.$font_f_wart.' ; color:black; background: none repeat scroll 0%  0% WHITE;">'.$font_f_wart.' '.$domyślny_kom;
										echo '</option></optgroup><optgroup label="Pozostałe" class="OPTGROUP">';
										
										$wyswietl = $db->query("select NR,NAZWA FROM CSS_FF WHERE WSK_V=1 AND NR!='$font_f_id' order by NR");
										while($rekord_col = mysqli_fetch_array($wyswietl))
																						{
																							echo '<option value="'.$rekord_col[0].'|'.$rekord_col[1].'" style="font-size:14px;color:BLACK; font-family: '.$rekord_col[1].' ;  background: none repeat scroll 0%  0% WHITE;">'.$rekord_col[1].'</option>';
																						};
										
										echo '</optgroup></select></p></td>';
//----------------------------------------------------------------------------------KONIEC-FONT-FAMILY-CIAŁO--------------------------------------------------
echo '</tr></table>';
//echo '<p class="P_SUBMIT"><input type="submit" value="Dodaj"  class="inp_button" name="trening"/></p>';
//echo '<input class="button" type="submit" value="Dodaj" name="artykul">';
echo '<input type="hidden" name="CSS" value="1" />';
echo '<input class="button_dodaj" type="submit" value="Dodaj" name="trening">';
echo '<input class="button_dodaj" type="submit" value="Podgląd" name="podglad">';

echo '</form>';
echo '<p class="P_LEG">Legenda :</p>';
echo '<p class="P_LEG_INFO">';
echo '- pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane;<br/>';
echo '- Pola NAZWA GRUPY, WIEK, DZIEŃ GODZINA TRENINGU,ZAPISY muszą zawierać MIN (<span class="S_LEG_INFO">5</span>) znaków; <br/> ';
echo '- Pola NAZWA GRUPY, WIEK, DZIEŃ GODZINA TRENINGU, mogą zawierać MAX (<span class="S_LEG_INFO">200</span>) znaków;<br/> ';
echo '- Pole ZAPISY może zawierać MAX (<span class="S_LEG_INFO">400</span>) znaków;<br/> ';
}
else if ($status_dodaj==1)
{
$pozyzja_id=array('','','','');
$pozycja_wart=	array('','','','');
$CSS_dane= array(0,0,0,0);
for ($css_1=0;$css_1<4;$css_1++){
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
for ($d_ins=0;$d_ins<4;$d_ins++){
									$dane_ins[$d_ins] = nl2br($_POST["dane".$d_ins]);
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
									list($pozyzja_id[$d_ins],$pozycja_wart[$d_ins]) = explode('|', $_POST["pozycja".$d_ins]);	
};														
list($kolor_tlo_hex) = explode('|', $_POST['kolor0']);//TŁO
list($kolor_tekst_hex) = explode('|', $_POST['kolor1']);//TEKST
														
														$db->query("INSERT INTO TRENING (NAZWA_GRUPY,ROK,DZIEN_GODZINA,OPIS,KOLOR_TLA,N_G_ROZMIAR,W_ROZMIAR,D_G_ROZMIAR,Z_ROZMIAR,KOLOR_TEKST,ID_PERS,DAT_UTW,VER,P_N_G,ID_P_N_G,P_W,ID_P_W,P_D_G,ID_P_D_G,P_Z,ID_P_Z,CSS_N_G,CSS_W,CSS_D_G,CSS_Z) VALUES ('$dane_ins[0]','$dane_ins[1]','$dane_ins[2]','$dane_ins[3]','$kolor_tlo_hex','$_POST[rozmiar0]', '$_POST[rozmiar1]', '$_POST[rozmiar2]' ,'$_POST[rozmiar3]','$kolor_tekst_hex','$_SESSION[id_user]',NOW(),2,'$pozycja_wart[0]','$pozyzja_id[0]','$pozycja_wart[1]','$pozyzja_id[1]','$pozycja_wart[2]','$pozyzja_id[2]','$pozycja_wart[3]','$pozyzja_id[3]','$CSS_dane[0]','$CSS_dane[1]','$CSS_dane[2]','$CSS_dane[3]')");
														echo '<p class="P_MAIN">Twój trening został dodany</p>';
														
														
														
														echo '<center><div style="margin-left:50px;"><table style="background-color:#E6E6FA; width:710px; border:1px solid white; border-collapse: collapse;"><tr>';
																				$col_width=array(190,150,220,220);
														for ($i=0;$i<4;$i++){
																			echo '<td style="background-color:'.$kolor_tlo_hex.';width:'.$col_width[$i].'px;">';
																			echo '<span style="color:'.$kolor_tekst_hex.';font-size:'.$_POST["rozmiar".$i].';">'.$dane_ins[$i].'</span>';
																			
																			echo '</td>';
																			};
														echo "</tr>";
														
														echo '</table></div></center>';

														

echo '<a href="'.PAGE_URL.'&IDW=0" class="A_BACK"><p class="P_HREF_BACK2">Powrót do pod MENU - TRENINGI</p></a>';
}
?>
</div>