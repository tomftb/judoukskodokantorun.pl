<?php if(!defined('TREN41')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
$err_obraz_tab=array('','','');
$err=array('','');
$status_dodaj=0;
$IMG_ZMIANA='';
if (isset($_POST["zawodnik"]))
{
	$checked_img=TRUE;
	$checked=TRUE;
	$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ -_,*.'!:\r\n]+$/";
	$err=array('','');
							for($ch_dane=0;$ch_dane<1;$ch_dane++){	
																if(!preg_match($string_exp,$_POST["dane".$ch_dane])) { $err[$ch_dane]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $checked=FALSE;};
																if($ch_dane==1) $ch_max=5000; else $ch_max=5000;
																check_len($checked, $_POST["dane".$ch_dane],$ch_max,$err[$ch_dane],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">5000</span>)',5,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
																};
							// Tekst poprawny rozpoczynamy upload plików
							
												for( $y = 1; $y<2; $y++ ) {
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
																																									//$IMG_ZMIANA=TRUE;
																																									if ($_SESSION['id_user']==1) echo "<p class=\"P_INFO\">IMG_ZMIANA = <span class=\"S_INFO\">".$IMG_ZMIANA."</span></p>";
																																									$err_obraz_tab[$y]="<span class=\"S_INFO\">Proszę wczytać jeszcze raz zdjęcie.</span>";
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
																			} else { $err_obraz_tab[$y]='<span class="S_ERR_DANE">Nie wskazano obrazu.</span>'; $checked_img=FALSE;}; // zmiana na FALSE uniemoliwia dodanie zawodnika bez zdjecia
																			}; // KONIEC petla FOR dla plików
																			
							if (($checked==FALSE)||($checked_img==FALSE)) $status_dodaj=0; else $status_dodaj=1;
							}; // KONIEC if isset $_POST["ZAWODNIK"]
//---------------------------------------------------------------------------------status_dodaj = 0 ----------------------------------------------------------------
							if ($status_dodaj==0){
													echo '<a class="A_BACK" href="cel_TREN.php?IDW=0&IDM='.$_GET["IDM"].'"><p class="P_HREF_BACK">Anuluj</p></a>';
													echo '<p class="P_MAIN">Dodaj trenera : </p>';
							echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
							echo '<p class="NG_DANE">Zdjęcie <span class="S_NG_DANE">*</span>:</p>';
							echo '<div class="DIV_DODAJ">';
							for( $x = 1; $x<2; $x++ ) {
							echo '<p class="P_INPUT"><input type="file" name="obraz'.$x.'"/>'.$err_obraz_tab[$x].'</p>';
							};
							echo '</div>';
							for ($tr=0;$tr<1;$tr++){
													switch($tr):
																case 0: 
																		$naglowek="OPIS";
																		//$rows=1;
																		$rows=20;
																		$ID_OPCJ_KOL=7;
																		$ID_OPCJ_ROZ=9;
																		$pozycja_d=5;
																		$pozycja_s=5;
																	break;
																case 1:
																		$naglowek="Dodatkowe informacje";
																		$rows=20;
																		$ID_OPCJ_KOL=6;
																		$ID_OPCJ_ROZ=9;
																		$pozycja_d=7;
																		$pozycja_s=7;
																	break;
																case 2:
																	break;
																default:
																	break;
													endswitch;
							echo '<p class="NG_DANE">';
							echo $naglowek.' <span class="S_NG_DANE">*</span>: '.$err[$tr].'</p>';
							echo '<div class="DIV_DODAJ">';
							echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA">';  
							if (isset($_POST["dane".$tr]))
							{
								echo $_POST["dane".$tr];
							}										
							echo '</textarea>';
							echo '<table><tr><td class="TD_L">';
//----------------------------------------------------------------------------------KOLOR-Imie/Nazwisko----------------------------------------------
							if(isset($_POST["kolor".$tr]))
							{
								list($kolor_id,$kolor_hex,$kolor_nazwa) = explode('|', $_POST["kolor".$tr]);
								if ($kolor_hex=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
								$domyślny_kom=' (ustawiony)';
								echo $kolor_id.' '.$kolor_nazwa.' '.$kolor_hex.'</br>';
								echo $_POST["kolor".$tr];
							} 
															else {
																	
																	$SEL_DOM_COL = $db->query("select c.ID,p.NAZWA,p.WART FROM PARM p,COLOR c WHERE p.WART=c.HEX and p.ID_GROUP=3 AND p.ID_OPCJ='$ID_OPCJ_KOL'");
																	$DOM_COL=mysqli_fetch_row($SEL_DOM_COL);
																	$kolor_id=$DOM_COL[0];
																	$kolor_nazwa=$DOM_COL[1];
																	$kolor_hex=	$DOM_COL[2];
																	if ($DOM_COL[2]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
																	$domyślny_kom=' (domyślny)';
															};
							echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Kolor tekstu : ';
							echo '<select name="kolor'.$tr.'" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$kolor_id.'|'.$kolor_hex.'|'.$kolor_nazwa.'" style="font-weight:bold; color:'.$kolor_font.';background: none repeat scroll 0%  0% '.$kolor_hex.';">'.$kolor_nazwa.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
							
							$wyswietl = $db->query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 AND ID!='$kolor_id'order by ID");
							while($r_kolor = mysqli_fetch_array($wyswietl))
																			{
																			if ($r_kolor[2]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
																			echo '<option value="'.$r_kolor[0].'|'.$r_kolor[2].'" style="color:'.$kolor_font.'; background: none repeat scroll 0%  0% '.$r_kolor[2].';">'.$r_kolor[1].'</option>';
																			}

							echo '</optgroup></select></p>';
//----------------------------------------------------------------------------------KONIEC-KOLOR------------------------------------------------------------------------------
//----------------------------------------------------------------------------------POZYCJA-TEXT----------------------------------------------------------------
							if(isset($_POST["pozycja".$tr]))
							{
								list($poz_id,$poz_wart,$poz_nazwa) = explode('|', $_POST["pozycja".$tr]);
								$domyślny_kom=' (ustawiony)';
							} 
							else {
									
									
									$DOM_POZ=mysqli_fetch_row($db->query("select ID,NAZWA,WART FROM PARM WHERE ID_GROUP=0 AND ID_MODUL='$_GET[IDM]'"));
									$poz_id=$DOM_POZ[0];
									$poz_nazwa=$DOM_POZ[1];
									$poz_wart=$DOM_POZ[2];		
									$domyślny_kom=' (domyślny)';
							}; //
							echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Pozycja tekstu : ';
							echo '<select name="pozycja'.$tr.'" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$poz_id.'|'.$poz_wart.'|'.$poz_nazwa.'" class="OPTION">'.$poz_nazwa.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
							
							$SEL_POZ_ALL = $db->query("select ID,NAZWA,WART FROM PARM WHERE WSK_U=0 AND ID_GROUP=0 AND ID!='$poz_id'  AND ID_OPCJ IN (1,2,3) order by ID");
							while($r_pozycja = mysqli_fetch_array($SEL_POZ_ALL))
																			{
																			echo '<option value="'.$r_pozycja[0].'|'.$r_pozycja[2].'" class="OPTION">'.$r_pozycja[1].'</option>';						
																			}
							echo '</optgroup></select></p>';
//-----------------------------------------------------------------------------------KONIEC-POZYCJA-TEXT---------------------------------------------------------
//-----------------------------------------------------------------------------------ROZMIAR-------------------------------------------------------------------------------
							echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Rozmiar czcionki : ';
							if(isset($_POST["rozmiar".$tr])) {
							
														$rozmiar=$_POST["rozmiar".$tr];
														$domyślny_kom=' (ustawiona)';
														}
															else {	
																	
																	$DOM_ROZ=mysqli_fetch_row($db->query("select WART FROM PARM WHERE ID_GROUP=2 AND ID_OPCJ='$ID_OPCJ_ROZ' AND ID_MODUL='$_GET[IDM]'"));
																	$rozmiar=$DOM_ROZ[0]; //Rozmiar Tytuł
																	$domyślny_kom=' (domyślna)';
															};
							echo '<select name="rozmiar'.$tr.'" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$rozmiar.'" class="OPTION">'.$rozmiar.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
							for( $i = 12; $i<33;) {
														if ($rozmiar!=$i) echo '<option value="'.$i.'" class="OPTION">'.$i.'</option>';
														$i=$i+2; 
														};
							echo '</optgroup></select></p>';
//-----------------------------------------------------------------------------------KONIEC-ROZMIAR------------------------------------------------------------------
//-----------------------------------------------------------------------------------STYLE-TEXT--------------------------------------------------------------------------------
							echo '</td><td class="TD_R">';
							echo '<p style="text-align: left; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Wskaż opcję formatowania :</p>';
							
							$SEL_CSS=$db->query("select c.ID,c.NAZWA FROM CSS c WHERE c.ID_GROUP=0 ORDER BY c.ID");
							if(!isset($_POST["CSS_DEF_".$tr])) $CSS_DEF[$tr]=TRUE; else $CSS_DEF[$tr]=FALSE;
							while($REK_CSS = mysqli_fetch_array($SEL_CSS)){
																			//list($def_css_dane0,$def_css_dane1) = explode('|', $REK_CSS[4]);
																			//echo ' CSS - '.$def_css_dane0.'|'.$def_css_dane1.' rekord - '.$REK_CSS[4].'<br/>';
																			if ($CSS_DEF[$tr]!=TRUE) {
																									if (!isset($_POST["CSS_".$tr."_".$REK_CSS[0]])) {
																																							$domyslny=''; 
																																							$domyslny_kom='';
																																							} 
																																							else {
																																									$domyslny='checked="checked"'; 
																																									$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																																								};
																			} 
																			else {
																					
																					$SEL_P_CSS=$db->query("select pc.ID,pc.WSK_V FROM PARM_CSS pc, PARM pm WHERE pc.ID_PARM=pm.ID AND pm.ID_MODUL='$_GET[IDM]' AND pc.ID_CSS='$REK_CSS[0]' AND pm.ID_OPCJ=8 LIMIT 1");
																					$REK_P_CSS = mysqli_fetch_array($SEL_P_CSS);
																					if($REK_P_CSS[1]=="0" ||$REK_P_CSS[1]=="") {$domyslny='';$domyslny_kom=''; }else IF ($REK_P_CSS[1]=="1") {
																					$domyslny='checked="checked"'; 
																					$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(domyślna)</span>';
																					};
																			};
							echo '<input type="checkbox" name="CSS_'.$tr.'_'.$REK_CSS[0].'" value="1" '.$domyslny.' class="CSS_CHBOX"  /><span class="S_CSS">'.$REK_CSS[1].'</span> '.$domyslny_kom.'<br/>';
							};	// Koniec petla WHILE
//----------------------------------------------------------------------------------KONIEC-STYLE-TEXT-Imie/Nazwisko----------------------------------------
echo '</td></tr></table>';
echo '<input type="hidden" name="CSS_DEF_'.$tr.'" value="1" />';
echo '</div>';
};

echo '<input class="button"type="submit" value="Dodaj" name="zawodnik"/></form>';
echo '<p class="P_LEG">Legenda :</p><p class="P_LEG_INFO">';
echo '- pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane;<br/>';
//echo '- IMIĘ/NAZWISKO musi zawierać min (<span class="S_LEG_INFO">5</span>) znaków; <br/> ';
//echo '- IMIĘ/NAZWISKO może zawierać max (<span class="S_LEG_INFO">200</span>) znaków;<br/> ';
//echo '- DODATKOWE INFORMACJE muszą zawierać min (<span class="S_LEG_INFO">5</span>) znaków;<br/> ';
//echo '- DODATKOWE INFORMACJE muszą zawierać max (<span class="S_LEG_INFO">2000</span>) znaków;<br/> ';
echo '- OPIS musi zawierać min (<span class="S_LEG_INFO">5</span>) znaków;<br/> ';
echo '- OPIS może zawierać max (<span class="S_LEG_INFO">2000</span>) znaków;<br/> ';
echo '- ZDJĘCIE, dozwolony TYP : (<span class="S_LEG_INFO">JPG JPEG PNG BMP GIF</span>);<br/> ';
echo '</p></center></div>';
} else if ($status_dodaj==1){
							echo '<a class="A_BACK"href="cel_TREN.php?IDW=0&IDM='.$_GET["IDM"].'"><p class="P_HREF_BACK">Powrót</p></a>';
							echo '<p class="P_MAIN">Trener został dodany.</p>';
$CSS_dane= array(0,0);
for ($css_1=0;$css_1<2;$css_1++){
								if ($_POST["CSS_".$css_1."_1"]!='') {
																	$CSS_dane[$css_1]=$_POST["CSS_".$css_1."_1"];
																	if ($_SESSION['id_user']==1){  echo 'CSS_'.$css_1.'_1 - '.$CSS_dane[$css_1].'<br/>';};
																	}
																	else { 
																		$CSS_dane[$css_1]=0;
																		if ($_SESSION['id_user']==1){  echo 'CSS_'.$css_1.'_1 - '.$CSS_dane[$css_1].'<br/>';};
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
$rozmiar=array('','');
//for ($d_ins=0;$d_ins<2;$d_ins++){
for ($d_ins=0;$d_ins<1;$d_ins++){	
								for( $css_licz=2; $css_licz<4; $css_licz++ ) {
																				if(isset($_POST["CSS_".$d_ins."_".$css_licz])) {
																																$CSS_dane[$d_ins]=$CSS_dane[$d_ins].'|'.$_POST["CSS_".$d_ins."_".$css_licz];
																				} else {
																						$CSS_dane[$d_ins]=$CSS_dane[$d_ins].'|0';
																				};
								if ($_SESSION['id_user']==1){ echo 'CSS_'.$d_ins.'_'.$css_licz.' - '.$CSS_dane[$d_ins].'<br/>';};
								};
								if ($d_ins==0) $nagl_wysw="Imie/Nazwisko"; else if ($d_ins==1) $nagl_wysw="Dodatkowe informacje";
										if ($_SESSION['id_user']==1){ echo 'CSS '.$nagl_wysw.' - '.$CSS_dane[$d_ins].'<br/>';};
										list($css_b[$d_ins],$css_i[$d_ins],$css_u[$d_ins]) = explode('|', $CSS_dane[$d_ins]);
										if ($css_b[$d_ins]==0) $font_weight='font-weight:normal;'; else $font_weight='font-weight:bold;';
										if ($css_i[$d_ins]==0) $font_style='font-style: normal;'; else $font_style='font-style:italic;';
										if ($css_u[$d_ins]==0) $text_dec=''; else $text_dec='text-decoration: underline;';	
								$dane[$d_ins] = nl2br($_POST["dane".$d_ins]);
								list($kol_id[$d_ins],$kol_hex[$d_ins]) = explode('|', $_POST["kolor".$d_ins]);
								list($pozyzja_id[$d_ins],$pozycja_wart[$d_ins]) = explode('|', $_POST["pozycja".$d_ins]);
								if ($_SESSION['id_user']==1){ echo $nagl_wysw." ( Kolor (HEX) : ".$kol_hex[$d_ins]." Kolor (ID) : ".$kol_id[$d_ins]." Rozmiar : ".$rozmiar[$d_ins]." Pozycja (ID) : ".$pozyzja_id[$d_ins]." Pozycja (WART) : ".$pozycja_wart[$d_ins]." CSS : ".$css_tyt_b.$css_tyt_i.$css_tyt_u." )<br/>";};
								echo '<p style="text-align: left; font-size: 20px; font-weight:bold;margin-left:50px;margin-bottom:5px;">OPIS : <br/>';
								echo '<div style="background:white;width:750px; border:1px; border-style:solid; margin:5px; border-color:#D3D3D3;" >';
								echo '<p style="background:white;color:'.$kol_hex[$d_ins].';font-size:'.$_POST["rozmiar".$d_ins].'; text-align:'.$pozycja_wart[$d_ins].';'.$font_weight.$font_style.$text_dec.'">'.$_POST["dane".$d_ins].'</p>';
								echo '</div></p>';
								};

$dane_ins[0] = nl2br($_POST["dane0"]);
$db->query("INSERT INTO TREN (ID_COL_IMIE_N,IMIE_N,ID_PERS,K_IMIE_N,R_IMIE_N,DAT_UTW,UID,ID_P_IMI_N,P_IMI_N_WART,CSS_IMI_N) VALUES ('$kol_id[0]','$dane_ins[0]','$_SESSION[id_user]','$kol_hex[0]','$_POST[rozmiar0]',NOW(),'$_SESSION[uid]','$pozyzja_id[0]','$pozycja_wart[0]','$CSS_dane[0]')");
$ID_ZWDK=$db->last();	
$log->log(0,"[".__FILE__."::".__LINE__."] Ostatnio dodany rekord ma id ".$ID_ZWDK);
/* FILES */																																							
for( $y = 1; $y<2; $y++ )
{
	if (is_uploaded_file($_FILES["obraz".$y]['tmp_name']))
	{		
		$uploads_dir = DR.'/zdjecia/trener/'; 
		$log->log(0,"[".__FILE__."] uploads dir => ".$uploads_dir);	
		$log->log(0,"[".__FILE__."] Odebrano obraz => ".$_FILES["obraz".$y]["name"]);	
		$filename = $_FILES["obraz".$y]["name"];
		$ext = strtolower(substr(strrchr($filename, '.'), 1)); //Get extension
		$log->log(0,"[".__FILE__."::".__LINE__."] ext - ".$ext);		
		$dir=opendir($uploads_dir);
		$ile_zdjec=0;
		while ($plik=readdir($dir))
		{
			if($plik!="." && $plik!="..")
			{
				if (strpos($plik,'min') !== false)
				{
					$log->log(0,"[".__FILE__."] MIN => ".$plik);		
				}
				else if (strpos($plik,"new") !== false)
				{
					$log->log(0,"[".__FILE__."] NEW => ".$plik);	
				}
				else
				{
					
					if (! file_exists($uploads_dir."org_".$ile_zdjec."_trener.".$ext))
					{ 
						
						$log->log(0,"[".__FILE__."] Brak pliku => org_".$ile_zdjec."_trener ".$ext);	
					}
					else
					{
						$log->log(0,"[".__FILE__."] ORG => org_".$plik." nie zawiera min/new");
						$ile_zdjec++;
					};																													
				};			
			};
		};
		closedir($dir);
		$log->log(0,"[".__FILE__."::".__LINE__."] Aktualna ilość zdjęć - ".$ile_zdjec);																
		$image_name='org_'.$ile_zdjec."_trener.".$ext; //New image name
		$log->log(0,"[".__FILE__."::".__LINE__."] NEW IMAGE NAME =>  ".$image_name);			
		$tmp_name = $_FILES["obraz".$y]["tmp_name"]; 
		$log->log(0,"[".__FILE__."::".__LINE__."] TMP IMAGE NAME =>  ".$tmp_name);	
		move_uploaded_file($tmp_name, $uploads_dir.$image_name); 
		list($width, $height) = getimagesize($uploads_dir.$image_name);
		$log->log(0,"[".__FILE__."::".__LINE__."] IMAGE WIDTH =>  ".$width);	
		$log->log(0,"[".__FILE__."::".__LINE__."] IMAGE HEIGHT =>  ".$height);	
//------------------------------------------------------------------------------------------------------RESIZE-IMG--------------------------------------------	
		$SQL_LIKE="%MAX";
		for ($p_resize=0;$p_resize<2;$p_resize++)
		{
			//--------------------------------------------------------SQL-SELECT-IMG-MAX-------------------------------------------------------------------
			$REK_IMG=mysqli_fetch_row($db->query("SELECT WART FROM PARM WHERE ID_MODUL='$_GET[IDM]' AND ID_GROUP=4 AND WSK_U=0 AND N_OPCJ LIKE '$SQL_LIKE' LIMIT 1"));
			//--------------------------------------------------------KONIEC-SQL-SELECT-IMG-MAX------------------------------------------------------------
																																					
																													switch ($p_resize):
																																			case 0: 
																																					$naglowek_img='new_'.$ile_zdjec."_trener.".$ext;
																																					$naglowek="Galeria ";
																																					$SQL_LIKE="%MIN";
																																					break;
																																			case 1:
																																					$
																																					$naglowek_img='min_'.$ile_zdjec."_trener.".$ext;
																																					$naglowek="Miniatura ";
																																					break;
																																			default:
																																					
																																					$naglowek_img='def_'.$ile_zdjec."_trener.".$ext;
																																					$naglowek="DEFAULT ";
																																					$SQL_LIKE="%MIN";
																																					break;
																													endswitch;
																		if ($width>$REK_IMG[0] || $height>$REK_IMG[0])
																		{ // 0 miniatura 324x324   // 1 900x900
																			$wynik_img_size = resize_image($width,$height,$REK_IMG[0],$naglowek_img,$ext,$uploads_dir,$image_name);
																			$new_width[$p_resize]=round($wynik_img_size[0]);
																			$new_height[$p_resize]=round($wynik_img_size[1]);
																			$new_image_name[$p_resize]=$wynik_img_size[2];
																		} else {
																				$new_image_name[$p_resize]=$image_name;
																				$new_height[$p_resize]=round($height);
																				$new_width[$p_resize]=round($width);
																				};
																		if($_SESSION['id_user']==1) {
																									echo "<p class=\"P_INFO\">".$naglowek." WIDTH : <span class=\"S_INFO\">".$new_width[$p_resize]."</span>";
																									echo " HEIGHT : <span class=\"S_INFO\">".$new_height[$p_resize]."</span></p>";
																									};
																		};
//------------------------------------------------------------------------------------------------------END-RESIZE-IMG-------------------------------------------------------------
																$new_width[1]=$new_width[1]+10;
																$new_height[1]=$new_height[1]+10;
																$db->query("INSERT INTO TREN_IMG (ID_TREN,NAZWA_O,NAZWA_I,ID_PERS,DAT_UTW,NR_IMG,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT) VALUES ('$ID_ZWDK','$filename','$new_image_name[0]','$_SESSION[id_user]',NOW(),'$y','$new_width[0]','$new_height[0]','$new_image_name[1]','$new_width[1]','$new_height[1]')");
																if($_SESSION['id_user'==1]) { echo "<p style=\"P_SQL_OK\">Poprawnie wykonano zapytanie SQL - INS_ZWDK_IMG</p>";};
																echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/trener/$new_image_name[0]','$new_image_name[0]','$new_width[0]','$new_height[0]')\">";
																echo '<center><img src="'.APP_URL.'/zdjecia/trener/'.$new_image_name[1].'" alt="'.$new_image_name[1].'" style="width:'.$new_width[1].'px; height:'.$new_height[1].'px; border:0px;" /></center>';
																echo '</a>';
																
								} else { }
							}
							
							echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0#ID'.$ID_ZWDK.'"><p class="P_HREF_BACK2">Powrót do MENU - Trenerzy</p></a>';
							unset($_POST["dane0"]);
							unset($_POST["dane1"]);
							
							//};					
}
?>
</div>