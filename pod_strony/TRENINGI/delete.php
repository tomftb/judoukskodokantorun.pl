<div class="DIV_MAIN">
<?php
echo '<a href="'.$AKT_URL.'cel_TRENINGI.php?IDW=0&IDM=15" class="A_BACK"><p class="P_HREF_BACK">Anuluj</p></a>';
echo '<p CLASS="P_MAIN_CEL">'.$frameTitle.'</p>';
if ($TAB_PRAW["Usuń"]['VAL']!=1)
{
    $db->insDbLog($IDM,'Próba uruchomienia funkcji - Usuń (BRAK UPRAWNIENIA)');
    echo '<p class="P_ERR">BRAK uprawnienia - Usuń wszystkie</p>';
    return false;
}
$db->insDbLog($IDM,'Uruchomiono funkcję - Usuń wszystkie');
$tabela='TRENING';
$info_naglowek="treningu";
if ($_SESSION['id_user']==1) echo '<p class="P_INFO"> Tabela - <span class="S_INFO">'.$tabela.'</span></p>';
							
							
							
							if ((isset($_GET["ID"])) && (!isset($_GET["USUN"]))){
													//echo '<form action="" method="GET" >';
													echo '<p class="P_PODAJ">Napewno chcesz usunąć '.$tabela.' nr : <input type="text" readonly name="ID" maxlength="5" size="3" value="'; if (isset($_GET["ID"])) echo $_GET['ID']; else echo $ID; echo'" ></b> ?</p>';
													echo '<div class="DIV_OPCJ">';
													echo '<p class="P_POZ_BUT">';
													//echo '<input type="submit" value="TAK" name="USUN"/>';
													echo '<a href="'.$_SERVER["PHP_SELF"].'?IDW='.$_GET['IDB'].'&IDM='.$_GET["IDM"].' "><span class="s_button">NIE</span></a></p>';
													echo '<a href="cel_TRENINGI.php?IDW='.$_GET["IDW"].'&IDM='.$_GET["IDM"].'&ID='.$_GET["ID"].'&USUN=T&IDB='.$_GET["IDB"].'"><span class="s_button">TAK</span></a>';
													//</form>';
													echo '</p></div>';
							} 
							else {						
									echo '<form Name="USUN" action="" method="POST" >';
									echo '<p class="P_PODAJ">Podaj numer ID '.$info_naglowek.' : <input type="text" name="IDU" size="5" value="'.$_POST['IDU'].'" maxlength="5" class="TEXTAREA" /></p>';
									echo '<div class="DIV_OPCJ">';
									echo '<p class="P_SUBMIT"><input type="submit" value="Usuń" name="USUN"  class="inp_button" /></p>';
									echo '</div></form>';							
							if((isset($_POST["USUN"])) || (isset($_GET["USUN"])))
							{
								if(isset($_POST["USUN"])) $IDU=$_POST["IDU"]; else $IDU=$_GET["ID"];
								
								if ($IDU!='')
								{
									mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
									$SEL_WZOR1="select ID,WSK_U from $tabela where ID='$IDU';";
									$SEL_DANE_U1 = mysqli_query($polaczenie,$SEL_WZOR1) or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL (SEL_DANE_U1 : $SEL_WZOR1) : <span class=\"S_SQL\"> ".mysqli_error()."</span></p>");
									$SEL_REK = mysqli_fetch_row($SEL_DANE_U1);
								if ($SEL_REK[0]!=$IDU) echo '<p class="P_ERROR">Nie znaleziono żadnego '.$info_naglowek.' o wpisanym <span class="S_ERR_E">ID ('.$SEL_ROK[0].'|'.$IDU.')</span>!</p>';
								else {
									if ($SEL_REK[1]>0) echo '<p class="P_ERROR">Istnieje '.$info_naglowek.' o podanym <span class="S_ERR_E"">ID</span> ale został ono już prędzej usunięty !</p>';
									else {				
									
														mysqli_query($polaczenie,"SET AUTOCOMMIT=0");
																				mysqli_query($polaczenie,"START TRANSACTION");	
																				mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
																				$usun = "UPDATE `$tabela` SET `WSK_U`='1' WHERE `$tabela`.`ID`=$IDU";
																				if (mysqli_query($polaczenie,$usun)) {
																										if ($_SESSION['id_user']==1) {
																										echo "\$usun - ".mysqli_query($polaczenie,$usun)."</br>";
																										};
																										//--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
																										INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Usunięto trening - ".$IDU,$polaczenie);
																										//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
																										mysqli_query($polaczenie,"COMMIT");
																										echo '<p class="P_PODAJ">Twój '.$info_naglowek.' został usunięty !</p>';
																				}
																				else {
																						if ($_SESSION["id_user"]==1){
																													echo "<p class=\"P_SQL_ERR\">Błąd zapytania SQL (usun) - <span class=\"S_SQL\">$usun ".mysqli_error()."</span></p>";
																						} 
																						else {
																								echo '<p class="P_MAIN">Wystąpił błąd podczas usuwania treningu! Skontaktuj się z Administratorem !</p>';
																						};
																						mysqli_query($polaczenie,"ROLLBACK");
																				};
																				
																				
																				echo "<p class=\"P_PODAJ\">Za chwilę zostaniesz przekierowany na stronę <span class=\"S_DODAJ\">MENU - $info_naglowek</span><script>
																											function init(){ setTimeout('document.location=\"cel_TRENINGI.php?IDW=$_GET[IDB]&IDM=$_GET[IDM]\"', 1000);}
																											window.onload=init;
																											</script></p>";
																				unset($_GET['IDU']);
									};
								};
								} else echo '<p class="P_ERROR">Nie podałeś <span class="S_ERR_E">ID</span> '.$info_naglowek.' !</p>';
							}
							};
							
                                                        ?>
</div>

