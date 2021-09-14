<?php								
//Zmiany od 28.09.2016 ------------------------------------------------------------------------------------------------------------------------------
echo "<center>";
echo "<h1>OBOZY WSPIERA MIASTO TORUŃ</h2>";
$height_m=38;
$height_d=640;
$width_d=71;
for ($i=0;$i<4;$i++)
{
    if ($i==0)
    {
	$height_m=255;
	$height_d=810;
	$width_d=610;
    }
    else
    {
	$height_m=38;
	$height_d=640;
	$width_d=71;
    };
    if ($i==2) {$height_m=53; $height_d=456; $width_d=71;};
    if ($i==3) {$width_d=172;$height_m=91;};
    echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/obozy/TorunMiastemSportu/org_".$i."_oboz.jpg',$height_d,$width_d)\">";
    echo "<img src=\"".APP_URL."/zdjecia/obozy/TorunMiastemSportu/min_".$i."_oboz.jpg\" alt=\"min_".$i."_oboz.jpg\"  style=\"width:340px; height:".$height_m."px; border:0px;\" />";
    echo "</a><p class=\"P_INFO_IMG\">(kliknij na zdjęcie aby je powiększyć)</p>";
};
echo "</center>";
    $query = $db->query("select * from `".$REK_MODUL['TABELA']."` WHERE `WSK_U`=0 AND `WSK_V`=1 ORDER BY `ID` DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
    while($rekord = mysqli_fetch_array($query))
    {
	if ($rekord[18]==2||$rekord[18]==1)
        {
            $JAVA_IMG_SOURCE=APP_URL."/zdjecia/obozy/".$rekord[6]."/".$rekord[2];
            $JAVA_WIDTH=$rekord[16];
            $JAVA_HEIGHT=$rekord[17];
	}
	else
        {
            $JAVA_IMG_SOURCE=APP_URL."/zdjecia/obozy/".$rekord[2];
            $JAVA_WIDTH=$rekord[14];
            $JAVA_HEIGHT=$rekord[15];
	};
        echo '<center>';
	if ($rekord[13]=='g')
        {
            if(in_array($userAgent['platform'],$mobilePlatform))
            {
                // GET MAX WIDHT AND HEIGHT OF IMAGE GALLERY
                $IMG_GALLERY=mysqli_fetch_row($db->query("SELECT MAX(WIDTH),MAX(HEIGHT) FROM OBOZ_IMG WHERE WSK_US=0 AND ID_OBOZU=$rekord[0]"));
                //echo $IMG_GALLERY[0]." - ".$IMG_GALLERY[1];
                $IMG_GALLERY[0]=$IMG_GALLERY[0]+10;
                $IMG_GALLERY[1]=$IMG_GALLERY[1]+100;
                echo "<a HREF=\"javascript:displayCamp2('".APP_URL."/view_public/v_galeria.php?LOCATION=".APP_URL."/zdjecia/obozy/$rekord[6]/&XML=$rekord[7]&ID=$rekord[0]&TB=$tabela',$IMG_GALLERY[0],$IMG_GALLERY[1])\">";
            }
            else
            {
                echo '<A HREF="javascript:displayCamp(&#39;'.$JAVA_IMG_SOURCE.'&#39;,'.$JAVA_WIDTH.','.$JAVA_HEIGHT.')">';
            }
            echo '<img src="'.APP_URL.'/zdjecia/obozy/'.$rekord[6].'/'.$rekord[3].'"  height="235px" width="314px" /></a><BR/>'.$rekord[5];
	} 
        else if  ($rekord[13]=='d')
        {
            echo '<A target="_blank" HREF="'.$rekord[20].'"><img src="'.APP_URL.'/zdjecia/obozy/'.$rekord[6].'/'.$rekord[3].'"  height="235px" width="314px" /></a><BR/>'.$rekord[5];
        }
	else
        {
            echo $rekord[1].'<BR/>'.$rekord[5];
	};
         echo '</center><br/>';
    }