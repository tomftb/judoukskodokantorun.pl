<?php
function myimagebmp ($filename)
{
  echo "Jestes w funkcji myimagebmp</br>";
  echo "Wczytano plik - ".$filename."</br>";
	//Create image from the BMP file
	//RETURN: = Image handle, or
	///		  = false if error
	if(!$f1 = fopen($filename, "rb"))
		return false;

	$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
	if($FILE['file_type'] != 19778)
		return false;

	$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
		'/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
		'/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));

	$BMP['colors'] = pow(2, $BMP['bits_per_pixel']);

	if($BMP['size_bitmap'] == 0)
		$BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];

	$BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
	$BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
	$BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
	$BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
	$BMP['decal'] = 4 - (4 * $BMP['decal']);
	if($BMP['decal'] == 4)
		$BMP['decal'] = 0;

	$PALETTE = array();
	if($BMP['colors'] < 16777216)
		$PALETTE = unpack('V'.$BMP['colors'], fread($f1, $BMP['colors'] * 4));

	$IMG = fread($f1, $BMP['size_bitmap']);
	$VIDE = chr(0);

	$res = imagecreatetruecolor($BMP['width'], $BMP['height']);
	$P = 0;
	$Y = $BMP['height'] - 1;
	while($Y >= 0)
	{
		$X=0;
		while($X < $BMP['width'])
		{
			switch($BMP['bits_per_pixel'])
			{
			case 32:
				$COLOR = unpack("V", substr($IMG, $P, 4));
				break;
			case 24:
				$COLOR = unpack("V", substr($IMG, $P, 3).$VIDE);
				break;
			case 16:
				$COLOR = unpack("n", substr($IMG, $P, 2));
				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
				break;
			case 8:
				$COLOR = unpack("n", $VIDE.substr($IMG, $P, 1));
				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
				break;
			case 4:
				$COLOR = unpack("n", $VIDE.substr($IMG, floor($P), 1));
				if(($P * 2) % 2 == 0)
					$COLOR[1] = ($COLOR[1] >> 4);
				else
					$COLOR[1] = ($COLOR[1] & 0x0F);
				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
				break;
			case 1:
				$COLOR = unpack("n", $VIDE.substr($IMG, floor($P), 1));
				switch(($P * 8) % 8)
				{
				case 0:
					$COLOR[1] = $COLOR[1] >> 7;
					break;
				case 1:
					$COLOR[1] = ($COLOR[1] & 0x40) >> 6;
					break;
				case 2:
					$COLOR[1] = ($COLOR[1] & 0x20) >> 5;
					break;
				case 3:
					$COLOR[1] = ($COLOR[1] & 0x10) >> 4;
					break;
				case 4:
					$COLOR[1] = ($COLOR[1] & 0x8) >> 3;
					break;
				case 5:
					$COLOR[1] = ($COLOR[1] & 0x4) >> 2;
					break;
				case 6:
					$COLOR[1] = ($COLOR[1] & 0x2) >> 1;
					break;
				case 7:
					$COLOR[1] = $COLOR[1] & 0x1;
					break;
				}

				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
				break;
			default:
				return false;
				break;
			}

			imagesetpixel($res, $X, $Y, $COLOR[1]);

			$X++;
			$P += $BMP['bytes_per_pixel'];
		}

		$Y--;
		$P += $BMP['decal'];
	}

	fclose($f1);
	return $res;
}

function resize_image($width,$height,$new_size,$img_new_name,$ext,$uploads_dir,$image_name)
{
$scale = 1;
$ext_change=TRUE;
$ext_low=strtolower($ext);
if ($_SESSION['id_user']==1){ 
							echo "<p class=\"P_INFO\">Wywołane wewnatrzy funkcji WIDTH : <span class=\"S_INFO\">".$width."</span> HEIGHT : <span class=\"S_INFO\">".$height."</span></p>";};
if (preg_match('/min_/',$img_new_name )){
										if ($width>$height) { 
										$scale=($new_size/$width);
										if ($_SESSION['id_user']==1)  echo "<p class=\"P_INFO\">Skalowanie : <span class=\"S_INFO\">".$scale."</span></p>";
										}
										else { 
												$scale=($new_size/$height); 
												if ($_SESSION['id_user']==1) echo "scale - ".$scale."<br/>"; 	
										};
} 
else{
	$scale=($new_size/$height); 
	if ($_SESSION['id_user']==1) echo "scale - ".$scale."<br/>"; 	
}
																						if ($scale<1) {
																								$source = $uploads_dir.$image_name;
																								$dest = $uploads_dir.$img_new_name;
																								$quality = 100;
																								$imsize = getimagesize($source);
																								$width = $scale * $imsize[0];
																								$height = $scale * $imsize[1];
																					switch ($ext_low){
																							case "jpg": 
																										if($_SESSION['id_user']==1)echo "<p class=\"P_INFO\">Rozszerzenie obrazu -  <span class=\"S_INFO\">JPG</span></p>";
																										$img_resize=imagecreatefromjpeg($source);
																										$ext_change=TRUE;
																										break;
																							case "jpeg":
																										if($_SESSION['id_user']==1)echo "<p class=\"P_INFO\">Rozszerzenie obrazu - <span class=\"S_INFO\">JPEG</span></p>";
																										$ext_change=TRUE;
																										$img_resize=imagecreatefromjpeg($source);
																										break;
																							case "png": 
																										if($_SESSION['id_user']==1) echo "<p class=\"P_INFO\">Rozszerzenie obrazu - <span class=\"S_INFO\">PNG</span></p>";
																										$ext_change=TRUE;
																										$img_resize=imagecreatefrompng($source);
																										break;
																							case "bmp": 
																										if($_SESSION['id_user']==1) echo "<p class=\"P_INFO\">Rozszerzenie obrazu - <span class=\"S_INFO\">BMP</span></p>";
																										$ext_change=TRUE;
																										$img_resize= myimagebmp($source);
																										break;
																							case "gif": 
																										if($_SESSION['id_user']==1)echo "<p class=\"P_INFO\">Rozszerzenie obrazu - <span class=\"S_INFO\">GIF</span></p>";
																										$ext_change=TRUE;
																										$img_resize=imagecreatefromgif($source);
																										break;																							
																							default:
																									  if($_SESSION['id_user']==1) echo "<p class=\"P_ERROR\">Żadna z powyższych !</p>";
																									  $ext_change=FALSE;
																									  break;
																						};
																				if($ext_change==TRUE){
																				$newim = imagecreatetruecolor($width,$height);
																				imagecopyresampled($newim, $img_resize, 0, 0, 0, 0, $width, $height, $imsize[0], $imsize[1]);
																				imagejpeg($newim, $dest, $quality);
																				//$image_name=$img_title_name;
																				} else {
																				//$image_name=$img_title.$image_name;	
																				}
																				};
return array($width,$height,$img_new_name);
};
?>