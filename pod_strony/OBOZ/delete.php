<?php if(!defined('OBOZ_53')) { exit('NO PERMISSION'); } ?>
<div class="DIV_MAIN">
<?php
echo '<p class="P_HREF_BACK"><a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'">Anuluj</a></p>';
echo '<p class="P_MAIN">Usuwanie obozu :</p>'; 

$end='<p class="P_BACK">Wybrany obóz został usunięty!<br/>';
$end.='<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span><span class="S_BACK2">MENU - OBOZY.</span></p>';
$end.='<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'"\', 1500);}window.onload=init;</script>';

if ($ID!='')
{
    echo '<p class="P_NG_INF">Napewno chcesz usunąć obóz o nr <span class="S_MAIN_NG">[</span>'.$ID.'<span class="S_MAIN_NG">]</span> ?</p>';
    echo '<div class="DIV_OPCJ">';
    echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
    echo '<a href="'.PAGE_URL.'&IDW=3&IDS='.$IDS.'&ID='.$ID.'&USN=T"><span class="s_button">TAK</span></a>';
    echo '</p></div>';
    if (isset($_GET["USN"]))
    {
        
        $db->query("UPDATE `OBOZ` SET `WSK_U`='1' WHERE `ID`='".$ID."'");
        echo $end;                                                                                                    					
    }
}
else {						
														echo '<form Name="USUN_1" action="" method="POST" >';
														echo '<p class="P_NG_INF">Podaj numer ID obozu : ';
														echo '<input type="text" name="ID" size="5" value="';
                                                                                                                if(filter_input(INPUT_POST,'ID')){ echo filter_input(INPUT_POST,'ID'); }
                                                                                                                echo '" maxlength="5" /></p>';
														echo '<div class="DIV_OPCJ">';
														echo '<input class="button" type="submit" value="Usuń" name="USUN"/></div></form>';
														if(isset($_POST["USUN"]))
                                                                                                                {
                                                                                                                    if (($_POST["ID"]!=''))
                                                                                                                    {
															if (mysqli_num_rows($db->query("select `ID` FROM OBOZ WHERE `ID`='".$_POST['ID']."'"))===1)
                                                                                                                        {
                                                                                                                            if (mysqli_num_rows($db->query("select ID from OBOZ WHERE ID='".$_POST['ID']."' AND `WSK_U`=1"))===0)
                                                                                                                            {
                                                                                                                                $db->query("UPDATE `OBOZ` SET `WSK_U`='1' WHERE `ID`='".$_POST['ID']."' AND `WSK_U`=0");
																echo $end;
                                                                                                                            }
                                                                                                                            else
                                                                                                                            {
                                                                                                                                echo "<p class=\"P_ERROR\">Istnieje obóz o podanym <span class=\"S_ERROR\">ID</span> ale został ono już prędzej usunięty !</p>";
                                                                                                                            }
															} else {echo "<p class=\"P_ERROR\">Nie znaleziono żadnego obozu o wpisanym <span class=\"S_ERROR\">ID</span>!</p>"; }	
														} else echo "<p class=\"P_ERROR\">Nie podałeś <span class=\"S_ERROR\">ID</span> obozu !</p>";
														};
		}
                                                                                                                ?>
</div>	

