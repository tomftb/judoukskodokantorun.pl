<?php
if(!defined('PAGE_URL')) { exit('NO PERMISSION');}
echo '<p CLASS="P_MAIN_CEL">'.$frameTitle.'</p>';

/* COUNT PAGES */
require(DR.'/class/page.php');
$page=NEW page();
$page->setDbRec("select COUNT(*) FROM `KLASA` WHERE `WSK_U`=0 AND `WSK_V`=1");
$page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
$page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
$IDS=$page->getIDS();
/* END COUNT PAGES */

$SEL_KL_W = $db->query("select ID,HTML,NAZW_I,OPIS,KATALOG,XML,WSK_V,DAT_UTW,TYP,WIDTH,HEIGHT,MAX_W,MAX_H from KLASA WHERE WSK_U=0 ORDER BY ID DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());		

echo $page->getPageLink('s');

while($rekord = mysqli_fetch_array($SEL_KL_W))
{
    if ($rekord[6]==1)
    {
        $STAT_W="<span class=\"S_STAT\">TAK</span>";
    }
    else
    {
        $STAT_W="<span class=\"S_STAT_N\">NIE</span>";
    }
								echo '<div class="DIV_MAIN">';
								echo '<div class="DIV_INFO">'; 
								echo '<span class="S_NG_MAIN">ID Klasy : <span class="S_NG_INF">'.$rekord[0].' </span>';
								echo ' Data utworzenia : <span class="S_NG_INF">'.$rekord[7].'</span></span>';
								echo '<span style="float:right ;text-align:right; color: #0099FF ;margin-top:10px;"> Widoczny : '.$STAT_W;
								/* CHECK PERMISSION */
                                                                if(array_key_exists(6, $_SESSION['perm'][$IDM]))
                                                                {
                                                                    echo '<a href="'.PAGE_URL.'&IDW=6&ID='.$rekord[0].'"><span class="s_button"> USTAW</span></a></span>';
								}
                                                                else
                                                                {
                                                                    echo '<button class="s_button_off">USTAW</button>';
                                                                }
								echo '</div>';
								echo '<div class="DIV_IMG"><center>';
								echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/klasa_sp/$rekord[4]/$rekord[1]',$rekord[11],$rekord[12])\">";
								echo '<img src="'.APP_URL.'/zdjecia/klasa_sp/'.$rekord[4].'/'.$rekord[2].'" alt="'.$rekord[2].'"  style="width:'.$rekord[9].'px; height:'.$rekord[10].'px; border:0px;" />';
								echo '</a><p class="P_INFO_IMG">(kliknij na zdjęcie aby uruchomić galerię)</p></center><p class="P_DANE">Rocznik : <span style="font-weight:bold;font-size:14px;color:black;font-style:normal;">'.$rekord[3].'</span></p></div>';
								echo '<div class="DIV_OPCJ">';
                                                                if(array_key_exists(3, $_SESSION['perm'][$IDM]))
								
                                                                {
                                                                    echo '<a href="'.PAGE_URL.'&IDW=3&ID='.$rekord[0].'"><span class="s_button">Usuń</span></a>';
								}
                                                                else
                                                                {
                                                                    echo '<button class="s_button_off">Usuń</button>';
                                                                }
								if(array_key_exists(2, $_SESSION['perm'][$IDM]))
                                                                {
                                                                    echo '<a href="'.PAGE_URL.'&IDW=2&ID='.$rekord[0].'"><span class="s_button">Edytuj</span></a>';
								}
                                                                else
                                                                {
                                                                    echo '<button class="s_button_off">Edytuj</button>';
                                                                }
								echo '</div>';
								echo "</div>";
}
echo $page->getPageLink('e');
								
