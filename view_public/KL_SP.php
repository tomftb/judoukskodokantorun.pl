<?php
$query = $db->query("SELECT ID,HTML,NAZW_I,OPIS,KATALOG,XML,WSK_V,DAT_UTW,TYP,WIDTH,HEIGHT,MAX_W,MAX_H FROM `".$REK_MODUL['TABELA']."` WHERE `WSK_U`=0 AND `WSK_V`=1 ORDER BY `ID` DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
while($rekord = mysqli_fetch_array($query))
{
    echo '<div class="DIV_IMG" style="float:center; width:710px;"><center>';
    if(in_array($userAgent['platform'],$mobilePlatform))
    {
        // GET MAX WIDHT AND HEIGHT OF IMAGE GALLERY
        $IMG_GALLERY=mysqli_fetch_row($db->query("SELECT MAX(WIDTH),MAX(HEIGHT) FROM KLASA_IMG WHERE WSK_U=0 AND ID_KLASA=$rekord[0]"));
        //echo $IMG_GALLERY[0]." - ".$IMG_GALLERY[1];
        $IMG_GALLERY[0]=$IMG_GALLERY[0]+10;
        $IMG_GALLERY[1]=$IMG_GALLERY[1]+100;
        echo "<a HREF=\"javascript:displayCamp2('".APP_URL."/view/v_galeria.php?LOCATION=".APP_URL."/zdjecia/klasa_sp/$rekord[4]/&XML=$rekord[5]&ID=$rekord[0]&TB=$tabela',$IMG_GALLERY[0],$IMG_GALLERY[1])\">";
    }
    else
    {
        echo "<a HREF=\"javascript:displayCamp('".APP_URL."/zdjecia/klasa_sp/$rekord[4]/$rekord[1]',$rekord[11],$rekord[12])\">";
    }								
    echo '<img src="'.APP_URL.'/zdjecia/klasa_sp/'.$rekord[4].'/'.$rekord[2].'" alt="'.$rekord[2].'"  style="width:'.$rekord[9].'px; height:'.$rekord[10].'px; border:0px;" />';
    echo '</a><p class="P_INFO_IMG">(kliknij na zdjęcie aby uruchomić galerię)</p></center>';
    echo '<p class="P_DANE">Rocznik : <span style="font-weight:bold;font-size:14px;color:black;font-style:normal;">'.$rekord[3].'</span></p>';
    echo '</div>';
}
                                                            
							
                                                    