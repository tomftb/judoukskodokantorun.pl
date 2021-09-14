<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
</head>
<body>
<?php
//echo "<p class=\"P_INFO\">ver. <span class=\"S_INFO\">6</span></br>"; // ----------------VERSION
include("check_size_img.php");
include("imagebmp.php");


function resize_image($max_width,$max_height,$ext,$img_new_name,$uploads_dir,$image_name)
{
    $uploads_dir_incude_name=$uploads_dir.$image_name;
    echo "<p class=\"P_INFO\">".__LINE__.":: RESIZE_IMAGE_NEW -> \$uploads_dir_incude_name = <span class=\"S_INFO\">$uploads_dir_incude_name</span></br>";
    $scale= check_size_img($max_width,$max_height,$uploads_dir_incude_name);
    echo __LINE__.":: RESIZE_IMAGE_NEW -> \$scale = <span class=\"S_INFO\">$scale</span></br>";
    $ext_change=TRUE;
    echo __LINE__.":: RESIZE_IMAGE_NEW -> TYP pliku - <span class=\"S_INFO\">".mime_content_type("$uploads_dir_incude_name") . "</span></p>";
    //$ext=strtolower(mime_content_type("$uploads_dir_incude_name"));
    $ext=mime_content_type("$uploads_dir_incude_name");
    //$ext = strtolower(substr(strrchr(mime_content_type("$uploads_dir_incude_name"), '/'), 1)); //Rozszerzenie
    if ($_SESSION['id_user']==1)
    { 
        echo "<p class=\"P_INFO\">[".__LINE__."] RESIZE_IMAGE_NEW -> WIDTH : <span class=\"S_INFO\">$max_width</span> HEIGHT : <span class=\"S_INFO\">$max_height</span> EXT : [<span class=\"S_INFO\">$ext</span>]</p>";
    }
																				if ($scale<1 || ($ext=="image/x-ms-bmp")) {
																								$dest = $uploads_dir.$img_new_name;
																								$quality = 100;
																								$imsize = getimagesize($uploads_dir_incude_name);
																								$width = $scale * $imsize[0];
																								$height = $scale * $imsize[1];
																								echo "strcmp : ".strcmp("image/bmp","image/bmp")."</br>";
																								//$num1 = var_dump($ext);
																								//$num2 = var_dump("gif");
																								if(!$f1 = fopen($uploads_dir_incude_name, "rb")){
																										echo "Nie można otworzyć pliku !</br>";
																										return false;
																									}
																								 $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
																								echo "File type - ".$FILE['file_type']."</br>";
																								//echo "num1 :  $num1 | num2 :  $num2 </BR>";
																								//if(strcmp("gif", $ext)) echo "TO NIE JEST GIF !</BR>"; else echo "TO JEST GIF !</BR>";
																								if('image/x-ms-bmp'===$ext) echo "TO JEST GIF !</BR>"; else echo "TO NIE JEST GIF !</BR>";
																					
																					switch ($ext){
																							case "image/jpeg":
																							case "image/jpg": 
																							
																										if($_SESSION['id_user']==1) {
																																	echo "<p class=\"P_INFO\">Rozszerzenie obrazu -  <span class=\"S_INFO\">";
																																	if($ext_low=="jpg")echo "JPG"; else echo "JPEG";
																																	echo "</span></p>";
																										};
																										$img_resize=imagecreatefromjpeg($uploads_dir_incude_name);
																										$ext_change=TRUE;
																										break;
																							case "image/png": 
																										if($_SESSION['id_user']==1) echo "<p class=\"P_INFO\">Rozszerzenie obrazu - <span class=\"S_INFO\">PNG</span></p>";
																										$ext_change=TRUE;
																										$img_resize=imagecreatefrompng($uploads_dir_incude_name);
																										break;
																							case "image/gif": 
																										if($_SESSION['id_user']==1)echo "<p class=\"P_INFO\">Rozszerzenie obrazu - <span class=\"S_INFO\">GIF</span></p>";
																										$ext_change=TRUE;
																										$img_resize=imagecreatefromgif($uploads_dir_incude_name);
																										break;
																							case "image/x-ms-bmp":
																							case "image/bmp": 
																										if($_SESSION['id_user']==1)
                                                                                                                                                                                                                {
                                                                                                                                                                                                                    echo "<p class=\"P_INFO\">Rozszerzenie obrazu -  <span class=\"S_INFO\">";
                                                                                                                                                                                                                    if($ext_low=="bmp")echo "BMP"; else echo "x-ms-bmp";
                                                                                                                                                                                                                    echo "</span></p>";
																										}
																										$ext_change=TRUE;
																										$img_resize= imagebmp($uploads_dir_incude_name);
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
																				
																				} else {
																				$img_new_name=$image_name;	
																				}
																				};
return array($width,$height,$img_new_name);
};
?>
</body>
</html>