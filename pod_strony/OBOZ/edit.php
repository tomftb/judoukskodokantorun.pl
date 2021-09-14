<?php if(!defined('OBOZ_52')) { exit('NO PERMISSION'); } ?>
<DIV class="DIV_MAIN">
<?php
echo '<p class="P_HREF_BACK"><a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'">Anuluj</a></p>';
echo '<p class="P_MAIN">Edytowanie obozu :</p>';
echo '<form action="" method="GET" name="edycja_obozu">';
echo '<p class="P_NG_INF">Podaj numer ID obozu : <input type="text" name="IDE" size="5" value="'; if (isset($_GET['IDE'])) { echo $_GET["IDE"]; } echo '" maxlength="5" /></p>';
echo '<div class="DIV_OPCJ">';
echo '<input type="hidden" name="IDW" value="2" />';
echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
echo '<input type="submit" class="button" name="wyb_edytuj" value="Dalej"/></form>';
echo "</div>";					
if (isset($_GET["wyb_edytuj"]))
{
                                                    if ($_GET["IDE"]!='')
                                                    {
							$SEL_OB_EDIT = $db->query("select ID,WSK_U from OBOZ where ID='".$_GET['IDE']."' AND WSK_U IN (0,1)");
                                                        $REK_OB_E=mysqli_fetch_array($SEL_OB_EDIT);
							if ($REK_OB_E[0]==$_GET["IDE"])
                                                        {
                                                            if ($REK_OB_E[1]==0)
                                                            {
								echo '<script type="text/javascript">
								window.location = "edytuj_o.php?IDE='.$_GET["IDE"].'&IDM='.$_GET["IDM"].'"
								</script>';
                                                            }
                                                            else
                                                            {
                                                                echo '<p style="color:red">Istnieje oboz o podanym <span style="color:black;">ID</span> ale zostało ono już prędzej usunięty !</p>';
                                                            }        
                                                        }
                                                        else 
                                                        {
                                                            echo '<p style="color:red">Nie znaleziono żadnego obozu o podanym <span style="color:black;">ID</span> !</p>';}
							} 
                                                        else echo '<p style="color:red;">Nie podałeś <span style="color:black;">ID</span> obozu !</p>';
}
?>
</div>

