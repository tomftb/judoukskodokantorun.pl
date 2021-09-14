<?php if(!defined('PAGE_URL')) { exit('NO PERMISSION');} ?>
<?php
echo '<div class="DIV_MAIN">';
echo '<p class="P_HREF_BACK"><a class="A_BACK"href="'.PAGE_URL.'IDW=0">Anuluj</a></p>';
							$status_edytuj=0;
							if (isset($_GET["EDIT"])){
													if (($_GET["ID"]!='')){
																			
																			$SEL_IST = $db->query("select ID from KLASA where ID='$_GET[ID]'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_IST".mysqli_error()."</span></p>");
																			$istnieje_rekord = mysqli_num_rows($SEL_IST);
																			if ($istnieje_rekord!=0) {
																								
																								$SEL_IST_USN = $db->query("SELECT ID FROM KLASA where ID='$_GET[ID]' AND WSK_U=1") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_IST_USN".mysqli_error()."</span></p>");
																								$usuniety_rekord = mysqli_num_rows($SEL_IST_USN);
																								if ($usuniety_rekord==0){
																								$status_edytuj=1;
																								} // KONIEC istnieje rekord usuniety
																										else $err_session='<p class="P_ERR_E"><span class="S_ERROR">Istnieje</span> Klasa o podanym <span class="S_ERROR">ID</span> ale została ona prędzej usunięta !</p>';
																									} // KONIEC istnieje rekord
																									else $err_session='<p class="P_ERR_E">Nie <span class="S_ERROR">istnieje</span> Klasa o podanym <span class="S_ERROR">ID</span> !</p>';
																			} // KONIEC wprowadzono wartość GET_["ID"]
																			else $err_session='<p class="P_ERR_E">Nie wprowadzono  <span class="S_ERROR">ID</span> Klasy !</p>';	
													};
							if ($status_edytuj==0){
							echo '<p class="P_MAIN">Edytowanie klasy :</p>';
							echo '<form action="" method="GET" name="EDYTUJ">';
							echo '<p class="P_NG_INF">Podaj numer ID klasy : ';
							echo '<input type="text" name="ID" size="5" value="'.$_GET["ID"].'" maxlength="5" /></p>';
							echo $err_session;
							echo '<div class="DIV_OPCJ">';
							echo '<input type="hidden" name="IDW" value="2" />';
							echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
							echo '<input class="button" type="SUBMIT" value="Edytuj" name="EDIT"/>';
							} else {
								if ($_SESSION['id_user']==1){ echo "Przekierowanie...";};
							echo '<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'?IDW=2&ID='.$_GET["ID"].'"\', 1);}window.onload=init;</script>';
							};
							echo '</div>';
							echo "</form>";
							echo "</div>";

