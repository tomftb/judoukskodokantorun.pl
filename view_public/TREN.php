<?php	
foreach(mysqli_fetch_all($db->query("SELECT `ID`,`IMIE_N`,`K_IMIE_N`,`R_IMIE_N`,`R_INFO`,`ILOZD`,`P_IMI_N_WART`,`P_INFO_WART`,`CSS_IMI_N` FROM `TREN` where `WSK_U`=0 AND `WSK_V`=1 order by `ID` desc LIMIT ".$page->getStartLimit().",".$page->getEndLimit()),MYSQLI_ASSOC) as $rek)
{
    $parseC->setCSS($rek['CSS_IMI_N']);
    echo '<div class="DIV_MAIN">';
    if ($rek['ILOZD']!=0)
    {
	foreach(mysqli_fetch_all($db->query("select `NAZWA_I`,`WIDTH`,`HEIGHT`,`NAZWA_I_M`,`M_WIDTH`,`M_HEIGHT` FROM `TREN_IMG` where WSK_U=0 AND ID_TREN='".$rek['ID']."'"),MYSQLI_ASSOC) as $v)
        {
            echo '<center>';
            echo "<a HREF=\"javascript:displayWindow3('".APP_URL."/zdjecia/trener/".$v['NAZWA_I']."',".$v['WIDTH'].",".$v['HEIGHT'].",'Trenerzy')\">";
            echo '<img src="'.APP_URL.'/zdjecia/trener/'.$v['NAZWA_I_M'].'" alt="'.$v['NAZWA_I_M'].'" style="width:'.$v['M_WIDTH'].'px; height:'.$v['M_HEIGHT'].'px; border:0px;" />';
            echo '</a>';
            echo '</center>';
        }
    }
    echo '<p style="font-face: Times New Roman ;margin:5px;color:'.$rek['K_IMIE_N'].';font-size:'.$rek['R_IMIE_N'].'px; text-align:'.$rek['P_IMI_N_WART'].';'.$parseC->getAllCSS().'">'.$rek['IMIE_N'].'</p>';
    echo "<hr>";
    echo '<div class="DIV_CLEAR"></div>';
    echo "</div>";
}


