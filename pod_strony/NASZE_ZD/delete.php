<?php if(!defined('NASZE_ZD_63')) { exit('NO PERMISSION');}?>
<div class="DIV_MAIN">
<?php
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Anuluj</p></a>';
echo '<p CLASS="P_MAIN_CEL">Usuwanie zdjęcia : </p>';
$end='<p class="P_BACK">Podana pozycja została usunięta !<br/>';
$end.='<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
$end.="<span class=\"S_BACK2\" onload=redirect('adsa','asd')>MENU - Nasze Zdjęcia.</span></p>";
if (isset($_GET["ID"]))
{
echo '<p class="P_NG_INF">Napewno chcesz usunąć zdjęcie nr <span class="S_MAIN_NG">[</span>'.$_GET["ID"].'<span class="S_MAIN_NG">]</span> ?</p>';
                                                            echo '<div class="DIV_OPCJ">';
                                                            echo '<p class="P_POZ_BUT"><a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
                                                            echo '<a href="'.PAGE_URL.'&IDW='.$IDW.'&ID='.$ID.'&USN=T&IDS='.$IDS.'"><span class="s_button">TAK</span></a>';
                                                            echo '</p></div>';
                                                            if (isset($_GET["USN"]))
                                                            {
                                                                $db->query("UPDATE `OUR_PHOTO` SET `WSK_U`='1' WHERE `ID`='".$_GET['ID']."'");
                                                                $db->insDbLog($_GET["IDM"],"USUŃ ID : ".$_GET["ID"]);
                                                                echo $end;
                                                                echo "<script>window.redirect('".PAGE_URL."&IDW=0&IDS=".$IDS."',500)</script>";
                                                            }
							} 
							else
                                                        {					
                                                            echo '<div class="DIV_OPCJ">';
                                                            echo '<form Name="USUN" action="" method="POST" >';
                                                            echo '<p class="P_NG_INF">Podaj numer ID : ';
                                                            echo '<input type="text" name="ID" size="5" value="';
                                                            if(filter_input(INPUT_POST,'ID')!='') { echo $_POST["ID"]; }
                                                            echo '" maxlength="5" /></p>';
                                                            if(filter_input(INPUT_POST,'IDM')!='') { $IDM=$_POST["IDM"]; } else {  $IDM=$_GET["IDM"]; }
                                                         
                                                            echo '<input type="hidden" name="IDM" value="'.$IDM.'" />';
                                                            echo '<input class="inp_button" type="submit" value="Usuń" name="USUN"/></form>';
                                                            echo '</div>';
							if(isset($_POST['USUN']))
							{
								if (($_POST["ID"]!=''))
								{
                                                                   
                                                                    $rec=mysqli_fetch_assoc($db->query("select ID,WSK_U from OUR_PHOTO where ID='".$_POST['ID']."'"));
                                                                    if( is_null($rec))
                                                                    {
                                                                        echo '<p style="color:red">Nie odnaleziono żadnej pozycji o podanym <span style="color:black;">ID</span>!</p>';
                                                                        echo "IS NULL";
                                                                    }
                                                                    else
                                                                    {
                                                                        if(intval($rec['WSK_U'])===1)
                                                                        {
                                                                            echo '<p style="color:red">Istnieje pozycja o podanym <span style="color:black;">ID</span> ale została ona już usunięta !</p>';
                                                                        }
                                                                        else
                                                                        {
                                                                            $db->query("UPDATE `OUR_PHOTO` SET `WSK_U`='1' WHERE `ID`='".$_POST['ID']."'");
                                                                            $db->insDbLog($_GET["IDM"],"Usunięto ID : ".$_POST['ID']);
                                                                            echo $end;
                                                                            echo "<script>window.redirect('".PAGE_URL."&IDW=0&IDS=".$IDS."',500)</script>";
                                                                        }
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                    echo '<p style="color:red;">Nie podałeś <span style="color:black;">ID</span>!</p>';
                                                                }
							}
	}
?>
</center></div>