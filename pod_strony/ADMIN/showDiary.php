<?php
if(!defined('ADMIN1314')) { exit('NO PERMISSION');}
////--------------------------------------------------------SQL-INSERT-DZIENNIK-------------------------------------------------------------------
							//INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - DZIENNIK");
							//--------------------------------------------------------KONIEC-SQL-INSERT-DZIENNIK------------------------------------------------------------
							echo '<p class="P_M_NAGL">Dziennik :</p>';
							
							//-----------------------
							$plik= DR."/widok/v_admin_dziennik.php";
								if(file_exists($plik))
								{
									if (is_readable($plik))
									{
										include($plik);
									}
									else
									{
										$err=TRUE;
										$komunikat="<p class=\"P_ERROR\">Plik nie jest do odczytu - <span class=\"SPAN_ERROR\">".$plik."</span></p>";
									}
								}
								else
								{
									echo "blad";
									$err=TRUE;
									$komunikat="<p class=\"P_ERROR\">Plik nie istnieje - <span class=\"SPAN_ERROR\">".$plik."</span></p>";
								}
								if($err)
								{
									//---- ZAPIS ERROR DO PLIKU
									$fp = fopen( DR."/.log/cms_log.log", "a");
									fputs($fp, $komunikat.PHP_EOL );
									fclose($fp);
									//-------------------------	
								}
							//------------------------
									//----------------------------------------------------------------------------------FILTR-----------------------------------------------------------------------
							
							
							
