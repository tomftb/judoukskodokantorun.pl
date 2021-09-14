<?php if(!defined('NASZE_ZD_62')) { exit('NO PERMISSION');}?>
<div class="DIV_MAIN">
<?php
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0"><p class="P_HREF_BACK">Anuluj</p></a>';						
echo '<p  CLASS="P_MAIN_CEL">Edytowanie : </p>';
echo '<form action="" method="POST" name="edycja_zdjecia">';
echo '<p class="P_NG_INF">Podaj numer ID zdjęcia : <input type="text" name="ID" size="5" value="'.$_POST["ID"].'" maxlength="5" /></p>';

IF(!$_POST["IDM"]) $IDM=$_GET["IDM"]; else $IDM=$_POST["IDM"];
							
							echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
							echo '<div class="DIV_OPCJ">';
							echo '<input type="submit" class="inp_button" value="Edytuj" name="wyb_edytuj"/></form>';
							echo '</div>';
							if (isset($_POST["wyb_edytuj"]))
                                                        {
							$ID=$_POST["ID"];
							if ($_POST["ID"]!='')
								{
                                                                    if (mysqli_num_rows($db->query("select ID from OUR_PHOTO where ID='".$_POST['ID']."'"))!=0)
                                                                    {
									if (mysqli_num_rows($db->query("select `ID` from `OUR_PHOTO` where `ID`='".$_POST['ID']."' AND `WSK_U`=1"))==0)
                                                                        {
									echo '<script type="text/javascript">window.location = "'.PAGE_URL.'&IDW=2&ID='.$_POST["ID"].'"</script>';
									} else echo '<p style="color:red">Istnieje zdjęcie o podanym <span style="color:black;">ID</span> ale zostało ono już prędzej usunięte !</p>';
									} else {echo '<p style="color:red">Nie znaleziono żadnego zdjęcia o podanym <span style="color:black;">ID</span> !</p>';}
								} else echo '<p style="color:red;">Nie podałeś <span style="color:black;">ID</span> zdjęcia !</p>';
							}
                                                        ?>
							</div>

