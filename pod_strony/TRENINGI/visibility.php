<?php
//----------------------------------------------------------------------WIDOCZNOŚĆ-TRENING-------------------------------------------------------------------------	
							if ($TAB_PRAW["USTAW"]['VAL']==1){
							if ($_GET["WSK_V"]!=''){
									if ($_SESSION["id_user"]==1){
									echo "GET[WSK_V]  nie pusty - [".$_GET["WSK_V"]."] </br>";
									};
							} else {
									if ($_SESSION["id_user"]==1){
									echo "GET[WSK_V] PUSTY - [".$_GET["WSK_V"]."] </br>";
									};
									//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
									INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - WIDOCZNOŚĆ trening ID : ".$_GET["ID"],$polaczenie);
									//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
							}
							mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$SEL_WZOR="select WSK_V,NAZWA_GRUPY,ROK,DZIEN_GODZINA,OPIS,KOLOR_TLA,N_G_ROZMIAR,W_ROZMIAR,D_G_ROZMIAR,Z_ROZMIAR,KOLOR_TEKST FROM TRENING WHERE ID='$_GET[ID]'";
							$SEL_STAT = mysqli_query($polaczenie,$SEL_WZOR) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL (<span class=\"S_SQL\">SEL_STAT - $SEL_WZOR</span>) <span class=\"S_SQL\"> : ".mysqli_error()."</span></p>");
							$REK_STAT = mysqli_fetch_row($SEL_STAT);
							if ($REK_STAT[0]==1){
												$STAT_KOM ='<span CLASS="STAT_KOM_T"> WIDOCZNY </span>';
							} 
							else {
									$STAT_KOM='<span CLASS="STAT_KOM_N"> NIEWIDOCZNY</span>';
							};
							echo '<a class="A_BACK" href="'.$BACK_URL.'&'.$ZAK_STR.'"><p class="P_HREF_BACK">Anuluj</p></a>';
							echo '<p class="P_MAIN">Zmienić statusu widoczności treingu nr <span class="S_MAIN_NG">[</span>'.$_GET["ID"].'<span class="S_MAIN_NG">]</span> ? </p>';
							//----POKAZ-TRENING
									echo '<table CLASS="TAB_TRENING">';
									echo '<tr CLASS="TAB_TRENING" style="background:'.$REK_STAT[5].';">';
									echo '<td CLASS="TAB_TRENING" width="100"><span style="font-size:'.$REK_STAT[6].'; color:'.$REK_STAT[10].'">'.$REK_STAT[1].'</span></td>';
									echo '<td CLASS="TAB_TRENING" width="150"><span style="font-size:'.$REK_STAT[7].'; color:'.$REK_STAT[10].'">'.$REK_STAT[2].'</span></td>';
									echo '<td CLASS="TAB_TRENING" width="220"><span style="font-size:'.$REK_STAT[8].'; color:'.$REK_STAT[10].'">'.$REK_STAT[3].'</span></td>';
									echo '<td CLASS="TAB_TRENING" width="220"><center><span style="font-size:'.$REK_STAT[9].'; color:'.$REK_STAT[10].'">'.$REK_STAT[4].'</span></center></td></tr>';
									echo "</table>";
							//----KONIEC-POKAZ-TRENING
							echo '<div class="DIV_OPCJ">';
							echo '<p class="P_POZ_BUT"><a href="'.$BACK_URL.'&'.$ZAK_STR.'"><span class="s_button">NIE</span></a>';
							echo '<a href="'.$AKT_URL.'?IDW='.$_GET["IDW"].'&IDM='.$_GET["IDM"].'&ID='.$_GET['ID'].'&WSK_V='.$REK_STAT[0].'&'.$ZAK_STR.'">';
							echo '<span class="s_button">TAK</span></a>';
							echo '</p>';
							echo '<p CLASS="P_STAT_V">(Aktualny status - '.$STAT_KOM.')</p>';
							echo '</div>';
							
							if (isset($_GET["WSK_V"])){
														if ($_GET["WSK_V"]==0) {
																					$WIDOK=1;
																					$STAT_KOM ='<span CLASS="STAT_KOM_T"> WIDOCZNY </span>';
														} 
														else {
																$WIDOK=0;
																$STAT_KOM='<span CLASS="STAT_KOM_N"> NIEWIDOCZNY</span>';
														};
							mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$UPD_WSKV = "UPDATE `TRENING` SET `WSK_V`=$WIDOK WHERE `TRENING`.`ID`=$_GET[ID]";
							mysqli_query($polaczenie,$UPD_WSKV) or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">UPD_WSKV - ".mysqli_error()."</span></p>");
							if ($UPD_WSKV) {
															
																	mysqli_query($polaczenie,"COMMIT");
																	//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Zmianiono status treningu z $_GET[WSK_V] na $WIDOK - ".$_GET["ID"],$polaczenie);
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
							
																	echo '<p class="P_BACK">Status twojego zdjęcia został zmieniony na - '.$STAT_KOM.'<br/>';
							}
							else {
																mysqli_query($polaczenie,"ROLLBACK");
																echo '<p class="P_MAIN">Wystąpił błąd podczas zmiany statusu widoczności treningu! Skontaktuj się z Administratorem !</p>';
							};
							
							echo '<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
							echo '<span class="S_BACK2">MENU - Treningi.</span></p>';
							echo '<script>function init(){ setTimeout(\'document.location="'.$BACK_URL.'"\', 1500);}window.onload=init;</script>';
							}
							} 
							else {
										//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
										INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Próba uruchomienia funkcji - WIDOCZNOŚĆ treningu (BRAK UPRAWNIEŃ).",$polaczenie);
										//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
										echo $BACK_URL_FULL;
										echo "<p class=\"P_ERR_E\">BRAK uprawnienia - <span class=\"S_ERR_E\">Ustaw widoczność treningu </span></p></div>";
							};

