<?php if(!defined('PAGE_URL')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php	
$zdjecia=FALSE;
//------------------------------------------------------------------------EDYTUJ-------------------------------------------------------------------------					
$status_dodaj=0;
$err=array('','','','','','','','','','','','','','','','','','','','',''); // 21 wartosci petla sie zaczyna od 1 a zmienne tablocowe sa indeksowane od 0
$err_spojnosc=array('','','','','','','','','','','','','','','','','','','','','');
$err_obraz_tab=array();
$spojnosc_dancyh=array(TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE,TRUE);
$ch_img=array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
$OPIS_D=array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
$track='';
$iImg=21;
if (isset($_POST["edytuj"])) {
							//$checked_img=TRUE;
							$checked=TRUE;
							$img_zmiana=FALSE; // IMG ZMIANA
							$spojnosc=TRUE;
							$empty_txt=TRUE;
							$empty_img=TRUE;
							$first_txt=TRUE;
							$OPIS_GLOBAL=FALSE;
							$string_exp = "/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ .'!:-_\r\n]+$/";
							
							// Tekst poprawny rozpoczynamy upload plików
							for( $y = 1; $y<$iImg; $y++ ) {
														
														$SEL_IMG_OPIS = $db->query("select OPIS FROM KLASA_IMG WHERE ID_KLASA='$_POST[ID_KLASA]' AND NR_IMG='$y'") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\"> SEL_IMG_OPIS : ".mysqli_error()."</span></p>"); 
														$rek_sel=mysqli_fetch_array($SEL_IMG_OPIS);
														$change_txt = str_replace("-","",$rek_sel[0]);
														$change_txt=trim($change_txt);
														$opis_post=trim($_POST["dane".$y]);
														if ($_SESSION['id_user']==1) {
 							//echo "<p class=\"P_INFO\">POST[DANE".$y."] : <span class=\"S_INFO\">".trim($_POST["dane".$y])."</span></p>";
 							//echo "<p class=\"P_INFO\">TXT z BAZY (".$y.") : <span class=\"S_INFO\">".$change_txt."</span></p>";
 						};
														if ($opis_post==$change_txt) {
 							//echo "<p class=\"P_ERROR\">IDNTYCZNE : <span class=\"S_INFO\"> TAK </span></p>";
 							//echo "<p class=\"P_INFO\">OPIS : <span class=\"S_INFO\"> ".$OPIS_D[$y]." </span></p>";
 							$GLOBAL_ERR="<span class=\"S_ERR_DANE\"> NIE wprowadzono żadnych zmian !</span>";
 						} else 
 								{
 								$OPIS_D[$y]=0;
 								//echo "<p class=\"P_INFO\">OPIS : <span class=\"S_INFO\"> ".$OPIS_D[$y]." </span></p>";
 								//echo "<p class=\"P_INFO\">IDNTYCZNE : <span class=\"S_INFO\"> NIE </span></p>";
 									};
														if ($y==1) {
 		if(!preg_match($string_exp,$_POST["dane".$y])) { $err[$y]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $first_txt=FALSE;};
 		check_len($first_txt, $_POST["dane".$y],2000,$err[$y],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">2000</span>)',4,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
 		$empty_txt=FALSE;
 		//echo "empty txt PIERWSZY TXT (".$y.") = ".$empty_txt."</br>";
 		//$img_zmiana=TRUE;
 		}
 		else if (trim($_POST["dane".$y])!='') {
 if(!preg_match($string_exp,$_POST["dane".$y])) { $err[$y]='<span class="S_ERR_DANE">Proszę usunąć niedozwolone znaki</span>'; $checked=FALSE;};
 check_len($first_txt, $_POST["dane".$y],2000,$err[$y],'<span class="S_ERR_DANE">Pole za długie (maxymalna ilość znaków - </span><span style="color:black;">2000</span>)',4,'<span class="S_ERR_DANE">Proszę wypełnić (minimalna ilość znaków - </span><span style="color:black;">5</span>)');
 $empty_txt=FALSE;
 //echo "empty txt NIE PUSTY (".$y.") = ".$empty_txt."</br>";
 } else {
 		if(isset($_POST["IMG_".$y])) {
 										$empty_txt=FALSE;
 									} else $empty_txt=TRUE;
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
  	$img_zmiana=TRUE;
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
 						if(isset($_POST["IMG_".$y])) {
 			if ($empty_txt==FALSE) $err_obraz_tab[$y]="<span class=\"S_OK_DANE\">OK</span>";
 			$empty_img=FALSE;
 			}
 			else {
 					if ($empty_txt==FALSE)$err_obraz_tab[$y]='<span class="S_ERR_DANE">Nie wskazano obrazu !</span>';
 					$empty_img=TRUE;
 			};
 						$ch_img[$y]=1;
 						//echo "empty img ( Nie wskazano obrazu ".$y." )= ".$empty_img."</br>";
 						}; 
 				if(($empty_txt!=$empty_img) || ($ch_img[$y]!=1)){
 						$spojnosc=FALSE;
 						if ($_SESSION['id_user']==1) echo "<p class=\"P_ERROR\">Spójność : <span class=\"S_ERROR\">FALSE</span></p>";
 						$err_spojnosc[$y]="<span class=\"S_ERR_DANE\">BRAK spójności danych!</span>";
 						};
 				if ($OPIS_D[$y]==0) {
 									$OPIS_GLOBAL=TRUE;
 									//echo "<p class=\"P_INFO\">OPIS GLOBAL - ZMIANA : <span class=\"S_INFO\">".$OPIS_GLOBAL."</span></p>";
 									$img_zmiana=TRUE;
 									};										
 				if ($img_zmiana==TRUE) {$OPIS_GLOBAL=TRUE; $GLOBAL_ERR="";};
 				//echo "<p class=\"P_INFO\">OPIS GLOBAL (".$y."): <span class=\"S_INFO\">".$OPIS_GLOBAL."</span></p>";
 				}; // KONIEC petla FOR dla plików
$log->log(0,"[".__FILE__."::".__LINE__."] CHECKED => ".$checked);
$log->log(0,"[".__FILE__."::".__LINE__."] IMG ZMIANA => ".$img_zmiana);
$log->log(0,"[".__FILE__."::".__LINE__."] FIRST TXT => ".$first_txt);
$log->log(0,"[".__FILE__."::".__LINE__."] SPOJNOSC => ".$spojnosc);
$log->log(0,"[".__FILE__."::".__LINE__."] OPIS GLOBAL => ".$OPIS_GLOBAL);
if (($checked==FALSE) || ($img_zmiana==FALSE) || ($first_txt==FALSE) || ($spojnosc==FALSE) || ($OPIS_GLOBAL==FALSE)) $status_dodaj=0; else $status_dodaj=1;}
//------------------------------------------ status_dodaj = 0 -------------------------------------------------------------------------
if ($status_dodaj==0)
{
 echo '<p class="P_HREF_BACK"><a class="A_BACK" href="cel_KL_SP.php?IDW=0&IDM='.$_GET['IDM'].'">Anuluj</a></p>';
 echo '<p class="P_MAIN">Edycja Klasy Sportowej ['.$_GET["ID"].'] : '.$GLOBAL_ERR.'</p>';
 echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
 $NR_IMG=0;
 for ($tr=1;$tr<$iImg;$tr++)
 {						
	$SEL_KLASA= $db->query("select k.ID,k.KATALOG,k.MAX_W,k.MAX_H,i.NAZW_O,i.NAZW_I,i.OPIS,i.NR_IMG,i.WIDTH,i.HEIGHT,i.WIDTH_M,i.HEIGHT_M,NAZWA_I_M FROM KLASA k,KLASA_IMG i WHERE k.ID=i.ID_KLASA AND k.WSK_U=0 AND i.NR_IMG='$tr' AND i.ID_KLASA='".$_GET['ID']."'");
	$rekord = mysqli_fetch_array($SEL_KLASA);
	$ID_ZWDK=$rekord[0];
	if ($tr==1)
        {
            $info_dod='<span class="S_NG_DANE">*</span>';
            $font_w="BOLD";
            $font_s="20px";
            $rows=1;
            $opis="Rocznik ";
            $ID_KAT=$rekord[1];
            $MAX_W=$rekord[2];
            $MAX_H=$rekord[3];
            $zmieniony =$rekord[6];
            $KLASA=$rekord[0];
	}
        else
        {
            $info_dod='';
            $font_w="BOLD";
            $font_s="16px";
            $rows=3;
            $opis="Opis ";
            $zmieniony = str_replace("-","",$rekord[6]);
	}							
	echo '<div class="DIV_DODAJ">';
	echo $err_spojnosc[$tr];
	echo '<p class="P_INPUT" style="font-weight:'.$font_w.';font-size:'.$font_s.'">Zdjęcie nr '.$tr.' '.$info_dod.'</p>';
	if ($rekord[0]!='')
        {
             echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/klasa_sp/$rekord[1]/$rekord[5]',$rekord[8],$rekord[9])\">";
             echo '<img src="'.APP_URL.'/zdjecia/klasa_sp/'.$rekord[1].'/'.$rekord[12].'" alt="'.$rekord[12].'" style="width:'.$rekord[10].'; height:'.$rekord[11].'; border:0px;" />';
             echo '</a>';
             echo '<p class="P_INFO_IMG">(kliknij na zdjęcie aby je powiększyć)</p>';
             echo '<input type="hidden" name="IMG_'.$tr.'" value="'.$rekord[4].'|'.$rekord[5].'|'.$rekord[6].'|'.$rekord[8].'|'.$rekord[9].'|'.$rekord[10].'|'.$rekord[11].'" />';
	}
            echo '<p class="P_INPUT"><input type="file" name="obraz'.$tr.'"/>';
            if(array_key_exists($tr, $err_obraz_tab)) { echo $err_obraz_tab['$tr']; }
            echo '</p>';
            echo '<p class="P_INPUT" style="font-weight:'.$font_w.';font-size:'.$font_s.'">'.$opis.''.$info_dod.' : '.$err[$tr].'</p>';
            echo '<textarea name="dane'.$tr.'" rows="'.$rows.'" cols="85" class="TEXTAREA"">'; 
            if (isset($_POST["dane".$tr])) { 
					echo $_POST["dane".$tr];
            } else {
 							
 							
 							echo $zmieniony; 
 							};										
												echo '</textarea>';
												echo '</div>';
    if ($NR_IMG<$rekord[7]) $NR_IMG=$rekord[7];
 } // END FOR LOOP
						echo '<input type="hidden" name="MAX_WIDTH" value="'.$MAX_W.'" />';
						echo '<input type="hidden" name="MAX_HEIGHT" value="'.$MAX_H.'" />';
						echo '<input type="hidden" name="KATALOG" value="'.$ID_KAT.'" />';
						echo '<input type="hidden" name="NR_IMG" value="'.$NR_IMG.'" />';
						echo '<input type="hidden" name="ID_KLASA" value="'.$KLASA.'" />';
						echo '<input class="button"type="submit" value="Edytuj" name="edytuj"/></form>';
						echo '<p class="P_LEG">Legenda :</p>';
						echo "<ul class=\"UL_LEG\">";
						echo '<li class="LI_LEG">pola z GWIAZDKĄ (<span class="S_LEG_INFO">*</span>) wymagane ;</li>';
						echo '<li class="LI_LEG">NIE dozwolone jest wprowadzenie <span class="S_LEG_INFO">OPISU</span> bez <span class="S_LEG_INFO">ZDJĘCIA</span> ! ;</li>';
						echo '<li class="LI_LEG">OPIS może zawierać max (<span class="S_LEG_INFO">2000</span>) znaków</li> ';
						echo '<li class="LI_LEG">OPIS zdjęcia <span class="S_LEG_INFO">NR 1</span> musi zawierać min (<span class="S_LEG_INFO">4</span>) znaków ;</li>';
						echo '<li class="LI_LEG">ZDJĘCIE, dozwolony TYP : (<span class="S_LEG_INFO">JPG JPEG PNG BMP GIF</span>) ;</li> ';
						echo '<li class="LI_LEG">ZDJĘCIE, <span class="S_LEG_INFO">MAX</span> Rozmiar ~ <span class="S_LEG_INFO">8 MB</span> ;</li> ';
						echo "</ul>";
						echo '</center></div>';
//------------------------------------------ koniec-status_dodaj = 0 -------------------------------------------------
}
else if($status_dodaj==1)
{
//------------------------------------------ status_dodaj = 1 --------------------------------------------------
    $max_width=$_POST["MAX_WIDTH"];
    $max_height=$_POST["MAX_HEIGHT"];
    $NR_IMG=$_POST["NR_IMG"];
    $F_IMG=0;
    $F_DANE=0;
    $F_ZMIANA=0;
    $katalog=DR."/zdjecia/klasa_sp/".$_POST["KATALOG"];
    //---------------------------------------------------DIR------------------------------------------------------------------------------------------------
$dir=opendir($katalog);
$true_i=0; 
while($plik=readdir($dir))
{ 
    if($plik!="." && $plik!="..")
    {
	if((strrchr($plik, "new")==FALSE) AND (strrchr($plik, "min")==FALSE))
        { 
            $true_i++;
        }
    }
}
closedir($dir);
$log->log(0,"[".__FILE__."::".__LINE__."] Aktualna ilość zdjęć => ".$true_i);
$log->log(0,"[".__FILE__."::".__LINE__."] NR IMG - ".$NR_IMG);
$true_i++; // zwiekszamy w celu dodania new img
//--------------------------------------------------END-DIR------------------------------------------------------------------------------------------------
for ($i=1;$i<$iImg;$i++)
{
    if (is_uploaded_file($_FILES["obraz".$i]['tmp_name'])) 
    {
        $NR_IMG++;
        
	if ($_SESSION['id_user']==1){ echo '<p style="text-align:left; margin-left:20px;">NR IMG - '.$NR_IMG.'</p>';};
	$filename = $_FILES["obraz".$i]['name'];
	//$ext = strtolower(substr(strrchr($filename, '.'), 1)); //Rozszerzenie $_FILES["obraz".$i]['name']
        $ext = strtolower(substr(strrchr(mime_content_type($_FILES["obraz".$i]["tmp_name"]), '/'), 1)); //Rozszerzenie
	$image_name = 'org_'.$true_i . '_kl_sp.' . $ext; //Nowa nazwa pliku
	$tmp_name = $_FILES["obraz".$i]["tmp_name"]; 
	move_uploaded_file($tmp_name,"$katalog/$image_name");
	//--------------------------------------------------------------TWORZENIE_MINIATURY---------------------------------------------
	$imgSize= getimagesize($katalog.'/'.$image_name);// 0 => width 1 => height
	$F_IMG=1;
	for ($p_resize=0;$p_resize<2;$p_resize++)
        {
            $log->log(0,"[".__FILE__."::".__LINE__."] P RESIZE => ".$p_resize);
            switch ($p_resize):
                            case 0: 
 				$rozmiar=324;
 				$naglowek_img="min_";
 				$naglowek="Miniatura ";
 				break;
                            case 1:
 				$rozmiar=900;
 				$naglowek_img="new_";
 				$naglowek="Galeria ";
 				break;
                            default:
 				$rozmiar=324;
 				$naglowek_img="def_";
 				$naglowek="DEFAULT ";
 			break;
            endswitch;
            if ($imgSize[0]>$rozmiar || $imgSize[1]>$rozmiar) // 0 miniatura 324x324 // 1 900x900
            {            
                $wynik_img_size = resize_image($rozmiar,$rozmiar,$ext,$naglowek_img.$true_i.'_kl_sp.'.$ext,$katalog."/",$image_name);
                $new_width[$p_resize]= round($wynik_img_size[0]);
                $new_height[$p_resize]=round($wynik_img_size[1]);
                $new_image_name[$p_resize]=$wynik_img_size[2];
            }
            else
            {
                $new_image_name[$p_resize]=$image_name;
                $new_height[$p_resize]=round($imgSize[1]);
                $new_width[$p_resize]=round($imgSize[0]);
            }
            $log->log(0,"[".__FILE__."::".__LINE__."] F_IMG => ".$naglowek." NEW WIDTH => ".$new_width[$p_resize]);           
            $log->log(0,"[".__FILE__."::".__LINE__."] F_IMG => ".$F_IMG); 
 			//--------------------------------------------------------------KONIEC-RESIZE-IMG--------------------------------------------	
            if($i==1)
            {  
                $F_ZMIANA=1;
                $F_IMAGE_O=$image_name;
                $F_IMAGE_N_M=$new_image_name[0];
                $F_IMAGE_N_H_M=$new_height[0];
                $F_IMAGE_N_W_M=$new_width[0];
                $log->log(0,"[".__FILE__."::".__LINE__."] ISSET POST IMG => ".isset($_POST['IMG_1']));
                $log->log(0,"[".__FILE__."::".__LINE__."] F_IMAGE_O : => ".$F_IMAGE_O);
                $log->log(0,"[".__FILE__."::".__LINE__."] F_IMAGE_N_M   => ".$F_IMAGE_N_M);
                $log->log(0,"[".__FILE__."::".__LINE__."] F_IMAGE_N_W_M => ".$F_IMAGE_N_W_M);
                $log->log(0,"[".__FILE__."::".__LINE__."] F_IMAGE_N_H_M => ".$F_IMAGE_N_H_M);					
            }
        }
        if($max_width<$new_width[1])
        {
            $max_width=$new_width[1];
        }
        if($max_height<$new_height[1])
        {
            $max_height=$new_height[1];
        }
        if (isset($_POST["IMG_".$i]))
        {
            $db->query("UPDATE `KLASA_IMG` SET `NAZW_O`='$image_name' ,`NAZW_I`='$new_image_name[1]',`WIDTH`='$new_width[1]', `HEIGHT`='$new_height[1]',`NAZWA_I_M`='$new_image_name[0]', `WIDTH_M`='$new_width[0]', `HEIGHT_M`='$new_height[0]',`WSK_K`=WSK_K+1,`DAT_K`=NOW() WHERE `ID_KLASA`='$_POST[ID_KLASA]' AND `NR_IMG`='$i'");
        }
        else
        {
            if(trim($_POST["dane".$i])!='') $image_info=trim($_POST["dane".$i]); else $image_info="-";
            $db->query("INSERT INTO KLASA_IMG (ID_KLASA,NAZW_O,NAZW_I,OPIS,ID_PERS,DAT_UTW,NR_IMG,WIDTH,HEIGHT,WIDTH_M,HEIGHT_M,NAZWA_I_M) VALUES ('$_POST[ID_KLASA]','$filename','$new_image_name[1]','$image_info','$_SESSION[id_user]',NOW(),'$NR_IMG','$new_width[1]','$new_height[1]','$new_width[0]','$new_height[0]','$new_image_name[0]')");
        }
        $true_i++;
    } //KONIEC is_upload_file
    if(trim($_POST["dane".$i])!='')
    {
 	$image_info=trim($_POST["dane".$i]);
 	if($i==1)
        {
            $TITLE=$image_info;
        };
 	$UPD_IMG_TXT="UPDATE `KLASA_IMG` SET `OPIS`='$image_info' WHERE `ID_KLASA`='$_POST[ID_KLASA]' AND `NR_IMG`='$i'";
 	$db->query($UPD_IMG_TXT) or die("<p class=\"P_SQL_ERR\"> Błąd zapytania SQL : <span class=\"S_SQL\"> UPD_IMG_TXT (".$i.") : ".mysqli_error()."</span></p>");
 	if($_SESSION['id_user']==1) {echo "<p class=\"P_SQL_OK\">Poprawnie wykonano zapytanie SQL : <span class=\"S_SQL\">UPD_IMG_TXT (".$i.")</span></p>";};
 	
 	$F_DANE=1;
 	ECHO "F_DANE : ".$F_DANE."</br>";
 	} else {
 			$image_info="-";
 	};
    }; // KONIEC pętli UPLOAD
//---------------------------------------------------DIR------------------------------------------------------------------------------------------------
$i_html=0;
$i_xml=0;
echo " KATALOG sciezka - ".$katalog."</br>";
$dir=opendir($katalog);
						while($plik=readdir($dir)){ 
													if($plik!="." && $plik!="..")
 					{
 					if(strrchr($plik, "html")==TRUE) $i_html++; 
 					else if(strrchr($plik, "xml")==TRUE) $i_xml++;
 					//if($_SESSION['id_user']==1) { echo "PLIK - ".$plik."<br/>";};
 					}
													};
closedir($dir);
				if ($_SESSION['id_user']==1){ 
											echo '<p style="text-align:left; margin-left:20px;">Aktualna ilość plików HTML - '.$i_html.'</p>';
											echo '<p style="text-align:left; margin-left:20px;">Aktualna ilość plików XML - '.$i_xml.'</p>';
											$i_html++;
											$i_xml++;
											echo "<p class=\"P_INFO\">Nowy nr pliku html : <span class=\"S_INFO\">".$i_html."</span></p>";
											echo "<p class=\"P_INFO\">Nowy nr pliku xml : <span class=\"S_INFO\">".$i_xml."</span></p>";
											};
//--------------------------------------------------END-DIR------------------------------------------------------------------------------------------------				

$SEL_IMG_XML = $db->query("select NAZW_I,OPIS from KLASA_IMG WHERE WSK_U=0 AND ID_KLASA='$_POST[ID_KLASA]' order by id");		
										while($rek_xml = mysqli_fetch_array($SEL_IMG_XML))
										{
										$track.='<track><title>'.$rek_xml[1].'</title><creator></creator><location>'.$rek_xml[0].'</location><info></info></track>';	
										};
$dok_xml = '<?xml version="1.0" encoding="UTF-8"?>
<playlist version="1" xmlns="http://xspf.org/ns/0/">
<trackList>'.$track.'</trackList>
</playlist>';
									$uchwyt_xml = fopen($katalog.'/'.$i_xml.'.xml' , "w");
									
									fwrite($uchwyt_xml,$dok_xml );
									fclose($uchwyt_xml);
$dok_html= '<html><head><title>Rocznik : '.$TITLE.'</title>
<meta name="Description" content="JUDO"/>
<meta name="Keywords" content="JUDO SPORT" />
<meta name="Author" content="Tomasz Borczynski; mass[.]hopto[.]org [@] gmail [.] com" />
<meta name="Robots" content="all" />
<meta http-equiv="Content-language" content="pl" />
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<link rel="shortcut icon" href="../../../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../../../images/favicon.ico" type="image/x-icon">
<style type="text/css" id="stylescreen" media="screen"></style>
</head>
<body>
<center>
<div id="container"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this rotator.</div>
<script type="text/javascript" src="../swfobject.js"></script>
<script type="text/javascript">
var s1 = new SWFObject("../imagerotator.swf","rotator","'.$max_width.'","'.$max_height.'","7");
s1.addVariable("file","'.$i_xml.'.xml");
s1.addVariable("width","'.$max_width.'");
s1.addVariable("height","'.$max_height.'");
s1.write("container");
</script>
</center>
</body>
</html>';
$uchwyt_html = fopen($katalog.'/'.$i_html.'.html' , "w");
fwrite($uchwyt_html,$dok_html);
fclose($uchwyt_html);
flush();
						$max_width_ins=$max_width+40; $max_height_ins=$max_height+30;
						if($F_ZMIANA==0)
                                                {
                                                    list($F_IMAGE_O,$F_IMAGE_N_M,$view_opis,$view_w,$view_h,$F_IMAGE_N_W_M,$F_IMAGE_N_H_M) = explode('|', $_POST["IMG_1"]);
						}
                                                    $log->log(0,"[".__FILE__."] view_w => $view_w");
                                                    $log->log(0,"[".__FILE__."] view_h => $view_h");
                                                    $log->log(0,"[".__FILE__."] f_image_n_w_m => $F_IMAGE_N_W_M");
                                                    $log->log(0,"[".__FILE__."] f_image_n_h_m => $F_IMAGE_N_H_M");
                                                    
						if(($F_DANE==1) AND (isset($_POST["IMG_1"])))
                                                {
                                                    $log->log(0,"[".__FILE__."] UPDATE DANE AND IMG");
                                                    $db->query("UPDATE KLASA SET `NAZW_O`='$F_IMAGE_O',`HTML`='$i_html.html',`NAZW_I`='$F_IMAGE_N_M',`OPIS`='$TITLE',`XML`='$i_xml.xml',`DAT_K`=NOW(),`WIDTH`='$F_IMAGE_N_W_M',`HEIGHT`='$F_IMAGE_N_H_M',`MAX_W`='$max_width_ins',`MAX_H`='$max_height_ins',`WSK_K`=WSK_K+1 WHERE `ID`='$_POST[ID_KLASA]'");														
						}
                                                else if
                                                ($F_DANE==1)
                                                {
                                                    $log->log(0,"[".__FILE__."] UPDATE DANE");
                                                    $db->query("UPDATE KLASA SET `HTML`='$i_html.html',`OPIS`='$TITLE',`XML`='$i_xml.xml',`DAT_K`=NOW(),`MAX_W`='$max_width_ins',`MAX_H`='$max_height_ins',`WSK_K`=WSK_K+1 WHERE `ID`='$_POST[ID_KLASA]'");														
 						}
                                                else if(isset($_POST["IMG_1"]))
                                                {
                                                    $log->log(0,"[".__FILE__."] UPDATE IMG");
                                                    $db->query("UPDATE KLASA SET `NAZW_O`='$F_IMAGE_O',`HTML`='$i_html.html',`NAZW_I`='$F_IMAGE_N_M',`XML`='$i_xml.xml',`DAT_K`=NOW(),`WIDTH`='$F_IMAGE_N_W_M',`HEIGHT`='$F_IMAGE_N_H_M',`MAX_W`='$max_width_ins',`MAX_H`='$max_height_ins',`WSK_K`=WSK_K+1 WHERE `ID`='$_POST[ID_KLASA]'");														
                                                }
                                                else
                                                {
                                                    $log->log(0,"[".__FILE__."] NO UPDATE DANE AND IMG");
                                                }
						echo '<div class="DIV_IMG"><center>';
						
						echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/klasa_sp/".$_POST["KATALOG"]."/".$i_html.".html',$max_width_ins,$max_height_ins)\">";
						echo '<img src="'.APP_URL.'/zdjecia/klasa_sp/'.$_POST["KATALOG"].'/'.$F_IMAGE_N_M.'" alt="'.$F_IMAGE_N_M.'" style="width:'.$F_IMAGE_N_W_M.'px; height:'.$F_IMAGE_N_H_M.'px; border:0px;" />';
						echo '</a><p class="P_INFO_IMG">(kliknij na zdjęcie aby uruchomić galerię)</p></center><p class="P_DANE">Rocznik : <span style="font-weight:bold;font-size:14px;color:black;font-style:normal;">'.$TITLE.'</span></p></div>';
foreach ($_POST as $key => $value)
{
    UNSET($_POST[$key]);
}
echo '<a href ="'.PAGE_URL.'?IDW=0" class="A_BACK"><p class="P_HREF_BACK2">Powrót do MENU - KLASY</p></a>';
}
//------------------------------------------------------------------------KONIEC-EDYTUJ-----------------------------------------------------------------------------------------------
?>
</div>