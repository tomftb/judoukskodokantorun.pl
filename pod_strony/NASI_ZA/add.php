<?php if(!defined('PAGE_URL')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php

require(DR.'/class/c_sprawdzImportPlik.php');
require(DR.'/class/c_zmienRozmiarObraz.php');
/* END UPRAWENINIE */	
$status_dodaj=0;
$err=array();
$err_obraz_tab=array();
							if (isset($_POST["zawodnik"])) {
							$checked_img=TRUE;
							$checked=TRUE;
							$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ .'!:-_\r\n]+$/";
							
							for($ch_dane=0;$ch_dane<2;$ch_dane++){
																if(!preg_match($string_exp,$_POST["dane".$ch_dane])) { $err[$ch_dane]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $checked=FALSE;};
																if($ch_dane==1) $ch_max=2000; else $ch_max=200;
																check_len($checked, $_POST["dane".$ch_dane],$ch_max,$err[$ch_dane],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">100</span>)',5,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
																};
							// Tekst poprawny rozpoczynamy upload plików
							$plik = new sprawdzImportowanyPlik(array('image/gif','image/jpeg','image/jpg','image/png','image/bmp','image/pjpeg'),8388608);
							for( $y = 1; $y<2; $y++ )
							{
								if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"]))
								{
									if ($checked_img==TRUE)
									{
										if($plik->sprawdzPlik($_FILES["obraz".$y]["type"],$_FILES["obraz".$y]["size"])!=1)
										{
											$checked_img=FALSE;
											$err_obraz_tab[$y]=$plik->zwrocKomunikatBledu();
										};
									}
									else
									{
										$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Błąd poprzedniego pliku </span>!";
									};											
								}
								else
								{ 
									$err_obraz_tab[$y]='<span class="S_ERR_DANE">Nie wskazano obrazu.</span>'; $checked_img=FALSE;
								}; // zmiana na FALSE uniemoliwia dodanie zawodnika bez zdjecia
							}; // KONIEC petla FOR dla plików
																			
							if (($checked==FALSE)||($checked_img==FALSE)) $status_dodaj=0; else $status_dodaj=1;
							}; // KONIEC if isset $_POST["ZAWODNIK"]
//---------------------------------------------------------------------------------status_dodaj = 0 ----------------------------------------------------------------
							if ($status_dodaj==0){
								echo '<a class="A_BACK" href="cel_NASI_ZA.php?IDW=0&IDM='.$_GET["IDM"].'"><p class="P_HREF_BACK">Anuluj</p></a>';
													echo '<p class="P_MAIN">Dodaj zawodnika : </p>';					
							echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
							for ($tr=0;$tr<2;$tr++){
													switch($tr):
																case 0: 
																		
																		$naglowek="Imie/Nazwisko";
																		$rows=1;
																		$ID_OPCJ_KOL=5;
																		$ID_OPCJ_ROZ=7;
																		$ID_OPCJ_CSS=6;
																		$pozycja_d=5;
																		$pozycja_s=5;
																	break;
																case 1:
																		
																		$naglowek="Dodatkowe informacje";
																		$rows=20;
																		$ID_OPCJ_KOL=6;
																		$ID_OPCJ_ROZ=8;
																		$ID_OPCJ_CSS=7;
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
echo $naglowek.' <span class="S_NG_DANE">*</span>: ';
if(array_key_exists($tr, $err)) { echo $err[$tr];}
echo '</p>';
echo '<div class="DIV_DODAJ">';
echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA">';  
if (isset($_POST["dane".$tr])) { echo $_POST["dane".$tr];}										
echo '</textarea>';
echo '<table><tr><td class="TD_L">';
//################################################################################## KOLOR-Imie/Nazwisko ##################################################################################
if(filter_input(INPUT_POST,"kolor".$tr)!='')
{
    list($kolor_id,$kolor_hex) = explode('|', $_POST["kolor".$tr]);
    $col=mysqli_fetch_row($db->query("select NAZWA from COLOR WHERE ID='$kolor_id' AND WSK_U=0"));
    $kolor_nazwa=$col[0];							
    if ($kolor_hex=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
							$domyślny_kom=' (ustawiony)';
							echo $kolor_id.' '.$kolor_nazwa.' '.$kolor_hex.'</br>';
							echo $_POST["kolor".$tr];
							} 
								else {
										
										
										
										$DOM_COL=mysqli_fetch_row($db->query("select c.ID,p.NAZWA,p.WART FROM PARM p,COLOR c WHERE p.WART=c.HEX and p.WSK_D=1 AND p.ID_GROUP=3 AND p.ID_OPCJ='$ID_OPCJ_KOL'"));
										$kolor_id=$DOM_COL[0];
										$kolor_nazwa=$DOM_COL[1];
										$kolor_hex=	$DOM_COL[2];
										if ($DOM_COL[2]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
										$domyślny_kom=' (domyślny)';
								};
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Kolor tekstu : ';
echo '<select name="kolor'.$tr.'" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$kolor_id.'|'.$kolor_hex.'" style="font-weight:bold; color:'.$kolor_font.';background: none repeat scroll 0%  0% '.$kolor_hex.';">'.$kolor_nazwa.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';

$wyswietl = $db->query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 AND ID!='$kolor_id'order by ID");
while($r_kolor = mysqli_fetch_array($wyswietl))
												{
												if ($r_kolor[2]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
												echo '<option value="'.$r_kolor[0].'|'.$r_kolor[2].'" style="color:'.$kolor_font.'; background: none repeat scroll 0%  0% '.$r_kolor[2].';">'.$r_kolor[1].'</option>';
												}

echo '</optgroup></select></p>';
//################################################################################## KONIEC-KOLOR ##################################################################################
//################################################################################## POZYCJA-TEXT ##################################################################################
if(filter_input(INPUT_POST,"pozycja".$tr)!='')
{
    list($poz_id,$poz_wart) = explode('|', $_POST["pozycja".$tr]);
							$col_poz=mysqli_fetch_row($db->query("select NAZWA FROM PARM WHERE ID='$poz_id' AND ID_GROUP=0 AND ID_OPCJ IN (1,2,3)"));
							$poz_nazwa=$col_poz[0];

							$domyślny_kom=' (ustawiony)';
							} 
else {
		
		
		$DOM_POZ=mysqli_fetch_row($db->query("select ID,NAZWA,WART FROM PARM WHERE SUBSTRING(WSK_D, '$pozycja_d', 1)='1' AND ID_GROUP=0 AND ID_OPCJ IN (1,2,3) "));
		$poz_id=$DOM_POZ[0];
		$poz_nazwa=$DOM_POZ[1];
		$poz_wart=$DOM_POZ[2];		
		$domyślny_kom=' (domyślny)';
		//echo $poz_id.'|'.$poz_wart;
}; //
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Pozycja tekstu : ';
echo '<select name="pozycja'.$tr.'" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$poz_id.'|'.$poz_wart.'" class="OPTION">'.$poz_nazwa.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';

$SEL_POZ_ALL = $db->query("select ID,NAZWA,WART FROM PARM WHERE WSK_U=0 AND ID_GROUP=0 AND ID!='$poz_id'  AND ID_OPCJ IN (1,2,3) order by ID");
while($r_pozycja = mysqli_fetch_array($SEL_POZ_ALL))
												{
												echo '<option value="'.$r_pozycja[0].'|'.$r_pozycja[2].'" class="OPTION">'.$r_pozycja[1].'</option>';						
												}
echo '</optgroup></select></p>';
//################################################################################## KONIEC-POZYCJA-TEXT ##################################################################################
//################################################################################## ROZMIAR ##############################################################################################
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Rozmiar czcionki : ';
if(filter_input(INPUT_POST,"rozmiar".$tr)!='')
{
							$rozmiar=$_POST["rozmiar".$tr];
							$domyślny_kom=' (ustawiona)';
							}
								else {	
										
										
										$DOM_ROZ=mysqli_fetch_row($db->query("select WART FROM PARM WHERE WSK_D=1 AND ID_GROUP=2 AND ID_OPCJ='$ID_OPCJ_ROZ'"));
										$rozmiar=$DOM_ROZ[0]; //Rozmiar Tytuł
										$domyślny_kom=' (domyślna)';
								};
echo '<select name="rozmiar'.$tr.'" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$rozmiar.'" class="OPTION">'.$rozmiar.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
for( $i = 12; $i<33;) {
							if ($rozmiar!=$i) echo '<option value="'.$i.'" class="OPTION">'.$i.'</option>';
							$i=$i+2; 
							};
echo '</optgroup></select></p>';
//################################################################################## KONIEC-ROZMIAR ##################################################################################
//################################################################################## STYLE-TEXT ######################################################################################
echo '</td><td class="TD_R">';
echo '<p style="text-align: left; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Wskaż opcję formatowania :</p>';

							$SEL_CSS=$db->query("select c.ID,c.NAZWA FROM CSS c WHERE c.ID_GROUP=0 ORDER BY c.ID");
							if(!isset($_POST["CSS_DEF_".$tr])) $CSS_DEF[$tr]=TRUE; else $CSS_DEF[$tr]=FALSE;
							while($REK_CSS = mysqli_fetch_array($SEL_CSS))
							{
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
																					
																					$SEL_P_CSS=$db->query("select pc.ID,pc.WSK_V FROM PARM_CSS pc, PARM pm WHERE pc.ID_PARM=pm.ID AND pm.ID_MODUL='$_GET[IDM]' AND pc.ID_CSS='$REK_CSS[0]' AND pm.ID_OPCJ='$ID_OPCJ_CSS' LIMIT 1");
																					$REK_P_CSS = mysqli_fetch_array($SEL_P_CSS);
																					if($REK_P_CSS[1]=="0" ||$REK_P_CSS[1]=="") {$domyslny='';$domyslny_kom=''; }else IF ($REK_P_CSS[1]=="1") {
																					$domyslny='checked="checked"'; 
																					$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(domyślna)</span>';
																					};
																			};
							echo '<input type="checkbox" name="CSS_'.$tr.'_'.$REK_CSS[0].'" value="1" '.$domyslny.' class="CSS_CHBOX"  /><span class="S_CSS">'.$REK_CSS[1].'</span> '.$domyslny_kom.'<br/>';
							};	// Koniec petla WHILE	
//################################################################################## KONIEC-STYLE-TEXT-Imie/Nazwisko #####################################
echo '</td></tr></table>';
echo '<input type="hidden" name="CSS_DEF_'.$tr.'" value="1" />';
echo '</div>';
};
//################################################################################## ZDJECIE ZAWONIKA ####################################################

echo '<p class="NG_DANE">Zdjęcie zawodnika <span class="S_NG_DANE">*</span>:</p>';
echo '<div class="DIV_DODAJ">';
for( $x = 1; $x<2; $x++ )
{
	echo '<p class="P_INPUT"><input type="file" name="obraz'.$x.'"/></br>';
        if(array_key_exists($x, $err_obraz_tab)) { echo $err_obraz_tab[$x];}
        echo '</p>';
}
echo '</div>';

//################################################################################## KONIEC ZDJECIE ZAWODNIKA ############################################
echo '<input class="button_dodaj"type="submit" value="Dodaj" name="zawodnik"/></form>';
echo '<p class="P_LEG">Legenda :</p><p class="P_LEG_INFO">';
echo '- pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane;<br/>';
echo '- IMIĘ/NAZWISKO musi zawierać min (<span class="S_LEG_INFO">5</span>) znaków; <br/> ';
echo '- IMIĘ/NAZWISKO może zawierać max (<span class="S_LEG_INFO">200</span>) znaków;<br/> ';
echo '- DODATKOWE INFORMACJE muszą zawierać min (<span class="S_LEG_INFO">5</span>) znaków;<br/> ';
echo '- DODATKOWE INFORMACJE muszą zawierać max (<span class="S_LEG_INFO">2000</span>) znaków;<br/> ';
echo '- ZDJĘCIE, dozwolony TYP : (<span class="S_LEG_INFO">JPG JPEG PNG BMP GIF</span>);<br/> ';
echo '- ZDJĘCIE, max rozmiar (<span class="S_LEG_INFO">8 MB</span>);<br/> ';
echo '</p></center></div>';
} else if ($status_dodaj==1){
							echo '<a class="A_BACK"href="cel_NASI_ZA.php?IDW=0&IDM='.$_GET["IDM"].'"><p class="P_HREF_BACK">Powrót</p></a>';
							echo '<p class="P_MAIN">Twój zawodnik został dodany.</p>';
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
for ($d_ins=0;$d_ins<2;$d_ins++)
{
	$dane_ins[$d_ins] = nl2br($_POST["dane".$d_ins]); // Dodanie na koniec lini znaku <br/>
	for( $css_licz=2; $css_licz<4; $css_licz++ )
	{
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
								echo '<p style="text-align: left; font-size: 20px; font-weight:bold;margin-left:50px;margin-bottom:5px;">Imie/Nazwisko : <br/>';
								echo '<div style="background:white;width:750px; border:1px; border-style:solid; margin:5px; border-color:#D3D3D3;" >';
								echo '<p style="background:white;color:'.$kol_hex[$d_ins].';font-size:'.$_POST["rozmiar".$d_ins].'; text-align:'.$pozycja_wart[$d_ins].';'.$font_weight.$font_style.$text_dec.'">'.$_POST["dane".$d_ins].'</p>';
								echo '</div></p>';
								};
$db->query("INSERT INTO ZWDK (ID_COL_IMIE_N,ID_COL_INFO,IMIE_N,INFO,ID_PERS,K_IMIE_N,K_INFO,R_IMIE_N,R_INFO,DAT_UTW,UID,ID_P_IMI_N,ID_P_INFO,P_IMI_N_WART,P_INFO_WART,CSS_IMI_N,CSS_INFO,ID_PERS_KOR) VALUES ('$kol_id[0]','$kol_id[1]','$dane_ins[0]','$dane_ins[1]','$_SESSION[id_user]','$kol_hex[0]','$kol_hex[1]','$_POST[rozmiar0]','$_POST[rozmiar1]',NOW(),'$_SESSION[uid]','$pozyzja_id[0]','$pozyzja_id[1]','$pozycja_wart[0]','$pozycja_wart[1]','$CSS_dane[0]','$CSS_dane[1]','$_SESSION[id_user]')");
$ID_ZWDK=$db->last();
$db->query("UPDATE `ZWDK` SET `ID_P`=`ID_P`+1 WHERE `WSK_U`=1");																																						
for( $y = 1; $y<2; $y++ ) {
							if (is_uploaded_file($_FILES["obraz".$y]['tmp_name'])) {
																					
																					if ($_SESSION['id_user']==1){ echo "Odebrano obraz : ".$_FILES["obraz".$y]["name"].'<br/>';};
																$uploads_dir = '../../zdjecia/nasi_za/'; 
																if ($_SESSION['id_user']==1){ echo "uploads dir : ".$uploads_dir.'<br/>';};
																$filename = $_FILES["obraz".$y]["name"];
																$ext = strtolower(substr(strrchr($filename, '.'), 1)); //Get extension
																echo '<p style="text-align:left;">';
																$dir=opendir("../../zdjecia/nasi_za");
																$ile_zdjec=0;
																//-------------------------------------------------------------------------------------SPRAWDZ-CZY-ISTENIEJE-PLIK---------
																while(file_exists($uploads_dir.$image_name))
																{
																				
																				$ile_zdjec++;
																				$image_name='org_'.$ile_zdjec.'_nasi_za.'.$ext;
																};
																//-------------------------------------------------------------------------------------KONIEC-SPRAWDZ-CZY-ISTENIEJE-PLIK---------
																echo "</p>";
																
																$image_name='org_'.$ile_zdjec."_nasi_za.".$ext; //New image name
																$tmp_name = $_FILES["obraz".$y]["tmp_name"]; 
																move_uploaded_file($tmp_name, "$uploads_dir$image_name"); 
																list($width, $height) = getimagesize($uploads_dir.$image_name);
//###################################################################################################### RESIZE-IMG ##################################################################################
																for ($p_resize=0;$p_resize<2;$p_resize++)
																{
																													switch ($p_resize):
																																			case 0: 
																																					$file_new=0;
																																					$naglowek_img='new_'.$file_new."_nasi_za.".$ext;
																																					while(file_exists($uploads_dir.$naglowek_img))
																																					{
																																						echo "<p class=\"P_ERROR\">PLIK - <span class=\"S_ERROR\">new_".$file_new."_nasi_za.".$ext."</span> Istenieje !</p>";
																																						$file_new++;
																																						$naglowek_img='new_'.$file_new.'_nasi_za.'.$ext;
																																					};
																																					$rozmiar=760;
																																					$naglowek="Galeria ";
																																					
																																					break;
																																			case 1:
																																					$rozmiar=324;
																																					$file_min=0;
																																					$naglowek_img='min_'.$file_min."_nasi_za.".$ext;
																																					while(file_exists($uploads_dir.$naglowek_img))
																																					{
																																						echo "<p class=\"P_ERROR\">PLIK - <span class=\"S_ERROR\">min_".$file_min."_nasi_za.".$ext."</span> Istenieje !</p>";
																																						$file_min++;
																																						$naglowek_img='min_'.$file_min.'_nasi_za.'.$ext;
																																					};
																																					$naglowek="Miniatura ";
																																					break;
																																			default:
																																					$rozmiar=324;
																																					//$naglowek_img="def_";
																																					$naglowek_img='min_'.$ile_zdjec_new."_nasi_za.".$ext;
																																					$naglowek="DEFAULT ";
																																					break;
																													endswitch;
																		if ($width>$rozmiar || $height>$rozmiar)
																		{ // 0 miniatura 324x324   // 1 900x900
																			if (file_exists($uploads_dir."/new_".$file_new."_nasi_za".$ext))
																			{
																				$image_name='new_'.$file_new."_nasi_za.".$ext;
																			};
																			$wynik_img_size = resize_image($rozmiar,$rozmiar,$ext,$naglowek_img,$uploads_dir,$image_name);	
																			$new_width[$p_resize]=round($wynik_img_size[0]);
																			$new_height[$p_resize]=round($wynik_img_size[1]);
																			$new_image_name[$p_resize]=$wynik_img_size[2];
																		}
																		else
																		{
																			$new_image_name[$p_resize]=$image_name;
																			$new_height[$p_resize]=round($height);
																			$new_width[$p_resize]=round($width);
																		};
																		//imagebmp($uploads_dir.$image_name);
																		
																};
//###################################################################################################### KONIEC-RESIZE-IMG ##################################################################################
																$new_width[1]=$new_width[1]+10;
																$new_height[1]=$new_height[1]+10;
																$db->query("INSERT INTO ZWDK_IMG (ID_ZWDK,NAZWA_O,NAZWA_I,ID_PERS,DAT_UTW,NR_IMG,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT) VALUES ('$ID_ZWDK','$filename','$new_image_name[0]','$_SESSION[id_user]',NOW(),'$y','$new_width[0]','$new_height[0]','$new_image_name[1]','$new_width[1]','$new_height[1]')");
																
																
																echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/nasi_za/$new_image_name[0]','$new_image_name[1]','$new_width[0]','$new_height[0]')\">";
																echo '<center><img src="'.APP_URL.'/zdjecia/nasi_za/'.$new_image_name[1].'" alt="'.$new_image_name[1].'" style="width:'.$new_width[1].'px; height:'.$new_height[1].'px; border:0px;" /></center>';
																echo '</a>';
																//echo '<img src="'.$uploads_dir.'/'.$image_name_min.'" alt="ZAWODNIK"><br/>';
								} else { };//echo '<p style="color:red;text-align:left;margin-left:20px;margin-top:0px;margin-bottom:5px;">Nie wskazano zdjęcia : <span style="color:black;">'.$y.'</span></p>';};
							};
							$db->insDbLog($_GET["IDM"],"Dodano ID : ".$ID_ZWDK);
							echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0#ID'.$ID_ZWDK.'"><p class="P_HREF_BACK2">Powrót do MENU - ZAWODNICY</p></a>';
							unset($_POST["dane0"]);
							unset($_POST["dane1"]);
							if ($_SESSION['id_user']==1){
														echo '<p style="text-align:left;">Kolory nazwa tytul - '.$kolor_tyt_nazwa.' <br/> Kolor nazwa tresc - '.$kolor_tresc_nazwa.'<br/>';
														echo 'Unset [dane0] - '.$_POST["dane0"].'Unset [dane1] - '.$_POST["dane1"].'</p>';
														};
							//};					
};
						echo "</div>";	
?>