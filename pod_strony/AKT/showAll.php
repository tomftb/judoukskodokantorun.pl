<?php
if(!defined('AKT20')) { exit('NO PERMISSION');}
echo '<p CLASS="P_MAIN_CEL">Wszystkie Artykuły : </p>';

                           
/* COUNT PAGES */
    require(DR.'/class/page.php');
    $page=NEW page();
    $page->setDbRec("select COUNT(*) FROM `NEWS` WHERE `WSK_U`=0 AND `WSK_V`=1");
    $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
    $page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
    $IDS=$page->getIDS();
/* END COUNT PAGES */

$SEL_NEWS = $db->query("SELECT ID,TYTUL,TRESC,ADRES,K_TYTUL,R_TYTUL,K_TRESC,R_TRESC,DAT_UTW,ILOZD,WSK_V,P_TYT,P_TRE,CSS_TYT,CSS_TRE,VER FROM NEWS WHERE WSK_U=0 ORDER BY ID DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
echo $page->getPageLink('s'); 
while($rekord = mysqli_fetch_array($SEL_NEWS))
                            {
				if($rekord[15]==4)
				{
                                    $rekord[1]=htmlspecialchars_decode($rekord[1]);
                                    $rekord[2]=htmlspecialchars_decode($rekord[2]);
				}
				if ($rekord[10]==1)
                                {
                                    $STAT_W="<span class=\"S_STAT\">TAK</span>";
                                }
                                else
                                {
                                    $STAT_W="<span class=\"S_STAT_N\">NIE</span>";
                                };
				$css_rek=13;
				$font_weight=array("font-weight:normal;","font-weight:normal;");
				$font_style=array("font-style: normal;","font-style: normal;");
				$text_dec=array("","");
				for ($i=0; $i<2;$i++)
                                {
                                    list($css_tyt_b,$css_tyt_i,$css_tyt_u) = explode('|', $rekord[$css_rek]);
                                    if ($css_tyt_b==1) $font_weight[$i]='font-weight:bold;';
                                    if ($css_tyt_i==1) $font_style[$i]='font-style:italic;';
                                    if ($css_tyt_u==1) $text_dec[$i]='text-decoration: underline;';
                                    $css_rek++;
				};
				echo '<DIV class="DIV_MAIN" ID="ID'.$rekord[0].'">';
				echo '<DIV class="DIV_INFO">';
				echo '<span class="S_NG_MAIN">ID: <span class="S_NG_INF">'.$rekord[0].'</span>';
				echo ' Data utworzenia : <span class="S_NG_INF">'.$rekord[8].'</span></span>';
				echo '<span class="S_WIDOK_STAT" >Widoczny : '.$STAT_W;
                                if(array_key_exists(6, $_SESSION['perm'][$IDM]))
                                {
                                    echo '<a href="'.PAGE_URL.'&IDW=6&IDS='.$IDS.'&ID='.$rekord[0].'"><span class="s_button" style="margin-right:10px;"> Ustaw</span></a></span>';
                                }
                                else
                                {
                                    echo '<button class="s_button_off" style="margin-right:10px;"> Ustaw</button>';
                                }
				echo '</DIV><DIV class="DIV_TRESC">';		
				echo '<p style="color:'.$rekord[4].';font-size:'.$rekord[5].'px; text-align:'.$rekord[11].';'. $font_weight[0].$font_style[0].$text_dec[0].'">'.$rekord[1].'</p>';
				echo '<p style="font-face: Times New Roman ;margin:10px;color:'.$rekord[6].';font-size:'.$rekord[7].'px; text-align:'.$rekord[12].';'. $font_weight[1].$font_style[1].$text_dec[1].'">'.$rekord[2].'</p>';
				echo '<center>';
				echo '</DIV><DIV class="DIV_ZDJ">';
//----------------------------------------------------------------------Wyświetlanie zdjęć------------------------------------------------------------
				if ($rekord[9]!='')
                                {
                                    if ($rekord[9]==1) {$pozycja="center";} 
                                    else {$pozycja="left";}; 				
                                    $licz=0;
                                    $HEIGHT_DIV_IMG=0;
                                    $margin_top=0;
                                    $zap_img = $db->query("select KATALOG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM NEWS_IMG WHERE WSK_U=0 AND ID_NEWS='$rekord[0]'");
                                    while($rek_img = mysqli_fetch_array($zap_img))
                                    {
                                    	if (($licz==1) && ($HEIGHT_DIV_IMG>$rek_img[6]))
                                        {
                                            $margin_top=($HEIGHT_DIV_IMG-$rek_img[6])/2;
                                            //echo "licze margines : $margin_top </br>";
					}
					if($licz==2)
                                        {
                                            $HEIGHT_DIV_IMG=0;
                                            $margin_top=0;
                                            $licz=0;
					};
					if ($HEIGHT_DIV_IMG<$rek_img[6]) $HEIGHT_DIV_IMG=$rek_img[6];
					echo '<DIV CLASS="DIV_IMG" style="float:'.$pozycja.';height='.$HEIGHT_DIV_IMG.'px">';
					echo '<a HREF="javascript:displayWindow(&#39;'.APP_URL.'/zdjecia/artykul/'.$rek_img[0].'/'.$rek_img[1].'&#39;,'.$rek_img[2].','.$rek_img[3].')">';
					echo '<img src="'.APP_URL.'/zdjecia/artykul/'.$rek_img[0].'/'.$rek_img[4].'" alt="Zdjecie" style="width:'.$rek_img[5].'px; height:'.$rek_img[6].'px; border:0px; margin-top:'.$margin_top.'px; margin-bottom:'.$margin_top.'px;" />';
					echo '</a></DIV>';
					$licz++;						
                                    }
                                };
								echo '<center>'.$rekord[3].'</center>';
//----------------------------------------------------------------------KONIEC-Wyświetlanie zdjęć------------------------------------------------------------								
								echo '</DIV><DIV class="DIV_CLEAR"></DIV><DIV class="DIV_OPCJ">';
								if ($_SESSION["id_user"]==1){
															//echo "MAX HEIGHT : ".$max_height."</br>";
								};
								$max_height=0;
                                                                if(array_key_exists(3, $_SESSION['perm'][$IDM]))
                                   
                                                                {
                                                                    echo '<a  href="'.PAGE_URL.'&IDW=3&IDS='.$IDS.'&ID='.$rekord[0].'"><span class="s_button" >Usuń</span></a>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<button class="s_button_off" style="margin-right:10px;"> Usuń</button>';

                                                                }
								if(array_key_exists(2, $_SESSION['perm'][$IDM]))
                                   
                                                                {
                                                                    echo '<a  href="'.PAGE_URL.'&IDW=2&IDS='.$IDS.'&ID='.$rekord[0].'"><span class="s_button" style="margin-right:10px;">Edytuj</span></a>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<button class="s_button_off">Edytuj</button>';

                                                                }
								echo '</DIV></DIV>';

								}
								//----------------OLD-NEWS-----------------------------------------
								IF ($IDS===1){
								if ($_SESSION["id_user"]==1) {echo "Starsze wpisy</br>";};	
								$query = $db->query("select * from NEWS_OLD where WSK_U=0 order by ID desc");
								if ($_SESSION['id_user']==1){
									while($rekord = mysqli_fetch_array($query)){
																				echo '<DIV style="border:1px; border-style:solid; border-radius:10px; margin:5px; border:1px; border-color:black; border-style:solid; width:710px;">';
																				echo '<p style="text-align:right; color: #0099FF ;">';
																				echo 'Data utworzenia : <span style="color: black; font-size: 18px; font-weight: bold;">'.$rekord[10].'</span>';
																				echo '<br/>ID wiadomości: <span style="color: black; font-size: 18px; font-weight: bold;">'.$rekord[0].'</span>';
																				echo '</p><center><p style="color:'.$rekord[12].'; font-size:'.$rekord[13].'">'.$rekord[1].'</p>';
																				echo '</center><pre><p style="font-face: Times New Roman ;color:'.$rekord[14].';font-size:'.$rekord[15].'; text-align:left ">'.$rekord[3].'</p>';
																				echo '</pre><center>'.$rekord[4].$rekord[5].$rekord[6].'<BR/><BR/>'.$rekord[7].'</center></DIV>';
									};
								} 
								else {
										while($rekord = mysqli_fetch_array($query)){
																				echo '<DIV style="border:1px; border-style:solid; border-radius:10px; margin:5px; border:1px; border-color:black; border-style:solid; width:710px;">';
																				echo '<p style="text-align:right; color: #0099FF ;">';
																				echo 'Numer wiadomości: <span style="color: black; font-size: 18px; font-weight: bold;">'.$rekord[0].'</span>';
																				echo '</p><center><p style="color:'.$rekord[12].'; font-size:'.$rekord[13].';">'.$rekord[1].'</p>';
																				echo '</center><pre><p style="font-face: Times New Roman ;color:'.$rekord[14].';font-size:'.$rekord[15].'; text-align:left">'.$rekord[3].'</p>';
																				echo '</pre><center>'.$rekord[4].$rekord[5].$rekord[6].'<BR/><BR/>'.$rekord[7].'</center></DIV>';
										}
								}
								}
								//----------------KONIEC-OLD-NEWS-----------------------------------------
echo "</center>";
echo $page->getPageLink('e');
