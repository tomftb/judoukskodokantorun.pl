<?php if(!defined('PAGE_URL')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<a href="'.PAGE_URL.'&IDW=0" class="A_BACK"><p class="P_HREF_BACK">Anuluj</p></a>';
echo '<p CLASS="P_MAIN_CEL">'.$frameTitle.'</p>';

$status_dodaj=0;
$err=array();
$err_spojnosc=array();
$err_obraz_tab=array();
$spojnosc_dancyh=array();
$checked=TRUE;
$track='';
if (isset($_POST["klasa"]))
{							
    $first_img=FALSE;
    $first_txt=TRUE;
    $spojnosc=TRUE;
    $empty_txt=TRUE;
    $empty_img=TRUE;
    $string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ .'!:-_\r\n]+$/";							
    $ch_img=array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
							// Tekst poprawny rozpoczynamy upload plików
							for( $y = 1; $y<21; $y++ ) {
														if ($y==1) {
																	if(!preg_match($string_exp,$_POST["dane".$y])) { $err[$y]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $first_txt=FALSE;};
																	check_len($first_txt, $_POST["dane".$y],2000,$err[$y],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">2000</span>)',4,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
																	$empty_txt=FALSE;
																	//echo "empty txt PIERWSZY TXT (".$y.") = ".$empty_txt."</br>";
																	}
																	else if (trim($_POST["dane".$y])!='') {
																									if(!preg_match($string_exp,$_POST["dane".$y])) { $err[$y]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $checked=FALSE;};
																									check_len($first_txt, $_POST["dane".$y],2000,$err[$y],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">2000</span>)',4,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
																									$empty_txt=FALSE;
																									//echo "empty txt NIE PUSTY (".$y.") = ".$empty_txt."</br>";
																									} else {
																											$empty_txt=TRUE;
																											//echo "empty txt PUSTY (".$y.") = ".$empty_txt."</br>";
																											};
																			if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"])) {
																					$empty_img=FALSE;
																								if(($_FILES["obraz".$y]["type"] == 'image/gif') || 
																											  ($_FILES["obraz".$y]["type"] == 'image/jpeg') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/jpg') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/png') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/bmp') || 
																											   ($_FILES["obraz".$y]["type"] == 'image/pjpeg')) 
																															{
																																$max_rozmiar = 8388608;
																																if ($_FILES["obraz".$y]["type"] < $max_rozmiar){
																																									$first_img=TRUE;
																																									if($empty_txt==TRUE) $empty_img=TRUE; else $empty_img=FALSE; // Włączenie możliwości dodanie IMG bez TXT
																																									$err_obraz_tab[$y]="<span class=\"S_OK_DANE\">Proszę jeszcze raz wczytać zdjęcie !</span>";
																																									//echo "empty img = ".$empty_img."</br>";
																																									$ch_img[$y]=1;
																																									} else {
																																											$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Za duży rozmiar obrazu !</span>";
																																											$empty_img=FALSE;
																																											$ch_img[$y]=0;
																																											//echo "empty img = ( Za duży rozmiar obrazu ) ".$empty_img."</br>";
																																											}
																															} else { 
																																	$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Wskazany plik nie jest obrazem !</span>";
																																	$empty_img=FALSE;
																																	$ch_img[$y]=0;
																																	//echo "empty img = ( Wskazany plik nie jest obrazem )".$empty_img."</br>";
																																	}
																			} else { 
																					if ($empty_txt==FALSE)$err_obraz_tab[$y]='<span class="S_ERR_DANE">Nie wskazano obrazu !</span>';
																					$empty_img=TRUE;
																					$ch_img[$y]=1;
																					//echo "empty img ( Nie wskazano obrazu ".$y." )= ".$empty_img."</br>";
																					}; 
																			if(($empty_txt!=$empty_img) || ($ch_img[$y]!=1)){
																															$spojnosc=FALSE;
																															if ($_SESSION['id_user']==1) echo "<p class=\"P_ERROR\">Spójność : <span class=\"S_ERROR\">FALSE</span></p>";
																															$err_spojnosc[$y]="<span class=\"S_ERR_DANE\">BRAK spójności danych!</span>";
																															};
																			}; // KONIEC petla FOR dla plików
    if (($checked==FALSE) || ($first_img==FALSE) || ($first_txt==FALSE) || ($spojnosc==FALSE)) $status_dodaj=0; else $status_dodaj=1;
}
//------------------------------------------ status_dodaj = 0 -------------------------------------------------------------------------
if ($status_dodaj==0)
{												
    echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
    for ($tr=1;$tr<21;$tr++){
												if ($tr==1) {
															$info_dod='<span class="S_NG_DANE">*</span>';
															$font_w="BOLD";
															$font_s="20px";
															$rows=1;
															$opis="Rocznik ";
															} else {
																	$info_dod='';
																	$font_w="BOLD";
																	$font_s="16px";
																	$rows=3;
																	$opis="Opis ";
																	};							
												echo '<div class="DIV_DODAJ">';
                                                                                                if(array_key_exists($tr, $err_spojnosc)) { echo $err_spojnosc[$tr]; }
												echo '<p class="P_INPUT" style="font-weight:'.$font_w.';font-size:'.$font_s.'">Zdjęcie nr '.$tr.' '.$info_dod.' <input type="file" name="obraz'.$tr.'"/>';
                                                                                                if(array_key_exists($tr, $err_obraz_tab)) { echo $err_obraz_tab[$tr]; }
                                                                                                echo '</p>';
												echo '<p class="P_INPUT" style="font-weight:'.$font_w.';font-size:'.$font_s.'">'.$opis.''.$info_dod.' : ';
                                                                                                if(array_key_exists($tr, $err)) { echo $err[$tr]; }
                                                                                                echo '</p>';
												echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA"">';  
												if(filter_input(INPUT_POST,"dane".$tr))
                                                                                                {
                                                                                                    echo $_POST["dane".$tr];
                                                                                                }									
												echo '</textarea>';
												echo '</div>';
												}
						echo '<input class="button"type="submit" value="Dodaj" name="klasa"/></form>';
						echo '<p class="P_LEG">Legenda :</p>';
						echo "<ul class=\"UL_LEG\">";
						echo '<li class="LI_LEG">pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane ;</li>';
						echo '<li class="LI_LEG">NIE dozwolone jest wprowadzenie <span class="S_LEG_INFO">OPISU</span> bez <span class="S_LEG_INFO">ZDJĘCIA</span> ! ;</li>';
						echo '<li class="LI_LEG">OPIS może zawierać max (<span class="S_LEG_INFO">2000</span>) znaków</li> ';
						echo '<li class="LI_LEG">OPIS zdjęcia <span class="S_LEG_INFO">NR 1</span> musi zawierać min (<span class="S_LEG_INFO">4</span>) znaków ;</li>';
						echo '<li class="LI_LEG">ZDJĘCIE, dozwolony TYP : (<span class="S_LEG_INFO">JPG JPEG PNG BMP GIF</span>) ;</li> ';
						echo '<li class="LI_LEG">ZDJĘCIE,  <span class="S_LEG_INFO">MAX</span> Rozmiar ~ <span class="S_LEG_INFO">8 MB</span> ;</li> ';
						echo "</ul>";
						echo '</center></div>';
//------------------------------------------ koniec-status_dodaj = 0 -------------------------------------------------
}
else if($status_dodaj==1)
{

    //------------------------------------------ status_dodaj = 1 --------------------------------------------------
    $INS_MYSQL=FALSE; // 
    $max_height=0;
    $max_width=0;
    $new_image_name=array('','');
    $new_height=array(0,0);
    $new_width=array(0,0);
    $p_resize=0; // START PĘTLI
    $true_i=1;
    for ($i=1;$i<21;$i++)
    {
        if (is_uploaded_file($_FILES["obraz".$i]['tmp_name'])) 
	{
																			if($true_i==1){
																						$i_kat=1;
																						$katalog="../../zdjecia/klasa_sp/1";
																						while(file_exists($katalog)){
																														//echo "<p class=\"P_ERROR\">Katalog istnieje - <span class=\"S_ERROR\">".$i_kat."</span> </p>";
																														$i_kat++;
																														$katalog="../../zdjecia/klasa_sp/".$i_kat;
																													};
																						$lp_html=1;
																						$lp_xml=1;
																						if (!mkdir($katalog, 0755, TRUE)) {
																															die('<p class="P_ERROR">NIE utworzono katalogu (<span class="S_ERROR">'.$i_kat.'</span>) !</p>');
																															} else echo '<p class="P_INFO">Utworzono katalog (<span class="S_INFO">'.$i_kat.'</span>) !</p>';
																						if($_SESSION['id_user']==1) {
																												echo "<p class=\"P_INFO\">Nowy nr pliku html : <span class=\"S_INFO\">".$lp_html."</span></p>";
																												echo "<p class=\"P_INFO\">Nowy nr pliku xml : <span class=\"S_INFO\">".$lp_xml."</span></p>";
																												};
																						};
																		$filename = $_FILES["obraz".$i]['name'];
																		$ext = strtolower(substr(strrchr($filename, '.'), 1)); //Rozszerzenie
																		$image_name = 'org_'.$true_i . '_kl_sp.' . $ext; //Nowa nazwa pliku
																		$tmp_name = $_FILES["obraz".$i]["tmp_name"]; 
																		move_uploaded_file($tmp_name,"$katalog/$image_name");
//--------------------------------------------------------------------------------------------------------------------------RESIZE-IMG-------------------------------------------------
																		list($width, $height) = getimagesize($katalog.'/'.$image_name);
																		for ($p_resize=0;$p_resize<2;$p_resize++){
																													switch ($p_resize):
																																			case 0: 
																																					$rozmiar=720;
																																					
																																					$naglowek_img='new_'.$true_i."_kl_sp.".$ext;
																																					$naglowek="Galeria ";
																																					
																																					break;
																																			case 1:
																																					$IMG_NEW=TRUE;
																																					$rozmiar=324;
																																					$naglowek="Miniatura ";
																																					
																																					$naglowek_img='min_'.$true_i."_kl_sp.".$ext;
																																					break;
																																			default:
																																					$rozmiar=720;
																																					$naglowek_img='def_'.$true_i."_kl_sp.".$ext;
																																					$naglowek="DEFAULT ";
																																					break;
																													endswitch;
																		if ($width>$rozmiar || $height>$rozmiar){ // 0 miniatura 900x900  // 1 324x324 
																												if (($IMG_NEW==TRUE) && (file_exists($katalog."/new_".$true_i."_kl_sp.".$ext))) {
																																	$image_name='new_'.$true_i."_kl_sp.".$ext;
																																	list($width, $height) = getimagesize($katalog.'/'.$image_name);
																												};
																												echo "$ext $katalog $image_name</br>";
																												$wynik_img_size = resize_image($rozmiar,$rozmiar,$ext,$naglowek_img,$katalog.'/',$image_name);
																												$new_width[$p_resize]=round($wynik_img_size[0]);
																												$new_height[$p_resize]=round($wynik_img_size[1]);
																												$new_image_name[$p_resize]=$wynik_img_size[2];
																		} else {
																				$new_image_name[$p_resize]=$image_name;
																				$new_height[$p_resize]=round($height);
																				$new_width[$p_resize]=round($width);
																				};
																		if($_SESSION['id_user']==1) {
																									echo "<p class=\"P_INFO\">".$naglowek." WIDTH : <span class=\"S_INFO\">".$new_width[$p_resize]."</span>";
																									echo " HEIGHT : <span class=\"S_INFO\">".$new_height[$p_resize]."</span></p>";
																									};
																		};
//--------------------------------------------------------------------------------------------------------------------------KONIEC-RESIZE-IMG--------------------------------------------
																		
																		if($true_i==1)
                                                                                                                                                {
                                                                                                                                                    $F_IMG_N=$new_image_name[1];
                                                                                                                                                    $F_IMG_H=$new_height[1];
                                                                                                                                                    $F_IMG_W=$new_width[1];
                                                                                                                                                    $title_html=trim($_POST["dane".$true_i]);
                                                                                                                                                    $db->query("INSERT INTO KLASA (NAZW_O,HTML,NAZW_I,OPIS,KATALOG,XML,ID_PERS,DAT_UTW,WIDTH,HEIGHT) VALUES ('$filename','$lp_html.html','$new_image_name[1]','$title_html','$i_kat','$lp_xml.xml','".$_SESSION['id_user']."',NOW(),'$new_width[1]','$new_height[1]')");
																						
																						
																						
                                                                                                                                                    $ID_KLASA=$db->last();
																		}
																		if($max_width<$new_width[0])$max_width=$new_width[0];
																		if($max_height<$new_height[0])$max_height=$new_height[0];
																		if(trim($_POST["dane".$i]) != '') $image_info=trim($_POST["dane".$i]); else $image_info="-";
																		$db->query("INSERT INTO KLASA_IMG (ID_KLASA,NAZW_O,NAZW_I,OPIS,NR_IMG,ID_PERS,DAT_UTW,WIDTH,HEIGHT,WIDTH_M,HEIGHT_M,NAZWA_I_M) VALUES ('$ID_KLASA','$filename','$new_image_name[0]','$image_info','$true_i','".$_SESSION['id_user']."',NOW(),'$new_width[0]','$new_width[0]','$new_width[1]','$new_height[1]','$new_image_name[1]')");
																		$track.='<track><title>'.$image_info.'</title><creator></creator><location>'.$new_image_name[0].'</location><info></info></track>';
																		$true_i++;
                                                                                                                                                
																		
        }
    } // END FOR LOOP
$dok_html= '<html><head><title>Rocznik : '.$title_html.'</title>
<meta name="Description" content="JUDO"/>
<meta name="Keywords" content="JUDO SPORT" />
<meta name="Author" content="Tomasz Borczynski; mass[.]hopto[.]org [@] gmail [.] com" />
<meta name="Robots" content="all" />
<meta http-equiv="Content-language" content="pl" />
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<link rel="shortcut icon" href="../../../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../../../images/favicon.ico" type="image/x-icon">
<style type="text/css" id="stylescreen" media="screen"></style>
</head><body><center>
<div id="container"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this rotator.</div>
<script type="text/javascript" src="../swfobject.js"></script>
<script type="text/javascript">
var s1 = new SWFObject("../imagerotator.swf","rotator","'.$max_width.'","'.$max_height.'","7");
s1.addVariable("file","1.xml");
s1.addVariable("width","'.$max_width.'");
s1.addVariable("height","'.$max_height.'");
s1.write("container");
</script>
</center><body></html>';
    $uchwyt_html = fopen($katalog.'/1.html' , "w");						
    fwrite($uchwyt_html,$dok_html);
    fclose($uchwyt_html);
    $dok_xml = '<?xml version="1.0" encoding="UTF-8"?><playlist version="1" xmlns="http://xspf.org/ns/0/"><trackList>'.$track.'</trackList></playlist>';
    $uchwyt_xml = fopen($katalog.'/1.xml' , "w");						
    fwrite($uchwyt_xml,$dok_xml );
    fclose($uchwyt_xml);
    flush();
    $max_w_ins=$max_width+40; 
    $max_h_ins=$max_height+30;						
    $db->query("UPDATE `KLASA` SET `MAX_W`='$max_w_ins',`MAX_H`='$max_h_ins' WHERE `ID`='$ID_KLASA'");
    echo '<div class="DIV_IMG"><center>';
    echo "<a HREF=\"javascript:displayWindow('$katalog/1.html',$max_w_ins,$max_h_ins)\">";
    echo '<img src="'.$katalog.'/'.$F_IMG_N.'" alt="'.$F_IMG_N.'"  style="width:'.$F_IMG_W.'; height:'.$F_IMG_H.'; border:0px;" />';
    echo '</a><p class="P_INFO_IMG">(kliknij na zdjęcie aby uruchomić galerię)</p></center><p class="P_DANE">Rocznik : <span style="font-weight:bold;font-size:14px;color:black;font-style:normal;">'.$title_html.'</span></p></div>';
    foreach ($_POST as $key => $value)
    {
	UNSET($_POST[$key]);
    }
echo '<a href ="'.PAGE_URL.'&IDW=0" class="A_BACK"><p class="P_HREF_BACK2">Powrót do MENU - KLASY</p></a>';
}
?>
</div>