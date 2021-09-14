<?php
$query=$db->query("SELECT * FROM ".$REK_MODUL['TABELA']." WHERE `WSK_U`=0 AND `WSK_V`=1 ORDER BY `ID` DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());

while($rekord = mysqli_fetch_assoc($query))
{
    echo '<div class="DIV_MAIN">';
    echo '<DIV class="DIV_TRESC">';
    if($rekord['VER']==4)
    {
	$rekord['TYTUL']=htmlspecialchars_decode($rekord['TYTUL']);
	$rekord['TRESC']=htmlspecialchars_decode($rekord['TRESC']);
    }
    $parseC->setCSS($rekord['CSS_TYT']);
    echo '<p style="color:'.$rekord['R_TYTUL'].';font-size:'.$rekord['K_TYTUL'].'px; text-align:'.$rekord['P_TYT'].';'.$parseC->getAllCSS().'">'.$rekord['TYTUL'].'</p>';
    $parseC->setCSS($rekord['CSS_TRE']);
    echo '<p style="color:'.$rekord['R_TRESC'].';font-size:'.$rekord['K_TRESC'].'px; text-align:'.$rekord['P_TRE'].';'.$parseC->getAllCSS().'">'.$rekord['TRESC'].'</p>';	
    echo '</div><DIV class="DIV_ZDJ"><center>';
    if ($rekord['ILOZD']!=0)
    {
	if ($rekord['ILOZD']==1) 
        {
            $pozycja="center";
        }
        else if ($rekord['ILOZD']>1 && $rekord['ILOZD']<5 )
        {
            $pozycja="left";
        }
        else
        {
            
        }
	$licz=0;
	$margin_top=0;
	$HEIGHT_DIV_IMG=0;
	$zap_img = $db->query("select KATALOG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT from `NEWS_IMG` where `WSK_U`=0 AND ID_NEWS='$rekord[ID]'");
	while($rek_img = mysqli_fetch_assoc($zap_img))
        {
            //echo "<pre>";
            //print_r($rek_img);
            //echo "</pre>";
            if (($licz==1) && ($HEIGHT_DIV_IMG>$rek_img['M_HEIGHT']))
            {
                $margin_top=($HEIGHT_DIV_IMG-$rek_img['M_HEIGHT'])/2;
            }
            if($licz==2)
            {
                $HEIGHT_DIV_IMG=0;
                $margin_top=0;
		$licz=0;
            }
            if ($HEIGHT_DIV_IMG<$rek_img['M_HEIGHT']) 
            {
                $HEIGHT_DIV_IMG=$rek_img['M_HEIGHT']; 
            }
            echo '<DIV CLASS="DIV_IMG" style="float:'.$pozycja.';height='.$HEIGHT_DIV_IMG.'px">';
            echo "<a HREF=\"javascript:displayWindow3('".APP_URL."/zdjecia/artykul/".$rek_img['KATALOG']."/".$rek_img['NAZWA_I']."',".$rek_img['WIDTH'].",".$rek_img['HEIGHT'].",'".$rekord['TYTUL']."')\">"; 																										
            echo '<img src="'.APP_URL.'/zdjecia/artykul/'.$rek_img['KATALOG'].'/'.$rek_img['NAZWA_I_M'].'" alt="Zdjecie" style="width:'.$rek_img['M_WIDTH'].'px; height:'.$rek_img['M_HEIGHT'].'px; border:0px; margin-top:'.$margin_top.'px; margin-bottom:'.$margin_top.'px;" />';
            echo '</a></DIV>';
	}
    }
	echo '</center></DIV><DIV class="DIV_CLEAR"></div>';
	echo '<div class="DIV_FILM"><center>'.$rekord['ADRES'].'</center></div>';
	echo '</div>';
        //die('STOP');
}
IF ($IDS===0)
{
    echo '<a href="'.APP_URL.'/iframe.php?IDM=18"><p style="color:#0099FF; font-size:20px;"><b>Wyświetl stare artykuły...<b></p></a>';
}
							




