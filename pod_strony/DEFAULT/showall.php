<?php
if(!defined('DEFAULT_0')) { exit('NO PERMISSION - NOT DEFINED'); }
/* PAGINATION */
    require(DR.'/class/page.php');
    $page=NEW page();
    $page->setDbRec("select COUNT(*) FROM `".$REK_MODUL['TABELA']."` WHERE `WSK_U`=0");
    $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
    $page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
    $IDS=$page->getIDS();

/* END PAGINATION */
echo '<p CLASS="P_MAIN_CEL">Wszystkie Pozycje : </p>';

$result=$db->query("SELECT ID,TRESC,KOLOR_HEX,ROZMIAR,POZYCJA,CSS,DAT_UTW,WSK_V FROM `".$REK_MODUL['TABELA']."` WHERE WSK_U=0 ORDER BY ID ASC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
echo $page->getPageLink('s');
while($d = mysqli_fetch_array($result,MYSQLI_ASSOC))
{  
    $parseC->setCSS($d['CSS']);
    echo '<DIV class="DIV_MAIN" ID="ID'.$d['ID'].'">';
    echo '<DIV class="DIV_INFO">';
    echo '<span class="S_NG_MAIN">ID: <span class="S_NG_INF">'.$d['ID'].'</span>';
    echo ' Data utworzenia : <span class="S_NG_INF">'.$d['DAT_UTW'].'</span></span>';
    echo '<span class="S_WIDOK_STAT" >Widoczny : '.$parseC->setRecStat($d['WSK_V']);
    echo '<a href="'.PAGE_URL.'&IDW=4&ID='.$d['ID'].'&IDS='.$IDS.'">';
    echo '<span class="s_button" style="margin-right:10px; margin-left:10px;"> Ustaw</span></a></span>';
    echo '</DIV>';
    echo '<DIV class="DIV_TRESC">';
    echo '<p style="color:'.$d['KOLOR_HEX'].';font-size:'.$d['ROZMIAR'].'px; text-align:'.$d['POZYCJA'].';'. $parseC->getFontWeight().$parseC->getFontStyle().$parseC->getTextDecoration().'">'.$d['TRESC'].'</p>';								
    echo '<center>';
    echo '</DIV>';
    echo '<DIV class="DIV_OPCJ">';
    echo '<p style="float:right;"><a href="'.PAGE_URL.'&IDW=3&ID='.$d['ID'].'&IDS='.$IDS.'">';
    echo '<span class="s_button" style="margin-right:10px;">Usuń</span></a>';
    echo '<a href="'.PAGE_URL.'&IDW=4&ID='.$d['ID'].'&IDS='.$IDS.'">';
    echo '<span class="s_button" >Edytuj</span></a></p>';
    echo '</DIV></DIV>';
} // END WHILE
echo "</center>";						
echo $page->getPageLink('e');