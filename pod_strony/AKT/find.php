<?php if(!defined('AKT22')) { exit('NO PERMISSION');}?>
<?php
echo '<div class="DIV_MAIN">';
$db->insDbLog($_GET["IDM"],"Uruchomiono funkcję - EDYTUJ artykuł");
$checked=TRUE;
echo '<p style="text-align: left; margin:20px;"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'">Anuluj</a></p>';
						echo '<p CLASS="P_MAIN_CEL">Edytowanie artykułu : </p>';
						echo '<form action="'.PAGE_URL.'&IDW=5&IDM='.$IDM.'" method="GET" name="edycja_obozu">';
						echo '<p class="P_NG_INF">Podaj numer ID artykułu : <input type="text" name="ID" size="5" value="';
                                                if(isset($_GET['ID'])) { echo $_GET['ID'];}
                                                echo '" maxlength="5"  class="TEXTAREA" /></p>';
						echo '<div class="DIV_OPCJ">';
						echo '<input type="hidden" name="IDM" value="'.$IDM.'" />';
                                                echo '<input type="hidden" name="IDW" value="'.$IDW.'" />';
						echo '<input type="submit" class="button" value="Edytuj"/></form>';
						echo '</div>';
						echo '</div>';
                                           
