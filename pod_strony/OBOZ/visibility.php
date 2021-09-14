<?php if(!defined('OBOZ_56')) { exit('NO PERMISSION'); } ?>
<div class="DIV_MAIN">
<?php
echo '<p class="P_HREF_BACK"><a class="A_BACK"href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'">Anuluj</a></p>';
echo '<p class="P_MAIN">Zmiana statusu widoczności obozu : </p>';
$rek=mysqli_fetch_row($db->query("select `WSK_V` from `OBOZ` WHERE `ID`='".$ID."'"))[0];
if ($rek==1)
{
    $STAT_W ='<font style="color:blue;font-weight:bold;"> WIDOCZNY </font>';
} 
else
{
    $STAT_W='<font style="color:red;font-weight:bold;"> NIEWIDOCZNY</font>';
}
						echo '<p class="P_NG_INF">Obóz nr <span class="S_MAIN_NG">[</span>'.$ID.'<span class="S_MAIN_NG">]</span> ?</p>';
						echo '<div class="DIV_OPCJ">';
						echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
						echo '<a href="'.PAGE_URL.'&IDW='.$IDW.'&IDS='.$IDS.'&ID='.$ID.'&VDK='.$rek.'#ID'.$ID.'"><span class="s_button">TAK</span></a>';
						echo '</p>';
						echo '<p style="font-size:14px;text-align:left;margin-left:20px;">(Aktualny status - '.$STAT_W.')</p>';
						echo '</div>';
						if (isset($_GET["VDK"]))
						{
                                                    if ($_GET["VDK"]==0)
                                                    {
									$WIDOK=1;
									$STAT_W ='<font style="color:blue"> WIDOCZNA </font>';
                                                    } 
                                                    else   {
								$WIDOK=0;
								$STAT_W='<font style="color:red"> NIEWIDOCZNA</font>';
								}
												
												
							$db->query("UPDATE `OBOZ` SET `WSK_V`=".$WIDOK." WHERE `OBOZ`.`ID`='".$ID."'");
												
												
                                                        echo '<p class="P_BACK">Status twojejego obozu został zmieniony na - '.$STAT_W.'<br/>';
							echo '<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
							echo '<span class="S_BACK2">MENU - OBOZY.</span></p>';
							echo'<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"\', 1500);}window.onload=init;</script>';
						}
						echo "</div>";

