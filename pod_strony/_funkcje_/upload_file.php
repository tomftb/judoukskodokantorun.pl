<?php
function upload_file($PLIK,$PLIK_TMP,$KATALOG,$RODZAJ,$POST_MAX_W,$POST_MAX_H,$POST_MIN_W,$POST_MIN_H){
	$TAB_POST_W=array($POST_MAX_W,$POST_MIN_W);
	$TAB_POST_H=array($POST_MAX_H,$POST_MIN_H);
																//echo 'Odebrano obraz : '.$_FILES['obraz'.$y]['name'].'<br/>';
																echo "Odebrano obraz : $PLIK <br/>";
																$image_name_tab = Array("org_"=>"org_0_".$RODZAJ, "new_" => "new_0_".$RODZAJ,"min_"=>"min_0_".$RODZAJ);
																$licznik_plik=0;						
																//$uploads_dir = '/zdjecia/nasze/'; 
																//$filename = $_FILES['obraz'.$y]['name'];
																
																$ext = strtolower(substr(strrchr($PLIK, '.'), 1)); //Get extension
																foreach($image_name_tab as $key => $value){
																											$image_name_tab[$key]=$key.$licznik_plik.$RODZAJ.".".$ext;
															
																while(file_exists($KATALOG.$image_name_tab[$key])){
																														
																														$licznik_plik++;
																														$image_name_tab[$key]=$key.$licznik_plik.$RODZAJ.".".$ext;
																};
																$licznik_plik=0;
																};
																echo "TAB 0 [org_] - $image_name_tab[org_] </br>";
																echo "TAB 1 [new_] - $image_name_tab[new_] </br>";
																echo "TAB 2 [min_] - $image_name_tab[min_] </br>";
																move_uploaded_file($PLIK_TMP, "$KATALOG$image_name_tab[org_]");
																//--------------------------------------------------------------------------------------------------------------------------RESIZE-IMG-------------------------------------------------
																		list($width, $height) = getimagesize($KATALOG.'/'.$image_name_tab["org_"]);
																		$IMG_NEW=FALSE;
																		for ($p_resize=0;$p_resize<2;$p_resize++){
																											switch ($p_resize):
																																			case 0: 
																																					$naglowek_img=$image_name_tab["new_"];
																																					$naglowek="Galeria ";
																																					break;
																																			case 1:
																																					$IMG_NEW=TRUE;
																																					$naglowek_img=$image_name_tab["min_"];
																																					$naglowek="Miniatura ";
																																					break;
																																			default:
																																					$naglowek_img='def_'.$licznik_plik."_our.".$ext;
																																					$naglowek="DEFAULT ";
																																					break;
																													endswitch;
																		if ($width>$TAB_POST_W[$p_resize] || $height>$TAB_POST_H[$p_resize]){
																												
																												if ($_SESSION['id_user']==1){
																																			echo "<p class=\"P_ERROR\">Nowy rozmiary ($naglowek) WIDTH : <span class=\"S_INFO\">".$TAB_POST_W[$p_resize]." px</span>";
																																			echo " HEIGHT : <span class=\"S_INFO\">".$TAB_POST_H[$p_resize]." px</span></p>";
																												};
																												if (($IMG_NEW==TRUE) && (file_exists($KATALOG."/new_".$licznik_plik."_our.".$ext))) {
																																//$image_name='new_'.$licznik_plik."_our.".$ext;
																																$image_name=$image_name_tab["new_"];
																												};
																												$wynik_img_size = resize_image($TAB_POST_W[$p_resize],$TAB_POST_H[$p_resize],$ext,$naglowek_img,$KATALOG,$image_name_tab["org_"]); //$image_name
																												$new_width[$p_resize]=round($wynik_img_size[0]);
																												$new_height[$p_resize]=round($wynik_img_size[1]);
																												$new_image_name[$p_resize]=$wynik_img_size[2];
																		} else {
																				$new_image_name[$p_resize]=$image_name_tab["org_"]; //$image_name;
																				$new_height[$p_resize]=round($height);
																				$new_width[$p_resize]=round($width);
																				};
																		if($_SESSION['id_user']==1) {
																									echo "<p class=\"P_INFO\">".$naglowek;
																									echo " WIDTH : <span class=\"S_INFO\">".$new_width[$p_resize]."</span>";
																									echo " HEIGHT : <span class=\"S_INFO\">".$new_height[$p_resize]."</span></p>";
																									};
																		};
//--------------------------------------------------------------------------------------------------------------------------KONIEC-RESIZE-IMG--------------------------------------------			
return array($new_width[0],$new_height[0],$new_image_name[0],$new_width[1],$new_height[1],$new_image_name[1]);
};
?>