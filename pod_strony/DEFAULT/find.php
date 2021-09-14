<?php
if(!defined('DEFAULT_2')) { exit('NO PERMISSION - NOT DEFINED'); }
echo '<div class="DIV_MAIN">';
echo '<p style="text-align: left; margin:20px;"><a href="'.PAGE_URL.'&IDW=0">Anuluj</a></p>';
echo '<p CLASS="P_MAIN_CEL">'.$frameTitle.'</p>';
echo '<form action="'.PAGE_URL.'" method="GET" name="FIND">';
echo '<p class="P_NG_INF">Podaj ID pozycji : <input type="number" name="ID" size="5" value="'.$ID.'" maxlength="5"  class="TEXTAREA" /></p>';
echo '<div class="DIV_OPCJ">';
echo '<input type="hidden" name="IDM" value="'.$IDM.'" />';
echo '<input type="hidden" name="IDW" value="'.$IDW.'" />';
echo '<input type="hidden" name="F" value="Y" />';
echo '<input type="submit" class="button" value="Edytuj"/></form>';
echo '</div>';
if(filter_input(INPUT_GET,'F')==='Y')
{
    $rec=mysqli_fetch_assoc($db->query("select ID,WSK_U from `".$REK_MODUL['SKROT']."` where ID='".$ID."'"));
    if(is_null($rec))
    {
        echo "<p class=\"P_ERROR\">Nie odnaleziono rekordu od podanym ID (<span class=\"S_ERROR\">".$ID."</span>)!</p>";
    }
    else if(intval($rec['WSK_U'])===1)
    {
        echo "<p class=\"P_ERROR\">Rekord o podanym ID (<span class=\"S_ERROR\">".$ID."</span>) jest już usunięty!</p>";
        //die('usuniety');
    }
    else
    {
        echo '<script>function init(){ setTimeout(\'document.location="/pod_strony/DEFAULT/iframe.php?IDW=4&IDM='.$_GET["IDM"].'&ID='.$_GET['ID'].'"\', 1);}window.onload=init;</script>';
    }																				  
}
echo '</div>';