<?php
/* CSS */

require_once(DR.'/class/parseCSS.php');
$parseC=NEW parseCSS();

$result=$db->query("SELECT ID,TRESC,KOLOR_HEX,ROZMIAR,POZYCJA,CSS FROM `".$REK_MODUL['TABELA']."` WHERE `WSK_U`=0 AND `WSK_V`=1 ORDER BY `ID` ASC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
while($d = mysqli_fetch_array($result,MYSQLI_ASSOC))
{  
    $parseC->setCSS($d['CSS']);
    echo '<DIV class="DIV_MAIN" ID="ID'.$d['ID'].'">';
    echo '<DIV class="DIV_TRESC">';
    echo '<p style="color:'.$d['KOLOR_HEX'].';font-size:'.$d['ROZMIAR'].'px; text-align:'.$d['POZYCJA'].';'. $parseC->getFontWeight().$parseC->getFontStyle().$parseC->getTextDecoration().'">'.$d['TRESC'].'</p>';								
    echo '</DIV></DIV>';
}