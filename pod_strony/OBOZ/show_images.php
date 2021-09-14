<?php
require_once(DR.'/pod_strony/_funkcje_/show_file.php');
echo '<p class="P_MAIN">Wszystkie zdjęcia obozu ['.$_GET["IDE"].'] :</p>'; 
if ($TAB_PRAW["Zdjecia"]['VAL']!=1)
{
    $db->insDbLog($_GET["IDM"],"Brak uprawnienia - Wyświetl zdjęcia");
    echo "Brak uprawnienia - Wyświetl zdjęcia";
    return '';							
}
$db->insDbLog($_GET["IDM"],"Uruchomiono funkcję - Wyświetl zdjęcia");
$SEL_VER_OB=$db->query("select VER,KATALOG FROM OBOZ WHERE ID='$_GET[IDE]'"); 
$rek_sel=mysqli_fetch_array($SEL_VER_OB);
if($rek_sel[1]<0)
{ 
    echo "Nie wprowadzno nazwy katalogu";
} 
                                                            else
                                                            {
								echo "Pobierz - ".$TAB_PRAW["Pobierz"]['VAL']."</br>";
                                                                show_file(DR."/zdjecia/obozy/".$rek_sel[1],APP_URL."/zdjecia/obozy/".$rek_sel[1],$TAB_PRAW["Pobierz"]['VAL']);
                                                            }
							
							
								echo "</DIV>";

