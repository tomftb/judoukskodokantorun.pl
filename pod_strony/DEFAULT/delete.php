<?php
if(!defined('DEFAULT_3')) { exit('NO PERMISSION - NOT DEFINED'); }
$redrect='<p class="P_BACK">Twója pozycja został usunięta!<br/>';
$redrect.='<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
$redrect.='<span class="S_BACK2">MENU.</span></p>';
$redrect.='<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'"\', 500);}window.onload=init;</script>';

echo '<div class="DIV_MAIN">';
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Anuluj</p></a>';

if (trim($ID)!='')
{
    echo '<DIV class="DIV_TRESC">';
    echo '<p class="P_MAIN">Napewno chcesz usunąć <span class="S_MAIN_NG">[</span>'.$ID.'<span class="S_MAIN_NG">]</span> ? </p>';
    echo $idData;
    echo '</DIV>';
    echo '<div class="DIV_OPCJ">';
    echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
    echo '<a href="'.PAGE_URL.'&IDW='.$IDW.'&IDS='.$IDS.'&ID='.$ID.'&USN=T"><span class="s_button">TAK</span></a>';
    echo '</p></div>';
    if (isset($_GET["USN"]))
    {
        $db->query("UPDATE `".$REK_MODUL['TABELA']."` SET `WSK_U` = '1' WHERE `ID` =$ID");
        $db->insDbLog($IDM,'USUŃ ID : '.$ID);
	unset($ID);
        echo $redrect;						
    }
}
else
{	
    echo '<p CLASS="P_MAIN_CEL">Usuwanie pozycji : </p>';
    echo '<form Name="USUN" ACTION="'.PAGE_URL.'" method="GET" >';
    echo '<p class="P_NG_INF">Podaj ID : ';
    echo '<input type="number" name="ID" size="5" value="" maxlength="5"  class="TEXTAREA" /></p>';
    echo '<input type="hidden" name="IDM" size="5" value="'.$IDM.'" /></p>';
    echo '<input type="hidden" name="IDW" size="5" value="'.$IDW.'"  /></p>';
    echo '<input type="hidden" name="IDS" size="5" value="'.$IDS.'"  /></p>';
    echo '<div class="DIV_OPCJ">';
    echo '<input class="button" type="submit" value="Usuń" name="USUN"/>';
    echo '</form>';
    echo '</div>';
}
echo "</center></div>";	
