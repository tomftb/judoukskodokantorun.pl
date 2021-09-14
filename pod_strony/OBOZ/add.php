<?php
if(!defined('OBOZ_51')) { exit('NO PERMISSION'); }
$TYP_DODAJ=filter_input(INPUT_GET,"TYP_DODAJ",FILTER_VALIDATE_INT);

$err=array('','','','','','','','','','','','','','','','','','','','');
$err_spojnosc=array('','','','','','','','','','','','','','','','','','','','');
$err_obraz_tab =array();
$track='';
$ext_old='';
switch($TYP_DODAJ):
            case 1:
                /*
                 * TO DO
                 * require_once(DR."/class/modulParm.php");
                    $parm=NEW modulParm();
                    $parm->setDbLink($polaczenie);
                    $parmModul=$parm->get($_GET['IDM']);
                 */
                    echo "<div class=\"DIV_MAIN\">";
                    echo "<center>";
                    //--------------------------------------------------------------start-parametry------------------
                    if ($_SESSION["id_user"]==1){echo '<p CLASS="P_INFO">Uruchomiono procedurę pobierania parametrów z bazy !</p>';};
                   
                    $SEL_PARM = $db->query("select ID,ID_GROUP,N_OPCJ,NAZWA,WART,WSK_D FROM PARM WHERE WSK_U=0 AND WSK_V=1 AND ID_MODUL=$_GET[IDM] order by ID_GROUP");
                    while($REK_PARM = mysqli_fetch_array($SEL_PARM))
                    {
                        if ($_SESSION["id_user"]==1){echo '<p CLASS="P_INFO">ID_GROUP [<span class="S_INFO">'.$REK_PARM[1].'</span>]</p>';};
                        switch($REK_PARM[1]):
									case 0:
										break;
									case 1:
										break;
									case 2:
										break;
									case 3:
										break;
									case 4:
                                                                            //--------------------------------------------------------SQL-SELECT-IMG-MAX-------------------------------------------------------------------
                                                                            echo '<p CLASS="P_INFO">Wartość [';
                                                                            switch($REK_PARM[2]):
                                                                                                default:
												case "ROZ_IMG_OBOZ_W_MIN": 
                                                                                                        		$parm_max_width[1]=$REK_PARM[4]; 
															break;
												case "ROZ_IMG_OBOZ_W_MAX": 
                                                                                                        		$parm_max_width[0]=$REK_PARM[4];
															break;
                                                                                                case "ROZ_IMG_OBOZ_H_MIN": 
															$parm_max_height[1]=$REK_PARM[4]; 
															break;
												case "ROZ_IMG_OBOZ_H_MAX": 
															$parm_max_height[0]=$REK_PARM[4]; 
															break;
                                                                            endswitch;
                                                                            echo $REK_PARM[2].'] : <span class="S_INFO">';
                                                                            echo $REK_PARM[4].' px</span></p>';
                                                                            //--------------------------------------------------------KONIEC-SQL-SELECT-IMG-MAX------------------------------------------------------------
                                                                            break;
									default:
										break;
                                                        endswitch;
						}
						//---------------------------------------------------------------koniec-parametry------------------
						$status_dodaj=0; 
                                                if (isset($_POST["klasa"]))
                                                {
                                                    $max_rozmiar = 67108864; // 	8Mb 8388608 byte || 64Mb 67108864 byte	sztucznie ustawionew na 54 Mb 56623104	byte NIE w bit							
                                                    //$checked_img=TRUE;
                                                    $wysylaniePlikow=1;
                                                    $checked=TRUE;
                                                    $first_img=FALSE;
                                                    $first_txt=TRUE;
                                                    $spojnosc=TRUE;
                                                    $empty_txt=TRUE;
                                                    $empty_img=TRUE;
                                                    $string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ .'!:-_\r\n]+$/";
                                                    
                                                    $spojnosc_dancyh=array(TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE);
                                                    $ch_img=array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
                                                    function sprawdzPlik($plik)
                                                    {
							$status=0;
							$tabImg=array ('image/gif','image/jpeg','image/jpg','image/png','image/bmp','image/pjpeg');
							foreach($tabImg  as $key => $value){
								if ($plik==$value) $status=1;
							};
							return 	$status;
                                                    };
                                                    // Tekst poprawny rozpoczynamy upload plików
//------------------------------------------------------------------------------------------------------------------- WYSYŁANIE PLIKÓW ---------------------------------------------------------------//							
							for( $y = 1; $y<21; $y++ )
                                                        { // 21
                                                            //----------------------------- GŁÓWNE ZDJĘCIE -----------------------------------------------------------------------------------------------//
                                                            if ($y==1)
                                                            {
                                                                if(!preg_match($string_exp,$_POST["dane".$y])) { $err[$y]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $first_txt=FALSE;};
								check_len($first_txt, $_POST["dane".$y],2000,$err[$y],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">2000</span>)',4,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
								$empty_txt=FALSE;
								//echo "empty txt PIERWSZY TXT (".$y.") = ".$empty_txt."</br>";
                                                            }
                                                            //----------------------------- KONIEC GŁÓWNEJ ZDJĘCIE --------------------------------------------------------------------------------------//			
                                                            else if (trim($_POST["dane".$y])!='')
                                                            {
								if(!preg_match($string_exp,$_POST["dane".$y])) { $err[$y]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $checked=FALSE;};
                                                                check_len($first_txt, $_POST["dane".$y],2000,$err[$y],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">2000</span>)',4,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
								$empty_txt=FALSE;
								//echo "empty txt NIE PUSTY (".$y.") = ".$empty_txt."</br>";
                                                            }
                                                            else
                                                            {
								$empty_txt=TRUE;
                                                                //echo "empty txt PUSTY (".$y.") = ".$empty_txt."</br>";
                                                            }
                                                            if ($wysylaniePlikow==1)
                                                            {
								if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"]))
                                                                {
                                                                    // if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"])) {
                                                                    echo "<p class=\"P_INFO\">Zaimportowano plik : <span class=\"S_INFO\">$y</span></p>";
                                                                    $empty_img=FALSE;
                                                                    $max_rozmiar = $max_rozmiar - $_FILES["obraz".$y]["size"]; 
                                                                    IF($_SESSION["id_user"]==1){ echo $max_rozmiar." | FILE : ".$_FILES["obraz".$y]["size"]."</br>"; }
                                                                    if ($_FILES["obraz".$y]["size"] < $max_rozmiar)
                                                                    {
									if(sprawdzPlik($_FILES["obraz".$y]["type"])==1)
                                                                        {
                                                                            $first_img=TRUE;
                                                                            if($empty_txt==TRUE) {$empty_img=TRUE;} else {$empty_img=FALSE;} // Włączenie możliwości dodanie IMG bez TXT
                                                                            $err_obraz_tab[$y]="<span class=\"S_OK_DANE\">Proszę jeszcze raz wczytać zdjęcie !</span>";
                                                                            //echo "empty img = ".$empty_img."</br>";
                                                                            $ch_img[$y]=1;
									} 
									else
                                                                        {
                                                                            $err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Wskazany plik nie jest obrazem !</span>";
                                                                            $empty_img=FALSE;
                                                                            $ch_img[$y]=0;
                                                                            //echo "empty img = ( Wskazany plik nie jest obrazem )".$empty_img."</br>";
									}
                                                                    }
                                                                    else
                                                                    { 
									$pokazRozmiarFile=round(($_FILES["obraz".$y]["size"] / 1048576),2) ;
                                                                        //$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Za duży rozmiar obrazu (<span class=\"S_INFO\">".$pokazRozmiarFile." Mb</span>) !</span>";
									$empty_img=FALSE;
									$ch_img[$y]=0;
									$wysylaniePlikow=0;
									//echo "empty img = ( Za duży rozmiar obrazu ) ".$empty_img."</br>";
                                                                    }
								}
								else
                                                                {
                                                                    if ($empty_txt==FALSE) {$err_obraz_tab[$y]='<span class="S_ERR_DANE">Nie wskazano obrazu !</span>';}
                                                                    $empty_img=TRUE;
                                                                    $ch_img[$y]=1;
                                                                    //echo "empty img ( Nie wskazano obrazu ".$y." )= ".$empty_img."</br>";
								}
                                                            }
                                                            else
                                                            { 
								$err_obraz_tab[$y]="<span class=\"S_ERR_DANE\">Przekroczono dozwolona wysyłkę danych (<span class=\"S_INFO\">64 Mb</span>) !</span>"; //".$pokazRozmiarFile."
                                                            } 
                                                            if(($empty_txt!=$empty_img) || ($ch_img[$y]!=1))
                                                            {
								$spojnosc=FALSE;
								if ($_SESSION['id_user']==1) echo "<p class=\"P_ERROR\">Spójność : <span class=\"S_ERROR\">FALSE</span></p>";
								$err_spojnosc[$y]="<span class=\"S_ERR_DANE\">BRAK spójności danych!</span>";
                                                            }
							} // KONIEC petla FOR dla plików
//------------------------------------------------------------------------------------------------------------------- KONIEC WYSYŁANIA PLIKÓW --------------------------------------------------------//
                                                    if ($_SESSION['id_user']==1)
                                                    {
                                                        echo "<p class=\"P_INFO\">CHECKED : <span class=\"S_INFO\">$checked</span></br>";
							echo "FIRST IMG : <span class=\"S_INFO\">$first_img</span></br>";
							echo "FIRST TXT : <span class=\"S_INFO\">$first_txt</span></br>";
							echo "SPOJNOSC : <span class=\"S_INFO\">$spojnosc</span></br>";
                                                    };
                                                    if (($checked==FALSE) || ($first_img==FALSE) || ($first_txt==FALSE) || ($spojnosc==FALSE)) {$status_dodaj=0;} else {$status_dodaj=1;}
//------------------------------------------ status_dodaj = 0 -------------------------------------------------------------------------
                                                }
                                                if ($status_dodaj==0)
                                                {
                                                    echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_OBOZ.php?IDW=0&IDM=5">Anuluj</a></p>';
                                                    echo '<p class="P_MAIN">Dodaj Obóz : </p>';
                                                    echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
                                                    for ($tr=1;$tr<21;$tr++)
                                                    {
                                                        if ($tr==1)
                                                        {
                                                            $info_dod='<span class="S_NG_DANE">*</span>';
                                                            $font_w="BOLD";
                                                            $font_s="20px";
                                                            $rows=1;
                                                            $opis="Tytuł ";
                                                            $op_zdj="główne";
															
                                                        }
                                                        else
                                                        {
                                                            $info_dod='';
                                                            $font_w="BOLD";
                                                            $font_s="16px";
                                                            $rows=3;
                                                            $opis="Opis ";
                                                            $op_zdj="nr";
                                                        }							
                                                        echo '<div class="DIV_DODAJ">';
                                                        if(array_key_exists($tr, $err_spojnosc)) { echo $err_spojnosc[$tr]; }
                                                        
							echo '<p class="P_INPUT" style="font-weight:'.$font_w.';font-size:'.$font_s.'">Zdjęcie '.$op_zdj.' '.$tr.' '.$info_dod.' <input type="file" id="fileInput" onclick="AlertFilesize();" name="obraz'.$tr.'"/>';
                                                        if(array_key_exists($tr, $err_obraz_tab)) { echo $err_obraz_tab[$tr]; }
                                                        echo '</p>';
							echo '<p class="P_INPUT" style="font-weight:'.$font_w.';font-size:'.$font_s.'">'.$opis.''.$info_dod.' : ';
                                                        if(array_key_exists($tr, $err)) { echo $err[$tr]; }
                                                        echo '</p>';
							echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA"">';  
							if(filter_input(INPUT_POST,"dane".$tr)!='')
                                                        { 
                                                            echo $_POST["dane".$tr];
							}
                                                        										
							echo '</textarea>';
							echo '</div>';
                                                    }						
                                                    echo "<input type=\"hidden\" name=\"TYP_DODAJ\" value=\"1\" />";
                                                    echo '<input class="button" type="submit" value="Dodaj" name="klasa"/></form>';
                                                    $tab_legenda=array("pola z GWIAZDKĄ (<span class=\"S_LEG_INFO\">*</span>) wymagane;","NIE dozwolone jest wprowadzenie <span class=\"S_LEG_INFO\">OPISU</span> bez <span class=\"S_LEG_INFO\">ZDJĘCIA</span> ! ;","OPIS może zawierać max (<span class=\"S_LEG_INFO\">2000</span>) znaków;","OPIS zdjęcia <span class=\"S_LEG_INFO\">NR 1</span> musi zawierać min (<span class=\"S_LEG_INFO\">4</span>) znaków ;","ZDJĘCIE, dozwolony TYP : (<span class=\"S_LEG_INFO\">JPG JPEG PNG BMP GIF</span>) ;","ZDJĘCIE,  <span class=\"S_LEG_INFO\">MAX</span> Rozmiar ~ <span class=\"S_LEG_INFO\">8 MB</span> ;");
                                                    echo '<p class="P_LEG">Legenda :</p>';
                                                    echo "<ul class=\"UL_LEG\">";
                                                    foreach ($tab_legenda as $key => $value)
                                                    {
							echo "<li class=\"LI_LEG\">$value</li>";
                                                    }
                                                    echo "</ul>";
                                                    echo '</center></div>';
//------------------------------------------ koniec-status_dodaj = 0 -------------------------------------------------
                                                }
                                                else if($status_dodaj==1)
                                                {
                                                    $start_time = time();
//------------------------------------------ status_dodaj = 1 --------------------------------------------------
                                                    $INS_MYSQL=FALSE; // 
                                                    $max_height=0;
                                                    $max_width=0;
                                                    $new_image_name=array('','');
                                                    $new_height=array(0,0);
                                                    $new_width=array(0,0);
                                                    // START PĘTLI
                                                    $true_i=1;
                                                    $lp_html=1;
                                                    $lp_xml=1;
                                                    for ($i=1;$i<21;$i++)
                                                    {
                                                        if (is_uploaded_file($_FILES["obraz".$i]['tmp_name'])) 
							{
                                                            if($true_i==1)
                                                            {
                                                                $katalog=1;
								$new_kat=FALSE;
								$i_file=0;
								echo "<p class=\"P_INFO\">NEW _KAT : <span class=\"S_INFO\">$new_kat</span></p>";
																						if ($handle = opendir(DR.'/zdjecia/obozy')) {
																									while ((false !== ($entry = readdir($handle))) && ($new_kat==FALSE)) {
																										if (!file_exists(DR."/zdjecia/obozy/".$katalog)) {
																																						$new_kat=TRUE;
																																						
																															} else {
																																$katalog++;
																															};
																									};
																						if(mkdir(DR.'/zdjecia/obozy/'.$katalog, 0766))	{
																																	
																						} else {
																								echo "<p class=\"P_INFO\"><span class=\"S_INFO\">BŁĄD ! Nie można utworzyć nowego katalogu ! Skontaktuj się z Administratorem !</span></p>";
																						};
																						closedir($handle);
																						};
																						
																						};
																		$uploads_dir = DR.'/zdjecia/obozy/'.$katalog; 
																		$filename = $_FILES["obraz".$i]["name"];
																		$tmp_name = $_FILES["obraz".$i]["tmp_name"]; 
																		$ext=strtolower(substr(strrchr($_FILES["obraz".$i]["type"], '/'), 1));
																		if($ext=="jpeg") {	
																							if ($_SESSION['id_user']==1){
																														echo "<p class=\"P_ERROR\">Zmiana <span class=\"S_INFO\">$ext</span> na  <span class=\"S_INFO\">jpg<span></p>";
																							};
																							$ext="jpg";
																		};
																		if($ext=="bmp") {
																							if ($_SESSION['id_user']==1){
																														echo "<p class=\"P_ERROR\">Plik <span class=\"S_INFO\">$ext</span> ! Zamiana na : <span class=\"S_INFO\">jpg</span></p>";
																							};
																							$ext_old=$ext; 
																							$ext="jpg";
																		};
																		echo "<p class=\"P_INFO\">CEL_OBOZ -> TRUE ext :  <span class=\"S_INFO\">$ext</span></p>";
																		$image_name="org_".$true_i.'_oboz.'.$ext; //Nowa nazwa pliku
																		move_uploaded_file($tmp_name,"$uploads_dir/$image_name");
//--------------------------------------------------------------------------------------------------------------------------RESIZE-IMG-------------------------------------------------
																		list($width, $height) = getimagesize($uploads_dir.'/'.$image_name);
																		$IMG_NEW=FALSE;
																		for ($p_resize=0;$p_resize<2;$p_resize++){
																											switch ($p_resize):
																																			case 0: 
																																					$naglowek_img='new_'.$true_i."_oboz.".$ext;
																																					$naglowek="Galeria ";
																																					break;
																																			case 1:
																																					$IMG_NEW=TRUE;
																																					$naglowek_img='min_'.$true_i."_oboz.".$ext;
																																					$naglowek="Miniatura ";
																																					break;
																																			default:
																																					$naglowek_img='def_'.$true_i."_oboz.".$ext;
																																					$naglowek="DEFAULT ";
																																					break;
																													endswitch;
																		if ($width>$parm_max_width[$p_resize] || $height>$parm_max_height[$p_resize] || ($ext_old=="bmp")){
																												
																												if ($_SESSION['id_user']==1){
																																			echo "<p class=\"P_ERROR\">Nowy rozmiary ($naglowek) WIDTH : <span class=\"S_INFO\">$parm_max_width[$p_resize] px</span>";
																																			echo " HEIGHT : <span class=\"S_INFO\">$parm_max_height[$p_resize] px</span></p>";
																												};
																												if (($IMG_NEW==TRUE) && (file_exists($uploads_dir."/new_".$true_i."_oboz.".$ext))) {
																																$image_name='new_'.$true_i."_oboz.".$ext;
																												};
																												$wynik_img_size = resize_image($parm_max_width[$p_resize],$parm_max_height[$p_resize],$ext,$naglowek_img,$uploads_dir.'/',$image_name);
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
																									};
																		};
//--------------------------------------------------------------------------------------------------------------------------KONIEC-RESIZE-IMG--------------------------------------------
																		
																		if($true_i==1){
																						$F_IMG_N=$new_image_name[1];
																						$F_IMG_H=$new_height[1];
																						$F_IMG_W=$new_width[1];
																						$title_html=trim($_POST["dane".$true_i]);
																						
																						$db->query("INSERT INTO OBOZ (NAZWA_O,NAZWA_H,NAZWA_I,OPIS,KATALOG,XML,ID_PERS,DAT_UTW,WIDTH,HEIGHT,VER) VALUES ('$filename','$lp_html.html','$new_image_name[1]','$title_html','$katalog','$lp_xml.xml','$_SESSION[id_user]',NOW(),'$new_width[1]','$new_height[1]',2)");
																						
																						//$ID_OBOZU=$db->query("select mysqli_insert_id() FROM `KLASA`") or die (mysqli_error());
																						
																						$ID_OBOZU=$db->last();
																						}
																		if($max_width<$new_width[0])$max_width=$new_width[0];
																		if($max_height<$new_height[0])$max_height=$new_height[0];
																		if(trim($_POST["dane".$i]) != '') $image_info=trim($_POST["dane".$i])."     "; else $image_info="-";
																		
																		$db->query("INSERT INTO OBOZ_IMG (ID_OBOZU,NAZWA_ORG,NAZWA_IMG,OPIS_IMG,NR_IMG,ID_PERS,DAT_UTW,WIDTH,HEIGHT,WIDTH_M,HEIGHT_M,NAZWA_I_M) VALUES ('$ID_OBOZU','$filename','$new_image_name[0]','$image_info','$true_i','$_SESSION[id_user]',NOW(),'$new_width[0]','$new_height[0]','$new_width[1]','$new_height[1]','$new_image_name[1]')");
																		
																		$track.='<track><title>'.$image_info.'</title><creator></creator><location>'.$new_image_name[0].'</location><info></info></track>';
																		$true_i++;
																		
																		
																		}
                                            } // END FOR LOOP
                                            
                                           $dok_html= '<html><head><title>Rocznik : '.$title_html.'</title><meta name="Description" content="JUDO"/><meta name="Keywords" content="JUDO SPORT" /><meta name="Author" content="Tomasz Borczynski; mass[.]hopto[.]org [@] gmail [.] com" /><meta name="Robots" content="all" /><meta http-equiv="Content-language" content="pl" /><meta http-equiv="Content-type" content="text/html;charset=UTF-8" /><link rel="shortcut icon" href="../../../images/favicon.ico" type="image/x-icon"><link rel="icon" href="../../../images/favicon.ico" type="image/x-icon"><style type="text/css" id="stylescreen" media="screen"></style></head><body style="margin:0px;padding:0px;"><center><div id="container"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this rotator.</div><div><script type="text/javascript" src="../swfobject.js"></script><script type="text/javascript">var s1 = new SWFObject("../imagerotator.swf","rotator","'.$max_width.'","'.$max_height.'","7");s1.addVariable("file","1.xml");s1.addVariable("width","'.$max_width.'");s1.addVariable("height","'.$max_height.'");s1.write("container");</script></div></center></body></html>';
						$file_html = fopen($uploads_dir.'/1.html' , "w");
						if(!fwrite($file_html,$dok_html))
                                                {
                                                    $log->log(0,"ERROR WRITE FILE => ".$uploads_dir.'/1.html');
                                                    die("ERROR WRITE HTML FILE");
                                                }
						fclose($file_html);
                                                $dok_xml = '<?xml version="1.0" encoding="UTF-8"?><playlist version="1" xmlns="http://xspf.org/ns/0/"><trackList>'.$track.'</trackList></playlist>';
						$file_xml = fopen($uploads_dir.'/1.xml' , "w");
						if(!fwrite($file_xml,$dok_xml ))
                                                {
                                                    $log->log(0,"ERROR WRITE FILE => ".$uploads_dir.'/1.xml');
                                                    die("ERROR WRITE XML FILE");
                                                }
						fclose($file_xml);
						flush(); 
						//$max_w_ins=$max_width+30; //+40; 
						$max_w_ins=$max_width; 
						//$max_h_ins=$max_height+30; //t+30;
						$max_h_ins=$max_height;
                                                if($_SESSION['id_user']==1)
                                                {
                                                    echo "<p class=\"P_INFO\">max_w_ins : <span class=\"S_INFO\">".$max_w_ins."</span></p>";
                                                    echo "<p class=\"P_INFO\">max_h_ins : <span class=\"S_INFO\">".$max_h_ins."</span></p>";
                                                    echo "<p class=\"P_INFO\">id_obóz : <span class=\"S_INFO\">".$ID_OBOZU."</span></p>";
						}
						
						$db->query("UPDATE `OBOZ` SET `MAX_W`='$max_w_ins',`MAX_H`='$max_h_ins' WHERE `ID`='$ID_OBOZU'");
						
						echo '<div class="DIV_IMG"><center>';
						echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/obozy/".$katalog."/1.html',$max_w_ins,$max_h_ins)\">";
						echo '<img src="'.APP_URL.'/zdjecia/obozy/'.$katalog."/".$F_IMG_N.'" alt="'.$F_IMG_N.'"  style="width:'.$F_IMG_W.'; height:'.$F_IMG_H.'; border:0px;" />';
						echo '</a><p class="P_INFO_IMG">(kliknij na zdjęcie aby uruchomić galerię)</p></center><p class="P_DANE">Rocznik : <span style="font-weight:bold;font-size:14px;color:black;font-style:normal;">'.$title_html.'</span></p></div>';
                                            
						foreach ($_POST as $key => $value)
                                                {
                                                    //if ($_SESSION['id_user']==1){ echo $key.' = '.$value.'<br/>';};
                                                    UNSET($_POST[$key]);
                                                    //if ($_SESSION['id_user']==1){ echo $key.' : '.$_POST[$key].'<br/>';};
						};
                                                    echo '<a href ="cel_OBOZ.php?IDW=0&IDM=5" class="A_BACK"><p class="P_HREF_BACK2">Powrót do MENU - OBOZY</p></a>';
                                                    $end_time = round(((time()) - $start_time),5);
                                                    //echo time()+microtime();
                                                    echo "<p class=\"P_INFO_OP\">Operacja wykonana w czasie : $end_time s.</p>";
                                                    };
                                                    echo "</div></center>";
                break;
            case 2:
                    echo "DODAJ FILM";
                break;
            case 3:
                    include(DR."/pod_strony/OBOZ/add_google_url_OBOZ.php");
                break;
            default:// wront type
                    echo "BŁĄD ! Skontaktuj się z Adminstratorem !";
                break;
endswitch;

