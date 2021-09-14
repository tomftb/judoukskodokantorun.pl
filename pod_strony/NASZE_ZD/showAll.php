<?php if(!defined('NASZE_ZD_60')) { exit('NO PERMISSION');} ?>
<p CLASS="P_MAIN_CEL">Wszystkie zdjęcia : </p>
<DIV CLASS="DIV_ZAKL">
<?php
/* COUNT PAGES */
    require(DR.'/class/page.php');
    $page=NEW page();
    $page->setDbRec("select COUNT(*) FROM `OUR_PHOTO` WHERE `WSK_U`=0");
    $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
    $page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
    $IDS=$page->getIDS();
/* END COUNT PAGES */
$ZAP_NEWS=$db->query("select ID,NAZWA_I,DAT_UTW,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT,WSK_V from `OUR_PHOTO` where WSK_U=0 ORDER BY ID DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
echo $page->getPageLink('s');
while($rek_img = mysqli_fetch_array($ZAP_NEWS))
{
																			if ($rek_img[8]==1){
																								$STAT_W="<span class=\"S_STAT\">TAK </span>";
																			} 
																			else {
																					$STAT_W="<span class=\"S_STAT_N\">NIE </span>";
																			};
										echo '<DIV CLASS="DIV_MAIN_IMG" ID="ID'.$rek_img[0].'">';
										echo '<DIV CLASS="DIV_NZ_NAGL">';
										echo '<span class="S_NG_MAIN">ID ZDJĘCIA: ';
										echo '<span class="S_NG_INF">'.$rek_img[0].' </span></span>';
										echo '<span class="S_WIDOK_STAT"> Widoczny : '.$STAT_W;
										if(array_key_exists(6, $_SESSION['perm'][$IDM]))
                                                                                {
                                                                                    echo '<a href="'.PAGE_URL.'&IDW=6&IDS='.$IDS.'&ID='.$rek_img[0].'"><span class="s_button" style="margin-right:10px;"> USTAW</span></a></span><br/>';							
										} 
										else
                                                                                {
                                                                                    echo '<button class="s_button_off">USTAW</button>';
										}
										echo '<span CLASS="S_DAT_UTW"> Data utworzenia : ';
										echo '<span class="S_NG_INF">'.$rek_img[2].'</span></span>';
										echo "</div>"; 
										echo '<DIV CLASS="DIV_NZ_IMG">'; // DIV_NZ_NAGL
										echo '<center>'; 
										if($rek_img[7]<330) $margin_top=(330-$rek_img[7])/2; else $margin_top=5;
										echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/nasze/$rek_img[1]','$rek_img[3]','$rek_img[4]')\">";
										echo '<img src="'.APP_URL.'/zdjecia/nasze/'.$rek_img[5].'" alt="Zdjecie" style="width:'.$rek_img[6].'px; height:'.$rek_img[7].'px; border:0px;margin-top:'.$margin_top.'px" />';
										echo '</a></center>';
										echo "</DIV>"; // DIV_NZ_IMG
										echo '<DIV CLASS="DIV_NZ_OPCJ">';
										if(array_key_exists(3, $_SESSION['perm'][$IDM]))
                                                                                {
                                                                                    echo '<a href="'.PAGE_URL.'&IDW=3&IDS='.$IDS.'&ID='.$rek_img[0].'"><span class="s_button" style="margin-right:10px;">Usuń</span></a>';
										}
                                                                                else
                                                                                {
                                                                                    echo '<button class="s_button_off" style="margin-right:10px;"> Usuń</button>';
										}
										if(array_key_exists(2, $_SESSION['perm'][$IDM]))
                                                                                {
                                                                                    echo '<a href="'.PAGE_URL.'&IDW=2&IDS='.$IDS.'&ID='.$rek_img[0].'"><span class="s_button" >Edytuj</span></a>';
										}
                                                                                else
                                                                                {
                                                                                    echo '<button class="s_button_off"> Edytuj</button>';
										}
										echo "</DIV>"; // DIV_NZ_OPCJ
										echo "</DIV>"; // DIV_MAIN_IMG
									}   
									
echo "<DIV class=\"DIV_CLEAR\"></div></div>"; // DIV_ZAKL
echo $page->getPageLink('e');
							
								

