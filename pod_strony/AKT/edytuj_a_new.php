<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<?php
$css="cel_AKT.css";
echo '<link rel="stylesheet" href="../../css/'.$css.'" type="text/css">';
?>
<link rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../../images/favicon.ico" type="image/x-icon">
<!----------------------------------------------------------------------------------------------------------------------------------->
<?php
if (!isset($_SESSION['login'])) {	
								 echo '<p style="text-align: center; font-size: 22px; font-weight:bold; color:red;margin-top:50px;">Musisz się zalogować, aby korzystać z tej części serwisu !</p>';
								}
								else {
										$now = time(); // Checking the time now when home page starts.
										if ($now > $_SESSION['expire']) {
																		session_destroy();
																		  echo '<p style="text-align: center; font-size: 22px; font-weight:bold; color:red;margin-top:50px;">Sesja wygasła, musisz się ponownie zalogować !</p>';
																		  //header('location:../../logowanie.php'); 
																		}
										else { //Starting this else one [else1]
//--------------------------------------------------------PLIKI----------------------------------------------------------------------------------
																				$kat_funkcje="../_funkcje_/";
																				$css="cel_AKT.css";
																				$PLIKI = Array("konfiguracja.php"=>"../../", $css => "../../css/","insert_dziennik.php"=>$kat_funkcje,"check_len.php"=>$kat_funkcje,"resize_image_new_v3.php"=>$kat_funkcje,"sprawdz_haslo.php"=>$kat_funkcje,"sprawdz_napis.php"=>$kat_funkcje,"duza_pierwsza_litera.php"=>$kat_funkcje);
																				foreach ($PLIKI as $n_plik => $s_plik){
																														$test_plik = file_exists($s_plik.$n_plik); 
																														if (!$test_plik) {
																																		echo "<p class=\"P_ERROR\" style=\"color:red;\">Brak pliku - <span class=\"S_INFO\">".$n_plik."</span></p>";  
																														}
																														else{
																																		if ($_SESSION['id_user']==1) echo "<p class=\"P_INFO\">Wczytano plik - <span  class=\"S_INFO\">".$n_plik."</span></p>";
																																		if ($n_plik=="konfiguracja.php") {require_once($s_plik.$n_plik); };
																																		if ($n_plik==$css) {$style_css=$s_plik.$n_plik;}
																																		if ($s_plik==$kat_funkcje) {include($s_plik.$n_plik);}
																														};
																				};
																				//--------------------------------------------------------KONIEC-PLIKI---------------------------------------------------------------------------
?>
<SCRIPT>
function displayWindow(url, width, height) {
		var left = (screen.width/2)-(width/2);
		var top = (screen.height/3)-(height/1);
        var Win = window.open(url,"displayWindow",'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + 'resizable=0,scrollbars=yes,menubar=no' );
											};
</SCRIPT>
</head>
<body>
<?php
//$checked=TRUE;
?>
<center>
<div class="DIV_MAIN">
<?php
IF (ISSET($_GET["IDSTR"])) {$ZAK_STR='&IDWs_poczatek='.$_GET["IDWs_poczatek"].'&IDWs_koniec='.$_GET["IDWs_koniec"].'&IDSTR='.$_GET["IDSTR"];
echo "ZAK_STR - $ZAK_STR</br>";
};
$ID = $_GET['IDE'];
if (($_GET['IDE']!=''))
	{
		mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
		$query = mysql_query("select * from NEWS where ID='$ID'") or die(mysql_error());
		$istnieje_rekord = mysql_num_rows($query);
		if ($istnieje_rekord!=0) {
							mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$query = mysql_query("select * from NEWS where ID='$ID' AND WSK_U=1") or die(mysql_error());
							$usuniety_rekord = mysql_num_rows($query);
							if ($usuniety_rekord==0){	
$zdjecia=FALSE;
echo '<a href="cel_AKT.php?IDW=0&IDM='.$_GET['IDM'].$ZAK_STR.'"><p style="text-align: left;margin:20px;">Anuluj</p></a>';
//------------------------------------------------------------------POBIERANIE-PARM-Z-BAZY------------------------------------------------------
if (!isset($_POST["artykul"])) {
																if ($_SESSION["id_user"]==1){echo '<p CLASS="P_INFO">Uruchomiono procedurę pobierania parametrów z bazy !</p>';};
																mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																$SEL_PARM = mysql_query("select n.ID, n.TYTUL,n.TRESC,n.ADRES,n.R_TYTUL,n.R_TRESC,n.ID_KATALOG,n.ID_COL_TYT,n.ID_COL_TRE,n.ID_P_TYT,n.ID_P_TRE,n.CSS_TYT,n.CSS_TRE,n.ILOZD FROM NEWS n where n.ID='$ID'") or die(mysql_error());
																$REK_PARM = mysql_fetch_array($query);
																$ID_NEWS=$REK_PARM[0];
																$ID_KAT=$REK_PARM[6];
																$ILE_ZD=$REK_PARM[13];
																mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																$SEL_PARM = mysql_query("select ID,ID_GROUP,N_OPCJ,NAZWA,WART,WSK_D FROM PARM WHERE WSK_U=0 AND WSK_V=1 AND ID_MODUL=$_GET[IDM] order by ID_GROUP");
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
																while($REK_PARM = mysql_fetch_array($SEL_PARM)){
																								if ($_SESSION["id_user"]==1){echo '<p CLASS="P_INFO">ID_GROUP [<span class="S_INFO">'.$REK_PARM[1].'</span>]</p>';};
																			switch($REK_PARM[1]):
																								case 0:
																										if (strpos($REK_PARM[2],"_AKT_TYT")) {
																										//echo "_AKT_TYT";
																										$poz_id[$i_poz]=$REK_PARM[5];
																										$poz_nazwa[$i_poz]=$REK_PARM[3];
																										$poz_wart[$i_poz]=$REK_PARM[4];
																										}
																										else if (strpos($REK_PARM[2],"_AKT_TRE")){
																										//echo "_AKT_TRE";
																										$poz_id[$i_poz]=$REK_PARM[5];
																										$poz_nazwa[$i_poz]=$REK_PARM[3];
																										$poz_wart[$i_poz]=$REK_PARM[4];
																										} else {
																												echo "BŁĄD - skontaktuj się z Administratorem !";
																										}
																										if ($_SESSION["id_user"]==1){echo '<p CLASS="P_INFO">'.$i_poz.' POZYCJA : [<span class="S_INFO">'.$poz_nazwa[$i_poz].' | '.$poz_wart[$i_poz].' | '.$poz_id[$i_poz].'</span>]</p>';};
																										$i_poz++;
																									break;
																								case 1:
																										$i_while=0;
																										mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																										$SEL_CSS=mysql_query("select c.ID,c.NAZWA FROM CSS c WHERE c.ID_GROUP=0 ORDER BY c.ID") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_P_CSS - ".mysql_error()."</span></p>");
																										while($REK_CSS = mysql_fetch_array($SEL_CSS)){
																											mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																											$SEL_P_CSS=mysql_query("select pc.ID,pc.WSK_V FROM PARM_CSS pc, PARM pm WHERE pc.ID_PARM=pm.ID AND pm.ID_MODUL='$_GET[IDM]' AND pc.ID_CSS='$REK_CSS[0]' AND pm.ID='$REK_PARM[0]' LIMIT 1")or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_P_CSS - ".mysql_error()."</span></p>");
																											$REK_P_CSS = mysql_fetch_array($SEL_P_CSS);
																											if($REK_P_CSS[1]=="0" ||$REK_P_CSS[1]=="") $css[$i_while]=0; else IF ($REK_P_CSS[1]=="1") $css[$i_while]=1;
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
																								case 2:
																										$rozmiar[$i_poz_r]=$REK_PARM[4];
																										if ($_SESSION["id_user"]==1){
																																	echo '<p CLASS="P_INFO">'.$i_poz_r.' |';
																																	echo '<span class="S_INFO">Wartość tozmiar tekstu :'.$rozmiar[$i_poz_r].'</span></p>';
																										};
																										$i_poz_r++;
																									break;
																								case 3:
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
																/*
																if ($_SESSION["id_user"]==1){
																							echo '<p CLASS="P_INFO">Parametr - ';
																							echo '<span class="S_INFO">'.$REK_PARM[1].'</span> | <span class="S_INFO">'.$REK_PARM[2].'</span>';
																							echo ' WART : <span class="S_INFO">'.$REK_PARM[4].'</span>';
																							echo ' WSK_D : <span class="S_INFO">'.$REK_PARM[5].'</span></p>';
																};
																*/
																};
								};
//------------------------------------------------------------------KONIEC-POBIERANIE-PARM-Z-BAZY------------------------------------------------------

if (isset($_POST["edytuj"])) {
	$checked_img=TRUE;
	$checked=TRUE;
	$IMG_ZMIANA=FALSE;
	$ZD_SPR=$_POST["ILE_PHOTO"];
	for( $y = 1; $y<5; $y++ ) {
								if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"])) {
								if ($checked_img==TRUE) {
														if(($_FILES["obraz".$y]["type"] == 'image/gif') || ($_FILES["obraz".$y]["type"] == 'image/jpeg') || ($_FILES["obraz".$y]["type"] == 'image/jpg') || ($_FILES["obraz".$y]["type"] == 'image/png') || ($_FILES["obraz".$y]["type"] == 'image/bmp') || ($_FILES["obraz".$y]["type"] == 'image/pjpeg')) 
																	{
																	$max_rozmiar = 8388608;
																	if ($_FILES["obraz".$y]["type"] < $max_rozmiar)
																		{	
																			$checked_img=TRUE;
																			$IMG_ZMIANA=TRUE;
																			$ZD_SPR++;
																		echo "IMG_ZMIANA = ".$IMG_ZMIANA." zdjecie nr - ".$y."<br/>";
																		} else {
																				$err_obraz_tab[$y]='<span style="color:red;">Za duży rozmiar obrazu</span> !';
																				$checked_img=FALSE;
																				//$ZD_SPR++;;
																				}
																	} else { 
																			$err_obraz_tab[$y]='<span style="color:red;">Wskazany plik nie jest obrazem </span>!';
																			$checked_img=FALSE;
																			//$ZD_SPR++;
																			}
									} else { 
											$err_obraz_tab[$y]='<span style="color:red;">Błąd poprzedniego zdjęcia </span>!';
											//$ZD_SPR++;
											}								
						} else {
								$err_obraz_tab[$y]='<span style="color:blue;">Nie wskazano obrazu.</span>';
								};
};
  $string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ .,'!:-_\r\n]+$/";
  if(!preg_match($string_exp,$_POST["tytul"])) { $err_tytul="Proszę usunąć niedozwolone znaki"; $checked=FALSE;};
  if(!preg_match($string_exp,$_POST["tresc"])) { $err_tresc="Proszę usunąć niedozwolone znaki"; $checked=FALSE;};

  check_len($checked, $_POST["tresc"],2000,$err_tresc,'Pole za długie (maxymalna ilość znaków - <span style="color:black;">800</span>)',5,'Proszę wypełnić (minimalna ilość znaków - <span style="color:black;">5</span>)');
  check_len($checked, $_POST["tytul"],200,$err_tytul,'Pole za długie (maxymalna ilość znaków - <span style="color:black;">100</span>)',5,'Proszę wypełnić (minimalna ilość znaków - <span style="color:black;">5</span>)');
if (($checked==FALSE) || ($checked_img==FALSE)) $status_popraw=0; else $status_popraw=1;
 };
if ($status_popraw==0){
//*********************************************************************************FORM-START*****************************************************************
echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';	
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
							echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA">';  
							if (isset($_POST["dane".$tr])) {
															echo $_POST["dane".$tr];
							}
							else { 
									echo $_POST["dane".$tr]; 
							};										
							echo '</textarea>';
							echo '<table><tr><td class="TD_L">';
							//----------------------------------------------------------------------------------KOLOR-Tytul/Treść----------------------------------------------
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
							echo '<p CLASS="P_CSS_NG_L">Kolor tekstu : ';
							echo '<select name="kolor'.$tr.'" class="SELECT">';
							echo '<optgroup label="Aktualny :" class="OPTGROUP">';
							echo '<option value="'.$kolor_id[$tr].'|'.$kolor_hex[$tr].'|'.$kolor_nazwa[$tr].'" style="font-weight:bold; color:'.$kolor_font[$tr].';background: none repeat scroll 0%  0% '.$kolor_hex[$tr].';">';
							echo $kolor_nazwa[$tr].' '.$domyślny_kom;
							echo '</option>';
							echo '</optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
							mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SEL_COLOR = mysql_query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 AND ID!='$kolor_id[$tr]'order by ID");
							while($r_kolor = mysql_fetch_array($SEL_COLOR)){
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
							if($_POST["pozycja".$tr]!='') {
															list($poz_id[$tr],$poz_wart[$tr],$poz_nazwa[$tr]) = explode('|', $_POST["pozycja".$tr]);
															$domyślny_kom=' (ustawiony)';
							};
							echo '<p CLASS="P_CSS_NG_L">Pozycja tekstu : ';
							echo '<select name="pozycja'.$tr.'" class="SELECT">';
							echo '<optgroup label="Aktualny :" class="OPTGROUP">';
							echo '<option value="'.$poz_id[$tr].'|'.$poz_wart[$tr].'|'.$poz_nazwa[$tr].'" class="OPTION">'.$poz_nazwa[$tr].' '.$domyślny_kom.'</option>';
							echo '</optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
							mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SEL_POZ_ALL = mysql_query("select ID,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=1 AND ID_OPCJ!='$poz_id[$tr]'  order by ID_OPCJ");
							while($r_pozycja = mysql_fetch_array($SEL_POZ_ALL)){
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
							mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SEL_CSS= mysql_query("select ID,NAZWA,WART,WSK_V FROM CSS WHERE WSK_V=1 AND ID_GROUP=0 ORDER BY ID") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_CSS".mysql_error()."</span></p>");
							while($REK_CSS = mysql_fetch_array($SEL_CSS)){
																			if ($css_tab[$tr][$i_wh_css]==$REK_CSS[3]) {
																														$domyslny='checked="checked"';
																														$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(domyślny)</span>';
																			}
														else if ($_POST["CSS_".$tr."_".$i_wh_css]!="") {
																					$domyslny='checked="checked"'; 
																					$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																					
																					} 
														else {
																$domyslny=''; 
																$domyslny_kom='';
															};
														echo '<input type="checkbox" name="CSS_'.$tr.'_'.$i_wh_css.'" value="'.$REK_CSS[0].'" '.$domyslny.' class="CSS_CHBOX"  /><span class="S_CSS">'.$REK_CSS[1].'</span> '.$domyslny_kom.'<br/>';
							$i_wh_css++;
							};	// Koniec petla WHILE
							//----------------------------------------------------------------------------------KONIEC-STYLE-TEXT-Imie/Nazwisko----------------------------------------
							echo '</td></tr></table>';
							echo '<input type="hidden" name="CSS_DEF_'.$tr.'" value="1" />';
							echo '<input type="hidden" name="MAX_WIDTH_'.$tr.'" value="'.$max_width[$tr].'" />';
							echo '<input type="hidden" name="MAX_HEIGHT_'.$tr.'" value="'.$max_height[$tr].'" />';	
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
							for( $x = 1; $x<5; $x++ ) {
														echo '<p class="P_INPUT">Zdjęcie nr : ';
														echo '<span class="S_NG_DANE">'.$x.' </span>';
														echo '<input type="file" name="obraz'.$x.'"/> ';
														echo $err_obraz_tab[$x].'</p>';
							};
							echo '</div>';
							echo '<input class="button" type="submit" value="Edytuj" name="artykul"></FORM>';
							//---------------------------------------------------------------LEGENDA----------------------------
							echo '<p class="P_LEG">Legenda :</p><p class="P_LEG_INFO">';
							echo '- pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane;<br/>';
							echo '- TYTUŁ musi zawierać min (<span class="S_LEG_INFO">5</span>) znaków; <br/> ';
							echo '- TYTUŁ może zawierać max (<span class="S_LEG_INFO">200</span>) znaków;<br/> ';
							echo '- TREŚĆ musi zawierać min (<span class="S_LEG_INFO">5</span>) znaków;<br/> ';
							echo '- TREŚĆ może zawierać max (<span class="S_LEG_INFO">2000</span>) znaków;<br/> ';
							echo '- ZDJĘCIA i FILM nie jest (<span class="S_LEG_INFO">wymagany</span>);<br/> ';
							echo '- ZDJĘCIA, dozwolony TYP : (<span class="S_LEG_INFO">JPG JPEG PNG BMP GIF</span>);<br/> ';
							//---------------------------------------------------------------KONIEC-LEGENDA----------------------------
							echo '</p></center>';
//**********************************************************************************KONIEC-FORM*****************************************************************************						
echo '<form action="" method="POST" ENCTYPE="multipart/form-data">';
echo '<p style="font-size:24; font-weight:bold; text-align:center; margin:0px;">Edytowanie treści artykułu nr [<span style="color:purple;">'.$rekord[0].'</span>]</p>';
echo '<p style="text-align: left; font-size: 20px; font-weight:bold;margin-left:50px;">Tytuł <span style="color:#B700FF;">*</span>: <font color="red">'.$err_tytul.'</font></p>';
echo '<div style="padding-top:25px; padding-left:0px; width:740px; padding-bottom:0px; border:1px; border-style:solid; ">';
echo '<textarea name="tytul" rows="1" cols="85" style="resize:none;">';
if (isset($_POST["tytul"])) {
							echo $_POST["tytul"];
							}
							else {
															//$konv = $rekord[1]; 
															//echo '$_rekord[dane'.$tab.'] = '.$rekord[$tab];
															$zmieniony = str_replace("<br />","",$rekord[1]); //- w tym przypadku znak "+" zostanie zastąpiony wyrazem "plus"	
															echo $zmieniony;
															};
echo '</textarea></br>';
//----------------------------------------------------------------------------------KOLOR-TYTUŁ------------------------------------------------------------------
echo '<table border="0px"><tr><td width="390px" valign="TOP">';
if($_POST["kolor_tyt"]!='') {
							list($kolor_tyt,$kolor_tyt_e_v) = explode('|', $_POST["kolor_tyt"]);
							mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SELECT_COLOR = mysql_query("select NAZWA,HEX from COLOR WHERE ID='$kolor_tyt_e_v' AND WSK_U=0") or die(mysql_error());;
							$col=mysql_fetch_row($SELECT_COLOR);
							$kolor_tyt_e_n=$col[0];
							$kolor_tyt_hex=$col[1];
							$domyślny_kom=' (ustawiony)';
							} 
else {
		mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
		$SEL_DOM_COL = mysql_query("select ID,NAZWA,HEX FROM COLOR WHERE ID='$rekord[7]';") or die(mysql_error());;
		$DOM_COL=mysql_fetch_row($SEL_DOM_COL);
		$kolor_tyt_e_v=	$DOM_COL[0];
		$kolor_tyt_e_n=	$DOM_COL[1];
		$kolor_tyt_hex=	$DOM_COL[2];
		$domyślny_kom=' (ustawiony)';
};
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;"">Kolor tekstu : ';
echo '<select name="kolor_tyt" style="height:23px;width:170px;"><optgroup label="Aktualny"><option value="'.$kolor_tyt_hex.'|'.$kolor_tyt_e_v.'" style="font-weight:bold; color: BLACK ;background: none repeat scroll 0%  0% '.$kolor_tyt_hex.';">'.$kolor_tyt_e_n.' '.$domyślny_kom.'</option></optgroup><optgroup label="Pozostałe">';
mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
$wyswietl = mysql_query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 order by ID");
while($rek_col = mysql_fetch_array($wyswietl))
												{
												$_COLOR_ ='<option value="'.$rek_col[2].'|'.$rek_col[0].'" style="color: BLACK ; background: none repeat scroll 0%  0% '.$rek_col[2].';">'.$rek_col[1].'</option>';
												echo $_COLOR_;
												}
echo '</optgroup></select>';
//----------------------------------------------------------------------------------KOLOR-TYTUŁ-KONIEC--------------
//----------------------------------------------------------------------------------POZYCJA-TEXT-TYTUŁ----------------------------------------------
if($_POST["poz_tyt"]!='') {
							list($poz_tyt,$poz_tyt_e_v) = explode('|', $_POST["poz_tyt"]);
							mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SELECT_COLOR = mysql_query("select ID,NAZWA,WART FROM PARM WHERE ID='$poz_tyt' AND ID_GROUP=0") or die(mysql_error());;
							$col=mysql_fetch_row($SELECT_COLOR);
							$poz_tyt_id=$col[0];
							$poz_tyt_e_n=$col[1];
							$poz_tyt_e_v=$col[2];
							$domyślny_kom=' (ustawiony)';
							} 
else {
		mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
		$SEL_DOM_COL = mysql_query("select ID,NAZWA,WART FROM PARM WHERE ID='$rekord[9]' AND ID_GROUP=0 ") or die(mysql_error());;
		$DOM_COL=mysql_fetch_row($SEL_DOM_COL);
		$poz_tyt_id=$DOM_COL[0];
		$poz_tyt_e_n=$DOM_COL[1];
		$poz_tyt_e_v=$DOM_COL[2];		
		$domyślny_kom=' (ustawiony)';
};
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Pozycja tekstu : ';
echo '<select name="poz_tyt" style="height:23px;width:170px;"><optgroup label="Aktualny"><option value="'.$poz_tyt_id.'|'.$poz_tyt_e_v.'" style="font-weight:bold; color: BLACK ;background: none repeat scroll 0%  0%;">'.$poz_tyt_e_n.' '.$domyślny_kom.'</option></optgroup><optgroup label="Pozostałe">';
mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
//$wyswietl = mysql_query("select ID,NAZWA,WART FROM PARM WHERE WSK_U=0 AND ID_GROUP=1 AND ID!='$poz_tyt_id'order by ID");
$SEL_POZ_ALL = mysql_query("select ID,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=1 AND ID_OPCJ!='$poz_tyt_id'  AND ID_OPCJ IN (1,2,3) order by ID_OPCJ");
while($rek_parm = mysql_fetch_array($SEL_POZ_ALL))
												{
												echo '<option value="'.$rek_parm[0].'|'.$rek_parm[2].'" style="color: BLACK ; background: none repeat scroll 0%  0% ;">'.$rek_parm[1].'</option>';						
												}
echo '</optgroup></select></p>';
//----------------------------------------------------------------------------------KONIEC-POZYCJA-TEXT-TYTUL--------------------------------------
//----------------------------------------------------------------------------------ROZMIAR-TYTUŁ-----------------------------------
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Rozmiar czcionki : ';
if($_POST["rozmiar_tyt"]!='') {
							$rozmiar_tyt=$_POST["rozmiar_tyt"];
							$domyślny_kom=' (ustawiona)';
							}
else {	
		$rozmiar_tyt=$rekord[4];
		$domyślny_kom=' (ustawiona)';
};
echo '<select name="rozmiar_tyt" style="height:23px;width:170px;"><optgroup label="Aktualny"><option value="'.$rozmiar_tyt.'" style="font-weight:bold; color: BLACK ;background: none repeat scroll 0%  0% white;">'.$rozmiar_tyt.' '.$domyślny_kom.'</option></optgroup><optgroup label="Pozostałe">';
for( $i = 12; $i<33;) {
							echo '<option value="'.$i.'" style="color: BLACK ; background: none repeat scroll 0%  0% white;">'.$i.'</option>';
							$i=$i+2; 
							};
echo '</optgroup></select>';
//----------------------------------------------------------------------------------ROZMIAR-TYTUŁ-KONIEC--------------------------------------------
//-----------------------------------------------------------------------------------STYLE-TEXT-TYTUL----------------------------------------------
echo '</td><td  WIDTH="350px">';
echo '<p style="text-align: left; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Wskaż opcję formatowania :</p>';
mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
//$CSS_DEF_TYT = mysql_query("select ID,ID_OPCJ,NAZWA,WART,SUBSTRING(WSK_D, 1, 1) FROM PARM WHERE WSK_U=0 AND ID_GROUP=2 ORDER BY ID");
$CSS_DEF_TYT = mysql_query("select ID,ID_OPCJ,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=0 ORDER BY ID_OPCJ");
if(!isset($_POST['CSS_TYT_DEF'])) $CSS_TYT_DEF=TRUE; else $CSS_TYT_DEF=FALSE;

//rek_css[0] -> CSS TYTUŁ BOLD
//rek_css[1] -> CSS TYTUŁ ITALIC
//rek_css[2] -> CSS TYTUŁ UNDERLINE
list($rek_css[0],$rek_css[1],$rek_css[2]) = explode('|', $rekord[11]);
if ($_SESSION['id_user']==1) {echo 'BOLD - '.$rek_css[0].' ITALIC - '.$rek_css[1].' Underline - '.$rek_css[2].'<br/>';};					
$i=0;
while($REK_CSS_TYT = mysql_fetch_array($CSS_DEF_TYT))
{
if ($CSS_TYT_DEF!=TRUE) {
							if (!isset($_POST['CSS_TYT_'.$REK_CSS_TYT[1]])) {
																			$domyslny=''; 
																			$domyslny_kom='';
																			} 
																			else {
																					$domyslny='checked="checked"'; 
																					$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																				};
						}
else if ($rek_css[$i]=='0') {
								$domyslny=''; 
								$domyslny_kom='';
								$i++;
								} 
								else {
										$domyslny='checked="checked"'; 
										$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
										$i++;
									};
echo '<input type="checkbox" name="CSS_TYT_'.$REK_CSS_TYT[1].'" value="'.$REK_CSS_TYT[0].'" '.$domyslny.'  />'.$REK_CSS_TYT[2].' '.$domyslny_kom.'<br/>';
};	
//----------------------------------------------------------------------------------KONIEC-STYLE-TEXT-TYTUL----------------------------------------
echo '</td></tr></table>';
echo '</div>';
echo '<p style="text-align: left; font-size: 20px; font-weight:bold;margin-left:50px;">Treść <span style="color:#B700FF;">*</span>: <font color="red">'.$err_tresc.'</font></p>';
echo '<div style="padding-top:25px; padding-left:0px; width:740px; padding-bottom:0px; border:1px; border-style:solid; ">';
echo '<textarea name="tresc" rows="20" cols="85" style="resize:none;">';
if (isset($_POST["tresc"])) {
							echo $_POST["tresc"];
							}
							else {
															//$konv = $rekord[2]; 
															//echo '$_rekord[dane'.$tab.'] = '.$rekord[$tab];
															$zmieniony = str_replace("<br />","",$rekord[2]); //- w tym przypadku znak "+" zostanie zastąpiony wyrazem "plus"	
															echo $zmieniony;
															};
echo '</textarea>';
//----------------------------------------------------------------------------------KOLOR-TRESC-----------------------------------------------------
echo '<table border="0px"><tr><td width="390px" valign="TOP">';
if($_POST["kolor_tresc"]!='') {
							list($kolor_tre,$kolor_tre_e_v) = explode('|', $_POST["kolor_tresc"]);
							mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SELECT_COLOR = mysql_query("select NAZWA,HEX from COLOR WHERE ID='$kolor_tre_e_v' AND WSK_U=0") or die(mysql_error());;
							$col=mysql_fetch_row($SELECT_COLOR);
							$kolor_tre_e_n=$col[0];
							$kolor_tre_hex=$col[1];
							$domyślny_kom=' (ustawiony)';
							} 
else {
		mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
		$SEL_DOM_COL = mysql_query("select ID,NAZWA,HEX FROM COLOR WHERE ID='$rekord[8]';") or die(mysql_error());;
		$DOM_COL=mysql_fetch_row($SEL_DOM_COL);
		$kolor_tre_e_v=	$DOM_COL[0];
		$kolor_tre_e_n=	$DOM_COL[1];
		$kolor_tre_hex=	$DOM_COL[2];
		$domyślny_kom=' (ustawiony)';
};
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;"">Kolor tekstu : ';
echo '<select name="kolor_tresc" style="height:23px;width:170px;"><optgroup label="Aktualny"><option value="'.$kolor_tre_hex.'|'.$kolor_tre_e_v.'" style="font-weight:bold; color: BLACK ;background: none repeat scroll 0%  0% '.$kolor_tre_hex.';">'.$kolor_tre_e_n.' '.$domyślny_kom.'</option></optgroup><optgroup label="Pozostałe">';
mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
$wyswietl = mysql_query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 order by ID");
while($rek_col = mysql_fetch_array($wyswietl))
												{
												$_COLOR_ ='<option value="'.$rek_col[2].'|'.$rek_col[0].'" style="color: BLACK ; background: none repeat scroll 0%  0% '.$rek_col[2].';">'.$rek_col[1].'</option>';
												echo $_COLOR_;
												}

echo '</optgroup></select>';
//----------------------------------------------------------------------------------KOLOR-TRESC-KONIEC-----------------------------------
//----------------------------------------------------------------------------------POZYCJA-TEXT-TRESC----------------------------------------------
if($_POST["poz_tre"]!='') {
							list($poz_tre,$poz_tyt_e_v) = explode('|', $_POST["poz_tre"]);
							mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SELECT_COLOR = mysql_query("select ID,NAZWA,WART FROM PARM WHERE ID='$poz_tre' AND ID_GROUP=0") or die(mysql_error());;
							$col=mysql_fetch_row($SELECT_COLOR);
							$poz_tre_id=$col[0];
							$poz_tre_e_n=$col[1];
							$poz_tyt_e_v=$col[2];
							$domyślny_kom=' (ustawiony)';
							} 
else {
		mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
		$SEL_DOM_COL = mysql_query("select ID,NAZWA,WART FROM PARM WHERE ID='$rekord[10]' AND ID_GROUP=0 ") or die(mysql_error());;
		$DOM_COL=mysql_fetch_row($SEL_DOM_COL);
		$poz_tre_id=$DOM_COL[0];
		$poz_tre_e_n=$DOM_COL[1];
		$poz_tre_e_v=$DOM_COL[2];		
		$domyślny_kom=' (ustawiony)';
};
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Pozycja tekstu : ';
echo '<select name="poz_tre" style="height:23px;width:170px;"><optgroup label="Aktualny"><option value="'.$poz_tre_id.'|'.$poz_tre_e_v.'" style="font-weight:bold; color: BLACK ;background: none repeat scroll 0%  0%;">'.$poz_tre_e_n.' '.$domyślny_kom.'</option></optgroup><optgroup label="Pozostałe">';
mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
$SEL_POZ_ALL = mysql_query("select ID,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=1 AND ID_OPCJ!='$poz_tre_id'  AND ID_OPCJ IN (1,2,3) order by ID_OPCJ");
while($rek_parm = mysql_fetch_array($SEL_POZ_ALL))
												{
												echo '<option value="'.$rek_parm[0].'|'.$rek_parm[2].'" style="color: BLACK ; background: none repeat scroll 0%  0% ;">'.$rek_parm[1].'</option>';
												
												}

echo '</optgroup></select>';
//-----------------------------------------------------------------------------------KONIEC-POZYCJA-TEXT-TRESC--------------------------------------
//----------------------------------------------------------------------------------ROZMIAR-TRESC----------------------------------
echo '<p style="text-align: right; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Rozmiar czcionki : ';
if($_POST["rozmiar_tresc"]!='') {
							$rozmiar_tresc=$_POST["rozmiar_tresc"];
							$domyślny_kom=' (ustawiona)';
							}
else {
				
		$rozmiar_tresc=$rekord[5];
		$domyślny_kom=' (ustawiona)';
};
echo '<select name="rozmiar_tresc" style="height:23px;width:170px;"><optgroup label="Aktualny"><option value="'.$rozmiar_tresc.'" style="font-weight:bold; color: BLACK ;background: none repeat scroll 0%  0% white;">'.$rozmiar_tresc.' '.$domyślny_kom.'</option></optgroup><optgroup label="Pozostałe">';
for( $i = 12; $i<33;) {
							echo '<option value="'.$i.'" style="color: BLACK ; background: none repeat scroll 0%  0% white;">'.$i.'</option>';
							$i=$i+2; 
							};
echo '</optgroup></select>';
//----------------------------------------------------------------------------------KONIEC-ROZMIAR-TRESC----------------------------------
//-----------------------------------------------------------------------------------STYLE-TEXT-TRESC----------------------------------------------
echo '</p></td><td  WIDTH="350px">';
echo '<p style="text-align: left; font-size: 16px; font-weight:bold; noresize:noresize; margin:10px;">Wskaż opcję formatowania :</p>';
mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
//$CSS_DEF = mysql_query("select ID,ID_OPCJ,NAZWA,WART,SUBSTRING(WSK_D, 3, 1) FROM PARM WHERE WSK_U=0 AND ID_GROUP=2 ORDER BY ID");
$CSS_DEF = mysql_query("select ID,ID_OPCJ,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=0 AND ID_OPCJ IN (1,2,3)ORDER BY ID");

//rek_css[0] -> CSS TYTUŁ BOLD
//rek_css[1] -> CSS TYTUŁ ITALIC
//rek_css[2] -> CSS TYTUŁ UNDERLINE
list($rek_css[0],$rek_css[1],$rek_css[2]) = explode('|', $rekord[12]);
if ($_SESSION['id_user']==1) {echo 'BOLD - '.$rek_css[0].' ITALIC - '.$rek_css[1].' Underline - '.$rek_css[2].'<br/>';};					
$i=0;
if(!isset($_POST['CSS_TRE_DEF'])) $CSS_TRE_DEF=TRUE; else $CSS_TRE_DEF=FALSE;
while($REK_CSS = mysql_fetch_array($CSS_DEF))
{
if ($CSS_TRE_DEF!=TRUE) {
						if (!isset($_POST['CSS_TRE_'.$REK_CSS[1]])) {
																	$domyslny=''; 
																	$domyslny_kom='';
																	} 
																	else {
																			$domyslny='checked="checked"'; 
																			$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
																		};
						}
else if ($rek_css[$i]=='0') {
							$domyslny=''; 
							$domyslny_kom='';
							$i++;
							} 
							else {
									$domyslny='checked="checked"'; 
									$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
									$i++;
									};
echo '<input type="checkbox" name="CSS_TRE_'.$REK_CSS[1].'" value="'.$REK_CSS[0].'" '.$domyslny.'  />'.$REK_CSS[2].' '.$domyslny_kom.'<br/>';

};								
//----------------------------------------------------------------------------------KONIEC-STYLE-TEXT-TRESC----------------------------------------
echo '</td></tr></table>';
echo '</div>';
echo '<p style="text-align:left;font-size:20px;font-weight:bold;margin-left:50px;">Adres URL Filmu :</p>';
echo '<div style="padding-top:20px;padding-bottom:20px; padding-left:0px; width:740px; border:1px; border-style:solid; ">';
echo '<textarea name="adres" rows="1" cols="85" style="resize:none">';  if (isset($_POST["adres"])) echo $_POST["adres"]; else echo $rekord[3]; echo '</textarea>';
echo '</div>';
$zap_img = mysql_query("select ID,KATALOG,NR_IMG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM NEWS_IMG where ID_NEWS='$ID_NEWS' AND WSK_U=0") or die(mysql_error());
for ($z=1; $z<5; $z++){
$ist_img = mysql_query("select ID from NEWS_IMG where ID_NEWS='$ID_NEWS' AND NR_IMG='$z'") or die(mysql_error());
$img_pom = mysql_num_rows($ist_img);
					if ($img_pom!=0){
									$zap_img = mysql_query("select ID,KATALOG,NR_IMG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM NEWS_IMG where ID_NEWS='$ID_NEWS' AND NR_IMG='$z' AND WSK_U=0") or die(mysql_error());
									$rek_img = mysql_fetch_array($zap_img);
									echo '<div style="border:1px; border-style:solid; margin:10px;">';
									echo '<a HREF="javascript:displayWindow(&#39;../../zdjecia/artykul/'.$ID_KAT.'/'.$rek_img[3].'&#39;,'.$rek_img[4].','.$rek_img[5].')">';
									echo '<img src="../../zdjecia/artykul/'.$ID_KAT.'/'.$rek_img[6].'" alt="Zdjecie" style="WIDTH:'.$rek_img[7].'px; HEIGHT:'.$rek_img[8].'px; border:0px; margin:10px;" />';
									echo '</a><p style="text-align:left; margin:10px;">NR - '.$rek_img[3];
									echo '<input type="file" name="obraz'.$z.'"/>'.$err_obraz_tab[$z].'</p></div>';
									}
					else  {
							echo '<div style="border:1px; border-style:solid; margin:10px;">';
							echo '<p style="margin:10px;text-align:left;">NR - '.$z;
							echo '<input type="file" name="obraz'.$z.'"/>'.$err_obraz_tab[$z].'</p>';
							echo '</div>';
						};
};
$lp_gl=0;
//$ILOZD=0;
if ($_SESSION['id_user']==1){ 
							echo '<p style="text-align:left; color:black; margin:10px;">KATALOG - <span style="color:green;">'.$ID_KAT.'</span></p>';
};
echo '<input type="hidden" name="ILE_ZD" value="'.$ID_KAT.'" />';
echo '<input type="hidden" name="ID_KAT" value="'.$ID_KAT.'" />';
echo '<input type="hidden" name="ID" value="'.$ID.'" />';
echo '<input type="hidden" name="CSS_TRE_DEF" value="1" />';
echo '<input type="hidden" name="CSS_TYT_DEF" value="1" />';
echo '<input type="hidden" name="IDWs_poczatek" value="'.$_GET["IDWs_poczatek"].'" />';
echo '<input type="hidden" name="IDWs_koniec" value="'.$_GET["IDWs_koniec"].'" />';
echo '<input type="hidden" name="IDSTR" value="'.$_GET["IDSTR"].'" />';
echo '<input class="button" type="submit" value="Edytuj" name="edytuj"/></form>';
echo '<p style="color:black;font-weight:bold;font-size:16px;text-align:left;margin-left:50px;">Legenda :</p><p style="text-align:left;margin-left:45px;">';
echo '- pola z GWIAZDKĄ (<span style="color:#B700FF;font-weight:bold;">*</span>) wymagane;<br/>';
echo '- TYTUŁ musi zawierać min (<span style="color:#B700FF;font-weight:bold;">5</span>) znaków; <br/> ';
echo '- TYTUŁ może zawierać max (<span style="color:#B700FF;font-weight:bold;">200</span>) znaków;<br/> ';
echo '- TREŚĆ musi zawierać min (<span style="color:#B700FF;font-weight:bold;">5</span>) znaków;<br/> ';
echo '- TREŚĆ może zawierać max (<span style="color:#B700FF;font-weight:bold;">2000</span>) znaków;<br/> ';
echo '- ZDJĘCIA i FILM nie jest (<span style="color:#B700FF;font-weight:bold;">wymagany</span>);<br/> ';
} else if ($status_popraw==1){
$ID= $_POST["ID"];
$CSS_TYT='';
if ($_POST['CSS_TYT_1']!='') $CSS_TYT=$_POST['CSS_TYT_1']; else $CSS_TYT=0;
for( $css_licz=2; $css_licz<4; $css_licz++ ) {
						
															if(isset($_POST['CSS_TYT_'.$css_licz])) {
																											$CSS_TYT=$CSS_TYT.'|'.$_POST['CSS_TYT_'.$css_licz];
																											}
															else $CSS_TYT=$CSS_TYT.'|0';
															echo 'CSS_TYT_'.$css_licz.' - '.$CSS_TYT.'<br/>';
															};
$CSS_TRE='';
if ($_POST['CSS_TRE_1']!='') $CSS_TRE=$_POST['CSS_TRE_1']; else $CSS_TRE=0;
for( $css_licz=2; $css_licz<4; $css_licz++ ) {
						
															if(isset($_POST['CSS_TRE_'.$css_licz])) {
																											$CSS_TRE=$CSS_TRE.'|'.$_POST['CSS_TRE_'.$css_licz];
																											}
															else $CSS_TRE=$CSS_TRE.'|0';
															echo 'CSS_TRE_'.$css_licz.' - '.$CSS_TRE.'<br/>';
															};
$rozmiar_tyt = $_POST["rozmiar_tyt"];
$rozmiar_tresc = $_POST["rozmiar_tresc"];
$tytul = nl2br($_POST["tytul"]);
$tresc = nl2br($_POST["tresc"]);
$adres = $_POST["adres"];
$ID_KAT = $_POST["ID_KAT"];
list($kolor_tyt,$kolor_tyt_id) = explode('|', $_POST["kolor_tyt"]);
list($kolor_tre,$kolor_tre_id) = explode('|', $_POST["kolor_tresc"]);
list($poz_tyt_id,$poz_tyt_wart) = explode('|', $_POST["poz_tyt"]);
list($poz_tre_id,$poz_tre_wart) = explode('|', $_POST["poz_tre"]);
if ($_SESSION['id_user']){
								echo "Kolor tytuł - ".$kolor_tyt." Kolor tyt ID - ".$kolor_tyt_id."  Rozmiar tytuł - ".$_POST["rozmiar_tyt"]."<br/>";
								echo "Kolor tresc - ".$kolor_tre." Kolor tresc ID - ".$kolor_tre_id." Rozmiar tresc - ".$_POST["rozmiar_tresc"]."<br/>";
								echo "Wartość IMG_ZMIANA - ".$IMG_ZMIANA."<br/>";
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
mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
$zapytanie = "UPDATE `NEWS` SET `WSK_K`=WSK_K+1, `TYTUL`='$tytul' ,`TRESC`='$tresc', `ADRES`='$adres',`K_TYTUL`='$kolor_tyt',`R_TYTUL`='$rozmiar_tyt',`K_TRESC`='$kolor_tre',`R_TRESC`='$rozmiar_tresc',`ID_COL_TYT`='$kolor_tyt_id',`ID_COL_TRE`='$kolor_tre_id',`P_TYT`='$poz_tyt_wart',`ID_P_TYT`='$poz_tyt_id',`P_TRE`='$poz_tre_wart',`ID_P_TRE`='$poz_tre_id',`CSS_TYT`='$CSS_TYT',`CSS_TRE`='$CSS_TRE',`WSK_V`=0 WHERE `ID`='$ID' ";
mysql_query($zapytanie) or die(mysql_error());
							//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$INS_DZIEN="INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ($_SESSION[id_user],$_GET[IDM],'EDYTUJ ARTYKUŁ - uaktualniono ID : $ID',NOW())";
							mysql_query($INS_DZIEN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_DZIEN - ".mysql_error()."</span></p>");
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
echo '<center><p>Twój artykuł został zaktualizowany</p></center>';
echo '<p class="NG_DANE">Tytuł : </p><div class="DIV_DODAJ">';
echo '<p style="background:white;margin-left:10px;margin-right:10px;color:'.$kolor_tyt.';font-size:'.$rozmiar_tyt.'; text-align:'.$poz_tyt_wart.';">'.$tytul.'</p>';
echo '</div>';
echo '<p class="NG_DANE">Treść : </p><div class="DIV_DODAJ">';
echo '<p style="background:white;margin-left:10px;margin-right:10px;color:'.$kolor_tre.';font-size:'.$rozmiar_tresc.'; text-align:'.$poz_tre_wart.';">'.$tresc.'</p>';
echo '</div>';
echo '<center><a href="cel_AKT.php?IDW=0&IDM='.$_GET['IDM'].'">Powrót do MENU - ARTYKUŁY</a></center>';

};
							} else {
									echo '<a href="cel_AKT.php?IDW=2&IDM='.$_GET['IDM'].'"><p style="text-align: left;">Popraw dane</p></a>';
									echo '<p style="color:red">Istnieje artykuł o podanym <span style="color:black;">ID</span> ale został on już prędzej usunięty !</p>';
									mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
									$KOM_DZIEN='EDYTUJ artykuł - BŁĄD - podany artykuł ma status usunięty - '.$_GET['IDE'];
									$INS_DZIEN="INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ($_SESSION[id_user],$_GET[IDM],'$KOM_DZIEN',NOW())";
									mysql_query($INS_DZIEN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_DZIEN - ".mysql_error()."</span></p>");
							}
		} else {
				echo '<a href="cel_AKT.php?IDW=2&IDM='.$_GET['IDM'].'"><p style="text-align: left;">Popraw dane</p></a>';
				echo '<p style="color:red">Nie znaleziono żadnego artykułu o wpisanym <span style="color:black;">ID</span> !</p>';
				mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
				$KOM_DZIEN='EDYTUJ artykuł - BŁĄD - nie ma artykułu o nr ID - '.$_GET['IDE'];
				$INS_DZIEN="INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ($_SESSION[id_user],$_GET[IDM],'$KOM_DZIEN',NOW())";
				mysql_query($INS_DZIEN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_DZIEN - ".mysql_error()."</span></p>");
		}
	} else {
			echo '<a href="cel_AKT.php?IDW=2&IDM='.$_GET['IDM'].'"><p style="text-align: left;">Popraw dane</p></a>';
			echo '<p style="color:red;">Nie podałeś <span style="color:black;">ID</span> artykułu !</p>';
			mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
			$INS_DZIEN="INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ($_SESSION[id_user],$_GET[IDM],'EDYTUJ artykuł - BŁĄD - nie podałeś ID artykułu',NOW())";
			mysql_query($INS_DZIEN) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">INS_DZIEN - ".mysql_error()."</span></p>");
	}
							
?>
</div>
</div>
</center>
<body>
<?php
										};
};
?>

