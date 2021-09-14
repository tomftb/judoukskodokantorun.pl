<?php if(!defined('TREN43')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Anuluj</p></a>';
echo '<p class="P_MAIN">Usuwanie Trenera :</p>'; 
							
$end='<p class="P_BACK">Trener został usunięty !<br/><span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
$end.='<span class="S_BACK2">MENU - Trenerzy.</span></p>';
$end.='<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0"\', 1500);}window.onload=init;</script>';
							if (isset($_GET['ID']))
							{	
                                                            $log->log(0,"[".__FILE__."] GET[ID] =  ".$_GET['ID']);
                                                            $log->log(0,"[".__FILE__."] GET[USN] =  ".$_GET['USN']);
								$log->log(0,"[".__FILE__."] ISSET GET[ID] =  ".$_GET['ID']);
								echo '<p class="P_NG_INF">Napewno chcesz usunąć trenera nr <span class="S_MAIN_NG">[</span>'.$_GET['ID'].'<span class="S_MAIN_NG">]</span> ?</p>';
								echo '<div class="DIV_OPCJ">';
								echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
								echo '<a href="'.PAGE_URL.'&IDW=3&ID='.$_GET['ID'].'&USN=T"><span class="s_button">TAK</span></a>';
								echo '</p></div>';
								if (isset($_GET["USN"]))
								{
                                                                    $db->query("UPDATE `TREN` SET `WSK_U`='1' WHERE `TREN`.`ID`=$_GET[ID]");
                                                                    echo $end;
                                                                    unset($_GET["IDU"]);							
								}
							}
							else
							{						
								$log->log(0,"[".__FILE__."] POST USN");
								echo '<form Name="USUN_1" action="" method="POST" >';
								echo '<p class="P_NG_INF">Podaj numer ID Trenera : ';
								echo '<input type="number" name="ID" size="5" value="';
								if(isset($_POST["ID"])) echo $_POST['ID'];
								echo '" maxlength="5" /></p>';
								echo '<div class="DIV_OPCJ">';
								echo '<input class="button" type="submit" value="Usuń" name="USUN"/></div></form>';
								if(isset($_POST["USUN"]))
								{
									$log->log(0,"[".__FILE__."] ISSET POST[USUN] =  ".$_POST["USUN"]);				
									if (($_POST["ID"]!=''))
									{												
										if (mysqli_num_rows($db->query("SELECT ID FROM TREN WHERE `ID`='".$_POST['ID']."'"))>0)
										{
											if (mysqli_num_rows($db->query("SELECT `ID` FROM `TREN` WHERE ID='".$_POST['ID']."' AND WSK_U=1"))===0)
											{				
												$db->query("UPDATE TREN SET `WSK_U`=12 WHERE `ID`='".$_POST['ID']."'");
												echo $end;													
											}
											else 
											{
												echo "<p class=\"P_ERROR\">Istnieje Trener o podanym <span class=\"S_ERROR\">ID</span> ale został ono już prędzej usunięty !</p>";
												$log->log(0,"[".__FILE__."] Istnieje Trener o podanym ID,ale został ono już prędzej usunięty !");		
											}
										}
										else
										{
											echo "<p class=\"P_ERROR\">Nie znaleziono żadnego Trenera o wpisanym <span class=\"S_ERROR\">ID</span>!</p>";
											$log->log(0,"[".__FILE__."] Nie znaleziono żadnego Trenera o wpisanym ID!");		
										}	
									}
									else
									{
                                                                            echo "<p class=\"P_ERROR\">Nie podałeś <span class=\"S_ERROR\">ID</span> Trenera !</p>";
									}
								}
							}
                                                        ?>
							</div>