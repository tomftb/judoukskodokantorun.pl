<?php  if(!defined('ACT_PERM')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<p CLASS="P_MAIN_CEL">'.$frameTitle.'</p>';								
							echo '<form action="" method="GET" name="edycja_zdjecia">';
							echo '<p class="P_NG_INF">Podaj numer ID treningu : <input type="text" name="ID" size="5" value="'.$_GET["ID"].'" maxlength="5" class="TEXTAREA" /></p>';
							echo '<input type="hidden" name="IDW" value="'.$_GET["IDW"].'" />';
							echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
							echo '<input type="hidden" name="IDB" value="'.$_GET["IDB"].'" />';
							echo '<div class="DIV_OPCJ">';
							echo '<input type="submit" class="inp_button" value="Edytuj" name="wyb_edytuj"/></form>';
							echo '</div>';
							if (isset($_GET["wyb_edytuj"])) {
															if ($_GET["ID"]!='')
                                                                                                                        {
															
															$SEL_REK = mysqli_fetch_row($db->query("select ID,WSK_U from TRENING where ID='".$_GET['ID']."'"));
															if ($SEL_REK[0]!=$_GET["ID"]) {echo '<p class="P_ERROR">Nie znaleziono żadnego treningu o podanym <span class="S_ERR_E">ID</span> !</p>';}
															else {
																	if ($SEL_REK[1]>0) echo '<p class="P_ERROR">Istnieje trening o podanym <span class="S_ERR_E">ID</span> ale zostało on usunięty !</p>';
																	else {
																			echo '<script type="text/javascript">
																				window.location = "'.PAGE_URL.'&IDW=5&ID='.$_GET["ID"].'"
																			</script>';
																	};
															};
														} else echo '<p class="P_ERROR">Nie podałeś <span class="S_ERR_E">ID</span> treningu !</p>';
													};
?>
</div>

