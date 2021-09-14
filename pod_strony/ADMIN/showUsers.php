<?php
if(!defined('ADMIN131')) { exit('NO PERMISSION');}
echo '<p class="P_M_NAGL">Aktualne ustawienia w systemie :</p>';
/* COUNT PAGES */
    require(DR.'/class/page.php');
    $page=NEW page();
    $page->setDbRec("select COUNT(*) FROM `PERS`");
    $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
    $page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
    $IDS=$page->getIDS();
/* END COUNT PAGES */
echo '<p class="P_M_NAGL">Użytkownicy :</p>';
						
						$FORMAT_CSS_L='<span style="float:right;margin-left:2px;margin-top:0px;margin-bottom:0px;margin-right:0px;color:black;"><span style="font-weight:bold;font-size:22px;"> [</span>';
						$FORMAT_CSS_R='<span style="font-weight:bold;font-size:22px;margin-left:0px;margin-top:0px;margin-bottom:0px;margin-right:2px;">]</span></span>';
						echo '<p style="text-align:left; margin-left:10px;margin-bottom:5px;font-weight:bold;">';
						if(array_key_exists(16, $_SESSION['perm'][$IDM]))
                                                
                                                {
														echo '<a href="cel_ADMIN.php?IDW=16&IDM='.$_GET["IDM"].'&IDB='.$_GET["IDW"].'"><button class="btn">Dodaj</button></a>';
						} else echo '<button class="btn_off">Dodaj</button>';
						if(array_key_exists(17, $_SESSION['perm'][$IDM]))
                                                {
														echo '<a href="cel_ADMIN.php?IDW=17&IDM='.$_GET["IDM"].'&IDB='.$_GET["IDW"].'"><button class="btn">Edytuj</button></a>';
						} else echo '<button class="btn_off">Edytuj</button>';
						if(array_key_exists(13, $_SESSION['perm'][$IDM])){
														echo '<a href="cel_ADMIN.php?IDW=13&IDM='.$_GET["IDM"].'&IDB='.$_GET["IDW"].'"><button class="btn">Usuń</button></a></p>';
						} else echo '<button class="btn_off">Usuń</button>';
						echo '<table class="GLOWNA_TAB"><tr class="NAGLOWEK_TAB">';
						echo '<td class="NAGLOWEK_TAB" width="50px"><p class="NAGLOWEK_P">ID : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="90px"><p class="NAGLOWEK_P">Login :</td>';
						echo '<td class="NAGLOWEK_TAB" ><p class="NAGLOWEK_P">Podstawowe informacje : </p></td>';
						//echo '<td class="NAGLOWEK_TAB" width="120px"><p class="NAGLOWEK_P">Nazwisko : </p></td>';
						echo '<td class="NAGLOWEK_TAB" width="130px"><p class="NAGLOWEK_P">Aktywny :</td>';
						echo '<td class="NAGLOWEK_TAB" width="250px"><p class="NAGLOWEK_P">OPCJE :</td></tr>';
						
						$SEL_PARM = $db->query("SELECT ID,IMIE,NAZWISKO,NAZWA,WSK_V,WSK_U FROM PERS WHERE WSK_U=0");
						while($REK_PARM=mysqli_fetch_array($SEL_PARM))
								{
								if ($REK_PARM[4]==1) {$AKTYWNY="TAK"; $AKT_COL="BLUE";} else {$AKTYWNY="NIE"; $AKT_COL="RED";};
								echo '<tr class="TRESC_TAB">';
								echo '<td class="TRESC_TAB"><p style="margin-left:2px; color:black; font-weight:bold;margin-bottom:0px; margin-top:0px; text-align:left;">'.$REK_PARM[0].'</p></td>';
								echo '<td class="TRESC_TAB"><p style="margin-left:2px; margin-bottom:0px; margin-top:0px;margin-right:0px;text-align:left;">'.$REK_PARM[3].'</p></td>';
								echo '<td class="TRESC_TAB"><span style="margin-left:2px; margin-bottom:0px; margin-top:0px;font-size:18px;text-align:left;">'.$REK_PARM[1].' '.$REK_PARM[2].'</span>';
								echo $FORMAT_CSS_L;
                                                                if(array_key_exists(18, $_SESSION['perm'][$IDM]))
                                                                {
                                                                    echo '<a href="cel_ADMIN.php?IDW=18&IDM='.$_GET["IDM"].'&IDB='.$_GET["IDW"].'&ID='.$REK_PARM[0].'" style="margin:0px;" class="A_UST"> WIĘCEJ </a>';
								} 
                                                                else
                                                                {
                                                                    echo "WIĘCEJ";
                                                                }
								echo $FORMAT_CSS_R;
								echo '</td>';
								//echo '<td class="TRESC_TAB"><p style="margin-left:10px; margin-bottom:0px; margin-top:0px;text-align:left;">'.$REK_PARM[2].'</p></td>';
								echo '<td class="TRESC_TAB"><span style="float:left;margin-left:2px;font-size:16px;margin-top:5px;color:'.$AKT_COL.'; font-weight:bold;MARGIN-RIGHT:0PX;">'.$AKTYWNY.'</span>';
								echo $FORMAT_CSS_L;
                                                                if(array_key_exists(15, $_SESSION['perm'][$IDM])){
								
								echo '<a href="cel_ADMIN.php?IDW=15&IDM='.$_GET["IDM"].'&IDB='.$_GET["IDW"].'&WSKV='.$REK_PARM[4].'&ID='.$REK_PARM[0].'" style="margin:0px;" class="A_UST"> ZMIEŃ </a>';
								} else echo "ZMIEŃ";
								echo $FORMAT_CSS_R;
								echo '</td>';
								echo '<td class="TRESC_TAB">';
									echo $FORMAT_CSS_L;
									//if ($TAB_PRAW[19]["HASŁO"]==1){
									echo '<a href="cel_ADMIN.php?IDW=19&IDM='.$_GET["IDM"].'&IDB='.$_GET["IDW"].'&ID='.$REK_PARM[0].'" style="margin:0px;" class="A_UST"> HASŁO </a>';
									//} else echo "HASŁO";
									echo $FORMAT_CSS_R;
									echo $FORMAT_CSS_L;
                                                                        if(array_key_exists(17, $_SESSION['perm'][$IDM])){
									
									echo '<a href="cel_ADMIN.php?IDW=17&IDM='.$_GET["IDM"].'&IDB='.$_GET["IDW"].'&IDE='.$REK_PARM[0].'" style="margin:0px;" class="A_UST"> EDYTUJ </a>';
									} else echo "EDYTUJ";
									echo $FORMAT_CSS_R;
									echo $FORMAT_CSS_L;
                                                                        if(array_key_exists(13, $_SESSION['perm'][$IDM])){
									
									echo '<a href="cel_ADMIN.php?IDW=13&IDM='.$_GET["IDM"].'&IDB='.$_GET["IDW"].'&IDU='.$REK_PARM[0].'" style="margin:0px;" class="A_UST"> USUŃ </a>';
									} else echo "USUŃ";
									echo $FORMAT_CSS_R;
								echo '</td></tr>';
								}
						echo "</table>";
						//}else {echo '<p class="P_ERR">BRAK uprawnienia - Wyświetl użytkowników </p>';};
//-------------------------------------------------------------------------------------------KONIEC-UŻYTKOWNICY--------------------------------------------------------------------------------
						

