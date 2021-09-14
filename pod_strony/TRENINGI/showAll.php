<?php  if(!defined('ACT_PERM')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<p CLASS="P_MAIN_CEL">'.$frameTitle.'</p>';
$checkVar->checkOnlyGet($IDS,'IDS');
/* COUNT PAGES */
require(DR.'/class/page.php');
$page=NEW page();
$page->setDbRec("select COUNT(*) FROM `TRENING` WHERE `WSK_U`=0");
$page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
$page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
$IDS=$page->getIDS();
echo $page->getPageLink('s');
/* END COUNT PAGES */

echo '<center><table CLASS="TAB_TRENING">';
$query = $db->query("select * from TRENING WHERE WSK_U=0 order by id");
while($rekord = mysqli_fetch_array($query))
{
    $css_rek=26;									
    $font_weight=array('','','','');
    $font_style=array('','','','');
    $text_dec=array('','','','');
    $tab_css=array($font_weight,$font_style,$text_dec);
    $kom_v="";
    if ($rekord[17]==2) { // Sprawdzamy wersje
										 $kom_v="[v.2]";
														for($i_tab=0;$i_tab<4;$i_tab++){
															list($css[0],$css[1], $css[2]) = explode('|', $rekord[$css_rek]);
															for ($tab_css_i=0;$tab_css_i<3;$tab_css_i++){
																if($tab_css_i==0) {
																					$css_wart=' font-weight:bold; ';
																} else if ($tab_css_i==1){
																							$css_wart=' font-style:italic; ';
																} else if ($tab_css_i==2){
																	$css_wart=' text-decoration: underline; ';
																} ;
																
																if ($css[$tab_css_i]>0) $tab_css[$tab_css_i][$i_tab]=$css_wart; else $tab_css[$tab_css_i][$i_tab]="";
															};
															
															$css_rek++;
															
														}
									}
									if ($rekord[16]==1){$STAT_W="<span class=\"S_STAT\">TAK</span>";} else {$STAT_W="<span class=\"S_STAT_N\">NIE</span>";};
									echo '<tr CLASS="TAB_TRENING"><td colspan="4">';
									echo '<span class="S_NG_MAIN">ID '.$kom_v.' : ';
									echo '<span class="S_NG_INF">'.$rekord[0].'</span></span>';
                                                                        if(array_key_exists(4, $_SESSION['perm'][$IDM]))
                                                                        {
                                                                            echo '<a href="'.PAGE_URL.'&IDW=4&ID='.$rekord[0].'"><span class="s_button" style="margin-top:5px;">USTAW</span></a>';
									} 
                                                                        else
                                                                        {
                                                                            echo '<button class="s_button_off">USTAW</button>';
									}
									echo '<span class="S_WIDOK_STAT"> Widoczny : '.$STAT_W.' </span></span></td></tr>';
									echo '<tr CLASS="TAB_TRENING" style="background:'.$rekord[5].';">';
									echo '<td CLASS="TAB_TRENING" width="100px" style="text-align:'.$rekord[18].';"><span style="font-size:'.$rekord[6].'px; color:'.$rekord[10].'; '.$tab_css[0][0].$tab_css[1][0].$tab_css[2][0].'">'.$rekord[1].'</span></td>'; //'.$rekord[18].' '.$tab_css[0][0].$tab_css[1][0].$tab_css[2][0].'
									echo '<td CLASS="TAB_TRENING" width="150px" style="text-align:'.$rekord[20].';"><span style="font-size:'.$rekord[7].'px; color:'.$rekord[10].'; '.$tab_css[0][1].$tab_css[1][1].$tab_css[2][1].'">'.$rekord[2].'</span></td>'; // '.$rekord[20].' '.$tab_css[0][1].$tab_css[1][1].$tab_css[2][1].' 
									echo '<td CLASS="TAB_TRENING" width="220px" style="text-align:'.$rekord[22].';"><span style="font-size:'.$rekord[8].'px; color:'.$rekord[10].'; '.$tab_css[0][2].$tab_css[1][2].$tab_css[2][2].'">'.$rekord[3].'</span></td>'; //'.$rekord[22].' '.$tab_css[0][2].$tab_css[1][2].$tab_css[2][2].' 
									if ($rekord[17]==1) {$center_s="<center>"; $style_alig=""; $center_e="</center>"; } else {$center_s=""; $center_e="";  $style_alig="style=\"text-align: $rekord[24] ;\"";};
									echo '<td CLASS="TAB_TRENING" width="220px" '.$style_alig.'>'.$center_s.'<span style="font-size:'.$rekord[9].'px; color:'.$rekord[10].'; '.$tab_css[0][3].$tab_css[1][3].$tab_css[2][3].' ">'.$rekord[4].'</span>'.$center_e.'</td></tr>'; // '.$rekord[24].' '.$tab_css[0][3].$tab_css[1][3].$tab_css[2][3].' 
									echo "<tr><td colspan=\"4\">";
                                                                        if(array_key_exists(2, $_SESSION['perm'][$IDM]))
                                                                        {
                                                                            echo '<a href="'.PAGE_URL.'&IDW=2&ID='.$rekord[0].'"><span class="s_tbutton" >Edytuj</span></a>';
									} 
                                                                        else
                                                                        {
                                                                            echo '<button class="s_button_off">Edytuj</button>';
									}
                                                                        if(array_key_exists(3, $_SESSION['perm'][$IDM]))
                                                                        {
                                                                            echo '<a href="'.PAGE_URL.'&IDW=3&ID='.$rekord[0].'"><span class="s_tbutton">Usuń</span></a>';
									} 
                                                                        else
                                                                        {
                                                                            echo '<button class="s_button_off">Edytuj</button>';
									}
									echo "</td></tr>";
									}
								echo "</table></center>";
?>
</div>
<?php
echo $page->getPageLink('e');
?>