<?php
if(!defined('ADMIN132')) { exit('NO PERMISSION');}
echo '<p class="P_M_NAGL">Uprawnienia :</p>';
/* COUNT PAGES */
    require(DR.'/class/page.php');
    $page=NEW page();
    $page->setDbRec("select COUNT(*) FROM `PERS`");
    $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
    $page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
    $IDS=$page->getIDS();
/* END COUNT PAGES */							
							
								IF($ID=="") $_GET["ID"]=1;
								IF (ISSET($_GET["CHANGE"]))
								{
									//echo "Uruchomiono funkcję zmień uprawnienia !</br>";
									//foreach($_GET as $klucz=>$wartosc){
									//	echo '$_GET['.$klucz.'] - '.$wartosc.' |</br>';
									//};
                                                                    
									$SEL_MODUL_F = $db->query("SELECT f.ID,f.NAZWA,f.OPIS FROM FUNKCJA f WHERE f.WSK_V=1 AND f.WSK_U=0 ORDER BY f.ID");
									
                                                                        while($REK_MODUL_F=mysqli_fetch_array($SEL_MODUL_F))
									{
										$ID_FUNKCJA=$REK_MODUL_F[0];
										IF(ISSET($_GET[$REK_MODUL_F[0]]))
										{					
											//echo "WSKAZANO</br>";
											//echo "ID_FUNKCJA - ".$ID_FUNKCJA."</br>";
											IF(mysqli_num_rows($db->query("SELECT p.ID FROM PERS_FUNKCJA p WHERE p.ID_PERS='$_GET[ID]' AND ID_FUNKCJA='$ID_FUNKCJA' LIMIT 1"))<1)
											{
												//INSERT
												//echo "INSERT</br>";
												$db->query("INSERT INTO PERS_FUNKCJA (ID_PERS, ID_FUNKCJA,WSK_V,DAT_UTW) VALUES ('".$_GET['ID']."','".$ID_FUNKCJA."',1,NOW())");
												
											}
											else
											{
												//UPDATE
												//echo "ISTNIEJE</br>";
												
												IF(mysqli_num_rows($db->query("SELECT p.ID FROM PERS_FUNKCJA p WHERE p.ID_PERS='".$_GET['ID']."' AND ID_FUNKCJA='".$ID_FUNKCJA."' AND WSK_V=1 LIMIT 1"))>0)
												{
													//echo "UPDATE - FALSE</br>";
												}
												else
												{
													//echo "UPDATE - TRUE</br>";
													$db->query("UPDATE `PERS_FUNKCJA` SET `WSK_V`='1' WHERE `PERS_FUNKCJA`.`ID_PERS`=$_GET[ID] AND `PERS_FUNKCJA`.`ID_FUNKCJA`=$ID_FUNKCJA");
													
												}
											}
										}
										else
										{
											
											IF(mysqli_num_rows($db->query("SELECT p.ID FROM PERS_FUNKCJA p WHERE p.ID_PERS='".$_GET['ID']."' AND p.ID_FUNKCJA='".$ID_FUNKCJA."' LIMIT 1"))<1)
											{
												//echo "NIE ISTENIEJE UPRAWENINIE Z ID_FUNKCJA</br>";
												//NIC NIE RÓB, NIE ISTNIEJE
											}
											ELSE
											{
												
												IF(mysqli_num_rows($db->query("SELECT p.ID FROM PERS_FUNKCJA p WHERE p.ID_PERS='$_GET[ID]' AND p.ID_FUNKCJA='$ID_FUNKCJA' AND p.WSK_V=1 LIMIT 1"))<1)
												{
													//echo "ISTENIEJE UPRAWENINIE Z ID_FUNKCJA - UPDATE == FALSE (WSK_V==0)</br>";
													//NIC NIE RÓB, JUZ jEST WSK_V=0
												}
												else
												{
													//0echo "ISTENIEJE UPRAWENINIE Z ID_FUNKCJA - UPDATE == TRUE (WSK_V==1)</br>";
													$db->query("UPDATE `PERS_FUNKCJA` SET `WSK_V`='0' WHERE `PERS_FUNKCJA`.`ID_PERS`=".$_GET['ID']." AND `PERS_FUNKCJA`.`ID_FUNKCJA`=".$ID_FUNKCJA."");
													
												}
											}
										}
									}
								echo "<p style=\"color:red;font-weight:bold;\">Uaktualniono uprawnienia !</p>";							
							}
							echo '<table class="GLOWNA_TAB" style="background-color:white;"><tr class="NAGLOWEK_TAB">';
							echo '<td class="NAGLOWEK_TAB" width="300px" colspan><p class="NAGLOWEK_P">Użytkownik : </p></td>';
							echo '<td class="NAGLOWEK_TAB" width="450px"><p class="NAGLOWEK_P">PRAWA :</td></tr>';
							echo '<tr class="TRESC_TAB"><td class="TRESC_TAB" VALIGN="TOP">';
							
							$SEL_PERS = $db->query("SELECT ID,NAZWA,IMIE,NAZWISKO FROM PERS WHERE WSK_U=0 ORDER BY ID");
							while($REK_PERS=mysqli_fetch_array($SEL_PERS))
								{
								if ($_GET["ID"]==$REK_PERS[0]) {$font_size="18px";$color="PURPLE";} else {$font_size="14px";$color="";};
								echo '<p style="margin-left:2px; margin-bottom:5px; margin-top:5px; font-size:'.$font_size.'; margin-top:0px;margin-right:10px; text-align:left;">';
								echo '<a class="A_UST_UPR" href="cel_ADMIN.php?IDW='.$_GET["IDW"].'&IDM='.$_GET["IDM"].'&ID='.$REK_PERS[0].'">';
								
								echo '<span style="color:black;">[ID: '.$REK_PERS[0].']</span><span style="color:'.$color.';">'.$REK_PERS[2].' '.$REK_PERS[3]."</span>";
								echo '</a></p>';
								};
							echo '</td><td style="background-color:#F2F5A9;">';
							echo '<form method="GET" ENCTYPE="multipart/form-data">';
							
							$SEL_MODUL = $db->query("SELECT m.ID,m.NAZWA FROM MODUL m WHERE m.WSK_V=1 ORDER BY m.ID");
							while($REK_MODUL=mysqli_fetch_array($SEL_MODUL))
								{
								
								echo '<p style="margin-left:10px; margin-bottom:5px; margin-top:5px;margin-right:10px; text-align:left; font-weight:bold;font-size:18px;">';
								echo "MODUŁ [".$REK_MODUL[0]."] : <span style=\"color:purple;\">$REK_MODUL[1]</span>";
								echo '</p>';
								//
								
								$SEL_MODUL_F = $db->query("SELECT f.ID,f.NAZWA,f.OPIS,f.IDW FROM FUNKCJA f WHERE f.WSK_V=1 AND f.WSK_U=0 AND f.ID_MODUL='$REK_MODUL[0]' ORDER BY f.IDW");
								while($REK_MODUL_F=mysqli_fetch_array($SEL_MODUL_F))
								{
									//echo "REK MODUL F - ".$REK_MODUL_F[0]."</br>"; 
									$checked="";
									IF (ISSET($_GET["IDPRAW"])){
																if(ISSET($_GET[$REK_MODUL_F[0]])) {
																		$checked="checked=\"checked\""; 
																		//echo "ISSET GET - ".$_GET[$REK_MODUL_F[0]]."</br>";
																		$VALUE=1;
																} else {
																		$checked=""; 
																		//echo "ISSET GET - ".$_GET[$REK_MODUL_F[0]]."</br>";
																		$VALUE=0;
																};
									} ELSE {
											
											$REK_PRAW=mysqli_fetch_row($db->query("SELECT p.ID,p.WSK_V FROM PERS_FUNKCJA p WHERE p.ID_PERS='$_GET[ID]' AND ID_FUNKCJA='$REK_MODUL_F[0]' LIMIT 1"));	
											if($REK_PRAW[1]==1) {
																$checked="checked=\"checked\""; 
																$VALUE=1;
									} else if ($REK_PRAW[1]==0) {
																$checked="";
																$VALUE=0;
									} else {
											$VALUE=2;
											$checked="";
									};
									};
									echo '<p style="margin-left:100px; margin-bottom:2px; margin-top:2px;margin-right:10px; text-align:left; font-weight:normal;font-size:14px;">';
									echo '<input type="checkbox" name="'.$REK_MODUL_F[0].'" value="'.$VALUE.'" '.$checked.' class="CSS_CHBOX"><span style="color:gray;">['.$REK_MODUL_F[3].']</span> '.$REK_MODUL_F[2],'';
									echo '</p>';
								};
								};
								echo '<input type="hidden" name="IDPRAW" value="1" />';// IDPRAW 1 - TRUE
								echo '<input type="hidden" name="IDW" value="'.$_GET["IDW"].'" />';// ID Wyboru
								echo '<input type="hidden" name="ID"  value="'.$_GET["ID"].'" />'; // ID Substring
								echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />'; // ID Modułu
								echo '<input class="button" type="submit" value="ZMIEŃ" name="CHANGE"/>';
								echo '</form>';
							echo '</td></tr>';
							echo "</table>";
							
