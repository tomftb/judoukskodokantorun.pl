<?php
if(!defined('TREN40')) { exit('NO PERMISSION');}

/* COUNT PAGES */
require(DR.'/class/page.php');
$page=NEW page();
$page->setDbRec("select COUNT(*) FROM `TREN` WHERE `WSK_U`=0");
$page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
$page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
$IDS=$page->getIDS();
echo $page->getPageLink('s');
/* END COUNT PAGES */

echo "<p class=\"P_MAIN\">Wszyscy Trenerzy : </p>";
$SEL_TREN_W = $db->query("SELECT ID,IMIE_N,INFO,K_IMIE_N,K_INFO,R_IMIE_N,R_INFO,DAT_UTW,ILOZD,WSK_V,P_IMI_N_WART,P_INFO_WART,CSS_IMI_N,CSS_INFO FROM `TREN` where WSK_U=0 order by ID desc");
while($rekord = mysqli_fetch_array($SEL_TREN_W))
{
								if ($rekord[9]==1){$STAT_W="<span class=\"S_STAT\">TAK</span>";} else {$STAT_W="<span class=\"S_STAT_N\">NIE</span>";};
								$font_w_dane=array(0,0);
								$font_s_dane=array(0,0);
								$text_d_dane=array(0,0);
								for ($i=0;$i<2;$i++){
													if ($i==0) $rek_ccs=$rekord[12]; else if ($i==1) $rek_ccs=$rekord[13];
													list($css_b_dane,$css_i_dane,$css_u_dane) = explode('|', $rek_ccs);
													if ($css_b_dane==0) $font_w_dane[$i]='font-weight:normal;'; else $font_w_dane[$i]='font-weight:bold;';
													if ($css_i_dane==0) $font_s_dane[$i]='font-style: normal;'; else $font_s_dane[$i]='font-style:italic;';
													if ($css_u_dane==0) $text_d_dane[$i]=''; else $text_d_dane[$i]='text-decoration: underline;';
													};
								echo '<div class="DIV_MAIN" ID="ID'.$rekord[0].'">';
								echo '<div class="DIV_INFO">'; 
								echo '<span class="S_NG_MAIN">ID Trenera : <span class="S_NG_INF">'.$rekord[0].' </span>';
								echo ' Data utworzenia : <span class="S_NG_INF">'.$rekord[7].'</span></span>';
								echo '<span style="float:right ;text-align:right; color: #0099FF ;margin-top:10px;"> Widoczny : '.$STAT_W;
								if (array_key_exists(6, $_SESSION['perm'][$IDM]))
                                                                {
                                                                    echo '<a href="'.PAGE_URL.'&IDW=6&IDS='.$IDS.'&ID='.$rekord[0].'">';
                                                                    echo '<span class="s_button" style="margin-right:10px;">USTAW</span></a></span>';
								} else {echo '<button class="s_button_off">USTAW</button>';};
								echo '</div>';
								// $rekord[0]; ID zawodnika w bazie  $rekord[8]; Ilość zdjęć
								if ($rekord[8]!=0)
								{
									$ZAP_TREN_IMG = $db->query("select NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM TREN_IMG where WSK_U=0 AND ID_TREN='$rekord[0]'");
									while($REK_IMG = mysqli_fetch_array($ZAP_TREN_IMG))
									{
										/**/
																					//if ($rekord[8]==1) $pozycja="center"; else $pozycja="left";
																					echo '<div class="DIV_IMG"><center>';
																					echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/trener/$REK_IMG[0]',' $rekord[1] ',$REK_IMG[1],$REK_IMG[2])\">";
																					echo '<img src="'.APP_URL.'/zdjecia/trener/'.$REK_IMG[3].'" alt="'.$REK_IMG[0].'" style="width:'.$REK_IMG[4].'px; height:'.$REK_IMG[5].'px; border:0px;" />';
																					echo '</a></center></div>';
																					
									}
								};
								echo '<div class="DIV_DANE">';
								echo '<p class="P_DANE">Opis : </p><p style="font-face: Times New Roman ;margin:5px;color:'.$rekord[3].';font-size:'.$rekord[5].'px; text-align:'.$rekord[10].';'.$font_w_dane[0].$font_s_dane[0].$text_d_dane[0].'">'.$rekord[1].'</p>';
								//echo '<p class="P_DANE">Imie, Nazwisko : // Opis : </p><p style="font-face: Times New Roman ;margin:5px;color:'.$rekord[4].';font-size:'.$rekord[6].'px; text-align:'.$rekord[11].';'.$font_w_dane[1].$font_s_dane[1].$text_d_dane[1].'">'.$rekord[2].'</p>';
								echo '</div>';	
								echo '<div class="DIV_CLEAR"></div>';
								echo '<div class="DIV_OPCJ">';
                                                                if (array_key_exists(3, $_SESSION['perm'][$IDM]))
								{
									echo '<a href="'.PAGE_URL.'&IDW=3&IDS='.$IDS.'&ID='.$rekord[0].'"><span class="s_button" style="margin-right:10px;">Usuń</span></a>';
								}
								else
								{
									echo '<button class="s_button_off" style="margin-right:10px;"> Usuń</button>';
								}
								 if (array_key_exists(2, $_SESSION['perm'][$IDM]))
                                                                {
                                                                    echo '<a href="'.PAGE_URL.'&IDW=2&IDS='.$IDS.'&ID='.$rekord[0].'"><span class="s_button">Edytuj</span></a>';
								}
                                                                else
                                                                {
                                                                    echo '<button class="s_button_off"> Edytuj</button>';
								}
								echo '</div>';
								echo "</div>";
								}
								echo $page->getPageLink('s');