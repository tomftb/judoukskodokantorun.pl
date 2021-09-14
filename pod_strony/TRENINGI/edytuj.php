<?php
session_start(); 
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
$start_time_page = time();
$css="cel_trener.css";

require_once(DR.'/.cfg/konfiguracja.php');
require_once(DR."/class/checkGlobalVar.php");
require_once(DR.'/view/v_head_cel.php');
require_once(DR.'/class/session.php');
require_once(DR.'/class/userPerm.php');
require_once(DR.'/pod_strony/_funkcje_/resize_image_new2.php');
require_once(DR.'/pod_strony/_funkcje_/check_len.php');

$log=NEW logToFile();

$checkVar=NEW checkGlobalVar();
$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDM,'IDM');
$checkVar->checkOnlyGet($IDW,'IDW');

$session=new session();
$session->checkInsideSession($ID,$IDM,$IDW,APP_URL);
$userPermissions=new userPerm($db->getDbLink());	
$TAB_PRAW=$userPermissions->getUserPerm($IDM);
?>
<body>
<center>
<div class="DIV_MAIN">
<?php

if ($TAB_PRAW["Edytuj"]['VAL']!=1)
{
    $db->insDbLog($_GET["IDM"],"Brak uprawnienia - Edytuj");
    echo "Brak uprawnienia - Edytuj";
    return '';							
}
$db->insDbLog($_GET["IDM"],"Uruchomiono funkcję Edytuj");

$ID_TREN=$_GET["IDE"];
if (isset($_POST["edytuj"])) {
	$checked_img=TRUE;
	$checked=TRUE;
	$IMG_ZMIANA=FALSE;
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
																													$IMG_ZMIANA=TRUE;
																													echo "IMG_ZMIANA = ".$IMG_ZMIANA." zdjecie nr - ".$y."<br/>";
																													$err_obraz_tab[$y]='<span style="color:green;">Proszę wczytać jeszcze raz zdjęcie.</span>';
																													} else {
																															$err_obraz_tab[$y]='<span style="color:red;">Za duży rozmiar obrazu</span> !';
																															$checked_img=FALSE;
																															}
																	} else { 
																			$err_obraz_tab[$y]='<span style="color:red;">Wskazany plik nie jest obrazem </span>!';
																			$checked_img=FALSE;
																			}
															} else { $err_obraz_tab[$y]='<span style="color:red;">Błąd poprzedniego zdjęcia </span>!';}												
						//} else { $err_obraz_tab[$y]='<span style="color:red;">Nie wskazano obrazu.</span>'; };
						} else { $err_obraz_tab[$y]='<span style="color:red;">Nie wskazano obrazu.</span>'; $checked_img=TRUE;};
					};
										//$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ .'!:-_\r\n]+$/";
										$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ -_,*.'!:\r\n]+$/";
$err=array('','');
							for($ch_dane=0;$ch_dane<1;$ch_dane++){
																if(!preg_match($string_exp,$_POST["dane".$ch_dane])) { $err[$ch_dane]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $checked=FALSE;};
																if($ch_dane==1) $ch_max=5000; else $ch_max=5000;
																check_len($checked, $_POST["dane".$ch_dane],$ch_max,$err[$ch_dane],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">5000</span>)',5,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
																};
if (($checked==FALSE) || ($checked_img==FALSE)) $status_popraw=0; else $status_popraw=1;
  };
if ($status_popraw==0){
							echo '<a class="A_BACK"href="cel_TREN.php?IDW=0&IDM='.$_GET['IDM'].'"><p class="P_HREF_BACK">Anuluj</p></a>';
						echo '<p class="P_MAIN">Edycja Trenera o nr ID - [<span style="color:purple;">'.$_GET["IDE"].'</span>]</p>';
echo '<form action="" method="POST" ENCTYPE="multipart/form-data">';
//---------------------------------------------------------------IMG----------------------------------------------------------------------------------
$SEL_IMG = $db->query("select ID,NR_IMG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM TREN_IMG where ID_TREN='$_GET[IDE]' AND WSK_U=0");
for ($z=1; $z<2; $z++){
$IST_IMG = $db->query("select ID FROM TREN_IMG where ID_TREN='$ID_TREN' AND NR_IMG='$z'");
$IMG_POM = mysqli_num_rows($IST_IMG);
					if ($IMG_POM!=0){
									$SEL_IMG2 = $db->query("select ID,NR_IMG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM TREN_IMG where ID_TREN='$_GET[IDE]' AND NR_IMG='$z' AND WSK_U=0");
									$rek_img = mysqli_fetch_array($SEL_IMG2);
									echo '<div style="padding-top:25px; padding-left:0px; width:740px; padding-bottom:0px; border:1px; border-style:solid;margin-top:20px; ">';
									echo "<a HREF=\"javascript:displayWindow('../../zdjecia/trener/$rek_img[2]',$rek_img[0],$rek_img[3],$rek_img[4])\">";
									echo '<img src="../../zdjecia/trener/'.$rek_img[5].'" alt="Zdjecie '.$rek_img[1].'" style="WIDTH:'.$rek_img[6].'px; HEIGHT:'.$rek_img[7].'px; border:0px; margin:10px;" />';
									echo '</a><p style="text-align:left; margin:10px;">';
									echo '<input type="file" name="obraz'.$z.'"/>'.$err_obraz_tab[$z].'</p></div>';
									}
					else  {
							echo '<div style="padding-top:25px; padding-left:0px; width:740px; padding-bottom:0px; border:1px; border-style:solid;margin-top:20px; ">';
							echo '<p style="margin:10px;text-align:left;">';
							echo '<input type="file" name="obraz'.$z.'"/>'.$err_obraz_tab[$z].'</p>';
							echo '</div>';
						};
};
//---------------------------------------------------------------KONIEC-IMG----------------------------------------------------------------------------------
$SEL_TREN=$db->query("SELECT ID,ID_COL_IMIE_N,ID_COL_INFO,IMIE_N,INFO,R_IMIE_N,R_INFO,ID_P_IMI_N,ID_P_INFO,CSS_IMI_N,CSS_INFO FROM TREN WHERE ID=$_GET[IDE]") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_TREN : ".mysqli_error()."</span></p>");
$rekord = mysqli_fetch_array($SEL_TREN);

$dane=array('','');
$k_dane=array(0,0);
$p_dane=array(0,0);
$r_dane=array(0,0);
$c_dane=array('','');
for ($tr=0;$tr<1;$tr++){
						switch($tr):
									case 0: 
											echo "CASE 0";
											//$naglowek="Imie/Nazwisko";
											$naglowek="Opis :";
											//$rows=1;
											$rows=20;
											$ID_OPCJ_KOL=7;
											$ID_OPCJ_ROZ=9;
											$dane[$tr]=$rekord[3];
											$k_dane[$tr]=$rekord[1];
											$p_dane[$tr]=$rekord[7];
											$r_dane[$tr]=$rekord[5];
											$c_dane[$tr]=$rekord[9];
										break;
									case 1:
											echo "CASE 1";
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
//};
echo '<p style="text-align: left; font-size: 20px; font-weight:bold;margin-left:50px; margin-bottom:0px;">';
if($_SESSION['id_user']==1) echo "TR - ".$tr." | ";
echo $naglowek.' <span style="color:#B700FF;">*</span>: <font color="red">'.$err[$tr].'</font></p>';
echo '<div style="padding-top:25px; padding-left:0px; width:740px; padding-bottom:0px; border:1px; border-style:solid; margin-top:20px; ">';
echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA">';
if (isset($_POST["dane".$tr])) {
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
if($_POST["kolor".$tr]!='') {
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
if($_POST["pozycja".$tr]!='') {
							list($poz_id,$poz_wart) = explode('|', $_POST["pozycja".$tr]);
							
							
							$COL=mysqli_fetch_row($db->query("select NAZWA FROM PARM WHERE ID='$poz_id' AND ID_GROUP=0 AND ID_OPCJ IN (1,2,3)"));
							$poz_nazwa=$COL[1];
							$domyślny_kom=' (ustawiony)';
							} 
else { 
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
if($_POST["rozmiar".$tr]!='') {
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
//-----------------------------------------------------------------------------------STYLE--------------------------------------------------------------------------------------
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
//----------------------------------------------------------------------------------KONIEC-STYLE-TEXT-TYTUL----------------------------------------
echo '</td></tr></table>';
};
echo '<input type="hidden" name="CSS_DEF_'.$tr.'" value="1" />';
echo '</div>';
echo '<input type="hidden" name="ID_TREN" value="'.$_GET["IDE"].'" />';
echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
echo '<input class="button" type="submit" value="Edytuj" name="edytuj"/></form>';
echo '<p class="P_LEG">Legenda :</p><p class="P_LEG_INFO">';
echo '- pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane;<br/>';
//echo '- IMIĘ/NAZWISKO musi zawierać min (<span class="S_LEG_INFO">5</span>) znaków; <br/> ';
//echo '- IMIĘ/NAZWISKO może zawierać max (<span class="S_LEG_INFO">200</span>) znaków;<br/> ';
//echo '- DODATKOWE INFORMACJE muszą zawierać min (<span class="S_LEG_INFO">5</span>) znaków;<br/> ';
//echo '- DODATKOWE INFORMACJE muszą zawierać max (<span class="S_LEG_INFO">2000</span>) znaków;<br/> ';
echo '- ZDJĘCIE, dozwolony TYP : (<span class="S_LEG_INFO">JPG JPEG PNG BMP GIF</span>);<br/> ';
echo '</p></center></div>';
} else if ($status_popraw==1){
							echo '<a class="A_BACK"href="cel_TREN.php?IDW=0"><p class="P_HREF_BACK">Powrót</p></a>';
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
for ($d_ins=0;$d_ins<1;$d_ins++){
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
								}

$db->query("UPDATE `TREN` SET `ID_COL_IMIE_N`='$kol_id[0]' ,`ID_COL_INFO`='$kol_id[1]', `IMIE_N`='$dane[0]',`INFO`='$dane[1]',`ID_PERS`='$_SESSION[id_user]',`K_IMIE_N`='$kol_hex[0]',`K_INFO`='$kol_hex[1]',`R_IMIE_N`='$_POST[rozmiar0]',`R_INFO`='$_POST[rozmiar1]',`DAT_K`=NOW(),`UID`='$_SESSION[uid]',`ID_P_IMI_N`='$pozyzja_id[0]',`ID_P_INFO`='$pozyzja_id[1]',`P_IMI_N_WART`='$pozycja_wart[0]',`P_INFO_WART`='$pozycja_wart[1]',`CSS_IMI_N`='$CSS_dane[0]',`CSS_INFO`='$CSS_dane[1]',`WSK_V`=0,`WSK_K`=WSK_K+1 WHERE `ID`='$_POST[ID_TREN]' ");


									echo '<p class="P_MAIN">Informacje o Trenerze zostały uaktualnione.</p>';
									if($_SESSION['id_user']==1) { echo "<span style=\"color:green;\">Poprawnie wykonano zapytanie SQL - UPD_TREN</span><br/>";};
									for($w=0;$w<2;$w++){
														list($css_b[$w],$css_i[$w],$css_u[$w]) = explode('|', $CSS_dane[$w]);
														if ($css_b[$w]==0) $font_weight='font-weight:normal;'; else $font_weight='font-weight:bold;';
														if ($css_i[$w]==0) $font_style='font-style: normal;'; else $font_style='font-style:italic;';
														if ($css_u[$w]==0) $text_dec=''; else $text_dec='text-decoration: underline;';
										//if ($w==0) $nagl_wysw="Imie/Nazwisko"; else if ($w==1) $nagl_wysw="Dodatkowe informacje";
										if ($w==0) $nagl_wysw="Opis"; else if ($w==1) $nagl_wysw="Dodatkowe informacje";
										if ($_SESSION['id_user']==1){ echo $nagl_wysw." ( Kolor (HEX) : ".$kol_hex[$w]." Kolor (ID) : ".$kol_id[$w]." Rozmiar : ".$rozmiar[$w]." Pozycja (ID) : ".$pozyzja_id[$w]." Pozycja (WART) : ".$pozycja_wart[$w].")<br/>";};
										if($_SESSION['id_user']==1) {echo 'CSS '.$nagl_wysw.' - '.$CSS_dane[$w].'<br/>';};
										echo '<p style="text-align: left; font-size: 20px; font-weight:bold;margin-left:50px;margin-bottom:5px;">'.$nagl_wysw.': <br/>';
										echo '<div style="background:white;width:750px; border:1px; border-style:solid; margin:5px; border-color:#D3D3D3;" >';
										echo '<p style="background:white;color:'.$kol_hex[$w].';font-size:'.$_POST["rozmiar".$w].'; text-align:'.$pozycja_wart[$w].';'.$font_weight.$font_style.$text_dec.'">'.$_POST["dane".$w].'</p>';
										echo '</div></p>';
										};							
									
//--------------------------------------------------------------------UPDATE-ZDJECIE---------------------------------------------------------------------------------------
if ($IMG_ZMIANA==TRUE) {
										if($_SESSION['id_user']==1) {echo "Jesteś w w Sekcji Update IMG<br/>";};
										for( $y = 1; $y<2; $y++ ) {
													if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"])) {
//---------------------------------------------------DIR------------------------------------------------------------------------------------------------
																$dir=opendir("../../zdjecia/trener");
																$ile_zdjec=0; 
																					while($plik=readdir($dir)){ 
																												if($plik!="." && $plik!="..")
																																			{
																																				if(strrchr($plik, "min")==FALSE) $ile_zdjec++;
																																				if($_SESSION['id_user']==1) { echo "PLIK - ".$plik."<br/>";};
																																				}
																												};
																					closedir($dir);
																					if ($_SESSION['id_user']==1){ 
																												echo '<p style="text-align:left; margin-left:20px;">Aktualna ilość zdjęć - '.$ile_zdjec.'</p>';
																												};
//--------------------------------------------------END-DIR------------------------------------------------------------------------------------------------
				if ($_SESSION['id_user']==1){ echo "Dodano nowy obraz<br/>";};
				$uploads_dir = '../../zdjecia/trener/'; 
				$filename=$_FILES["obraz".$y]["name"];
				$ext=strtolower(substr(strrchr($filename, '.'), 1));
				$ile_zdjec++;
				$image_name="org".$ile_zdjec."_trener.".$ext;
				$tmp_name=$_FILES["obraz".$y]["tmp_name"]; 
				move_uploaded_file($tmp_name, "$uploads_dir$image_name"); 
				list($width, $height)=getimagesize($uploads_dir.$image_name);
//------------------------------------------------------------------------------------------------------RESIZE-IMG--------------------------------------------	
																$SQL_LIKE="%MAX";
																for ($p_resize=0;$p_resize<2;$p_resize++){
																											//--------------------------------------------------------SQL-SELECT-IMG-MAX-------------------------------------------------------------------
																											
																											$REK_IMG=mysqli_fetch_row($db->query("SELECT WART FROM PARM WHERE ID_MODUL='$_GET[IDM]' AND ID_GROUP=4 AND WSK_U=0 AND N_OPCJ LIKE '$SQL_LIKE' LIMIT 1"));
																											//--------------------------------------------------------KONIEC-SQL-SELECT-IMG-MAX------------------------------------------------------------
																																					
																													switch ($p_resize):
																																			case 0: 
																																					$SQL_LIKE="%MIN";
																																					$naglowek_img='new_'.$ile_zdjec."_trener.".$ext;
																																					$naglowek="Galeria ";
																																					break;
																																			case 1:
																																					
																																					$naglowek_img='min_'.$ile_zdjec."_trener.".$ext;
																																					$naglowek="Miniatura ";
																																					break;
																																			default:
																																					
																																					$naglowek_img='def_'.$ile_zdjec."_trener.".$ext;
																																					$naglowek="DEFAULT ";
																																					$SQL_LIKE="%MIN";
																																					break;
																													endswitch;
																		if ($width>$REK_IMG[0] || $height>$REK_IMG[0]){ // 0 miniatura 324x324   // 1 900x900
																												$wynik_img_size = resize_image($width,$height,$REK_IMG[0],$naglowek_img,$ext,$uploads_dir,$image_name);
																												$new_width[$p_resize]=round($wynik_img_size[0]);
																												$new_height[$p_resize]=round($wynik_img_size[1]);
																												$new_image_name[$p_resize]=$wynik_img_size[2];
																		} else {
																				$new_image_name[$p_resize]=$image_name;
																				$new_height[$p_resize]=round($height);
																				$new_width[$p_resize]=round($width);
																				}
																		
																		}
//------------------------------------------------------------------------------------------------------END-RESIZE-IMG-------------------------------------------------------------			
				
				
				$istnieje_rekord = mysqli_num_rows($db->query("select ID FROM TREN_IMG where ID_TREN='$_POST[ID_TREN]' AND NR_IMG='$y'"));
									$width=$width+10;
									$height=$height+10;
					             if ($istnieje_rekord>0) {
														$db->query("UPDATE `TREN_IMG` SET `NAZWA_O`='$filename' ,`NAZWA_I`='$new_image_name[0]', `WIDTH`='$new_width[0]', `HEIGHT`='$new_height[0]',`NAZWA_I_M`='$new_image_name[1]', `M_WIDTH`='$new_width[1]', `M_HEIGHT`='$new_height[1]' WHERE `ID_TREN`='$_POST[ID_TREN]' AND `NR_IMG`='$y'");
														 
															
														
								 } else {
										$db->query("INSERT INTO TREN_IMG (ID_TREN,NAZWA_O,NAZWA_I,ID_PERS,DAT_UTW,NR_IMG,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT) VALUES ('$_POST[ID_TREN]','$filename','$new_image_name[0]','$_SESSION[id_user]',NOW(),'$y','$new_width[0]','$new_height[0]','$new_image_name[1]','$new_width[1]','$new_height[1]')");
										}
				echo '<a>';
			
				echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/trener/$image_name','$filename','$width','$height')\">";
																echo '<img src="'.APP_URL.'/zdjecia/trener/'.$image_name_min.'" alt="'.$image_name_min.'" style="width:'.$min_width.'px; height:'.$min_height.'px; border:0px;" />';
																echo '</a>';
				echo '</a>';
				$ILE_ZD++;
																								} else { 
																									//	echo '<span style="color:red">Nie wskazano '';
																									// 	echo '<span style="color:#0099FF;">NOWEGO</span>';
																									// 	echo 'obrazu pomocniczego nr : <span style="color:black;">'.$y.'</span></span><BR/>';
																										};
													};// Koniec pętla FOR - IMG
//--------------------------------------------------------------------KONIEC-UPDATE-ZDJECIE---------------------------------------------------------------------------------------		
									} // KONIEC IMG TRUE
									else {echo "<p class=\"P_INFO\"> Nie wskazano żadnego nowego zdjęcia </p>";};
																		echo '<a class="A_BACK" href="cel_TREN.php?IDW=0&IDM='.$_GET['IDM'].'"><p class="P_HREF_BACK2">Powrót do MENU - Trenerzy</p></a>';
									

$db->insDbLog($_GET["IDM"],'EDYTUJ TRENER - uaktualniono - '.$ID_TREN);	
foreach ($_POST as $key => $value) {
																		echo '<p style="text-align:left;">';
																			if ($_SESSION['id_user']==1){ echo $key.' = '.$value.'<br/>';};
																		UNSET($_POST[$key]);
																			if ($_SESSION['id_user']==1){ echo $key.' : '.$_POST[$key].'<br/>';};
																		echo '</p>';
																		};					
}; // Koniec status-popraw=1
?>
</div>
</center>
<body>

