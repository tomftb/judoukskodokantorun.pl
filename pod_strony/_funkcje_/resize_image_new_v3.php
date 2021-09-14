<?php

include("check_size_img.php");
include("imagebmp.php");

function resize_image($max_width,$max_height,$ext,$img_new_name,$uploads_dir,$image_name)
{
	$DR=filter_input(INPUT_SERVER,"DOCUMENT_ROOT");
	require_once($DR."/class/logToFile.php");
	$log=NEW logToFile();
	
	$log->log(0,"[".__FILE__."::".__LINE__."] max_width => ".$max_width);	
	$log->log(0,"[".__FILE__."::".__LINE__."] max_height => ".$max_height);	
	$log->log(0,"[".__FILE__."::".__LINE__."] ext => ".$ext);	
	$log->log(0,"[".__FILE__."::".__LINE__."] img_new_name => ".$img_new_name);	
	$log->log(0,"[".__FILE__."::".__LINE__."] uploads_dir => ".$uploads_dir);
	$log->log(0,"[".__FILE__."::".__LINE__."] image_name => ".$image_name);
	
	$uploads_dir_incude_name=$uploads_dir.$image_name;
	$scale= check_size_img($max_width,$max_height,$uploads_dir_incude_name);
	$ext_change=TRUE;
	$true_typ=mime_content_type("$uploads_dir_incude_name");
        $ext_true = strtolower(substr(strrchr(mime_content_type("$uploads_dir_incude_name"), '/'), 1)); //Rozszerzenie
        
        $log->log(0,"[".__FILE__."::".__LINE__."] true type => ".$true_typ);
	$log->log(0,"[".__FILE__."::".__LINE__."] ext true => ".$ext_true);
        
	if ($scale<1 || $ext_true=="x-ms-bmp")
	{
		$dest = $uploads_dir.$img_new_name;
                $log->log(0,"[".__FILE__."::".__LINE__."] dest img => ".$dest);
		$quality = 100;
		$imsize = getimagesize($uploads_dir_incude_name);
		$width = $scale * $imsize[0];
		$height = $scale * $imsize[1];
		$log->log(0,"[".__FILE__."::".__LINE__."] NEW WIDTH => ".$width);
                $log->log(0,"[".__FILE__."::".__LINE__."] NEW HEIGHT => ".$height);
		switch($ext_true) { 
				case "jpeg":
                    		case "jpg":
					$img_resize=imagecreatefromjpeg($uploads_dir_incude_name);
					$ext_change=TRUE;
                                    break;
				case "x-ms-bmp":
					$ext_change=TRUE;
                                        $img_resize= imagebmp($uploads_dir_incude_name);
                                    break;
				case "png":
                                        $ext_change=TRUE;
					$img_resize=imagecreatefrompng($uploads_dir_incude_name);
                                    break;
				case "gif":
					$ext_change=TRUE;
					$img_resize=imagecreatefromgif($uploads_dir_incude_name);
                                    break;
					case "bmp":
					$ext_change=TRUE;
					$img_resize= imagebmp($uploads_dir_incude_name);
					break;																		
				default:
                                        $ext_change=FALSE;
					break;
		};
		if($ext_change==TRUE)
                {
                    $log->log(0,"[".__FILE__."::".__LINE__."] ext change === TRUE ");
                    $newim = imagecreatetruecolor($width,$height);
                    imagecopyresampled($newim, $img_resize, 0, 0, 0, 0, $width, $height, $imsize[0], $imsize[1]);
                    imagejpeg($newim, $dest, $quality);
                    //$img_new_name=$dest;                                                                                                                                                         
		}
                else
                {
                    $log->log(0,"[".__FILE__."::".__LINE__."] ext change === FALSE ");
                    $img_new_name=$image_name;                                                                                                                                                    
		}
                $log->log(0,"[".__FILE__."::".__LINE__."] NEW IMAGE NAME => ".$img_new_name);
            }
            else
            {
                $log->log(0,"[".__FILE__."::".__LINE__."] Skala większa od 1");
                $log->log(0,"[".__FILE__."::".__LINE__."] Nie robie RESIZE !");
            }           
    return array($width,$height,$img_new_name);
}