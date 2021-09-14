<?php if(!defined('OBOZ_52')) { exit('NO PERMISSION'); } ?>
<DIV class="DIV_MAIN">
<?php
echo '<p class="P_HREF_BACK"><a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'">Anuluj</a></p>';
$GLOBAL_ERR='';
require(DR.'/pod_strony/_funkcje_/sprawdz_napis.php');
$err_obraz_tab=array();
$err=array(); // 21 wartosci petla sie zaczyna od 1 a zmienne tablocowe sa indeksowane od 0
$err_spojnosc=array();
$zdjecia=FALSE;					
$status_dodaj=0;

//----------------------------------------------------------------------------------------Pobieranie-parametrow-z-bazy---------------------
																
																
																
																
																$SEL_PARM = $db->query("select ID,ID_GROUP,N_OPCJ,NAZWA,WART,WSK_D FROM PARM WHERE WSK_U=0 AND WSK_V=1 AND ID_MODUL='".$_GET['IDM']."' order by ID_GROUP");
																
                                                                                                                                
																										
                                                                                                                                    while($REK_PARM = mysqli_fetch_array($SEL_PARM)){
																								
																			switch($REK_PARM[1]):
																								case 0:
																									break;
																								case 1:
																									break;
																								case 2:
																									break;
																								case 3:
																									break;
																								case 4:
																											//--------------------------------------------------------SQL-SELECT-IMG-MAX-------------------------------------------------------------------
																											echo '<p CLASS="P_INFO">Wartość [';
																											switch($REK_PARM[2]):
																														default:
																														case "ROZ_IMG_OBOZ_W_MIN": 
																																				$parm_max_width[1]=$REK_PARM[4]; 
																															break;
																														case "ROZ_IMG_OBOZ_W_MAX": 
																																				$parm_max_width[0]=$REK_PARM[4];
																															break;
																														case "ROZ_IMG_OBOZ_H_MIN": 
																																				$parm_max_height[1]=$REK_PARM[4]; 
																															break;
																														case "ROZ_IMG_OBOZ_H_MAX": 
																																				$parm_max_height[0]=$REK_PARM[4]; 
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
																
																
																
//----------------------------------------------------------------------------------------KONIEC-Pobieranie-parametrow-z-bazy---------------------
if (isset($_POST["edytuj"])) {
							//$checked_img=TRUE;
							$checked=TRUE;
							$img_zmiana=FALSE; // IMG ZMIANA
							$txt_zmiana=FALSE; // TXT ZMIANA
							$spojnosc=TRUE;
							$empty_txt=TRUE;
							$empty_img=TRUE;
							$txt_baza=FALSE;
							$img_baza=FALSE;
							$err_img=FALSE;
							$OPIS_GLOBAL=FALSE;
							$checked_global=TRUE;
							//$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓyY ,.'!:-_\r\n]+$/";
							
							// Tekst poprawny rozpoczynamy upload plików
							if ($_SESSION['id_user']==1) {
															echo '<p class="P_INFO">$licz_start = <span class="S_INFO">'.$_POST["LICZ_START"].'</span></p>';
															echo '<p class="P_INFO">$licz_end = <span class="S_INFO">'.$_POST["LICZ_END"].'</span></p>';
															echo '<p class="P_INFO">/*<span class="S_INFO">-------------------------------------------------------------------------------------------------------------------------------------</span>*/</p>';
							};
							for($y=$_POST["LICZ_START"]; $y<$_POST["LICZ_END"]; $y++ ) {
														
														$SEL_IMG_OPIS = $db->query("select count(ID) FROM OBOZ_IMG WHERE ID_OBOZU='".$_POST['ID_KLASA']."' AND NR_IMG='$y'"); 
														$REK_SEL=mysqli_fetch_array($SEL_IMG_OPIS);
														echo "<p class=\"P_INFO\"> \$REK_SEL (COUNT) : <span class=\"S_INFO\">$REK_SEL[0]</span></p>";
														if ($REK_SEL[0]>0){
																			
																			$SEL_IMG_OPIS_2 = $db->query("select OPIS_IMG FROM OBOZ_IMG WHERE ID_OBOZU='$_POST[ID_KLASA]' AND NR_IMG='$y'"); 
																			$REK_SEL_2=mysqli_fetch_array($SEL_IMG_OPIS_2);
																			$change_txt = str_replace("-","",$REK_SEL_2[0]);
																			$change_txt=trim($change_txt);
																			$opis_post=trim($_POST["dane".$y]);
																			if ($opis_post==$change_txt) {
																						$SQL_RESULT="TAK";
																						$txt_baza=TRUE; 
																						$empty_img=FALSE;
																						$empty_txt=FALSE;
																						
																			} else {
																					$SQL_RESULT="NIE";
																					$txt_baza=FALSE;
																					$empty_txt=FALSE;		
																					$empty_img=FALSE;
																			} ;
														} else {
																$txt_baza=FALSE;
																$empty_img=TRUE;
																$change_txt="";
														}
														if ($_SESSION['id_user']==1) {
																						echo "<p class=\"P_INFO\">\$_POST[\"dane$y\"] : <span class=\"S_INFO\">".trim($_POST["dane".$y])."</span></p>";
																						echo "<p class=\"P_INFO\">TXT z BAZY (".$y.") : <span class=\"S_INFO\">".$change_txt."</span></p>";
														};
														
														echo "<p class=\"P_ERROR\">IDENTYCZNE : <span class=\"S_INFO\"> $SQL_RESULT </span></p>";
														echo "<p class=\"P_INFO\">OPIS : <span class=\"S_INFO\"> ".$_POST["dane".$y]." </span></p>";
														echo "<p class=\"P_INFO\">\$empty_img = <span class=\"S_INFO\">$empty_img</span> \$empty_txt =  <span class=\"S_INFO\">$empty_txt</span></p>";
														if (trim($_POST["dane".$y])!='') {
																							$empty_txt=FALSE;
																							echo "<p class=\"P_INFO\">$y - \$_POST : <span class=\"S_INFO\"> ".$_POST["dane".$y]." </span></p>";
																							
																							list($checked,$err[$y]) = sprawdz_napis($_POST["dane".$y],"oboz",4,2000);
																							if (($checked==TRUE) && ($txt_baza==FALSE)) {
																																		$txt_zmiana=TRUE;
																																		echo "<p class=\"P_INFO\">$y - txt_zmiana : <span class=\"S_INFO\"> $txt_zmiana </span></p>";
																																		
																							} else {
																									echo "<p class=\"P_INFO\">$y - txt_zmiana : <span class=\"S_INFO\"> $txt_zmiana </span></p>";
																							};
																							
														} else {
																IF($txt_baza==TRUE) $empty_txt=FALSE; else $empty_txt=TRUE; // Jeżeli istnieje zdjęcie w bazie bez opisu test ma przejść poprawnie
														};
														
														if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"])) {
																												$FILE="TAK";
																												$empty_img=FALSE;
																												if(($_FILES["obraz".$y]["type"] == 'image/gif') || 
																											  ($_FILES["obraz".$y]["type"] == 'image/jpeg') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/jpg') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/png') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/bmp') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/pjpeg')) 
																															{
																																$max_rozmiar = 8388608;
																																if ($_FILES["obraz".$y]["type"] < $max_rozmiar){
																																									$img_zmiana=TRUE;
																																									if($empty_txt==TRUE) $empty_img=TRUE; else $empty_img=FALSE; // Włączenie możliwości dodanie IMG bez TXT
																																									$err_obraz_tab[$y]="<span class=\"S_OK_DANE\">Proszę jeszcze raz wczytać zdjęcie !</span>";
																																									//echo "empty img = ".$empty_img."</br>";
																																									//$empty_img=false;
																																} else {
																																		$err_img=TRUE;
																																		$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Za duży rozmiar obrazu !</span>";
																																		//echo "empty img = ( Za duży rozmiar obrazu ) ".$empty_img."</br>";
																																}
																															} else { 
																																	$err_img=TRUE;
																																	$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Wskazany plik nie jest obrazem !</span>";
																																	//echo "empty img = ( Wskazany plik nie jest obrazem )".$empty_img."</br>";
																															};
																	
														} else {
																$FILE="NIE";
														};
														echo "<p class=\"P_ERROR\">ZAIMPORTOWANO PLIK : <span class=\"S_INFO\"> $FILE </span></p>";
														if ($checked==FALSE){
																				$checked_global=FALSE; // Test na poprawność napisu, jeżeli który kolwiek nie przejdzie
														};
														if($empty_txt!=$empty_img){
																					$spojnosc=FALSE;
																					if ($_SESSION['id_user']==1) echo "<p class=\"P_ERROR\">Spójność : <span class=\"S_ERROR\">FALSE</span></p>";
																					$err_spojnosc[$y]="<span class=\"S_ERR_DANE\">BRAK spójności danych!</span>";
														} else {
																if ($_SESSION['id_user']==1) echo "<p class=\"P_INFO\">Spójność : <span class=\"S_INFO\">TRUE</span></p>";
																$err_spojnosc[$y]="<span class=\"S_OK_DANE\">Spójności danych zachowana!</span>";
														};
														if (($img_zmiana==FALSE) && ($txt_zmiana==FALSE))
                                                                                                                {
																			$GLOBAL=FALSE;
																			$GLOBAL_ERR="<span class=\"S_ERR_DANE\"> NIE wprowadzono żadnych zmian !</span>";
																			//echo "<p class=\"P_INFO\">OPIS GLOBAL - ZMIANA : <span class=\"S_INFO\">".$OPIS_GLOBAL."</span></p>";
                                                                                                                } else {$GLOBAL=TRUE;}
														
														} // KONIEC petla FOR dla plików
if ( ($spojnosc==FALSE) || ($GLOBAL==FALSE) || ($checked_global==FALSE) || ($err_img==TRUE)) $status_dodaj=0; else $status_dodaj=1;}
//------------------------------------------ status_dodaj = 0 -------------------------------------------------------------------------
if ($status_dodaj==0)
{
						
						
						
						
						$SEL_VER_OB = $db->query("select VER,KATALOG FROM OBOZ WHERE ID='".$ID."'"); 
						$rek_sel=mysqli_fetch_array($SEL_VER_OB);

						echo '<p class="P_MAIN">Edycja Obozu ['.$ID.'] : '.$GLOBAL_ERR.'</p>';
						echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
						if ($rek_sel[0]==2) {$licz_end=21; $licz_start=1;$ver=2;} else {$licz_end=20; $licz_start=0;$ver=1;};
						for ($tr=$licz_start;$tr<$licz_end;$tr++){
												
												$SEL_OBOZ= $db->query("select o.ID,o.KATALOG,o.MAX_W,o.MAX_H,i.NAZWA_ORG,i.NAZWA_IMG,i.OPIS_IMG,i.NR_IMG,i.WIDTH,i.HEIGHT,i.WIDTH_M,i.HEIGHT_M,i.NAZWA_I_M,o.VER FROM OBOZ o,OBOZ_IMG i WHERE o.ID=i.ID_OBOZU AND o.WSK_U=0 AND i.NR_IMG='$tr' AND i.ID_OBOZU='".$ID."'");
												$rekord = mysqli_fetch_array($SEL_OBOZ);
												if ($rekord[13]==2||$rekord[13]==1) {
													$IMG_LOKAL=$rekord[12];
													$IMG_WIDTH=$rekord[10];
													$IMG_HEIGHT=$rekord[11];
												}
												else {
													$IMG_LOKAL=$rekord[5];
													$IMG_WIDTH="320";
													$IMG_HEIGHT="320";
												};
												if($rekord[7]>0) $NR_IMG=$rekord[7];
												if ($tr==$licz_start) {
															$info_dod='<span class="S_NG_DANE">*</span>';
															$font_w="BOLD";
															$font_s="20px";
															$rows=1;
															$opis="Tytuł ";
															$ID_KAT=$rekord[1];
															$MAX_W=$rekord[2];
															$MAX_H=$rekord[3];
															$zmieniony =$rekord[6];
															$ID_OBOZ=$rekord[0];
															echo '<input type="hidden" name="FIRST_IMG_DANE" value="'.$rekord[12].'|'.$rekord[10].'|'.$rekord[11].'" />';
															} else {
																	$info_dod='';
																	$font_w="BOLD";
																	$font_s="16px";
																	$rows=3;
																	$opis="Opis ";
																	$zmieniony = str_replace("-","",$rekord[6]);
																	};							
												echo '<div class="DIV_DODAJ">';
                                                                                                if(array_key_exists($tr, $err_spojnosc))
                                                                                                {
                                                                                                    echo  $err_spojnosc[$tr];
                                                                                                }
												
												echo '<p class="P_INPUT" style="font-weight:'.$font_w.';font-size:'.$font_s.'">Zdjęcie nr '.$tr.' '.$info_dod.'</p>';
												if ($rekord[0]!=''){
												echo "<a HREF=\"javascript:displayWindow('../../zdjecia/obozy/$rekord[1]/$rekord[5]',$rekord[8],$rekord[9])\">";
												echo '<img src="../../../zdjecia/obozy/'.$rekord[1].'/'.$IMG_LOKAL.'" alt="'.$IMG_LOKAL.'"  style="width:'.$IMG_WIDTH.'px; height:'.$IMG_HEIGHT.'px; border:0px;" />';
												echo '</a>';
												echo '<p class="P_INFO_IMG">(kliknij na zdjęcie aby je powiększyć)</p>';
												};
												echo '<p class="P_INPUT"><input type="file" name="obraz'.$tr.'"/>';
                                                                                                
                                                                                                if(array_key_exists($tr, $err_obraz_tab))
                                                                                                {      
                                                                                                      echo $err_obraz_tab[$tr];
                                                                                                }
                                                                                                        echo '</p>';
												echo '<p class="P_INPUT" style="font-weight:'.$font_w.';font-size:'.$font_s.'">'.$opis.''.$info_dod.' : ';
                                                                                                if(array_key_exists($tr, $err))
                                                                                                {  
                                                                                                    echo $err[$tr];
                                                                                                }
                                                                                                echo '</p>';
												echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA"">';  
												if (isset($_POST["dane".$tr])) { 
																				echo $_POST["dane".$tr];
												}
												else {
														echo $zmieniony; 
												};										
												echo '</textarea>';
												echo '</div>';
												
												};
						echo '<input type="hidden" name="MAX_WIDTH" value="'.$MAX_W.'" />';
						echo '<input type="hidden" name="MAX_HEIGHT" value="'.$MAX_H.'" />';
						echo "MAX_WIDTH : $MAX_W MAX_HEIGHT : $MAX_H</BR>";
						echo '<input type="hidden" name="LICZ_START" value="'.$licz_start.'" />';
						echo '<input type="hidden" name="LICZ_END" value="'.$licz_end.'" />';
						echo '<input type="hidden" name="KATALOG" value="'.$ID_KAT.'" />';
						echo '<input type="hidden" name="NR_IMG" value="'.$NR_IMG.'" />';
						echo '<input type="hidden" name="ID_KLASA" value="'.$ID_OBOZ.'" />';
						echo '<input type="hidden" name="VER" value="'.$ver.'" />';
						echo '<input class="button"type="submit" value="Edytuj" name="edytuj"/></form>';
						echo '<p class="P_LEG">Legenda :</p>';
						echo "<ul class=\"UL_LEG\">";
						echo '<li class="LI_LEG">pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane ;</li>';
						echo '<li class="LI_LEG">NIE dozwolone jest wprowadzenie <span class="S_LEG_INFO">OPISU</span> bez <span class="S_LEG_INFO">ZDJĘCIA</span> ! ;</li>';
						echo '<li class="LI_LEG">OPIS może zawierać max (<span class="S_LEG_INFO">2000</span>) znaków</li> ';
						echo '<li class="LI_LEG">OPIS zdjęcia <span class="S_LEG_INFO">NR 1</span> musi zawierać min (<span class="S_LEG_INFO">4</span>) znaków ;</li>';
						echo '<li class="LI_LEG">ZDJĘCIE, dozwolony TYP : (<span class="S_LEG_INFO">JPG JPEG PNG BMP GIF</span>) ;</li> ';
						echo '<li class="LI_LEG">ZDJĘCIE,  <span class="S_LEG_INFO">MAX</span> Rozmiar ~ <span class="S_LEG_INFO">8 MB</span> ;</li> ';
						echo "</ul>";
						echo '</center></div>';
//------------------------------------------ koniec-status_dodaj = 0 -------------------------------------------------
} else if($status_dodaj==1) {
//------------------------------------------ status_dodaj = 1 --------------------------------------------------
$max_width_post=$_POST["MAX_WIDTH"];
$max_height_post=$_POST["MAX_HEIGHT"];
$max_width=0;
$max_height=0;
$NR_IMG=$_POST["NR_IMG"];
$FIRST_IMG=FALSE;
$ver=$_POST["VER"];
$uploads_dir="../../zdjecia/obozy/".$_POST["KATALOG"];
if($_SESSION['id_user']==1){
							echo '<p style="text-align:left; margin-left:20px;">BYŁO NR_IMG - '.$NR_IMG.'</p>';
};
//--------------------------------------------------END-DIR------------------------------------------------------------------------------------------------
for ($i=$_POST["LICZ_START"];$i<$_POST["LICZ_END"];$i++){
														$licznik_plik=0;
//echo '<p style="text-align:left; margin-left:20px;">Wartość licznika $i = '.$i.'</p>';	
												if (is_uploaded_file($_FILES["obraz".$i]['tmp_name'])) 
																		{
																		$filename = $_FILES["obraz".$i]['name'];
																		$tmp_name = $_FILES["obraz".$i]["tmp_name"]; 
																		//$ext = strtolower(substr(strrchr($filename, '.'), 1)); //Rozszerzenie
																		print "CEL_OBOZ -> FILENAME - ".$filename. "</br>";
																		print "CEL_OBOZ -> TYP pliku - ".mime_content_type("$tmp_name") . "</br>";
																		$ext = strtolower(substr(strrchr(mime_content_type("$tmp_name"), '/'), 1)); //Rozszerzenie
																		if($ext=="jpeg") {echo "Zmiana JPEG na JPG </br>"; $ext="jpg";};
																		print "CEL_OBOZ -> TRUE ext - ".$ext . "</br>";
																		$image_name = 'org_'.$licznik_plik . '_oboz.' . $ext; //Nowa nazwa pliku
																		
																		//-------------------------------------------------------------------------------------SPRAWDZ-CZY-ISTENIEJE-PLIK---------
																		while(file_exists("../../zdjecia/obozy/".$_POST["KATALOG"]."/".$image_name)){
																																					echo "<p class=\"P_ERROR\">PLIK - <span class=\"S_ERROR\">org_".$licznik_plik."_oboz.".$ext.".</span> Istenieje !</p>";
																																					$licznik_plik++;
																																					$image_name='org_'.$licznik_plik . '_oboz.' . $ext;
																		};
																		//-------------------------------------------------------------------------------------KONIEC-SPRAWDZ-CZY-ISTENIEJE-PLIK---------
																		move_uploaded_file($tmp_name,"$uploads_dir/$image_name");
																		//--------------------------------------------------------------TWORZENIE_MINIATURY---------------------------------------------
																		list($width, $height) = getimagesize($uploads_dir.'/'.$image_name);	
																		
																		$IMG_NEW=FALSE;
																		for ($p_resize=0;$p_resize<2;$p_resize++){
																			if($ext=="x-ms-bmp") $ext_old=$ext; $ext="jpg";
																		echo ' NOWY rozmiary WIDTH : <span class="S_INFO">'.$parm_max_width[$p_resize].'</span> HEIGHT : <span class="S_INFO">'.$parm_max_height[$p_resize].'</span></br>';
																											switch ($p_resize):
																																			case 0: 
																																					$file_new=0;
																																					$naglowek_img='new_'.$file_new."_oboz.".$ext;
																																					while(file_exists("../../zdjecia/obozy/".$_POST["KATALOG"]."/".$naglowek_img)){
																																										echo "<p class=\"P_ERROR\">PLIK - <span class=\"S_ERROR\">new_".$file_new."_oboz.".$ext."</span> Istenieje !</p>";
																																										$file_new++;
																																										$naglowek_img='new_'.$file_new.'_oboz.'.$ext;
																																					};
																																					$naglowek="Galeria ";
																																					break;
																																			case 1:
																																					$IMG_NEW=TRUE;
																																					$file_min=0;
																																					$naglowek_img='min_'.$file_min."_oboz.".$ext;
																																					while(file_exists("../../zdjecia/obozy/".$_POST["KATALOG"]."/".$naglowek_img)){
																																										echo "<p class=\"P_ERROR\">PLIK - <span class=\"S_ERROR\">min_".$file_min."_oboz.".$ext."</span> Istenieje !</p>";
																																										$file_min++;
																																										$naglowek_img='min_'.$file_min.'_oboz.'.$ext;
																																					};
																																					$naglowek="Miniatura ";
																																					break;
																																			default:
																																					$naglowek_img='def_'.$true_i."_oboz.".$ext;
																																					$naglowek="DEFAULT ";
																																					break;
																											endswitch;
																		if ($width>$parm_max_width[$p_resize] || $height>$parm_max_height[$p_resize]|| ($ext_old=="x-ms-bmp")){
																												if (($IMG_NEW==TRUE) && (file_exists($uploads_dir."/new_".$true_i."_oboz.".$ext))) {
																																$image_name='new_'.$true_i."_oboz.".$ext;
																												//				
																												//					//list($width, $height) = getimagesize($uploads_dir.'/'.$image_name);
																												};
																												if (($IMG_NEW==TRUE) && (file_exists($uploads_dir."/new_".$file_new."_oboz.".$ext))) {
																																	$image_name='new_'.$file_new."_oboz.".$ext;
																																	list($width, $height) = getimagesize($uploads_dir.'/'.$image_name);
																												};
																												//DODAC SKALOWANIE !!! $scale=($new_size/$width);
																												$wynik_img_size = resize_image($parm_max_width[$p_resize],$parm_max_height[$p_resize],$ext,$naglowek_img,$uploads_dir.'/',$image_name);
																												$new_width[$p_resize]=round($wynik_img_size[0]);
																												$new_height[$p_resize]=round($wynik_img_size[1]);
																												$new_image_name[$p_resize]=$wynik_img_size[2];
																		} else {
																				echo "Nie wykonano resize</br>";
																				$new_image_name[$p_resize]=$image_name;
																				$new_height[$p_resize]=round($height);
																				$new_width[$p_resize]=round($width);
																		};
																		if($_SESSION['id_user']==1) {
																									echo "<p class=\"P_INFO\">".$naglowek;
																									echo " WIDTH : <span class=\"S_INFO\">".$new_width[$p_resize]."</span>";
																									echo " HEIGHT : <span class=\"S_INFO\">".$new_height[$p_resize]."</span></p>";
																									echo "Sciezka :".$SCIEZKA_RESIZE."</br>";
																									};
																		};
																		//--------------------------------------------------------------KONIEC-RESIZE-IMG--------------------------------------------	
																		if($i==$_POST["LICZ_START"]){
																					$FIRST_IMG=TRUE;
																					$FIRST_IMG_NAME_ORG=$image_name;
																					for ($r=0;$r<2;$r++){
																										$FIRST_IMG_NAME[$r]=$new_image_name[$r];
																										$FIRST_IMG_HEIGHT[$r]=$new_height[$r];
																										$FIRST_IMG_WIDTH[$r]=$new_width[$r];
																					};
																					if($_SESSION['id_user']==1) { 
																												echo '<p class="P_INFO"> isset($_POST[IMG_'.$_POST["LICZ_START"].']) : <span class="S_INFO"> </span></p>';
																												echo "<p class=\"P_INFO\"> FIRST_IMG_NAME_ORG : <span class=\"S_INFO\">".$FIRST_IMG_NAME_ORG."</span></p>";
																												echo "<p class=\"P_INFO\"> FIRST_IMG_NAME[0] : <span class=\"S_INFO\">".$FIRST_IMG_NAME[0]."</span></p>";
																												echo "<p class=\"P_INFO\"> FIRST_IMG_HEIGHT[0] : <span class=\"S_INFO\">".$FIRST_IMG_HEIGHT[0]."</span></p>";
																												echo "<p class=\"P_INFO\"> FIRST_IMG_WIDTH[0] : <span class=\"S_INFO\">".$FIRST_IMG_WIDTH[0]."</span></p>";
																												};
																		};									
																		if($max_width<$new_width[0])$max_width=$new_width[0];
																		if($max_height<$new_height[0])$max_height=$new_height[0];
//--------------------------------------------------------------------------------------------------------------------------------UPDATE-KLASA-IMG------------------------------------------------------
																		//if($i>$NR_IMG) { } else {  };
																		//if ($_SESSION['id_user']==1){ echo '<p style="text-align:left; margin-left:20px;">NOWY NR_IMG - '.$NR_IMG.'</p>';};
																		if($i<=$NR_IMG){
																						echo "NR_IMG BEZ ZMIAN ! ";
																										
																										$db->query("UPDATE `OBOZ_IMG` SET `NAZWA_ORG`='".$image_name."' ,`NAZWA_IMG`='".$new_image_name[0]."',`WIDTH`='".$new_width[0]."', `HEIGHT`='$new_height[0]',`NAZWA_I_M`='$new_image_name[1]', `WIDTH_M`='".$new_width[1]."', `HEIGHT_M`='".$new_height[1]."',`WSK_K`=WSK_K+1,`DAT_K`=NOW() WHERE `ID_OBOZU`='".$_POST['ID_KLASA']."' AND `NR_IMG`='$i'");
																										
																										
																		} else {
																				echo "NR_IMG ++ ";$NR_IMG++;
																				if(trim($_POST["dane".$i])!='') $image_info=trim($_POST["dane".$i]); else $image_info="-";
																				
																				$db->query("INSERT INTO OBOZ_IMG (ID_OBOZU,NAZWA_ORG,NAZWA_IMG,OPIS_IMG,ID_PERS,DAT_UTW,NR_IMG,WIDTH,HEIGHT,WIDTH_M,HEIGHT_M,NAZWA_I_M) VALUES ('".$_POST['ID_KLASA']."','$filename','$new_image_name[0]','$image_info','".$_SESSION['id_user']."',NOW(),'$NR_IMG','$new_width[0]','$new_height[0]','$new_width[1]','$new_height[1]','$new_image_name[1]')");
																				
																		}
																									
//--------------------------------------------------------------------------------------------------------------------------------KONIEC-UPDATE-KLASA-IMG------------------------------------------------------
																		
																		}; //KONIEC is_upload_file
																		
																		if (trim($_POST["dane".$i])!='') {
																										//echo "<p class=\"P_INFO\">\$_POST[dane$i] : <span class=\"S_INFO\">$_POST[dane$y]</span></p>";	
																										$image_info=trim($_POST["dane".$i]);
																										if($i==$_POST["LICZ_START"])
                                                                                                                                                                                                                {
																																		$TITLE=$image_info;
																										};
																									
																										$db->query("UPDATE `OBOZ_IMG` SET `OPIS_IMG`='$image_info' WHERE `ID_OBOZU`='".$_POST['ID_KLASA']."' AND `NR_IMG`='$i'");
																										
																		};
																		
																		
}; // KONIEC pętli UPLOAD
//---------------------------------------------------DIR------------------------------------------------------------------------------------------------
$i_html=0;
$i_xml=0;
						$max_ramka_width=$parm_max_width[0]+30;
						$max_width_ins2=$max_width+30;
						echo "<p class=\"P_INFO\">\$max_width - <span class=\"S_INFO\">$max_width</span></p>";
						echo "<p class=\"P_INFO\">\$max_ramka_width - <span class=\"S_INFO\">$max_ramka_width</span></p>";
						echo "<p class=\"P_INFO\">\$max_width_post - <span class=\"S_INFO\">$max_width_post</span></p>";
						echo "<p class=\"P_INFO\">\$max_width_ins - <span class=\"S_INFO\">$max_width_ins2</span></p>";
						if($max_width>=$max_ramka_width) {
															$max_width_ins=$max_width;
															echo "CASE 1 Osiagnieto maksymalna szeroosc ramki . Aktualnie : $max_width max : $max_ramka_width [\$max_width>=\$max_ramka_width]</br>";
						} 
						else if ($max_width_ins2<=$max_width_post){
							$max_width_ins=$max_width_post;
							echo "CASE 2 RAMKA pozostaje na aktualnie ustawionym : $max_width_ins2 mniejszy lub rowny - $max_width_post [\$max_width_ins2<=\$max_width_post] </br>";
						} else {
							$max_width_ins=$max_width+30;
							echo "CASE 3 Powiekszam szerokosc ramke z $max_width do $max_width_ins []</br>";
							
						} ;
						$max_ramka_height=$parm_max_height[0]+20;
						$max_height_ins2=$max_height+20;	
						echo "<p class=\"P_INFO\">\$max_ramka_height - <span class=\"S_INFO\">$max_ramka_height</span></p>";
						echo "<p class=\"P_INFO\">\$max_height_post - <span class=\"S_INFO\">$max_height_post</span></p>";
						echo "<p class=\"P_INFO\">\$max_height_ins - <span class=\"S_INFO\">$max_height_ins2</span></p>";						
						if($max_height>=$max_ramka_height) {
															
															$max_height_ins=$max_height;
															echo "Osiagnieto maksymalna wysokosc ramki . Aktualnie : $max_height_ins</br>";
															echo "CASE 1 Osiagnieto maksymalna szerokość ramki . Aktualnie : $max_height max : $max_height_width [\$max_height>=\$max_height_width]</br>";
						} 
						else if($max_height_ins2<=$max_height_post)  {
								echo "CASE 2 RAMKA_WYSOKOŚĆ pozostaje na aktualnie ustawionym : $max_height_ins2 mniejszy lub równy od \$max_height - $max_height_post [\$max_height_ins2<=\$max_height_post] </br>";
								$max_height_ins=$max_height_post;
								echo "Powiekszam wysokosc ramki z $max_height do $max_height_ins</br>";
						}else {
								$max_height_ins=$max_height+20;
								echo "Powiekszam wysokosc ramki z $max_height do $max_height_ins</br>";
						};
echo " KATALOG sciezka  - ".$uploads_dir."</br>";
if ($handle = opendir($uploads_dir)) {
						while(false !== ($plik = readdir($handle))){
													if($plik!="." && $plik!="..")
																				{
																				if(strrchr($plik, "html")==TRUE) $i_html++; 
																				else if(strrchr($plik, "xml")==TRUE) $i_xml++;
																				//if($_SESSION['id_user']==1) { echo "PLIK - ".$plik."<br/>";};
																				}
													};
closedir($handle);
};
$i_html++;
$i_xml++;
				if ($_SESSION['id_user']==1){
											echo "<p class=\"P_INFO\">Nowy nr pliku html : <span class=\"S_INFO\">".$i_html."</span></p>";
											echo "<p class=\"P_INFO\">Nowy nr pliku xml : <span class=\"S_INFO\">".$i_xml."</span></p>";
											};
$max_w_html=$max_width_ins-30;
$max_h_html=$max_height_ins-20;
//--------------------------------------------------END-DIR------------------------------------------------------------------------------------------------				
$ID=$_POST['ID_KLASA'];
$SEL_IMG_XML = $db->query("select NAZWA_IMG,OPIS_IMG FROM OBOZ_IMG WHERE WSK_US=0 AND ID_OBOZU='$_POST[ID_KLASA]' ORDER BY ID");		
while($rek_xml = mysqli_fetch_array($SEL_IMG_XML))
{
    $track.='<track><title>'.$rek_xml[1].'</title><creator></creator><location>'.$rek_xml[0].'</location><info></info></track>';	
};
$dok_xml = '<?xml version="1.0" encoding="UTF-8"?>
<playlist version="1" xmlns="http://xspf.org/ns/0/">
<trackList>'.$track.'</trackList>
</playlist>';
									$uchwyt_xml = fopen($uploads_dir.'/'.$i_xml.'.xml' , "w");
									fwrite($uchwyt_xml,$dok_xml );
									fclose($uchwyt_xml);
$dok_html= '<html><head><title>Tytul : '.$TITLE.'</title>
<meta name="Description" content="JUDO"/>
<meta name="Keywords" content="JUDO SPORT" />
<meta name="Author" content="Tomasz Borczynski; mass[.]hopto[.]org [@] gmail [.] com" />
<meta name="Robots" content="all" />
<meta http-equiv="Content-language" content="pl" />
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<link rel="shortcut icon" href="../../../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../../../images/favicon.ico" type="image/x-icon">
<style type="text/css" id="stylescreen" media="screen"></style>
</head>
<body>
<center>
<div id="container"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this rotator.</div>
<script type="text/javascript" src="../swfobject.js"></script>
<script type="text/javascript">
var s1 = new SWFObject("../imagerotator.swf","rotator","'.$max_width_ins.'","'.$max_height_ins.'","7");
s1.addVariable("file","'.$i_xml.'.xml");
s1.addVariable("width","'.$max_width_ins.'");
s1.addVariable("height","'.$max_height_ins.'");
s1.write("container");
</script>
</center>
</body>
</html>';
										$uchwyt_html = fopen($uploads_dir.'/'.$i_html.'.html' , "w");
										fwrite($uchwyt_html,$dok_html);
										fclose($uchwyt_html);
										flush();
						if((trim($_POST["dane".$_POST["LICZ_START"]])!="") && ($FIRST_IMG==TRUE))
                                                {
                                                    $log->log(0,"[".__FILE__."::".__LINE__."] UPDATE DANE and IMG");
                                                    $db->query("UPDATE OBOZ SET `NAZWA_O`='$FIRST_IMG_NAME_ORG',`NAZWA_H`='$i_html.html',`NAZWA_I`='$FIRST_IMG_NAME[1]',`OPIS`='$TITLE',`XML`='$i_xml.xml',`DAT_K`=NOW(),`WIDTH`='$FIRST_IMG_WIDTH[1]',`HEIGHT`='$FIRST_IMG_HEIGHT[1]',`MAX_W`='$max_width_ins',`MAX_H`='$max_height_ins',`WSK_K`=WSK_K+1,VER='$ver' WHERE `ID`='".$_POST['ID_KLASA']."'");														
														
						} else if ((trim($_POST["dane".$_POST["LICZ_START"]])!="") && ($FIRST_IMG==FALSE))
                                                {
                                                    $log->log(0,"[".__FILE__."::".__LINE__."] UPDATE DANE");
															
                                                    $db->query("UPDATE OBOZ SET `NAZWA_H`='$i_html.html',`OPIS`='$TITLE',`XML`='$i_xml.xml',`DAT_K`=NOW(),`MAX_W`='$max_width_ins',`MAX_H`='$max_height_ins',`WSK_K`=WSK_K+1, VER='$ver' WHERE `ID`='".$_POST['ID_KLASA']."'");														
															
						} else if ((trim($_POST["dane".$_POST["LICZ_START"]])=="") && ($FIRST_IMG==TRUE))
                                                {
                                                    $log->log(0,"[".__FILE__."::".__LINE__."] UPDATE IMAGE");
                                                    $db->query("UPDATE OBOZ SET `NAZWA_O`='$FIRST_IMG_NAME_ORG',`NAZWA_H`='$i_html.html',`NAZWA_I`='$FIRST_IMG_NAME[1]',`XML`='$i_xml.xml',`DAT_K`=NOW(),`WIDTH`='$FIRST_IMG_WIDTH[1]',`HEIGHT`='$FIRST_IMG_HEIGHT[1]',`MAX_W`='$max_width_ins',`MAX_H`='$max_height_ins',`WSK_K`=WSK_K+1, VER='$ver' WHERE `ID`='".$_POST['ID_KLASA']."'");														
						
						} else
                                                {
                                                    echo "BRAK ZMIAN";
						}
						if($FIRST_IMG==FALSE)
                                                {
                                                    list($FIRST_IMG_NAME[1],$FIRST_IMG_WIDTH[1],$FIRST_IMG_HEIGHT[1]) = explode('|', $_POST["FIRST_IMG_DANE"]);
						}
						echo '<div class="DIV_IMG"><center>';
						echo "<a HREF=\"javascript:displayWindow('$uploads_dir/$i_html.html',$max_width_ins,$max_height_ins)\">";
						echo '<img src="'.$uploads_dir.'/'.$FIRST_IMG_NAME[1].'" alt="'.$FIRST_IMG_NAME[1].'"  style="height:'.$FIRST_IMG_HEIGHT[1].'px; width:'.$FIRST_IMG_WIDTH[1].'px; border:0px;" />';
						echo '</a><p class="P_INFO_IMG">(kliknij na zdjęcie aby uruchomić galerię)</p></center><p class="P_DANE">Tytul : <span style="font-weight:bold;font-size:14px;color:black;font-style:normal;">'.$TITLE.'</span></p></div>';
						foreach ($_POST as $key => $value) {
															//if ($_SESSION['id_user']==1){ echo $key.' = '.$value.'<br/>';};
															UNSET($_POST[$key]);
															//if ($_SESSION['id_user']==1){ echo $key.' : '.$_POST[$key].'<br/>';};
															};
						echo '<a href ="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'" class="A_BACK"><p class="P_HREF_BACK2">Powrót do MENU - OBOZY</p></a>';
						};
//------------------------------------------------------------------------KONIEC-EDYTUJ-----------------------------------------------------------------------------------------------
?>
</div>						


