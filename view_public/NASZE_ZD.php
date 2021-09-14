<?php								
echo "<center>";
foreach(mysqli_fetch_all($db->query("select `NAZWA_I`,`WIDTH`,`HEIGHT`,`NAZWA_I_M`,`M_WIDTH`,`M_HEIGHT` FROM `OUR_PHOTO` WHERE `WSK_U`=0 AND `WSK_V`=1 order by `ID` DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit()),MYSQLI_ASSOC) as $img)
{
    echo "<a HREF=\"javascript:displayWindow3('".APP_URL."/zdjecia/nasze/".$img['NAZWA_I']."',".$img['WIDTH'].",".$img['HEIGHT'].",'Nasze zdjęcia')\">";
    echo '<img src="'.APP_URL.'/zdjecia/nasze/'.$img['NAZWA_I_M'].'" alt="Zdjecie" style="WIDTH:'.$img["M_WIDTH"].'px; HEIGHT:'.$img["M_HEIGHT"].'px;border:0px; margin:5px;" /></a>';
}
echo "</center>";
echo '<strong><center><big><span style="color:grey">Kliknij na wybrane zdjęcie aby je powiększyć...</span></big></center></strong>';					
