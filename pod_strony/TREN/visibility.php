<?php if(!defined('TREN46')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Anuluj</p></a>';
echo '<p class="P_MAIN">Zmiana statusu widoczności Trenera : </p>';

$rekord = mysqli_fetch_row($db->query("SELECT `WSK_V` FROM `TREN` WHERE `ID`='".$ID."'"));

if (intval($rekord[0])===1)
{
    $STAT_W ='<font style="color:blue;font-weight:bold;"> WIDOCZNY </font>';
} 
else
{
    $STAT_W='<font style="color:red;font-weight:bold;"> NIEWIDOCZNY</font>';
}
						echo '<p class="P_NG_INF">Trener nr <span class="S_MAIN_NG">[</span>'.$ID.'<span class="S_MAIN_NG">]</span> ?</p>';
						echo '<div class="DIV_OPCJ">';
						echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
						echo '<a href="'.PAGE_URL.'&IDW=6&ID='.$ID.'&VDK=T&IDS='.$IDS.'"><span class="s_button">TAK</span></a>';
						echo '</p></div>';
						echo '<p style="font-size:14px;text-align:left;margin-left:20px;">(Aktualny status - '.$STAT_W.')</p>';
						if (isset($_GET["VDK"]))
						{
                                                    $rekord = mysqli_fetch_row($db->query("SELECT WSK_V FROM TREN WHERE ID='".$ID."'"));
                                                    if ($rekord[0]==0)
                                                    {
							$WIDOK=1;
							$STAT_W ='<font style="color:blue"> WIDOCZNY </font>';
                                                    } 
                                                    else{
														$WIDOK=0;
														$STAT_W='<font style="color:red"> NIEWIDOCZNY</font>';
						}
								
$db->query("UPDATE `TREN` SET `WSK_V`=$WIDOK WHERE `TREN`.`ID`='".$ID."'");
echo '<p class="P_BACK">Status Trenera został zmieniony na - '.$STAT_W.'<br/>';
echo '<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
echo '<span class="S_BACK2">MENU - Trenerzy.</span></p>';
echo '<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"\', 1500);}window.onload=init;</script>';
						}
						echo "</div>";