<?php if(!defined('PAGE_URL')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<a class="A_BACK"href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Anuluj</p></a>';
echo '<p class="P_MAIN">Edytowanie Zawodnika o nr ID - [<span style="color:purple;">'.$_GET["ID"].'</span>]</p>';
require(DR.'/class/c_sprawdzImportPlik.php');
require(DR.'/class/c_zmienRozmiarObraz.php');
$status_popraw=0;
$err=array();
$err_obraz_tab=array();
$ID_ZWDK=$_GET["ID"];
if (isset($_POST["edytuj"])) 
{
	$checked_img=TRUE;
	$checked=TRUE;
	$IMG_ZMIANA=FALSE;
$plik = new sprawdzImportowanyPlik(array('image/gif','image/jpeg','image/jpg','image/png','image/bmp','image/pjpeg'),8388608);
for( $y = 1; $y<2; $y++ )
{
	if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"]))
	{
		$IMG_ZMIANA=TRUE;
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
		$checked_img=TRUE;
		IF($checked_img) $err_obraz_tab[$y]='<span class="S_ERR_DANE">Nie wskazano obrazu.</span>';
		else $err_obraz_tab[$y]='';
	}; // zmiana na FALSE uniemoliwia dodanie zawodnika bez zdjecia
}; // KONIEC petla FOR dla plików
										$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ .'!:-_\r\n]+$/";
$err=array('','');
							for($ch_dane=0;$ch_dane<2;$ch_dane++){
																if(!preg_match($string_exp,$_POST["dane".$ch_dane])) { $err[$ch_dane]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $checked=FALSE;};
																if($ch_dane==1) $ch_max=2000; else $ch_max=200;
																check_len($checked, $_POST["dane".$ch_dane],$ch_max,$err[$ch_dane],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">100</span>)',5,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
																};
if (($checked==FALSE) || ($checked_img==FALSE)) $status_popraw=0; else $status_popraw=1;
  };
if ($status_popraw==0)
{
$SEL_ZWDK=$db->query("SELECT ID,ID_COL_IMIE_N,ID_COL_INFO,IMIE_N,INFO,R_IMIE_N,R_INFO,ID_P_IMI_N,ID_P_INFO,CSS_IMI_N,CSS_INFO FROM ZWDK WHERE ID=".$_GET['ID']);
$rekord = mysqli_fetch_array($SEL_ZWDK);
echo '<form action="" method="POST" ENCTYPE="multipart/form-data">';
$dane=array('','');
$k_dane=array(0,0);
$p_dane=array(0,0);
$r_dane=array(0,0);
$c_dane=array('','');
for ($tr=0;$tr<2;$tr++){
						switch($tr):
									case 0: 
											
											$naglowek="Imie/Nazwisko";
											$rows=1;
											$ID_OPCJ_KOL=7;
											$ID_OPCJ_ROZ=9;
											$dane[$tr]=$rekord[3];
											$k_dane[$tr]=$rekord[1];
											$p_dane[$tr]=$rekord[7];
											$r_dane[$tr]=$rekord[5];
											$c_dane[$tr]=$rekord[9];
										break;
									case 1:
											
											$naglowek="Dodatkowe informacje";
											$rows=20;
											$ID_OPCJ_KOL=8;
											$ID_OPCJ_ROZ=10;
											$dane[$tr]=$rekord[4];
											$k_dane[$tr]=$rekord[2];
											$p_dane[$tr]=$rekord[8];
											$r_dane[$tr]=$rekord[6];
											$c_dane[$tr]=$rekord[10];
										break;
									case 2:
										break;
									default:
										break;
						endswitch;
echo '<p class="NG_DANE">';
if($_SESSION['id_user']==1) echo "TR - ".$tr." | ";
echo $naglowek.' <span class="S_NG_DANE">*</span>: <font color="red">';
if(array_key_exists($tr, $err)) { echo $err[$tr];}
echo '</font></p>';
echo '<div style="padding-top:25px; padding-left:0px; width:740px; padding-bottom:0px; border:1px; border-style:solid; margin-top:20px; ">';
echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA">';
if (filter_input(INPUT_POST,"dane".$tr)!='')
{
    echo $_POST["dane".$tr];
}
else {
	$zmieniony = str_replace("<br />","",$dane[$tr]); //- w tym przypadku znak "+" zostanie zastąpiony wyrazem "plus"	
	echo $zmieniony;
};
echo '</textarea></br>';
//----------------------------------------------------------------------------------KOLOR------------------------------------------------------------------------
echo '<table style="border:0px" cellspacing="0" cellpadding="0">';
echo '<tr style="border:0px" cellspacing="0" cellpadding="0">';
echo '<td style="border:0px" cellspacing="0" cellpadding="0" width="390px" valign="TOP">';
if (filter_input(INPUT_POST,"kolor".$tr)!=''){
							list($kolor_id,$kolor_hex) = explode('|', $_POST["kolor0"]);
							
							
							$col=mysqli_fetch_row($db->query("select NAZWA FROM COLOR WHERE ID='$kolor_id' AND WSK_U=0"));
							$kolor_nazwa=$col[0];
							if ($kolor_hex=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
							$domyślny_kom=' (ustawiony)';
							echo $kolor_id.' '.$kolor_nazwa.' '.$kolor_hex.'</br>';
							} 
else {
		
		
		$DOM_COL=mysqli_fetch_row($db->query("select ID,NAZWA,HEX FROM COLOR WHERE ID='$k_dane[$tr]';"));
		$kolor_id=$DOM_COL[0];
		$kolor_nazwa=$DOM_COL[1];
		$kolor_hex=	$DOM_COL[2];
		if ($DOM_COL[2]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
		$domyślny_kom=' (ustawiony)';
};
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;"">Kolor tekstu : ';
echo '<select name="kolor'.$tr.'" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$kolor_id.'|'.$kolor_hex.'" style="font-weight:bold; color:'.$kolor_font.';background: none repeat scroll 0%  0% '.$kolor_hex.';">'.$kolor_nazwa.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';

$wyswietl = $db->query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 AND ID!='$kolor_id'order by ID");
while($r_kolor = mysqli_fetch_array($wyswietl))
												{
												if ($r_kolor[2]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
												echo '<option value="'.$r_kolor[0].'|'.$r_kolor[2].'" style="color:'.$kolor_font.'; background: none repeat scroll 0%  0% '.$r_kolor[2].';">'.$r_kolor[1].'</option>';
												}
echo '</optgroup></select>';
//----------------------------------------------------------------------------------KONIEC-KOLOR-----------------------------------------------------------------
//----------------------------------------------------------------------------------POZYCJA-TXT-----------------------------------------------------------------
if (filter_input(INPUT_POST,"pozycja".$tr)!='') {
							list($poz_id,$poz_wart) = explode('|', $_POST["pozycja0"]);
					
							$col=mysqli_fetch_row($db->query("select NAZWA FROM PARM WHERE ID='$poz_id' AND ID_GROUP=0 AND ID_OPCJ IN (1,2,3)"));
							$poz_nazwa=$col[1];
							$domyślny_kom=' (ustawiony)';
							} 
else { 
		  // rekord[7] - Imie/Nazwisko rekord[8] Dodatkowe informacje
		
		$DOM_COL=mysqli_fetch_row($db->query("select ID,NAZWA,WART FROM PARM WHERE ID='$p_dane[$tr]' AND ID_GROUP=0 AND ID_OPCJ IN (1,2,3)"));
		$poz_id=$DOM_COL[0];
		$poz_nazwa=$DOM_COL[1];
		$poz_wart=$DOM_COL[2];		
		$domyślny_kom=' (ustawiony)';
};
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Pozycja tekstu : ';
echo '<select name="pozycja'.$tr.'" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$poz_id.'|'.$poz_wart.'" class="OPTION">'.$poz_nazwa.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';

$SEL_POZ_ALL = $db->query("select ID,NAZWA,WART FROM PARM WHERE WSK_U=0 AND ID_GROUP=0 AND ID!='$poz_id'  AND ID_OPCJ IN (1,2,3) order by ID");
while($r_pozycja = mysqli_fetch_array($SEL_POZ_ALL))
												{
												echo '<option value="'.$r_pozycja[0].'|'.$r_pozycja[2].'" class="OPTION">'.$r_pozycja[1].'</option>';						
												}
echo '</optgroup></select></p>';
//----------------------------------------------------------------------------------KONIEC-POZYCJA-TXT---------------------------------------------------------
//----------------------------------------------------------------------------------ROZMIAR---------------------------------------------------------------------
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Rozmiar czcionki : ';

if (filter_input(INPUT_POST,"rozmiar".$tr)!=''){
							$rozmiar=$_POST["rozmiar0"];
							$domyślny_kom=' (ustawiona)';
							}
else {	
		//
		$rozmiar=$r_dane[$tr];  // rekord[5] Rozmiar czcionki Imie/Nazwisko rekord[6] Rozmiar czcionki Dodatkowe informacje
		$domyślny_kom=' (ustawiona)';
};
echo '<select name="rozmiar'.$tr.'" class="SELECT"><optgroup label="Aktualny :" class="OPTGROUP"><option value="'.$rozmiar.'" class="OPTION">'.$rozmiar.' '.$domyślny_kom.'</option></optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
for( $i = 12; $i<33;) {
							if ($rozmiar!=$i) echo '<option value="'.$i.'" class="OPTION">'.$i.'</option>';
							$i=$i+2; 
							};
echo '</optgroup></select>';

//-----------------------------------------------------------------------------------KONIEC-ROZMIAR-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------------STYLE-TEXT-TYTUL-------------------------------------------------------------------------------------
echo '</td><td style="border:0px" cellspacing="0" cellpadding="0" WIDTH="350px">';
echo '<p style="text-align: left; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Wskaż opcję formatowania :</p>';
// rekord[9] CSS Imie/Nazwisko rekord[10] CSS Dodatkowe informacje

$SELECT_CSS = $db->query("select ID,ID_OPCJ,NAZWA FROM PARM WHERE WSK_U=0 AND ID_GROUP=1 AND ID_OPCJ IN (1,2,3) ORDER BY ID");
if(!isset($_POST["CSS_DEF_".$tr])) $CSS_DEF[$tr]=TRUE; else $CSS_DEF[$tr]=FALSE;
//rek_css[0] -> CSS TYTUŁ BOLD
//rek_css[1] -> CSS TYTUŁ ITALIC
//rek_css[2] -> CSS TYTUŁ UNDERLINE
list($r_css[0],$r_css[1],$r_css[2]) = explode('|',$c_dane[$tr]);
if ($_SESSION['id_user']==1) {echo 'BOLD - '.$r_css[0].' ITALIC - '.$r_css[1].' Underline - '.$r_css[2].'<br/>';};					
$i=0;
while($REK_CSS = mysqli_fetch_array($SELECT_CSS))
{
if  ($CSS_DEF[$tr]!=TRUE) {
							if (!isset($_POST["CSS_".$tr."_".$REK_CSS[1]])) {
																			$domyslny=''; 
																			$domyslny_kom='';
																			} 
																			else {
																					$domyslny='checked="checked"'; 
																					$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																				};
						}
else if ($r_css[$i]=='0') {
								$domyslny=''; 
								$domyslny_kom='';
								$i++;
								} 
								else {
										$domyslny='checked="checked"'; 
										$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
										$i++;
									};
echo '<input type="checkbox" name="CSS_'.$tr.'_'.$REK_CSS[1].'" value="1" '.$domyslny.' class="CSS_CHBOX" /><span class="S_CSS">'.$REK_CSS[2].'</span> '.$domyslny_kom.'<br/>';
};	
//----------------------------------------------------------------------------------KONIEC-STYLE-TEXT-TYTUL-------------------------------------------
echo '</td></tr></table>';
echo '<input type="hidden" name="CSS_DEF_'.$tr.'" value="1" />';
echo '</div>';
//----------------------------------------------------------------------------------ZDJĘCIE-ZAWODNIK---------------------------------------------------
};
for ($z=1; $z<2; $z++)
{
					if (mysqli_num_rows($db->query("select ID from ZWDK_IMG where ID_ZWDK='$ID_ZWDK' AND NR_IMG='$z'"))!=0)
                                        {
									$zap_img = $db->query("select ID,NR_IMG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM ZWDK_IMG where ID_ZWDK='".$_GET['ID']."' AND NR_IMG='$z' AND WSK_U=0");
									$rek_img = mysqli_fetch_array($zap_img);
									echo '<div style="padding-top:25px; padding-left:0px; width:740px; padding-bottom:0px; border:1px; border-style:solid;margin-top:20px; ">';
									echo "<a HREF=\"javascript:displayWindow('../../zdjecia/nasi_za/$rek_img[2]',$rek_img[3],$rek_img[4])\">";
									echo '<img src="../../zdjecia/nasi_za/'.$rek_img[5].'" alt="Zdjecie '.$rek_img[1].'" style="WIDTH:'.$rek_img[6].'px; HEIGHT:'.$rek_img[7].'px; border:0px; margin:10px;" />';
									echo '</a><p class="P_INFO_IMG">(kliknij na zdjęcie aby je powiększyć)</p><p style="text-align:left; margin:10px;">';
									echo '<input type="file" name="obraz'.$z.'"/>';
                                                                        if(array_key_exists($z, $err_obraz_tab)) { echo $err_obraz_tab[$z]; }
                                                                                echo '</p></div>';
									}
					else  {
							echo '<div style="padding-top:25px; padding-left:0px; width:740px; padding-bottom:0px; border:1px; border-style:solid;margin-top:20px; ">';
							echo '<p style="margin:10px;text-align:left;">';
							echo '<input type="file" name="obraz'.$z.'"/>'.$err_obraz_tab[$z].'</p>';
							echo '</div>';
						};
};
//----------------------------------------------------------------------------------KONIEC-ZDJĘCIE-ZAWODNIK---------------------------------------------------
echo '<input type="hidden" name="ID" value="'.$_GET["ID"].'" />';
echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
echo '<input class="button" type="submit" value="Edytuj" name="edytuj"/></form>';
echo '<p class="P_LEG">Legenda :</p><p class="P_LEG_INFO">';
echo '- pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane;<br/>';
echo '- IMIĘ/NAZWISKO musi zawierać min (<span class="S_LEG_INFO">5</span>) znaków; <br/> ';
echo '- IMIĘ/NAZWISKO może zawierać max (<span class="S_LEG_INFO">200</span>) znaków;<br/> ';
echo '- DODATKOWE INFORMACJE muszą zawierać min (<span class="S_LEG_INFO">5</span>) znaków;<br/> ';
echo '- DODATKOWE INFORMACJE muszą zawierać max (<span class="S_LEG_INFO">2000</span>) znaków;<br/> ';
echo '- ZDJĘCIE, dozwolony TYP : (<span class="S_LEG_INFO">JPG JPEG PNG BMP GIF</span>);<br/> ';
echo '</p></center></div>';
} else if ($status_popraw==1)
{
							echo '<a class="A_BACK"href="/pod_strony/NASI_ZA/cel_NASI_ZA.php?IDW=0&IDM='.$_GET["IDM"].'"><p class="P_HREF_BACK">Powrót</p></a>';
$CSS_dane= array(0,0);
for ($css_1=0;$css_1<2;$css_1++){
								if ($_POST["CSS_".$css_1."_1"]!='') {
																	$CSS_dane[$css_1]=$_POST["CSS_".$css_1."_1"];
																	//echo 'CSS_'.$css_1.'_4 - '.$CSS_dane[$css_1].'<br/>';
																	}
																	else { 
																		$CSS_dane[$css_1]=0;
																		//echo 'CSS_'.$css_1.'_4 - '.$CSS_dane[$css_1].'<br/>';;
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
for ($d_ins=0;$d_ins<2;$d_ins++){
								for( $css_licz=2; $css_licz<4; $css_licz++ ) {
																				if(isset($_POST["CSS_".$d_ins."_".$css_licz])) {
																																$CSS_dane[$d_ins]=$CSS_dane[$d_ins].'|'.$_POST["CSS_".$d_ins."_".$css_licz];
																																}
																				else {
																						$CSS_dane[$d_ins]=$CSS_dane[$d_ins].'|0';
																						};
																				//echo 'CSS_'.$d_ins.'_'.$css_licz.' - '.$CSS_dane[$d_ins].'<br/>';
																				};
								$dane[$d_ins] = nl2br($_POST["dane".$d_ins]);
								list($kol_id[$d_ins],$kol_hex[$d_ins]) = explode('|', $_POST["kolor".$d_ins]);
								list($pozyzja_id[$d_ins],$pozycja_wart[$d_ins]) = explode('|', $_POST["pozycja".$d_ins]);
								};	

$db->query("UPDATE `ZWDK` SET `ID_COL_IMIE_N`='$kol_id[0]' ,`ID_COL_INFO`='$kol_id[1]', `IMIE_N`='$dane[0]',`INFO`='$dane[1]',`ID_PERS_KOR`='".$_SESSION['id_user']."',`K_IMIE_N`='$kol_hex[0]',`K_INFO`='$kol_hex[1]',`R_IMIE_N`='".$_POST['rozmiar0']."',`R_INFO`='".$_POST['rozmiar1']."',`DAT_K`=NOW(),`UID`='".$_SESSION['uid']."',`ID_P_IMI_N`='$pozyzja_id[0]',`ID_P_INFO`='$pozyzja_id[1]',`P_IMI_N_WART`='$pozycja_wart[0]',`P_INFO_WART`='$pozycja_wart[1]',`CSS_IMI_N`='$CSS_dane[0]',`CSS_INFO`='$CSS_dane[1]',`WSK_V`=0,`ID_PERS_KOR`='".$_SESSION['id_user']."' WHERE `ID`='".$_POST['ID']."' ");

									echo '<p class="P_MAIN">Twój zawodnik został uaktualniony.</p>';
									for($w=0;$w<2;$w++){
														list($css_b[$w],$css_i[$w],$css_u[$w]) = explode('|', $CSS_dane[$w]);
														if ($css_b[$w]==0) $font_weight='font-weight:normal;'; else $font_weight='font-weight:bold;';
														if ($css_i[$w]==0) $font_style='font-style: normal;'; else $font_style='font-style:italic;';
														if ($css_u[$w]==0) $text_dec=''; else $text_dec='text-decoration: underline;';
										if ($w==0) $nagl_wysw="Imie/Nazwisko"; else if ($w==1) $nagl_wysw="Dodatkowe informacje";
										
										echo '<p style="text-align: left; font-size: 20px; font-weight:bold;margin-left:50px;margin-bottom:5px;">'.$nagl_wysw.': <br/>';
										echo '<div style="background:white;width:750px; border:1px; border-style:solid; margin:5px; border-color:#D3D3D3;" >';
										echo '<p style="background:white;color:'.$kol_hex[$w].';font-size:'.$_POST["rozmiar".$w].'; text-align:'.$pozycja_wart[$w].';'.$font_weight.$font_style.$text_dec.'">'.$_POST["dane".$w].'</p>';
										echo '</div></p>';
										};							
									//}; // KONIEC SQL UPDATE ZWDK
//--------------------------------------------------------------------UPDATE-ZDJECIE---------------------------------------------------------------------------------------
if ($IMG_ZMIANA==TRUE)
{
for( $y = 1; $y<2; $y++ )
{
	if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"]))
	{
		$ile_zdjec=0;
		$uploads_dir = '/zdjecia/nasi_za/';
		$filename=$_FILES["obraz".$y]["name"];
		$ext=mime_content_type($_FILES["obraz".$y]["tmp_name"]);
		$ext=strtolower(substr(strrchr($ext, '/'), 1)); //Rozszerzenie
		$image_name='org_'.$ile_zdjec.'_nasi_za.'.$ext;
//---------------------------------------------------DIR------------------------------------------------------------------------------------------------
																while(file_exists($uploads_dir.$image_name))
																{
																	echo "<p class=\"P_ERROR\">PLIK - <span class=\"S_ERROR\">org_".$ile_zdjec."_nasi_za.".$ext."</span> Istenieje !</p>";
																	$ile_zdjec++;
																	$image_name='org_'.$ile_zdjec.'_nasi_za.'.$ext;
																};
//--------------------------------------------------END-DIR------------------------------------------------------------------------------------------------
//###################################################################################################### RESIZE-IMG ##################################################################################
				move_uploaded_file($_FILES["obraz".$y]["tmp_name"], "$uploads_dir$image_name"); 
				$plik = new zmienRozmiarObraz($uploads_dir,$image_name);
				for ($p_resize=0;$p_resize<2;$p_resize++)
				{
																													switch ($p_resize):
																																			case 0: 
																																					$file_new=0;
																																					$naglowek_img='new_'.$file_new."_nasi_za";
																																					while(file_exists($uploads_dir.$naglowek_img.".".$ext))
																																					{
																																						echo "<p class=\"P_ERROR\">PLIK - <span class=\"S_ERROR\">new_".$file_new."_nasi_za.".$ext."</span> Istenieje !</p>";
																																						$file_new++;
																																						$naglowek_img='new_'.$file_new.'_nasi_za';
																																					};
																																					
																																					$wymiar=760;
																																					$naglowek="Galeria ";
																																					break;
																																			case 1:
																																					$wymiar=324;
																																					$file_min=0;
																																					$naglowek_img='min_'.$file_min."_nasi_za";
																																					while(file_exists($uploads_dir.$naglowek_img.".".$ext))
																																					{
																																						echo "<p class=\"P_ERROR\">PLIK - <span class=\"S_ERROR\">min_".$file_min."_nasi_za.".$ext."</span> Istenieje !</p>";
																																						$file_min++;
																																						$naglowek_img='min_'.$file_min.'_nasi_za';
																																					};
																																					
																																					$naglowek="Miniatura ";
																																					break;
																																			default:
																																					$wymiar=324;
																																					//$naglowek_img="def_";
																																					$naglowek_img='min_'.$ile_zdjec_new."_nasi_za";
																																					$naglowek="DEFAULT ";
																																					break;
																													endswitch;
																		$plik->zmienRozmiarObraz($naglowek_img,$uploads_dir, $wymiar,$wymiar);
																		if($plik->zwrocStatus())
																		{
																			$new_image_name[$p_resize]=$plik->zwrocNazweNowegoPliku();
																			$new_width[$p_resize]=$plik->zwrocWymiarNowegoPliku('s');
																			$new_height[$p_resize]=$plik->zwrocWymiarNowegoPliku('w');
																		}
																		if($_SESSION['id_user']==1)
																		{
																			echo "<p class=\"P_INFO\">".$new_image_name[$p_resize]." WIDTH : <span class=\"S_INFO\">".$new_width[$p_resize]."</span>";
																			echo " HEIGHT : <span class=\"S_INFO\">".$new_height[$p_resize]."</span></p>";
																		};
				};
//###################################################################################################### KONIEC-RESIZE-IMG ##################################################################################			
				
				
					             if (mysqli_num_rows($db->query("select ID FROM ZWDK_IMG where ID_ZWDK='".$_POST['ID']."' AND NR_IMG='$y'"))>0)
								 {
                                                                    $db->query("UPDATE `ZWDK_IMG` SET `NAZWA_O`='$filename' ,`NAZWA_I`='$new_image_name[0]', `WIDTH`='$new_width[0]', `HEIGHT`='$new_height[0]',`NAZWA_I_M`='$new_image_name[1]', `M_WIDTH`='$new_width[1]', `M_HEIGHT`='$new_height[1]' WHERE `ID_ZWDK`='".$_POST['ID']."' AND `NR_IMG`='$y'");
														
														
								 } 
								 else
								 {
                                                                    $db->query("INSERT INTO ZWDK_IMG (ID_ZWDK,NAZWA_O,NAZWA_I,ID_PERS,DAT_UTW,NR_IMG,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT) VALUES ('".$_POST['ID']."','$filename','$new_image_name[0]','".$_SESSION['id_user']."',NOW(),'$y','$new_width[0]','$new_height[0]','$new_image_name[1]','$new_width[1]','$new_height[1]')");
									
								}
				echo '<a>';
				//echo '<img src="'.$uploads_dir.'/'.$image_name_min.'" alt="Zdjecie '.$y.'"><br/>';
				echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/nasi_za/$new_image_name[0]','$new_width[0]','$new_height[0]')\">";
				echo '<img src="'.APP_URL.'/zdjecia/nasi_za/'.$new_image_name[1].'" alt="'.$new_image_name[1].'" style="width:'.$new_width[1].'px; height:'.$new_height[1].'px; border:0px;" />';
				echo '</a>';
				echo '</a>';
				$ILE_ZD++;
																								} else { 
																									
																										}
													};// Koniec pętla FOR - IMG

									
//--------------------------------------------------------------------KONIEC-UPDATE-ZDJECIE---------------------------------------------------------------------------------------		
									} // KONIEC IMG TRUE
									else {echo "<p class=\"P_INFO\"> Nie wskazano żadnego nowego zdjęcia </p>";};
																		echo '<a class="A_BACK" href="'.APP_URL.'IDW=0#'.$_POST['ID'].'"><p class="P_HREF_BACK2">Powrót do MENU - ZAWODNICY</p></a>';
									foreach ($_POST as $key => $value) {
																		echo '<p style="text-align:left;">';
																			
																		UNSET($_POST[$key]);
																			
																		echo '</p>';
																		}
									}; // Koniec status-popraw=1
?>
</div>

