<p style="font-weight: bold; font-size: 16pt; text-align: center; color: #006699;">AKTUALNY PLAN ZAJĘĆ :<br /><br />
<span style="font-size:14pt;">ZAJĘCIA ODBYWAJĄ SIĘ W SZEŚCIU GRUPACH :</span></p>
<center>
<table class="TABELA" border="1px" bordercolor="black">
<tr style="background:#C0C0C0;">
<td class="KOLUMNA1"><span class="zawartosc1">NAZWA GRUPY:</span></td> 	
<td align="center" class="KOLUMNA2"><span class="zawartosc1">WIEK-ROCZNIK</br>
UCZESTNIKA ZAJ&#280;&#262;</span></td>		
<td align="center" class="KOLUMNA3"><span class="zawartosc1">DNI I GODZINY ZAJĘĆ</span></td>		
<td align="center" class="KOLUMNA4"><span class="zawartosc1">ZAPISY</span></td>
</tr>
<?php							
//$query = $db->query("SELECT * FROM `TRENING` WHERE `WSK_V`=1 AND `WSK_U`=0 ORDER BY `ID`");
$query=$db->query(" SELECT `NAZWA_GRUPY`,`ROK`,`DZIEN_GODZINA`,`OPIS`,`KOLOR_TLA`,`N_G_ROZMIAR`,`W_ROZMIAR`,`D_G_ROZMIAR`,`Z_ROZMIAR`,`KOLOR_TEKST`,`VER`,`P_N_G`,`P_W`,`P_D_G`,`P_Z`,`CSS_N_G`,`CSS_W`,`CSS_D_G`,`CSS_Z` FROM `TRENING` WHERE `WSK_V`=1 AND `WSK_U`=0 ORDER BY `ID`");
while($rec = mysqli_fetch_assoc($query))
{																	
    if ($rec['VER']==2)
    { 
        $parseC->setCSS($rec['CSS_N_G']);
        $CSS_POKAZ[0]=$parseC->getAllCSS();
        $parseC->setCSS($rec['CSS_W']);
        $CSS_POKAZ[1]=$parseC->getAllCSS();
        $parseC->setCSS($rec['CSS_D_G']);
        $CSS_POKAZ[2]=$parseC->getAllCSS();
        $parseC->setCSS($rec['CSS_Z']);
        $CSS_POKAZ[3]=$parseC->getAllCSS();      
	$tab_css="";		
	$center_st="";
	$center_end="";										
    }
    else
    {
        $CSS_POKAZ=array('','','','');
	$tab_css=' font-weight:bold; ';
	$center_st="<center>";
	$center_end="</center>";
    }
    echo '<tr style="background:'.$rec['KOLOR_TLA'].'; color:'.$rec['KOLOR_TEKST'].'; ">';
    echo '<td width="100" style="text-align:'.$rec['P_N_G'].';"><span style="font-size:'.$rec['N_G_ROZMIAR'].';'.$tab_css.$CSS_POKAZ[0].' ">'.$rec['NAZWA_GRUPY'].'</b></span></td>';
    echo '<td width="150" style="text-align:'.$rec['P_W'].';"><span style="font-size:'.$rec['W_ROZMIAR'].';'.$tab_css.$CSS_POKAZ[1].' ">'.$rec['ROK'].'</b></span></td>';
    echo '<td width="220" style="text-align:'.$rec['P_D_G'].';"><span style="font-size:'.$rec['D_G_ROZMIAR'].';'.$tab_css.$CSS_POKAZ[2].' "><br/>'.$rec['DZIEN_GODZINA'].'<br/><br/></span></td>';
    echo '<td width="220" style="text-align:'.$rec['P_Z'].';">'.$center_st.'<span style="font-size:'.$rec['Z_ROZMIAR'].';'.$tab_css.$CSS_POKAZ[3].' "><br/>'.$rec['OPIS'].'<br/><br/></span>'.$center_end.'</td></tr>';
}?>
</table></center>
							
							