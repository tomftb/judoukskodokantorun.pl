<?php if(!defined('PAGE_URL')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Anuluj</p></a>';
echo '<p class="P_MAIN">Zmiana statusu widoczności zawodnika : </p>';

if (!isset($_GET["VDK"]))
						{
    $STATUS = $db->query("select WSK_V from ZWDK WHERE ID='".$_GET['ID']."'");
						$rekord = mysqli_fetch_array($STATUS);
						$STAT=$rekord[0];
						if ($STAT==1){
										$STAT_W ='<font style="color:blue;font-weight:bold;"> WIDOCZNY </font>';
									} 
									else {
											$STAT_W='<font style="color:red;font-weight:bold;"> NIEWIDOCZNY</font>';
										};
						
						echo '<p class="P_NG_INF">Zawodnik nr <span class="S_MAIN_NG">[</span>'.$_GET['ID'].'<span class="S_MAIN_NG">]</span> ?</p>';
						echo '<div class="DIV_OPCJ">';
						echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
						echo '<a href="'.PAGE_URL.'&IDW=6&IDS='.$IDS.'&ID='.$_GET['ID'].'&VDK=T"><span class="s_button">TAK</span></a>';
						echo '</p></div>';
						echo '<p style="font-size:14px;text-align:left;margin-left:20px;">(Aktualny status - '.$STAT_W.')</p>';
                                                }
                                                else
						{
							
							$STATUS = $db->query("select `WSK_V` from `ZWDK` WHERE ID='".$_GET['ID']."'");
							$rekord = mysqli_fetch_array($STATUS);
							$STAT=$rekord[0];
							if ($STAT==0) 
							{
                                                            $WIDOK=1;
								$STAT_W ='<font style="color:blue"> WIDOCZNY </font>';
							} 
							else
							{
								$WIDOK=0;
								$STAT_W='<font style="color:red"> NIEWIDOCZNY</font>';
							};
								
								$db->query("UPDATE `ZWDK` SET `WSK_V`=$WIDOK WHERE `ZWDK`.`ID`='".$_GET['ID']."'");
								echo '<p class="P_BACK">Status twojego zawodnika został zmieniony na - '.$STAT_W.'<br/>';
                                                                echo '<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
                                                                echo '<span class="S_BACK2">MENU - ZAWODNICY.</span></p>';
                                                                echo '<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"\', 1500);}
																																window.onload=init;
																																</script>';
						}

 ?>                                               </div>
