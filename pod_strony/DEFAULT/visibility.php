<?php
if(!defined('DEFAULT_6')) { exit('NO PERMISSION - NOT DEFINED'); }
echo "<div class=\"DIV_MAIN\">";
echo '<DIV class="DIV_INFO">';
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'">';
echo '<p class="P_HREF_BACK">Anuluj</p></a>';
echo '</DIV>';
echo '<DIV class="DIV_TRESC">';
echo '<p class="P_MAIN">Zmienić statusu widoczności pozycji nr <span class="S_MAIN_NG">[</span>'.$ID.'<span class="S_MAIN_NG">]</span> ? </p>';    
echo $idData;
echo '</DIV>';
echo '<div class="DIV_OPCJ">';
echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
echo '<a href="'.PAGE_URL.'&IDW='.$IDW.'&IDS='.$IDS.'&ID='.$ID.'&WSK_V='.$WSKV.'"><span class="s_button">TAK</span></a>';
echo '</p>';
echo '<p CLASS="P_STAT_V">(Aktualny status - '.$STAT_KOM.')</p>';
echo '</div>';
if (isset($_GET["WSK_V"]))
{
    if ($_GET["WSK_V"]==0)
    {
	$WIDOK=1;
	$STAT_KOM ='<font style="color:blue"> WIDOCZNY </font>';
    } 
    else
    {
        $WIDOK=0;
        $STAT_KOM='<font style="color:red"> NIEWIDOCZNY</font>';
    }
    $db->query("UPDATE `".$REK_MODUL['TABELA']."` SET `WSK_V` = $WIDOK WHERE `ID` =$ID");
    $db->insDbLog($IDM,"Zmieniony status widoczności $ID - $WIDOK");
   
    echo '<p class="P_BACK">Status twojej pozycji został zmieniony na - '.$STAT_KOM.'<br/>';
    echo '<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
    echo '<span class="S_BACK2">MENU</span></p>';
    echo '<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"\', 1000);}window.onload=init;</script>';
} 
echo '</div>';