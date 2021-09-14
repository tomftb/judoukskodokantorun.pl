<?php
function show_file($directory,$link,$pobr_case)
{
	echo "<p CLASS=\"P_INFO\">Katalog do odczytu : <span class=\"S_INFO\">".$directory."</span></p>";
	$ile_plikow=0;
	echo "<p CLASS=\"P_INFO\">Możliwość pobierania zdjęć - ";
	if ($pobr_case==1){
			echo "<span style=\"color:blue;\">TAK</span>";
			$DIV_IMG_HEIGHT="height:130px;";
	} else {
		echo "<span style=\"color:red;\">NIE</span>";
		$DIV_IMG_HEIGHT="height:85px;";
	};
	echo "</p>";
if ($handle = opendir($directory)) 
{
			while($plik=readdir($handle)){ 
										if($plik!="." && $plik!="..")
																	{
																	//if((strrchr($plik, "new")==FALSE) AND (strrchr($plik, "min")==FALSE)) $true_i++;
																	$ext = strtolower(substr(strrchr($plik, '.'), 1));
																	//if($_SESSION['id_user']==1) { echo "PLIK - ".$plik."<br/>";};
																	if((strtoupper($ext) == "GIF") || 
																											  (strtoupper($ext) == "JPEG") || 
																											   (strtoupper($ext) == "JPG") || 
																											   (strtoupper($ext) == "PNG") || 
																											   (strtoupper($ext) == "BMP") || 
																											   (strtoupper($ext) == "PJPEG")){
																												   echo "<div class=\"DIV_SHOW_IMG\" style=\"$DIV_IMG_HEIGHT\">";
																												   
																												  //echo"OBRAZ $ext  - ";
																												  
																													list($width, $height) = getimagesize($directory.'/'.$plik);
																												  echo "<center><a HREF=\"javascript:displayWindow('$link/$plik',$width,$height)\">";
																												   echo "<img src=\"$link/$plik\" alt=\"$plik\" width=\"50px\" height=\"50px\" >";
																												  echo "</a></center></br>";
																													
																													echo "<p CLASS=\"P_INFO\">Width: <span class=\"S_INFO\">$width px</span> Height: <span class=\"S_INFO\">$height px</span></br>";
																												$BYTES=filesize($directory."/".$plik);
																												$KB=round($BYTES/1024,2);
																												echo "Rozmiar : <span class=\"S_INFO\">". $KB ." KB</span></p>";
																												//int filesize ( string $directory."/."$plik );
																												if($pobr_case==1) {
																																echo 'Pobierz - <a href="download.php?directory='.$directory.'&file='.$plik.'&typ='.$ext.'" class="A_BACK" >'.$plik.'</a></br>';
																												} 
																												else {};
																												echo "</div>";
																											   }
																											   //else echo "nie obraz $ext </br>";
																	};
										};
closedir($handle);
} else {
		echo "Nie moge otworzyc katalogu ! [$directory]</br>";
};
echo "<div class=\"DIV_CLEAR\"></div>";
return $ile_plikow;
};
?>