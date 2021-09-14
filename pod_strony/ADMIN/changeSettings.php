<?php
if(!defined('ADMIN1321')) { exit('NO PERMISSION');}
require(DR.'/pod_strony/_funkcje_/check_len.php');
echo '<p class="P_HREF_BACK"><a class="A_BACK" href="'.PAGE_URL.'&IDW=0">Anuluj</a></p>';
$IDZ=array();
$IDZ_zm=FALSE;
$UPD=FALSE;
$err_dane=array();
$checkVar->checkOnlyGet($MAX_I_ALL,'MAX_I_ALL');
$log->log(0,"[".__FILE__."] MAX I ALL => ".$MAX_I_ALL);
for($i_all=0; $i_all<$MAX_I_ALL; $i_all++)
{
    if (isset($_GET["IDZ".$i_all]))
    {
	echo "Wcisnieto IDZ=".$i_all."</br>"; $IDZ[$i_all]=TRUE; $IDZ_zm=TRUE;
    }
    else
    {
    	$IDZ[$i_all]=FALSE;
    }					
}
if (isset($_GET["IDZ_ALL"]) || $IDZ_zm==TRUE)
{
    for($i_all=0; $i_all<$MAX_I_ALL; $i_all++)
    {
	$IDG_UPD=$_GET["IDG_ALL_".$i_all];	
	$IDP_UPD=$_GET["IDP_ALL_".$i_all];
        if (isset($_GET["DANE".$i_all]))
        {
            $DANE_UPD=$_GET["DANE".$i_all];
        }
        else
        {
            echo "Nie wprowadzono zmian - "; $DANE_UPD="";   
        }
								
	echo $i_all." - ID_GROUP: ";
	echo $_GET["IDG_ALL_".$i_all];
	echo "| ID - $IDP_UPD | DANE - $DANE_UPD </br>";
								
													switch ($IDG_UPD):
														case 0: // USTAWIENIA POZYCJONOWANIE TEKSTU
                                                                                                                                if (isset($_GET["IDZ".$i_all]) AND $IDZ_zm==TRUE) {$UPD=TRUE;} else if ($IDZ_zm==FALSE) {$UPD=TRUE;} else {$UPD=FALSE;}
																if ($UPD==TRUE)
                                                                                                                                {
                                                                                                                                    echo "CASE 0 USTAWIENIA POZYCJONOWANIE TEKSTU $DANE_UPD </br>";
                                                                                                                                    list($align_nazwa,$align_wart,$align_id) = explode('|', $DANE_UPD);
                                                                                                                                    $db->query("UPDATE `PARM` SET `WART`='$align_wart', `NAZWA`='$align_nazwa', `WSK_D`='$align_id',`WSK_K`=WSK_K+1, `ID_PERS`='$_SESSION[id_user]' WHERE `ID_GROUP`='$IDG_UPD' AND `ID`='$IDP_UPD'");
																}
                                                                                                                                else {echo "Nie dokonano zmian - CASE 0 USTAWIENIA POZYCJONOWANIE TEKSTU</br>";}
															break;
														case 1:// USTAWIENIA CSS
																if (isset($_GET["IDZ".$i_all]) AND $IDZ_zm==TRUE) $UPD=TRUE; else if ($IDZ_zm==FALSE) $UPD=TRUE; else $UPD=FALSE;
																if ($UPD==TRUE)
                                                                                                                                {
                                                                                                                                    echo "CASE 1 USTAWIENIA CSS</br>";
										
                                                                                                                                    $SEL_CSS = $db->query("SELECT c.ID,c.NAZWA,c.WART FROM CSS c WHERE c.WSK_V=1 AND c.ID_GROUP=0 ORDER BY c.ID");
                                                                                                                                    while($REK_CSS=mysqli_fetch_array($SEL_CSS))
																{
																	$ID_PARM=$IDP_UPD;
																	IF(ISSET($_GET[$IDP_UPD.'|'.$REK_CSS[0]]))
                                                                                                                                        {
																										
																				echo "<p class=\"P_INFO\">WSKAZANO : ";
																				echo "ID_PARM - <span class=\"S_INFO\">".$IDP_UPD."</span>";
																				echo " ID_CSS - <span class=\"S_INFO\">".$REK_CSS[0]."</span></p>";
																				IF(mysqli_num_rows($db->query("SELECT pc.ID FROM PARM_CSS pc, PARM pm WHERE pc.ID_PARM=pm.ID AND pm.ID_MODUL='$_GET[IDMZ]' AND pc.ID_PARM='$IDP_UPD' AND pc.ID_CSS='$REK_CSS[0]' LIMIT 1"))<1)
                                                                                                                                                                {
                                                                                                                                                                    //INSERT
                                                                                                                                                                    echo "<p class=\"P_INFO\">BRAK REKORDU - <span class=\"S_INFO\">INSERT (ID_PARM=".$ID_PARM." ID_CSS=".$REK_CSS[0].")</span></p>";
                                                                                                                                                                    $db->query("INSERT INTO PARM_CSS (ID_PARM, ID_CSS,WSK_V,DAT_UTW,ID_PERS) VALUES ('$IDP_UPD','$REK_CSS[0]',1,NOW(),'$_SESSION[id_user]')");
                                                                                                                                                                   
																				}
                                                                                                                                                                else
                                                                                                                                                                {
                                                                                                                                                                    //UPDATE
                                                                                                                                                                    echo "<p class=\"P_INFO\">REKORD ISTNIEJE - <span class=\"S_INFO\">SPRAWDZ WSK_V</span></p>";
                                                                                                                                                                    
																								IF(mysqli_num_rows($db->query("SELECT pc.ID FROM PARM_CSS pc WHERE pc.ID_PARM='$IDP_UPD' AND ID_CSS='$REK_CSS[0]' AND pc.ID_PARM='$IDP_UPD' AND WSK_V=1 LIMIT 1"))>0)
                                                                                                                                                                                                {
                                                                                                                                                                                                    echo "<p class=\"P_INFO\">REKORD ISTNIEJE (WSK_V==1) - <span class=\"S_INFO\">UPDATE FALSE</span></p>";
																								}
                                                                                                                                                                                                else
                                                                                                                                                                                                {
                                                                                                                                                                                                    echo "<p class=\"P_INFO\">REKORD ISTNIEJE (WSK_V==0) - <span class=\"S_INFO\">UPDATE TRUE</span></p>";
                                                                                                                                                                                                    $db->query("UPDATE `PARM_CSS` SET `WSK_V`='1' WHERE `PARM_CSS`.`ID_PARM`=$IDP_UPD AND `PARM_CSS`.`ID_CSS`=$REK_CSS[0]");
																								}	
																				}
																	} else {
																			echo "<p class=\"P_INFO\">NIE WSKAZANO STYLU CSS ";
																			echo "ID_PARM - <span class=\"S_INFO\">".$IDP_UPD."</span>";
																			echo " ID_CSS - <span class=\"S_INFO\">".$REK_CSS[0]."</span></p>";
																			
																			IF(mysqli_num_rows($db->query("SELECT pc.ID FROM PARM_CSS pc WHERE pc.ID_PARM='$IDP_UPD' AND pc.ID_CSS='$REK_CSS[0]' LIMIT 1"))<1)
                                                                                                                                                        {
                                                                                                                                                            echo "<p class=\"P_INFO\">NIE ISTENIEJE UPRAWENINIE Z ID_PARM</br>";
                                                                                                                                                            echo "NIC NIE RÓB, NIE ISTNIEJE</p>";
																			} ELSE {
																					
																					IF(mysqli_num_rows($db->query("SELECT pc.ID FROM PARM_CSS pc WHERE pc.ID_PARM='$IDP_UPD' AND pc.ID_CSS='$REK_CSS[0]' AND pc.WSK_V=1 LIMIT 1"))<1)
                                                                                                                                                                        {
                                                                                                                                                                            echo "<p class=\"P_INFO\">ISTENIEJE UPRAWENINIE Z ID_PARM_CSS -<span class=\"S_INFO\"> UPDATE == FALSE (WSK_V==0)</span></p>";
                                                                                                                                                                            echo "NIC NIE RÓB, JUZ jEST WSK_V=0 </p>";
																					} else
                                                                                                                                                                        {
                                                                                                                                                                            echo "<p class=\"P_INFO\">ISTENIEJE UPRAWENINIE Z ID_PARM_CSS -<span class=\"S_INFO\"> UPDATE == TRUE (WSK_V==1)</span></p>";
                                                                                                                                                                            $db->query("UPDATE `PARM_CSS` SET `WSK_V`='0' WHERE `PARM_CSS`.`ID_PARM`=$IDP_UPD AND `PARM_CSS`.`ID_CSS`=$REK_CSS[0]");
																					
																					}
																			}
																	}
                                                                                                                                    }
																	
                                                                                                                                } else {echo "Nie dokonano zmian - CASE 1 USTAWIENIA CSS</br>";}
															break;
														case 2:// ROZMIAR CZCIONKI
																if (isset($_GET["IDZ".$i_all]) AND $IDZ_zm==TRUE) $UPD=TRUE; else if ($IDZ_zm==FALSE) $UPD=TRUE; else $UPD=FALSE;
																if ($UPD==TRUE)
                                                                                                                                {
                                                                                                                                    echo "CASE 2</br>";
                                                                                                                                    $db->query("UPDATE `PARM` SET `WART`='$DANE_UPD',`WSK_K`=`WSK_K`+1, `ID_PERS`='$_SESSION[id_user]' WHERE `ID_GROUP`='$IDG_UPD' AND `ID`='$IDP_UPD'");
																
																
                                                                                                                                } else {echo "Nie dokonano zmian - CASE 1 USTAWIENIA CSS</br>";}
															break;
														case 3: // USTAWIENIA KOLOR
																if (isset($_GET["IDZ".$i_all]) AND $IDZ_zm==TRUE) $UPD=TRUE; else if ($IDZ_zm==FALSE) $UPD=TRUE; else $UPD=FALSE;
																if ($UPD==TRUE)
                                                                                                                                {
                                                                                                                                    echo "CASE 3 USTAWIENIA KOLOR - $DANE_UPD</br>";
                                                                                                                                    list($kolor_id,$kolor_hex,$kolor_nazwa) = explode('|', $DANE_UPD);
                                                                                                                                    $db->query("UPDATE `PARM` SET `WART`='$kolor_hex', `NAZWA`='$kolor_nazwa',`WSK_K`=WSK_K+1, `ID_PERS`='$_SESSION[id_user]' WHERE `ID_GROUP`='$IDG_UPD' AND `ID`='$IDP_UPD'");
                                                                                                                                } else {echo "Nie dokonano zmian - CASE 1 USTAWIENIA CSS</br>";}
															break;
														case 6:
														case 4: // USTAWIENIA ROZMIAR ZDJĘCIA
																if (isset($_GET["IDZ".$i_all]) AND $IDZ_zm==TRUE) $UPD=TRUE; else if ($IDZ_zm==FALSE) $UPD=TRUE; else $UPD=FALSE;
																if ($UPD==TRUE){
																echo "CASE 4 USTAWIENIA ROZMIAR ZDJĘCIA</br>";
																$checked=TRUE;
																$string_exp = "/^[0-9]+$/";
																if(!preg_match($string_exp,$DANE_UPD)) { $err_dane[$i_all]='<span class="S_ERR_RED">Proszę usunąć niedozwolone znaki</span>'; $checked=FALSE;};
																check_len($checked, $DANE_UPD,4,$err_dane[$i_all],'<span class="S_ERR_RED">Pole za długie (maksymalna ilość znaków - </span><span style="color:black;">4</span>)',1,'<span class="S_ERR_RED">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">1</span>)');
																if ($checked==TRUE)
                                                                                                                                {
																					
																	$db->query("UPDATE `PARM` SET `WART`='$DANE_UPD',`WSK_K`=WSK_K+1, `ID_PERS`='$_SESSION[id_user]' WHERE `ID_GROUP`='$IDG_UPD' AND `ID`='$IDP_UPD'");
																					
																					
																}
																echo $err_dane[$i_all];
																} else echo "Nie dokonano zmian - CASE 1 USTAWIENIA CSS</br>";
															break;
														case 5: // USTAWIENIA WIDOCZNOŚCI ZDJĘCIA
																if (isset($_GET["IDZ".$i_all]) AND $IDZ_zm==TRUE) $UPD=TRUE; else if ($IDZ_zm==FALSE) $UPD=TRUE; else $UPD=FALSE;
																if ($UPD==TRUE)
                                                                                                                                {
                                                                                                                                    echo "CASE 5 USTAWIENIA WIDOCZNOŚCI ZDJĘCIA</br>";
                                                                                                                                    $DANE_UPD=$_GET["WID_ZDJ".$i_all];
                                                                                                                                    $db->query("UPDATE `PARM` SET `WART`='$DANE_UPD',`WSK_K`=WSK_K+1, `ID_PERS`='$_SESSION[id_user]' WHERE `ID_GROUP`='$IDG_UPD' AND `ID`='$IDP_UPD'");
																
																
                                                                                                                                } else {echo "CASE 5 USTAWIENIA WIDOCZNOŚCI ZDJĘCIA</br>";}
															break;
														case 7: // RODZINA czcionki 
																if (isset($_GET["IDZ".$i_all]) AND $IDZ_zm==TRUE) $UPD=TRUE; else if ($IDZ_zm==FALSE) $UPD=TRUE; else $UPD=FALSE;
																if ($UPD==TRUE)
                                                                                                                                {
                                                                                                                                    echo "CASE 7 USTAWIENIA FONT FACE TEKSTU $DANE_UPD </br>";
                                                                                                                                    list($ff_id,$ff_wart) = explode('|', $DANE_UPD);
                                                                                                                                    $db->query("UPDATE `PARM` SET `WART`='$ff_wart',  `WSK_D`='$ff_id',`WSK_K`=WSK_K+1, `ID_PERS`='$_SESSION[id_user]' WHERE `ID_GROUP`='$IDG_UPD' AND `ID`='$IDP_UPD'");
																
                                                                                                                                } else {echo "Nie dokonano zmian - CASE 0 USTAWIENIA POZYCJONOWANIE TEKSTU</br>";}
															break;
														default:
															break;
								endswitch;
								} //*************************************************UPDATE-ALL-OPTIONS-----------------------
							}
							$db->insDbLog($IDM,'Uruchomiono funkcję - USTAWIENIA EDYCJA ROZMIARU TEKSTU');
						
							$SEL_MODUL = $db->query("SELECT ID,NAZWA,SKROT FROM MODUL WHERE `ID`='".$_GET['IDMZ']."'");
                                                        $REK_MODUL=mysqli_fetch_array($SEL_MODUL);
						echo '<p class="P_M_NAGL">Moduł : '.$REK_MODUL[1].'</p>';
                                                if ($UPD==TRUE) {echo "<p style=\"color:red;font-weight:bold;\">Uaktualniono ustawienia !</p>";}
						echo '<form action="" method="GET" ENCTYPE="multipart/form-data" >';
						echo '<input type="hidden" name="WART" value="Y" />';
						echo '<table class="GLOWNA_TAB">';
						echo '<tr class="NAGLOWEK_TAB">';
						echo '<td class="NAGLOWEK_TAB" width="50%"><p class="NAGLOWEK_P">Opcja : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="50%"><p class="NAGLOWEK_P">Wartość :</td></tr>';
						$IDG_ALL=array();
						$IDP_ALL=array();
						$i_IDG=0;
						
						$SEL_PARM = $db->query("SELECT ID,ID_GROUP,ID_OPCJ,NAZWA,WART,OPIS,WSK_D FROM PARM WHERE WSK_U=0 AND ID_MODUL='$REK_MODUL[0]' GROUP BY ID");
						while($REK_PARM=mysqli_fetch_array($SEL_PARM))
                                                {
								echo '<tr class="TRESC_TAB">';
								echo '<td class="TRESC_TAB"><p class="P_OPCJ"><span CLASS="S_OPCJ">['.$REK_PARM[0].']['.$REK_PARM[1].']</span> '.$REK_PARM[5].'</p></td>';
								echo '<td class="TRESC_TAB">';
								echo'<input type="hidden" name="IDMZ" value="'.$_GET["IDMZ"].'" />';
								echo'<input type="hidden" name="IDW" value="'.$IDW.'" />';
								echo'<input type="hidden" name="IDM" value="'.$IDM.'" />';
								
								$FORM_START="<form action=\"\" method=\"GET\" ENCTYPE=\"multipart/form-data\" >";
								$FORM_END="<span CLASS=\"SPAN_BUT\"><input class=\"btn\" type=\"submit\" value=\"ZMIEŃ\" name=\"IDZ$i_IDG\"/></span><form>";
								switch ($REK_PARM[1]):
											case 0: // Wyrównanie tekstu
													echo $FORM_START;
															$REK_PARM[6];//ID_OPCJ POZYCJI TEKSTU
															$REK_PARM[3];//NAZWA POZYCJI TEKSTU
															$REK_PARM[4];//WARTOSC POZYCJI
															//echo $REK_PARM[1].' '.$REK_PARM[3].' '.$REK_PARM[4].'</br>';
															//echo 'GET_[DANE] - '.$_GET["DANE".$i_IDG].'</br>';
													echo '<p CLASS="P_WART">';
													echo '<select name="DANE'.$i_IDG.'" class="SELECT">';
													echo '<optgroup label="Aktualny :" class="OPTGROUP">';
													echo '<option value="'.$REK_PARM[3].'|'.$REK_PARM[4].'|'.$REK_PARM[6].'" class="OPTION" style="color:BLACK;background: none repeat scroll 0%  0% WHITE;">'.$REK_PARM[3];
													echo '</option></optgroup>';
													echo '<optgroup label="Dostępne :" class="OPTGROUP">';
													mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
													$SEL_CSS_A = mysqli_query($polaczenie,"select ID,NAZWA,WART,ID_OPCJ FROM CSS WHERE WSK_V=1 AND ID_OPCJ!='$REK_PARM[6]' AND ID_GROUP=1 ORDER BY ID_group") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_CSS_A - ".mysqli_error()."</span></p>");
													while($REK_ALIGN = mysqli_fetch_array($SEL_CSS_A)){
																									echo '<option value="'.$REK_ALIGN[1].'|'.$REK_ALIGN[2].'|'.$REK_ALIGN[3].'" style="color:BLACK; background: none repeat scroll 0%  0% WHITE;">'.$REK_ALIGN[1].'</option>';
													};
													echo "</optgroup></select>";
													echo $FORM_END."</p>";
												break;
											case 1: // Ustawienia CSS Pogrubiony Pochylony Podkreslony irp.
													echo $FORM_START;
													echo '<input type="hidden" name="DANE'.$i_IDG.'" value="Y" />';
													mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
													$SEL_CSS=mysqli_query($polaczenie,"select c.ID,c.NAZWA,c.ID_OPCJ FROM CSS c WHERE c.ID_GROUP=0 ORDER BY c.ID") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_P_CSS - ".mysqli_error()."</span></p>");
													while($REK_CSS = mysqli_fetch_array($SEL_CSS)){
														mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
														$SEL_P_CSS=mysqli_query($polaczenie,"select pc.ID,pc.WSK_V FROM PARM_CSS pc, PARM pm WHERE pc.ID_PARM=pm.ID AND pm.ID_MODUL='$_GET[IDMZ]' AND pc.ID_CSS='$REK_CSS[2]' AND pm.ID='$REK_PARM[0]' LIMIT 1")or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_P_CSS - ".mysqli_error()."</span></p>");
														$REK_P_CSS = mysqli_fetch_array($SEL_P_CSS);
														if($REK_P_CSS[1]=="0" ||$REK_P_CSS[1]=="") $USTAWIONY=""; else IF ($REK_P_CSS[1]=="1") $USTAWIONY='checked="checked"';
														echo "<span CLASS=\"SPAN_WART\">";
														echo '<input type="checkbox" name="'.$REK_PARM[0].'|'.$REK_CSS[0].'" value="1" '.$USTAWIONY.' class="CSS_CHBOX"  />';
														echo '<span class="S_CSS">'.$REK_CSS[1].'</span></span>';
													};
													echo $FORM_END;
												break;
											case 2:
													echo $FORM_START;
													echo '<p CLASS="P_WART">';
													echo '<select name="DANE'.$i_IDG.'" class="SELECT">';
													echo '<optgroup label="Aktualny :" class="OPTGROUP">';
													echo '<option value="'.$REK_PARM[4].'" class="OPTION">'.$REK_PARM[4];
													echo '</option></optgroup>';
													echo '<optgroup label="Dostępne :" class="OPTGROUP">';
													for( $i = 12; $i<33;) {
																			if ($REK_PARM[4]!=$i) echo '<option value="'.$i.'" class="OPTION">'.$i.'</option>';
																			$i=$i+2; 
													};
													echo "</optgroup></select>";
													echo $FORM_END."</p>";
												break;
											case 3: // Ustawienia koloru
													echo $FORM_START;
															$REK_PARM[0];// ID
															$REK_PARM[3];//NAZWA KOLORU
															$REK_PARM[4];//HEX
															if ($REK_PARM[4]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
															$domyślny_kom=' (domyślny)';
															//echo $REK_PARM[0].' '.$REK_PARM[3].' '.$REK_PARM[4].'</br>';
															//echo $_GET["DANE".$i_IDG];
													echo '<p CLASS="P_WART">';
													echo '<select name="DANE'.$i_IDG.'" class="SELECT">';
													echo '<optgroup label="Aktualny :" class="OPTGROUP">';
													echo '<option value="'.$REK_PARM[0].'|'.$REK_PARM[4].'|'.$REK_PARM[3].'" class="OPTION" style="color:'.$kolor_font.';background: none repeat scroll 0%  0% '.$REK_PARM[4].';">'.$REK_PARM[3].'</option></optgroup>';
													echo '<optgroup label="Dostępne :" class="OPTGROUP">';
													mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
													$wyswietl = mysqli_query($polaczenie,"select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 AND HEX!='$REK_PARM[4]' ORDER BY ID");
													while($r_kolor = mysqli_fetch_array($wyswietl))
																									{
																									if ($r_kolor[2]=='#000000') $kolor_font='#FFFFFF'; else  $kolor_font='#000000';
																									echo '<option value="'.$r_kolor[0].'|'.$r_kolor[2].'|'.$r_kolor[1].'" style="color:'.$kolor_font.'; background: none repeat scroll 0%  0% '.$r_kolor[2].';">'.$r_kolor[1].'</option>';
																									}

													echo "</optgroup></select>";
													echo $FORM_END."</p>";
												break;
											case 6:	
											case 4:
													echo $FORM_START;
													echo '<p CLASS="P_WART">';
													echo '<input type="text" name="DANE'.$i_IDG.'" class="TEXTAREA" value="'; 
                                                                                                        if (isset($_GET["DANE".$i_IDG])) {echo $_GET["DANE".$i_IDG];} 
                                                                                                                else {echo $REK_PARM[4]; echo '">';}
                                                                                                                if(array_key_exists($i_IDG,$err_dane))
                                                                                                                {
                                                                                                                    echo $err_dane[$i_IDG];
                                                                                                                }
													
													echo $FORM_END."</p>";
												break;
											case 5:
													echo $FORM_START;
													//echo '<span style="float:left;margin-left:5px;"><input type="text" name="DANE'.$i_IDG.'" class="TEXTAREA" value="'; if (isset($_GET["DANE".$i_IDG])) echo $_GET["DANE".$i_IDG]; else echo $REK_PARM[4]; echo '"></span>';
													$STAT_TAK=""; 
													$STAT_NIE="";
													$NIE="<span style=\"color:red;\">NIE</span>";
													$TAK="<span style=\"color:blue;\">TAK</span>"; 
													if ($REK_PARM[4]==0) {
																			//$WYSW_KOM="<span style=\"color:grey; font-size:12px;\">Aktualny ststus - </span>".$NIE;
																			$STAT_TAK=""; 
																			$STAT_NIE="checked=\"checked\"";
													}
													else if ($REK_PARM[4]==1) { 
																				//$WYSW_KOM="<span style=\"color:grey; font-size:12px;\">Aktualny ststus - </span>".$TAK;
																				$STAT_TAK="checked=\"checked\"";
																				$STAT_NIE="";
													} 
													else {
															echo "<span style=\"color:red;\">BŁĄD !</span>";
													};
													echo "<p CLASS=\"P_WART\">";
													echo '<input type="radio" class="RADIO" name="WID_ZDJ'.$i_IDG.'" value="1" '.$STAT_TAK.' /><b>'.$TAK."</b>";
													echo '<input type="radio" class="RADIO" name="WID_ZDJ'.$i_IDG.'" value="0" '.$STAT_NIE.'/><b>'.$NIE."</b>";
													//echo $WYSW_KOM;
													echo $err_dane[$i_IDG];
													echo $FORM_END."</p>";
												break;
											case 7: // CSS FONT-FAMILY
												echo $FORM_START;
															$REK_PARM[6];//WSK_D -> NR.CSS_FF
															//$REK_PARM[3];//NAZWA Font-Family ------ moze byc pusta
															$REK_PARM[4];//WARTOSC / NAZWA Font-Family
															//echo $REK_PARM[1].' '.$REK_PARM[3].' '.$REK_PARM[4].'</br>';
															//echo 'GET_[DANE] - '.$_GET["DANE".$i_IDG].'</br>';
													echo '<p CLASS="P_WART">';
													echo '<select name="DANE'.$i_IDG.'" class="SELECT">';
													echo '<optgroup label="Aktualna :" class="OPTGROUP">';
													//echo '<option value="'.$REK_PARM[3].'|'.$REK_PARM[4].'|'.$REK_PARM[6].'" class="OPTION" style="color:BLACK;background: none repeat scroll 0%  0% WHITE;">'.$REK_PARM[3];
													echo '<option value="'.$REK_PARM[6].'|'.$REK_PARM[4].'" class="OPTION" style="color:BLACK;background: none repeat scroll 0%  0% WHITE;  font-family: '.$REK_CSS_F[4].'">'.$REK_PARM[4];
													echo '</option></optgroup>';
													echo '<optgroup label="Dostępne :" class="OPTGROUP">';
													
													$SEL_CSS_FF = $db->query("select NR,NAZWA FROM CSS_FF WHERE WSK_V=1 AND NR!='$REK_PARM[6]' ORDER BY NR");
													while($REK_CSS_F = mysqli_fetch_array($SEL_CSS_FF)){
																									echo '<option value="'.$REK_CSS_F[0].'|'.$REK_CSS_F[1].'" style="color:BLACK; background: none repeat scroll 0%  0% WHITE; font-family: '.$REK_CSS_F[1].' ; ">'.$REK_CSS_F[1].'</option>';
													};
													echo "</optgroup></select>";
													echo $FORM_END."</p>";
												break;
											default:
												break;
							endswitch;
								//---------------
								//$IDP_TAB[$REK_PARM[0]]=$REK_PARM[1];
								$IDG_ALL[$i_IDG]=$REK_PARM[1];
								$IDP_ALL[$i_IDG]=$REK_PARM[0];
								//echo '<input type="hidden" name="IDP_TAB_'.$i_IDG.'" value="'.$IDG_ALL[$i_IDG].'" />';
								echo '<input type="hidden" name="IDG_ALL_'.$i_IDG.'" value="'.$IDG_ALL[$i_IDG].'" />';
								echo '<input type="hidden" name="IDP_ALL_'.$i_IDG.'" value="'.$IDP_ALL[$i_IDG].'" />';
								$i_IDG++;
								echo '<input type="hidden" name="MAX_I_ALL" value="'.$i_IDG.'" />'; //wysylam o jeden wikszy w celu poprwanego wykonania petli	
								echo '</td>';
						};
						echo "<tr><td colspan=\"2\">";
						echo "<span CLASS=\"SPAN_BUT\">";
						echo "<input class=\"btn\" type=\"submit\" value=\"ZMIEŃ WSZYSTKO\" name=\"IDZ_ALL\"/>";
						echo "</span></td></tr></tr></table>";
						echo "</form>";