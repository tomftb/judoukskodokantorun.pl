<?php
$ID_KAT=$_POST["ID_KAT"];
if ($ID_KAT==0){
				$dir=opendir(DR."/zdjecia/artykul");
												$ID_KAT=1; 
												while($plik=readdir($dir)){ 
																			if($plik!="." && $plik!="..")
																			$ID_KAT++; 
																			};
																			mkdir(DR.'/zdjecia/artykul/'.$ID_KAT, 0777);
																			closedir($dir);
																			if ($_SESSION['id_user']==1){ echo "Utworzono nowy katalog - ".$ID_KAT."<br/>";};																			
} else {
	if ($_SESSION['id_user']==1){ echo "Katalog - ".$ID_KAT."<br/>";};	
};
$NR_IMG=$_POST["ILE_ZD"];
$licznik_plik=0;
for( $y = 1; $y<5; $y++ ) 
	{
	if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"]))
		{
				echo "Dodano nowy obraz<br/>";
				$uploads_dir = DR.'/zdjecia/artykul/'.$ID_KAT.'/'; 
				$filename=$_FILES["obraz".$y]["name"];
				$ext=strtolower(substr(strrchr($filename, '.'), 1));
				$image_name = "org_".$licznik_plik."_news.".$ext; //Nowa nazwa pliku
																		
																		//-------------------------------------------------------------------------------------SPRAWDZ-CZY-ISTENIEJE-PLIK---------
																		while(file_exists(DR."/zdjecia/artykul/".$ID_KAT."/".$image_name)){
																																					echo "<p class=\"P_ERROR\">PLIK - <span class=\"S_ERROR\">org_".$licznik_plik."_news.".$ext.".</span> Istenieje !</p>";
																																					$licznik_plik++;
																																					$image_name="org_".$licznik_plik."_news.".$ext;
																		};
																		//-------------------------------------------------------------------------------------KONIEC-SPRAWDZ-CZY-ISTENIEJE-PLIK---------
				//$image_name="org_".$licznik_plik."_news.".$ext;
				$tmp_name=$_FILES["obraz".$y]["tmp_name"]; 
				move_uploaded_file($tmp_name, "$uploads_dir$image_name"); 
				list($width, $height)=getimagesize($uploads_dir.$image_name);
				//--------------------------------------------------------------TWORZENIE_MINIATURY---------------------------------------------
				$SQL_LIKE="%W_MAX";
				$IMG_NEW=FALSE;
				$REK_IMG=array(0,0);
				
				for ($p_resize=0;$p_resize<2;$p_resize++){
															if ($_SESSION['id_user']==1){ 
																							echo '<p CLASS="P_INFO">SQL rozmiar - <span class="S_INFO">'.$SQL_LIKE."</span></p>";
																};
																											//--------------------------------------------------------SQL-SELECT-IMG-MAX-------------------------------------------------------------------
																											for ($i_sql=0;$i_sql<2;$i_sql++)
                                                                                                                                                                                                                        {
																											
																											
																											$REK_SEL_IMG=mysqli_fetch_row($db->query("SELECT WART FROM PARM WHERE ID_MODUL='$_GET[IDM]' AND ID_GROUP=4 AND WSK_U=0 AND N_OPCJ LIKE '$SQL_LIKE' LIMIT 1"));
																											$REK_IMG[$i_sql]=$REK_SEL_IMG[0];
																											if ($p_resize==0){
																												if ($_SESSION['id_user']==1){ 
																												echo '<p CLASS="P_INFO">SQL ['.$REK_SEL_IMG[0].'] rozmiar ['.$i_sql.'] - <span class="S_INFO">'.$SQL_LIKE.'</span> : '.$REK_IMG[$i_sql].'</p>';
																												
																												}
																												$SQL_LIKE="%H_MAX";
																											} 
																											else {
																													if ($_SESSION['id_user']==1){ 
																													echo '<p CLASS="P_INFO">SQL rozmiar ['.$i_sql.'] - <span class="S_INFO">'.$SQL_LIKE.'</span>: '.$REK_IMG[$i_sql].'</p>';
																													}
																													$SQL_LIKE="%H_MIN";
																											};
																											};
																											//--------------------------------------------------------KONIEC-SQL-SELECT-IMG-MAX------------------------------------------------------------
																																					
																													switch ($p_resize):
																																			case 0: 
																																					$naglowek_img='new_'.$licznik_plik."_news.".$ext;
																																					$naglowek="Galeria ";
																																					$SQL_LIKE="%W_MIN";
																																					break;
																																			case 1:
																																					$IMG_NEW=TRUE;
																																					$width=$new_width[0];
																																					$height=$new_height[0];
																																					$naglowek_img='min_'.$licznik_plik."_news.".$ext;
																																					$naglowek="Miniatura ";
																																					break;
																																			default:
																																					//$SQL_LIKE="%MAX";
																																					$naglowek_img='def_'.$licznik_plik."_news.".$ext;
																																					$naglowek="DEFAULT ";
																																					$SQL_LIKE="%W_MIN";
																																					break;
																													endswitch;
																		if ($width>$REK_IMG[0] || $height>$REK_IMG[1] || ($ext_old=="bmp")){
																												
																												if ($_SESSION['id_user']==1){
																																			echo "<p class=\"P_ERROR\">Nowy rozmiary ($naglowek) WIDTH : <span class=\"S_INFO\">".$REK_IMG[0]." px</span>";
																																			echo " HEIGHT : <span class=\"S_INFO\">".$REK_IMG[1]." px</span></p>";
																												};
																												if (($IMG_NEW==TRUE) && (file_exists($uploads_dir."/new_".$licznik_plik."_news.".$ext))) {
																																$image_name='new_'.$licznik_plik."_news.".$ext;
																												};
																												
																												$wynik_img_size = resize_image($REK_IMG[0],$REK_IMG[1],$ext,$naglowek_img,$uploads_dir.'/',$image_name);
																												$new_width[$p_resize]=round($wynik_img_size[0]);
																												$new_height[$p_resize]=round($wynik_img_size[1]);
																												$new_image_name[$p_resize]=$wynik_img_size[2];
																		} else {
																				$new_image_name[$p_resize]=$image_name;
																				$new_height[$p_resize]=round($height);
																				$new_width[$p_resize]=round($width);
																		};
																		if($_SESSION['id_user']==1) {
																									echo "<p class=\"P_INFO\">".$naglowek;
																									echo " WIDTH : <span class=\"S_INFO\">".$new_width[$p_resize]."</span>";
																									echo " HEIGHT : <span class=\"S_INFO\">".$new_height[$p_resize]."</span></p>";
																									echo "IMAGE NAME - $image_name </br>";
																									};
																		};
				//--------------------------------------------------------------TWORZENIE_MINIATURY-KONIEC--------------------------------------------				
				//mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
				//$SPR_IMG = mysqli_query($polaczenie,"select ID from NEWS_IMG where KATALOG='$ID_KAT' AND NR_IMG='$NR_IMG'")  or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SPR_IMG - ".mysqli_error()."</span></p>");
				//$istnieje_rekord = mysqli_num_rows($SPR_IMG);
									$width=$width+10;
									$height=$height+10;
					             //if (($istnieje_rekord>0) && ($NR_IMG==$y)) {
									 if($y<=$NR_IMG){
														$dziennik_zmiana=TRUE;
														$dziennik="uaktualniono zdjęcie [$y] o nr ".$NR_IMG;
														if ($_SESSION["id_user"]==1) echo $dziennik."</br>";
														 $db->query("UPDATE `NEWS_IMG` SET `WSK_K`=WSK_K+1,`KATALOG`='$ID_KAT', `NAZWA_O`='$filename' ,`NAZWA_I`='$new_image_name[0]', `WIDTH`='$new_width[0]', `HEIGHT`='$new_height[0]',`NAZWA_I_M`='$new_image_name[1]', `M_WIDTH`='$new_width[1]', `M_HEIGHT`='$new_height[1]' WHERE `ID_NEWS`='$ID' AND `NR_IMG`='$y'");
														 if($_SESSION['id_user']==1) {
																					echo '<p style="text-align:left;margin-left:20px;">Uaktualniono zdjęcie w bazie danych - '.$y.'</p>';
																					echo "SQL_UPDATE = $UPDATE_O </br>";
																					$SQL_ZAD="INSERT";
														 };
								 } else {
										$NR_IMG++;
										$dziennik="dodano nowe zdjęcie [$y] o nr ".$NR_IMG;
										if ($_SESSION["id_user"]==1) echo $dziennik."</br>";
										$db->query("INSERT INTO NEWS_IMG (ID_NEWS,KATALOG,NAZWA_O,NAZWA_I,ID_PERS,DAT_UTW,NR_IMG,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT) VALUES ('$ID','$ID_KAT','$filename','$new_image_name[0]','$ID_PERS',NOW(),'$NR_IMG','$new_width[0]','$new_height[0]','$new_image_name[1]','$new_width[1]','$new_height[1]')");
										
										if($_SESSION['id_user']==1) {
											echo '<p style="text-align:left;margin-left:20px;">Wstawiono nowe zdjecie do bazy danych - '.$NR_IMG.'</p>';
											echo "SQL_INSERT = $INSERT_O </br>";
											$SQL_ZAD="UPDATE";
										};
								 };
				if ($_SESSION["id_user"]==1) echo "Wykonano - SQL[$SQL_ZAD] </br>";
				echo '<a HREF="javascript:displayWindow(&#39;'.$uploads_dir.$new_image_name[0].'&#39;,'.$new_width[0].','.$new_height[0].')">';
				echo '<img src="'.$uploads_dir.'/'.$new_image_name[1].'" alt="ARTYKUŁ"><br/>';
				echo '</a>';
                                $db->insDbLog($_GET["IDM"],"EDYTUJ IMG - ".$dziennik." ID : ".$ID);
		}
	else {
			echo '<span style="color:red">Nie wskazano <span style="color:#0099FF;">NOWEGO</span> obrazu pomocniczego nr : <span style="color:black;">'.$y.'</span></span><BR/>';
		};
	if ($_SESSION["id_user"]==1) echo "\$y - $y || \$NR_IMG - $NR_IMG </br>";	
	};	
$db->query("UPDATE `NEWS` SET `ILOZD`='$NR_IMG' ,`ID_KATALOG`='$ID_KAT' WHERE `ID`='$ID' ");
$db->insDbLog($_GET["IDM"],"EDYTUJ IMG - uaktualniono ILE_ZD ".$NR_IMG." ID : ".$ID);
