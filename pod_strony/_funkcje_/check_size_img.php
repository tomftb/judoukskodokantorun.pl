<?php
function check_size_img($max_width,$max_height,$uploads_dir_include_name)
{
	list($width, $height) = getimagesize($uploads_dir_include_name);	
	echo "<p class=\"P_INFO\">check_size_img.php -> \$uploads_dir_include_name = <span class=\"S_INFO\">$uploads_dir_include_name</span></p>";
	$SCIEZKA_RESIZE="";
	if($width>$height){
						$SCIEZKA_RESIZE.="[1] width [$width] > height [$height]";
						if($width>$max_width) {
												$SCIEZKA_RESIZE.="[2] --> width [$width] > max_width [$max_width]";
												$skalowanie_width=$max_width/$width;
												$skalowanie_height=$skalowanie_width*$height;
												if($skalowanie_height>$max_height) {
																					$SCIEZKA_RESIZE.=" [5] --> skalowanie_height [$skalowanie_height] > max_height [$max_height]";
																					$resize=$max_height/$height;
																					$SCIEZKA_RESIZE.=" --> resize --> $resize";
												}
												else {
														$SCIEZKA_RESIZE.=" [6] --> skalowanie_height [$skalowanie_height] < max_height [$max_height]";
														$resize=$max_width/$width;
														$SCIEZKA_RESIZE.=" resize --> $resize";
												};																				
						} 
						else if ($height>$max_height){
														$SCIEZKA_RESIZE.=" [3] --> height[$height] > \$max_height [$max_height]";
														$resize=$max_height/$height;
						}
						else{
								$resize=1; 
								echo "<p class=\"P_ERROR\">[4] NIC NIE PASUJE !</p>";
						};
	} 
	else if ($width<$height){
							$SCIEZKA_RESIZE.="[7] height > width";
							if($height>$max_height) {
														$SCIEZKA_RESIZE.=" [8] --> height[$height] \ max_height [$max_height]";
														$skalowanie_height=$max_height/$height;
														$skalowanie_width=$skalowanie_height*$width;
														if($skalowanie_width>$max_height) {
																							$resize=$max_width/$width;
																							$SCIEZKA_RESIZE.=" [11] resize --> $resize";
														}
														else {																													
																$resize=$max_height/$height;
																$SCIEZKA_RESIZE.=" [12] resize --> $resize";
														};
							} 
							else if ($width>$max_width){ 
														$SCIEZKA_RESIZE.=" [9] --> width>\$max_width";
														$resize=$max_width/$width;
							}
							else {
									$resize=1; 
									echo "<p class=\"P_ERROR\"> [10] NIC NIE PASUJE !</p>";
							};
	} 
	else if($width==$height){
							if($max_height<$max_width) $resize=$max_height/$height; else $resize=$max_width/$width;
							$SCIEZKA_RESIZE.=" [14] --> height[$height] \ max_height [$max_height]";
							$resize=$max_height/$height;
	}
	else {
			$resize=1; 
			echo "<p class=\"P_ERROR\"> [13] NIC NIE PASUJE !</p>";
	};
	if($_SESSION['id_user']==1) {
								echo "<p class=\"P_ERROR\">SCIEZKA_RESIZE : <span class=\"S_INFO\">$SCIEZKA_RESIZE</span></p>";
	};
	return ($resize);
}