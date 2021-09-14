<?php if(!defined('NASZE_ZD_66')) { exit('NO PERMISSION');}?>
<div class="DIV_MAIN">							
<?php
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Anuluj</p></a>';

$REK_STAT = mysqli_fetch_row($db->query("select WSK_V,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT from OUR_PHOTO WHERE ID='".$_GET['ID']."'"));
							if ($REK_STAT[0]==1)
                                                        {
                                                            $STAT_KOM ='<span CLASS="STAT_KOM_T"> WIDOCZNY </span>';
							} 
							else
                                                        {
                                                            $STAT_KOM='<span CLASS="STAT_KOM_N"> NIEWIDOCZNY</span>';
							}
							echo '<p class="P_MAIN">Zmienić statusu widoczności zdjęcia nr <span class="S_MAIN_NG">[</span>'.$_GET["ID"].'<span class="S_MAIN_NG">]</span> ? </p>';
							
							echo '<center>'; 
							//if($rek_img[7]<330) $margin_top=(330-$rek_img[7])/2; else $margin_top=5;
							echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/nasze/$REK_STAT[1]',' $REK_STAT[1] ',$REK_STAT[2],$REK_STAT[3])\">";
							echo '<img src="'.APP_URL.'/zdjecia/nasze/'.$REK_STAT[4].'" alt="Zdjecie" style="width:'.$REK_STAT[5].'px; height:'.$REK_STAT[6].'px; border:0px;margin-bottom:2px;" />';
							echo '</a><p class="P_INFO_IMG">(kliknij na zdjęcie aby je powiększyć)</p></center>';
							echo '<div class="DIV_OPCJ">';
							echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
							echo '<a href="'.PAGE_URL.'&IDW='.$IDW.'&IDS='.$IDS.'&ID='.$ID.'&WSK_V='.$REK_STAT[0].'&VDK=T"><span class="s_button">TAK</span></a>';
							echo '</p>';
							echo '<p CLASS="P_STAT_V">(Aktualny status - '.$STAT_KOM.')</p>';
							echo '</div>';
							
							if (isset($_GET["VDK"])){
														if ($_GET["WSK_V"]==0) {
																					$WIDOK=1;
																					$STAT_KOM ='<span CLASS="STAT_KOM_T"> WIDOCZNY </span>';
														} 
														else {
																$WIDOK=0;
																$STAT_KOM='<span CLASS="STAT_KOM_N"> NIEWIDOCZNY</span>';
														};
							
							$db->query("UPDATE `OUR_PHOTO` SET `WSK_V`=$WIDOK WHERE `OUR_PHOTO`.`ID`='".$_GET['ID']."'");
							echo '<p class="P_BACK">Status twojego zdjęcia został zmieniony na - '.$STAT_KOM.'<br/>';
							echo '<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
							echo '<span class="S_BACK2">MENU - Nasze zdjęcia.</span></p>';
                                                        echo "<script>window.redirect('".PAGE_URL."&IDW=0&IDS=".$$IDS."#ID".$ID."',1500)</script>";
							}
                                                        ?>
							</div>
