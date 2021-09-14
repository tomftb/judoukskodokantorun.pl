<?php
if(!defined('AKT26')) { exit('NO PERMISSION');}
//------------------------------------------------------------------------------------------------------------------------------------------	
$SEL_STAT = $db->query("select n.WSK_V,n.TYTUL,n.TRESC,n.K_TYTUL,n.R_TYTUL,n.K_TRESC,n.R_TRESC,n.P_TYT,n.P_TRE,n.CSS_TYT,n.CSS_TRE FROM NEWS n WHERE n.ID='$_GET[ID]'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_STAT - ".mysql_error()."</span></p>");
$REK_STAT = mysqli_fetch_row($SEL_STAT);
							
if ($REK_STAT[0]==1){
			$STAT_KOM ='<font style="color:blue;font-weight:bold;"> WIDOCZNY </font>';
							} 
							else {
									$STAT_KOM='<font style="color:red;font-weight:bold;"> NIEWIDOCZNY</font>';
							};
							
							echo "<div class=\"DIV_MAIN\">";
							echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'&#ID'.$ID.'">';
							echo '<p class="P_HREF_BACK">Anuluj</p></a>';
							echo '<p class="P_MAIN">Zmienić statusu widoczności artykułu nr <span class="S_MAIN_NG">[</span>'.$_GET["ID"].'<span class="S_MAIN_NG">]</span> ? </p>';
							//if($_SESSION['id_user']==1) echo '<p style="text-align:left;margin-left:20px;">ID artykułu - '.$_GET["IDV"].'</p>';
							//----INCLUDE-INF
							$PLIK_INC="pobr_dane2.php";
							if(file_exists(DR."/pod_strony/_include_/".$PLIK_INC)) include(DR."/pod_strony/_include_/".$PLIK_INC); else echo "Nie można załadować potrzebnego pliku - $PLIK_INC . Skontaktuj się z Administratorem!";
							//----KONIEC-INCLUDE-INF
							echo '<div class="DIV_OPCJ">';
							echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
							echo '<a href="'.PAGE_URL.'&IDW='.$IDW.'&IDS='.$IDS.'&ID='.$ID.'&WSK_V='.$REK_STAT[0].'&VDK=T"><span class="s_button">TAK</span></a>';
							echo '</p>';
							echo '<p CLASS="P_STAT_V">(Aktualny status - '.$STAT_KOM.')</p>';
							echo '</div>';
							if (isset($_GET["VDK"])){
														if ($_GET["WSK_V"]==0) {
																					$WIDOK=1;
																					$STAT_KOM ='<font style="color:blue"> WIDOCZNY </font>';
														} 
														else {
																$WIDOK=0;
																$STAT_KOM='<font style="color:red"> NIEWIDOCZNY</font>';
														};
							
							$ID=$_GET["ID"];
                                                        $db->query("UPDATE  `NEWS` SET  `WSK_V` =  $WIDOK WHERE `ID` =$ID");
							$db->insDbLog($_GET["IDM"],"Zmieniony status widoczności $ID - $STAT_KOM");
							
							
							
							echo '<p class="P_BACK">Status twojego zawodnika został zmieniony na - '.$STAT_KOM.'<br/>';
							echo '<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
							echo '<span class="S_BACK2">MENU - Aktualności.</span></p>';
							echo '<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"\', 1000);}window.onload=init;</script>';
							}
							echo '</div>';

