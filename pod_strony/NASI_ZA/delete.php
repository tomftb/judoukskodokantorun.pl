<?php if(!defined('PAGE_URL')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0"><p class="P_HREF_BACK">Anuluj</p></a>';
echo '<p class="P_MAIN">Usuwanie zawodnika :</p>';
$end='<p class="P_BACK">Twój zawodnik został usunięty !<br/><span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span><span class="S_BACK2">MENU - ZAWODNICY.</span></p><script>
																																function init(){ setTimeout(\'document.location="cel_NASI_ZA.php?IDW=0&IDM='.$_GET["IDM"].'"\', 1500);}
																																window.onload=init;
																																</script>';
							if (isset($_GET['ID'])){
													
													echo '<p class="P_NG_INF">Napewno chcesz usunąć zawodnika nr <span class="S_MAIN_NG">[</span>'.$_GET['ID'].'<span class="S_MAIN_NG">]</span> ?</p>';
													echo '<div class="DIV_OPCJ">';
													echo '<p class="P_POZ_BUT"><a href="cel_NASI_ZA.php?IDW=0&IDM='.$_GET["IDM"].'"><span class="s_button">NIE</span></a>';
													echo '<a href="cel_NASI_ZA.php?IDW=3&IDM='.$_GET["IDM"].'&ID='.$_GET['ID'].'&USN=T"><span class="s_button">TAK</span></a>';
													echo '</p></div>';
													if (isset($_GET["USN"])){
																				
																				$db->query("UPDATE `ZWDK`  SET `WSK_U`='1',`ID_PERS_KOR`='".$_SESSION['id_user']."' WHERE `ZWDK`.`ID`='".$_GET['ID']."'");
																				unset($_GET["ID"]);
																				echo $end;					
																				}
													}
													else
													{						
														echo '<form Name="USUN_1" action="" method="POST" >';
														echo '<p class="P_NG_INF">Podaj numer ID zawodnika : ';
														echo '<input type="number" name="ID" size="5" value="'.$_POST["ID"].'" maxlength="5" /></p>';
														echo '<div class="DIV_OPCJ">';
														echo '<input class="inp_button" type="submit" value="Usuń" name="USUN"/></div></form>';
														if(isset($_POST["USUN"]))
														{
														// $_POST["IDU"] ID zawodnika du usniecia
														if (($_POST["ID"]!=''))
														{
                                                                                                                        if (mysqli_num_rows($db->query("select ID FROM ZWDK WHERE ID='".$_POST['ID']."'"))==1)
															{
																if (mysqli_num_rows($db->query("select ID from ZWDK WHERE ID='".$_POST['ID']."' AND WSK_U=1"))==0)
																{				
																	$db->query("UPDATE `ZWDK`SET `WSK_U`='1',`ID_PERS_KOR`='".$_SESSION['id_user']."' WHERE `ZWDK`.`ID`='".$_POST['ID']."' AND WSK_U=0");
																	
                                                                                                                                    echo $end;
																}
                                                                                                                                else {echo "<p class=\"P_ERROR\">Istnieje zawodnik o podanym <span class=\"S_ERROR\">ID</span> ale został ono już prędzej usunięty !</p>";}
														}
														else
														{
															echo "<p class=\"P_ERROR\">Nie znaleziono żadnego zawodnika o wpisanym <span class=\"S_ERROR\">ID</span>!</p>";
														}	
														} else echo "<p class=\"P_ERROR\">Nie podałeś <span class=\"S_ERROR\">ID</span> zawodnika !</p>";
														}
														}
							?>
</div>