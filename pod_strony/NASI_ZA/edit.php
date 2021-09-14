<?php if(!defined('PAGE_URL')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<p class="P_HREF_BACK"><a class="A_BACK"href="'.PAGE_URL.'&IDW=0">Anuluj</a></p>';
$err_session='';
/* END UPRAWENINIE */	
							
							$status_edytuj=0;
							if (isset($_GET["EDIT"]))
							{
								if (($_GET["IDE"]!=''))
								{
                                                                    
									if (mysqli_num_rows($db->query("select ID from ZWDK where ID='".$_GET['IDE']."'"))!=0)
                                                                        {
																								
																								
																								if (mysqli_num_rows($db->query("SELECT ID FROM ZWDK where ID='".$_GET['IDE']."' AND WSK_U=1"))==0){
																								$status_edytuj=1;
																								} // KONIEC istnieje rekord usuniety
																										else $err_session='<p class="P_ERR_E"><span class="S_ERROR">Istnieje</span> Zawodnik o podanym <span class="S_ERROR">ID</span> ale został on już prędzej usunięty !</p>';
																									} // KONIEC istnieje rekord
																									else $err_session='<p class="P_ERR_E">Nie <span class="S_ERROR">istnieje</span> Zawodnik o podanym <span class="S_ERROR">ID</span> !</p>';
								} // KONIEC wprowadzono wartość GET_["IDE"]
																			else $err_session='<p class="P_ERR_E">Nie wprowadzono  <span class="S_ERROR">ID</span> Zawodnika !</p>';	
							};
							//---------------------------- FORMULARZ ------------------------
							if ($status_edytuj==0)
							{
								echo '<p class="P_MAIN">Edytowanie zawodnika :</p>';
								echo '<form action="" method="GET" name="EDYTUJ">';
								echo '<p class="P_NG_INF">Podaj numer ID zawodnika : ';
								echo '<input type="number" name="ID" size="5" value="';
                                                                if(filter_input(INPUT_GET,'GET')!='') { echo $_GET["IDE"]; }
                                                                echo '" maxlength="5" /></p>';
								echo $err_session;
								echo '<div class="DIV_OPCJ">';
								echo '<input type="hidden" name="IDW" value="'.$_GET["IDW"].'" />';
								echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
								echo '<input class="inp_button" type="SUBMIT" value="Edytuj" name="EDIT"/>';
							}
							else
							{
								if ($_SESSION['id_user']==1)
								{
									echo "Przekierowanie...";
								};
								echo '<script>function init(){ setTimeout(\'document.location="'.APP_URL.'/pod_strony/NASI_ZA/cel_NASI_ZA.php?IDW=5&IDM='.$_GET["IDM"].'&IDE='.$_GET['IDE'].'"\', 1);}window.onload=init;</script>';
							};
							echo '</div>';
							echo "</form>";
							Echo "</div>";
?>