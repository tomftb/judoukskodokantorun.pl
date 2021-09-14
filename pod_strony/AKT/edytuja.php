<?php if(!defined('AKT22')) { exit('NO PERMISSION');}?>
<div class="DIV_MAIN">
<?php
$ID = $_GET["ID"];
if (($_GET["ID"]!=''))
{		
		$istnieje_rekord = mysqli_num_rows($db->query("select * from NEWS where ID='$ID'"));
		if ($istnieje_rekord!=0)
                {                   
                    $usuniety_rekord = mysqli_num_rows($db->query("select * from NEWS where ID='$ID' AND WSK_U=1"));
                    if ($usuniety_rekord==0)
                    {	
                        echo '<a href="cel_AKT.php?IDW=0&IDM='.$_GET['IDM'].'"><p style="text-align: left;margin:20px;">Anuluj</p></a>';
                        echo '<p class="P_MAIN">Edytowanie artykułu nr<span class="S_MAIN_NG">[</span>'.$_GET["ID"].'<span class="S_MAIN_NG">]</span></p>';
                        $status_popraw=0;
//------------------------------------------------------------------POBIERANIE-PARM-Z-BAZY------------------------------------------------------
if (!isset($_POST["edytuj"])) {
																if ($_SESSION["id_user"]==1){echo '<p CLASS="P_INFO">Uruchomiono procedurę pobierania parametrów z bazy !</p>';};
																
																$SEL_NEWS = $db->query("select n.ID, n.TYTUL,n.TRESC,n.ADRES,n.R_TYTUL,n.R_TRESC,n.ID_KATALOG,n.ID_COL_TYT,n.ID_COL_TRE,n.ID_P_TYT,n.ID_P_TRE,n.CSS_TYT,n.CSS_TRE,n.ILOZD,n.P_TYT,n.P_TRE FROM NEWS n where n.ID='$ID'");
																$REK_NEWS = mysqli_fetch_row($SEL_NEWS);
																$ID_NEWS=$REK_NEWS[0];
																$ID_KAT=$REK_NEWS[6];
																$ILE_ZD=$REK_NEWS[13];
																list($CSS_TYT[0],$CSS_TYT[1],$CSS_TYT[2])=explode("|",$REK_NEWS[11]);
																echo " CSS - $CSS_TYT[0] , $CSS_TYT[1] , $CSS_TYT[2] </br>";
																list($CSS_TRE[0],$CSS_TRE[1],$CSS_TRE[2])=explode("|",$REK_NEWS[12]);
																$css_tab[0]=$CSS_TYT;
																echo "CSS - $CSS_TRE[0] $CSS_TRE[1] $CSS_TRE[2] </br>";
																$css_tab[1]=$CSS_TRE;
																//$CSS_TRE=$REK_NEWS[12];
																echo "REK_NEWS[14] - $REK_NEWS[14] | REK_NEWS[15] - $REK_NEWS[15] </br>";
																//--------------------------------------------POZYCJA-TEKSTU------------------------------------------------------------------
																$i_poz=0;
																
																
																$REK_CSS = mysqli_fetch_row($db->query("select c.ID,c.NAZWA,c.WART FROM CSS c WHERE c.WART='$REK_NEWS[14]'"));
																$poz_id[$i_poz]=$REK_CSS[0];
																$poz_nazwa[$i_poz]=$REK_CSS[1];
																$poz_wart[$i_poz]=$REK_CSS[2];
																$i_poz++;
																
																
																$REK_CSS = mysqli_fetch_row($db->query("select c.ID,c.NAZWA,c.WART FROM CSS c WHERE c.WART='$REK_NEWS[15]'"));
																$poz_id[$i_poz]=$REK_CSS[0];
																$poz_nazwa[$i_poz]=$REK_CSS[1];
																$poz_wart[$i_poz]=$REK_CSS[2];
																//--------------------------------------------KONIEC-POZYCJA-TEKSTU------------------------------------------------------------------
																$rozmiar[0]=$REK_NEWS[4];
																$rozmiar[1]=$REK_NEWS[5];
																//--------------------------------------------KOLOR-TEKSTU------------------------------------------------------------------
																$i_kol=0;
																
																
																$REK_COLOR = mysqli_fetch_row($db->query("select c.ID,c.NAZWA,c.HEX FROM COLOR c WHERE c.ID='$REK_NEWS[7]'"));
																$kolor_id[$i_kol]=$REK_COLOR[0];
																$kolor_nazwa[$i_kol]=$REK_COLOR[1];
																$kolor_hex[$i_kol]=$REK_COLOR[2];	
																
																$i_kol++;
																
																
																$REK_COLOR = mysqli_fetch_row($db->query("select c.ID,c.NAZWA,c.HEX FROM COLOR c WHERE c.ID='$REK_NEWS[8]'"));
																$kolor_id[$i_kol]=$REK_COLOR[0];
																$kolor_nazwa[$i_kol]=$REK_COLOR[1];
																$kolor_hex[$i_kol]=$REK_COLOR[2];
																
																//--------------------------------------------KONIEC-KOLOR-TEKSTU------------------------------------------------------------------
																
																$kolor_font="";
																$domyślny_kom=" (ustawiony)";
																$max_width=array("0","0");
																$max_height=array("0","0");
																
																$SEL_PARM = $db->query("select ID,ID_GROUP,N_OPCJ,NAZWA,WART,WSK_D FROM PARM WHERE WSK_U=0 AND WSK_V=1 AND ID_MODUL=$_GET[IDM] order by ID_GROUP");
																while($REK_PARM = mysqli_fetch_array($SEL_PARM)){
																								if ($_SESSION["id_user"]==1){echo '<p CLASS="P_INFO">ID_GROUP [<span class="S_INFO">'.$REK_PARM[1].'</span>]</p>';};
																			switch($REK_PARM[1]):
																								case 4:
																											//--------------------------------------------------------SQL-SELECT-IMG-MAX-------------------------------------------------------------------
																											echo '<p CLASS="P_INFO">Wartość [';
																											
																											switch($REK_PARM[2]):
																														default:
																														case "ROZ_IMG_AKT_W_MIN": 
																																				$max_width[1]=$REK_PARM[4]; 
																															break;
																														case "ROZ_IMG_AKT_W_MAX": 
																																				$max_width[0]=$REK_PARM[4];
																															break;
																														case "ROZ_IMG_AKT_H_MIN": 
																																				$max_height[1]=$REK_PARM[4]; 
																															break;
																														case "ROZ_IMG_AKT_H_MAX": 
																																				$max_height[0]=$REK_PARM[4]; 
																														break;
																											endswitch;
																											echo $REK_PARM[2].'] : <span class="S_INFO">';
																											echo $REK_PARM[4].' px</span></p>';
																											//--------------------------------------------------------KONIEC-SQL-SELECT-IMG-MAX------------------------------------------------------------
																											
																									break;
																								default:
																									break;
																			endswitch;
																
																};
								};
//------------------------------------------------------------------KONIEC-POBIERANIE-PARM-Z-BAZY------------------------------------------------------
if (isset($_POST["edytuj"])) {
								$ZD_SPR=$_POST["ILE_PHOTO"];
								$IMG_ZMIANA=FALSE;
								$checked_img=TRUE;
								$checked=TRUE;
								$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ -_,*.'!:\r\n]+$/";
								$err=array('','');
								for($ch_dane=0;$ch_dane<2;$ch_dane++){
/*									 htmlspecialchars
																	if(!preg_match($string_exp,$_POST["dane".$ch_dane])) {
																															$err[$ch_dane]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; 
																															$checked=FALSE;
																	};
																	*/
																	if($ch_dane==1) $ch_max=8000; else $ch_max=2000;
																	check_len($checked, $_POST["dane".$ch_dane],$ch_max,$err[$ch_dane],'<span class="S_ERR_DANE">Pole za długie (maksymalna ilość znaków - <span CLASS="S_ERR_DANE2">'.$ch_max.'</span>)</span>',5,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - <span style="color:black;font-size:14px;">5</span>)</span>');
								};
								for( $y = 1; $y<5; $y++ ) {
															if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"])) {
																					if ($checked_img==TRUE) {
																								if(($_FILES["obraz".$y]["type"] == 'image/gif') || 
																											  ($_FILES["obraz".$y]["type"] == 'image/jpeg') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/jpg') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/png') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/bmp') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/pjpeg')) 
																															{
																																$max_rozmiar = 8388608;
																																if ($_FILES["obraz".$y]["type"] < $max_rozmiar){
																																									$checked_img=TRUE;
																																									$IMG_ZMIANA=TRUE;
																																									if ($_SESSION['id_user']==1) echo "<p class=\"P_INFO\">IMG_ZMIANA = <span class=\"S_INFO\">".$IMG_ZMIANA."</span></p>";
																																									$err_obraz_tab[$y]="<span class=\"S_INFO_OK\">Proszę wczytać jeszcze raz zdjęcie.</span>";
																																									$ZD_SPR++;
																																									} else {
																																											$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Za duży rozmiar obrazu</span> !";
																																											$checked_img=FALSE;
																																											}
																															} else { 
																																	$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Wskazany plik nie jest obrazem </span>!";
																																	$checked_img=FALSE;
																																	}
																											} else { $err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Błąd poprzedniego zdjęcia </span>!";}												
																							//} else { $err_obraz_tab[$y]='<span style="color:red;">Nie wskazano obrazu.</span>'; };
																			} else { $err_obraz_tab[$y]="<span class=\"S_INFO_OK\">Nie wskazano obrazu.</span>"; }; // zmiana na FALSE uniemoliwia dodanie zawodnika bez zdjecia
									}; // KONIEC petla FOR dla plików
echo "checked - $checked | checked_img - $checked_img </br>"; 
if (($checked==FALSE) || ($checked_img==FALSE)) $status_popraw=0; else $status_popraw=1;
};
echo "Status popraw - $status_popraw</br>";
if ($status_popraw==0){
//*********************************************************************************FORM-START*****************************************************************
echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
$i_dane=1;	
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
							//----------------------------------------------------------------------------------KOLOR-Tytul/Treść-NAGŁÓWEK---------------------------------------------
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
							//----------------------------------------------------------------------------------TRESC----------------------------------------------------------------------
							echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA" style="color:'.$kolor_hex[$tr].'; text-align:'.$poz_wart[$tr].'">';  // na przyszlosc style="font-size:'.rozmair[$tr].'px;"
							if (isset($_POST["dane".$tr]))
                                                        {
                                                            echo $_POST["dane".$tr];
							}
							else
                                                        { 
                                                            //echo mb_detect_encoding ($REK_NEWS[$i_dane]);
                                                            //echo str_replace('<br />',"", iconv ( mb_detect_encoding ($REK_NEWS[$i_dane]) , 'UTF-8' , $REK_NEWS[$i_dane] ));
                                                            //echo str_replace('<br />',"",$REK_NEWS[$i_dane]);
                                                            $zmieniony= str_replace("&lt;br /&gt;","",$REK_NEWS[$i_dane]);//&lt;br /&gt;
                                                             $zmieniony= str_replace("<br />","",$zmieniony);//&lt;br /&gt;
                                                            //echo preg_replace('/(<br\s\/>)*/i','a', $REK_NEWS[$i_dane]);
                                                            echo $zmieniony;
                                                            
                                                            //echo $REK_NEWS[$i_dane]; 
                                                            $i_dane++;
							}										
							echo '</textarea>';
							//----------------------------------------------------------------------------------KONIEC-TRESC----------------------------------------------------------------------
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
							
							$SEL_POZ_ALL = $db->query("select ID,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=1 AND ID!='$poz_id[$tr]'  order by ID_OPCJ");
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
							$i_wh_css=0;
							
							$SEL_CSS= $db->query("select NAZWA,WSK_V FROM CSS WHERE WSK_V=1 AND ID_GROUP=0 ORDER BY ID") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_CSS".mysqli_error()."</span></p>");
							while($REK_CSS = mysqli_fetch_array($SEL_CSS)){
																			if ($css_tab[$tr][$i_wh_css]==$REK_CSS[1]) {
																														$domyslny='checked="checked"';
																														$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																			}
														else if ($_POST["CSS_".$tr."_".$i_wh_css]!="") {
																					$domyslny='checked="checked"'; 
																					$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																					
																					} 
														else {
																$domyslny=''; 
																$domyslny_kom='';
														};
							echo '<input type="checkbox" name="CSS_'.$tr.'_'.$i_wh_css.'" value="'.$REK_CSS[1].'" '.$domyslny.' class="CSS_CHBOX"  /><span class="S_CSS">'.$REK_CSS[0].'</span> '.$domyslny_kom.'<br/>';
							//$STYL[$tr].='<input type="checkbox" name="CSS_'.$tr.'_'.$i_wh_css.'" value="'.$REK_CSS[0].'" '.$domyslny.' class="CSS_CHBOX"  /><span class="S_CSS">'.$REK_CSS[1].'</span> '.$domyslny_kom.'<br/>';
							$i_wh_css++;
							};	// Koniec petla WHILE
							//echo $STYL[$TR];
							//----------------------------------------------------------------------------------KONIEC-STYLE-TEXT-Imie/Nazwisko----------------------------------------
							echo '</td></tr></table>';
							echo '<input type="hidden" name="ILE_ZD" value="'.$ILE_ZD.'" />';
							echo '<input type="hidden" name="ILE_ZD" value="'.$ILE_ZD.'" />';
							echo '<input type="hidden" name="ID" value="'.$_GET["ID"].'" />';
							echo '<input type="hidden" name="CSS_DEF_'.$tr.'" value="1" />';
							echo '<input type="hidden" name="MAX_WIDTH_'.$tr.'" value="'.$max_width[$tr].'" />';
							echo '<input type="hidden" name="MAX_HEIGHT_'.$tr.'" value="'.$max_height[$tr].'" />';
							IF(!$_POST["IDWs_poczatek"]) $IDWs_poczatek=$_GET["IDWs_poczatek"]; else $IDWs_poczatek=$_POST["IDWs_poczatek"];
							IF(!$_POST["IDWs_koniec"]) $IDWs_koniec=$_GET["IDWs_koniec"]; else $IDWs_poczatek=$_POST["IDWs_koniec"];
							IF(!$_POST["IDSTR"]) $IDSTR=$_GET["IDSTR"]; else $IDSTR=$_POST["IDSTR"];
							echo '<input type="hidden" name="IDWs_poczatek" value="'.$IDWs_poczatek.'" />';
							echo '<input type="hidden" name="IDWs_koniec" value="'.$IDWs_koniec.'" />';
							echo '<input type="hidden" name="IDSTR" value="'.$IDSTR.'" />';
							echo '</div>';
							};
							echo '<p class="NG_DANE">Poniżej wprowadź adres URL do filmu który chcesz załączyć:</p>';
							echo '<div class="DIV_DODAJ">';
							echo '<textarea name="adres" rows="1" cols="85" class="TEXTAREA">';
							if (isset($_POST["adres"])) echo $_POST["adres"]; else echo $_POST["adres"];
							echo '</textarea></br>';
							echo '</div>';
							echo '<p class="NG_DANE">Część dodawania zdjęć (<span CLASS="S_NG_DANE">Max 4</span>):</p>';
							echo '<div class="DIV_DODAJ">';
							$zap_img = $db->query("select ID,KATALOG,NR_IMG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM NEWS_IMG where ID_NEWS='$ID_NEWS' AND WSK_U=0") or die(mysqli_error());
							for ($z=1; $z<5; $z++){
							$ist_img = $db->query("select ID from NEWS_IMG where ID_NEWS='$ID_NEWS' AND NR_IMG='$z'") or die(mysqli_error());
							$img_pom = mysqli_num_rows($ist_img);
							if ($img_pom!=0){
											$zap_img = $db->query("select ID,KATALOG,NR_IMG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM NEWS_IMG where ID_NEWS='$ID_NEWS' AND NR_IMG='$z' AND WSK_U=0") or die(mysqli_error());
											$rek_img = mysqli_fetch_array($zap_img);
											echo '<div style="border:1px; border-style:solid; margin:10px;">';
											echo '<a HREF="javascript:displayWindow(&#39;../../zdjecia/artykul/'.$ID_KAT.'/'.$rek_img[3].'&#39;,'.$rek_img[4].','.$rek_img[5].')">';
											echo '<img src="../../zdjecia/artykul/'.$ID_KAT.'/'.$rek_img[6].'" alt="Zdjecie" style="WIDTH:'.$rek_img[7].'px; HEIGHT:'.$rek_img[8].'px; border:0px; margin:10px;" />';
											echo '</a><p style="text-align:left; margin:10px;">NR - '.$rek_img[3];
											echo '<input type="file" name="obraz'.$z.'"/>'.$err_obraz_tab[$z].'</p></div>';
							}
							else  {
									
										echo '<div style="border:1px; border-style:solid; margin:10px;">';
														echo '<p class="P_INPUT">Zdjęcie nr : ';
														echo '<span class="S_NG_DANE">'.$z.' </span>';
														echo '<input type="file" name="obraz'.$z.'"/> ';
														echo $err_obraz_tab[$z].'</p>';
														echo "</div>";
								};
							};
							echo '</div>';
							echo '<DIV style="width:800px; height:30px; ">'; // DIV BUTTON DODAJ border: 1px solid green;
							echo '<input class="button" type="submit" value="Edytuj" name="edytuj"></FORM>';
							echo '</DIV>';
							//---------------------------------------------------------------LEGENDA----------------------------
							echo '<DIV style="width:800px; ">'; // DIV LEGEND DODAJ 	border: 1px solid green;
							$tab_legenda=array("pola z GWIAZDKĄ (<span class=\"S_LEG_INFO\">*</span>) wymagane;","TYTUŁ musi zawierać min (<span class=\"S_LEG_INFO\">5</span>) znaków;","TYTUŁ może zawierać max (<span class=\"S_LEG_INFO\">200</span>) znaków;","TREŚĆ musi zawierać min (<span class=\"S_LEG_INFO\">5</span>) znaków;","TREŚĆ może zawierać max (<span class=\"S_LEG_INFO\">4000</span>) znaków;","ZDJĘCIA i FILM nie jest (<span class=\"S_LEG_INFO\">wymagany</span>);","ZDJĘCIA, dozwolony TYP : (<span class=\"S_LEG_INFO\">JPG JPEG PNG BMP GIF</span>);","ZDJĘCIE,  <span class=\"S_LEG_INFO\">MAX</span> Rozmiar ~ <span class=\"S_LEG_INFO\">8 MB</span> ;");
							echo '<p class="P_LEG">Legenda :</p>';
							echo "<ul class=\"UL_LEG\">";
							foreach ($tab_legenda as $key => $value){
								echo "<li class=\"LI_LEG\">$value</li>";
							};
							echo "</ul>";
							echo '</DIV>';
							//---------------------------------------------------------------KONIEC-LEGENDA----------------------------
							echo '</p></center>';
//**********************************************************************************KONIEC-FORM*****************************************************************************						
} 
else if ($status_popraw==1){
							echo '<p class="P_MAIN">Twój artykuł został uaktualniony</p>';
							$ID= $_POST["ID"];		
$CSS_dane= array(0,0);
														for ($css_1=0;$css_1<3;$css_1++){
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
														if ($_SESSION['id_user']==1){ 
																					echo "<p CLASS=\"P_INFO\">".$nagl_wysw."</br>";
																					echo " Kolor (HEX) : <span class=\"S_INFO\">".$kol_hex[$d_ins]."</span></br>";
																					echo " Kolor (ID) : <span class=\"S_INFO\">".$kol_id[$d_ins]."</span></br>";
																					echo " Rozmiar : <span class=\"S_INFO\">".$_POST["rozmiar".$d_ins]."</span></br>";
																					echo " Pozycja (ID) : <span class=\"S_INFO\">".$pozyzja_id[$d_ins]."</span></br>";
																					echo " Pozycja (WART) : <span class=\"S_INFO\">".$pozycja_wart[$d_ins]."</span></br>";
																					echo " CSS : <span class=\"S_INFO\">".$css_tyt_b.$css_tyt_i.$css_tyt_u."</span></p>";
														};
														echo '<p class="NG_DANE">'.$nagl_wysw.' : </p>';
														echo '<div class="DIV_DODAJ">';
														echo '<p style="background:white;margin-left:10px;margin-right:10px;color:'.$kol_hex[$d_ins].';font-size:'.$_POST["rozmiar".$d_ins].'; text-align:'.$pozycja_wart[$d_ins].';'.$font_weight.$font_style.$text_dec.'">'.$dane_ins[$d_ins].'</p>';
														echo '</div>';
														};
if ($IMG_ZMIANA==TRUE) {
						$plik = "edytuja_img.php"; 
						$test_plik = file_exists($plik); 
						if (!$test_plik) 	{
											echo "<p style=\"text-align:left; color:red; margin:10px;\">Brak pliku - <span style=\"color:black;\">".$plik."</span></p>"; 
											}
										else { 
												include($plik);
											};
};

$db->query("UPDATE `NEWS` SET `WSK_K`=WSK_K+1, `TYTUL`='$dane_ins[0]' ,`TRESC`='$dane_ins[1]', `ADRES`='$adres',`K_TYTUL`='$kol_hex[0]',`R_TYTUL`='$_POST[rozmiar0]',`K_TRESC`='$kol_hex[1]',`R_TRESC`='$_POST[rozmiar1]',`ID_COL_TYT`='$kol_id[0]',`ID_COL_TRE`='$kol_id[1]',`P_TYT`='$pozycja_wart[0]',`ID_P_TYT`='$pozyzja_id[0]',`P_TRE`='$pozycja_wart[1]',`ID_P_TRE`='$pozyzja_id[1]',`CSS_TYT`='$CSS_dane[0]',`CSS_TRE`='$CSS_dane[1]',`WSK_V`=0 WHERE `ID`='$_POST[ID]' ");
$db->insDbLog($_GET["IDM"],"EDYTUJ ARTYKUŁ - uaktualniono ID : ".$ID);
echo '<center><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$_POST['ID'].'">Powrót do MENU - ARTYKUŁY</a></center>';
};
							} else {
									echo '<a href="cel_AKT.php?IDW=2&IDM='.$_GET['IDM'].'"><p style="text-align: left;">Popraw dane</p></a>';
									echo '<p style="color:red">Istnieje artykuł o podanym <span style="color:black;">ID</span> ale został on już prędzej usunięty !</p>';
                                                                        $db->insDbLog($_GET["IDM"],'EDYTUJ artykuł - BŁĄD - podany artykuł ma status usunięty - '.$_GET["ID"]);
									}
		} else {
				echo '<a href="cel_AKT.php?IDW=2&IDM='.$_GET['IDM'].'"><p style="text-align: left;">Popraw dane</p></a>';
				echo '<p style="color:red">Nie znaleziono żadnego artykułu o wpisanym <span style="color:black;">ID</span> !</p>';
				$db->insDbLog($_GET["IDM"],'EDYTUJ artykuł - BŁĄD - nie ma artykułu o nr ID - '.$_GET["ID"]);
									
			}
	} else {
			echo '<a href="cel_AKT.php?IDW=2&IDM='.$_GET['IDM'].'"><p style="text-align: left;">Popraw dane</p></a>';
			echo '<p style="color:red;">Nie podałeś <span style="color:black;">ID</span> artykułu !</p>';
			$db->insDbLog($_GET["IDM"],'EDYTUJ artykuł - BŁĄD - nie podałeś ID artykułu');
	}						
?>
</div>																			