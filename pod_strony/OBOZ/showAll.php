<?php
if(!defined('OBOZ_50')) { exit('NO PERMISSION'); }
echo "asdadas";
IF ($_GET["IDW"]==4)
                                { 
                                    $nagl_IDW="(<font color=\"#0099FF\">GALERIE</font>)";
                                    $nagl_dziennik=" (GALERIE)";
                                    $SEL_SQL="TYP='g'";
				}
				else if ($_GET["IDW"]==5)
                                {
                                    $nagl_IDW="(<font color=\"#0099FF\">FILMY</font>)";
                                    $nagl_dziennik=" (FILMY)";
                                    $SEL_SQL="TYP='f'";
				}
                                else if($_GET["IDW"]==9)
                                {
                                    $nagl_IDW="(<font color=\"#0099FF\">Google Drive</font>)";
                                    $nagl_dziennik=" (Google Drive)";
                                    $SEL_SQL="TYP='d'";
                                }
                                else
                                {
                                    $nagl_IDW="";
                                    $nagl_dziennik="";
                                    $SEL_SQL="TYP='g' OR TYP='f' OR TYP='d'";
				}
/* COUNT PAGES */
require_once(DR.'/class/page.php');
$page=NEW page();
$page->setDbRec("select COUNT(*) FROM `OBOZ` WHERE (".$SEL_SQL.") AND `WSK_U`=0");
$page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
$page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
$IDS=$page->getIDS();

/* END COUNT PAGES */
//--------------------------------------------------------PETLA-PODZIAL-STRON------------------------------------------------------------------
				$tabela="OBOZ";
				$strona="cel_OBOZ";
				echo '<center><p style="font-weight:bold;font-size:34px;color:black;">Wszystkie Obozy '.$nagl_IDW.' :</p></center>';
                                echo $page->getPageLink('s');
				$SEL_OBOZ = $db->query("SELECT * FROM `OBOZ` WHERE (".$SEL_SQL.") AND `WSK_U`=0 ORDER BY ID desc LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
				while($rekord = mysqli_fetch_array($SEL_OBOZ))
                                {
                                    if ($rekord[10]==1){$STAT_W="<span class=\"S_STAT\">TAK</span>";} else {$STAT_W="<span class=\"S_STAT_N\">NIE</span>";};
                                    //echo "REKORD19-".$rekord[18]."</br>";
                                     if ($rekord[18]==2||$rekord[18]==1)
                                     {
					$JAVA_IMG_SOURCE=APP_URL."/zdjecia/obozy/".$rekord[6]."/".$rekord[2];
					$JAVA_WIDTH=$rekord[16];
					$JAVA_HEIGHT=$rekord[17];
					$IMG_WIDTH=$rekord[14];
					$IMG_HEIGHT=$rekord[15];
                                    }
                                    else
                                    {
					$JAVA_IMG_SOURCE=APP_URL."/zdjecia/obozy/".$rekord[2];
                                        $JAVA_WIDTH=$rekord[14];
					$JAVA_HEIGHT=$rekord[15];
					$IMG_WIDTH="250";
					$IMG_HEIGHT="235";
                                    };
                                    if ($rekord[13]=="g") {$info="Galeria";} else if ($rekord[13]=="f") {$info="Film";} else if($rekord[13]=="d") { $info='Google Drive';} else $info="BŁĄD";
                                    echo '<div class="DIV_MAIN" ID="ID'.$rekord[0].'">';
                                    echo '<div class="DIV_INFO">';
                                    echo '<span class="S_NG_MAIN">ID Obozu: <span class="S_NG_INF">'.$rekord[0].'</span>(<span class="S_NG_GF">'.$info.'</span>) ';
                                    echo 'Data utworzenia : <span class="S_NG_INF">'.$rekord[11].'</span></span>';
                                    echo '<span class="S_WIDOK_STAT">Widoczny : '.$STAT_W;
                                    
                                    if(array_key_exists(6, $_SESSION['perm'][$IDM]))
                                    {
					echo '<a href="'.PAGE_URL.'&IDW=6&IDS='.$IDS.'&ID='.$rekord[0].'"><span class="s_button" style="margin-right:10px;"> Ustaw</span></a></span>';
                                    }
                                    else
                                    {
                                        echo '<button class="s_button_off" style="margin-right:10px;"> Ustaw</button>';
                                    };
                                    echo '</div>';
                                    echo '<div class="DIV_IMG"><center>';
                                    if ($rekord[13]=='g')
                                    {
                                        $TYP_TYT="galerię";
					//----------------------------------Galeria------------------------------------------
					 if(array_key_exists(98, $_SESSION['perm'][$IDM]))
                                        {
                                            echo '<A HREF="javascript:displayWindow(&#39;'.$JAVA_IMG_SOURCE.'&#39;,'.$JAVA_WIDTH.','.$JAVA_HEIGHT.')">';
                                            echo '<img src="'.APP_URL.'/zdjecia/obozy/'.$rekord[6].'/'.$rekord[3].'"  style="width:'.$IMG_WIDTH.'px; height:'.$IMG_HEIGHT.'px; border:0px;" />';
                                            echo "</a>";
					}
					else
                                        {
                                            echo '<img src="'.APP_URL.'/zdjecia/obozy/'.$rekord[6].'/'.$rekord[3].'"  style="width:'.$IMG_WIDTH.'px; height:'.$IMG_HEIGHT.'px; border:0px;" />';
					};
                                    } 
                                    else if($rekord[13]=='d')
                                    {
                                        echo '<A TARGET="_blank" HREF="'.$rekord[20].'">';
                                        echo '<img src="'.APP_URL.'/zdjecia/obozy/'.$rekord[6].'/'.$rekord[3].'"  style="width:'.$IMG_WIDTH.'px; height:'.$IMG_HEIGHT.'px; border:0px;" />';
                                        echo "</a>";
                                        $TYP_TYT="link Google Drive";
                                    }
                                    else
                                    {
                                        //----------------------------------Film------------------------------------------
					if(array_key_exists(97, $_SESSION['perm'][$IDM]))
                                        {
                                            $TYP_TYT="film";
                                            echo $rekord[1].'</br>';
					}
					else
                                        {
                                            echo '<p class="P_ERR_MAIN">BRAK uprawnienia - Uruchom film </p>';
					};
                                    }
                                    echo '<p class="P_INFO_IMG">(kliknij na zdjęcie aby uruchomić '.$TYP_TYT.')</p></center>';
                                    echo '<p class="P_DANE">Tytul : <span class="S_INF_TYT">'.$rekord[5].'</span></p></div>';
                                    echo '<div class="DIV_OPCJ">';
                                    if(array_key_exists(3, $_SESSION['perm'][$IDM]))
                                   
                                    {
                                        echo '<a  href="'.PAGE_URL.'&IDW=3&IDS='.$IDS.'&ID='.$rekord[0].'"><span class="s_button" style="margin-right:10px;">Usuń</span></a>';
                                    }
                                    else
                                    {
                                        echo '<button class="s_button_off" style="margin-right:10px;"> Usuń</button>';
                                        
                                    };
                                    if(array_key_exists(2, $_SESSION['perm'][$IDM]))
                                    {
                                        if($rekord[13]=='g')
                                        {
                                            echo '<a  href="'.PAGE_URL.'&IDW=2&ID='.$rekord[0].'&IDS='.$IDS.'"><span class="s_button">Edytuj</span></a>';
                                        }
                                        else if($rekord[13]=='d')
                                        {
                                            echo '<a  href="add_google_url_OBOZ.php?IDW='.$_GET["IDW"].'&ID='.$rekord[0].'&IDS='.$IDS.'&IDM='.$IDM.'"><span class="s_button">Edytuj</span></a>';
                                                    
                                        }
                                        else
                                        {
                                            // MOVIE
                                        }
                                        
                                    }
                                    else
                                    {
                                        echo '<button class="s_button_off"> Edytuj</button>';
                                    };
                                    //-----NEW-Pokaz-wszystkie-zdjecia/obozy
                                    if (array_key_exists(8, $_SESSION['perm'][$IDM]) && $rekord[13]=='g')
                                    {
                                        echo '<a  href="cel_OBOZ.php?IDW=8&IDWB='.$_GET["IDW"].'&IDE='.$rekord[0].'&IDM='.$_GET["IDM"].'"><span class="s_button">Zdjęcia</span></a>';
                                        //-----KONIEC-NEW-Pokaz-wszystkie-zdjecia/obozy
                                    } else {if($rekord[13]=='g') echo '<button class="s_button_off"> Zdjęcia</button>';};
                                    echo '</div></div>';
				}
				echo $page->getPageLink('e');

