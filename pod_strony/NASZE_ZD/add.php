<?php if(!defined('NASZE_ZD_61')) { exit('NO PERMISSION');}
echo '<center><div style="background-color:#E6E6FA; border:1px; border-style:solid; border-radius:10px; margin:5px; border-color:orange;">';				
echo '<a href="'.PAGE_URL.'&IDW=0"><p style="text-align: left;margin:20px;">Powrót</p></a>';
echo '<center><p CLASS="P_MAIN_CEL">Dodaj zdjęcia : </p>';
$status_dodaj=0;
$err_obraz_tab =array();

if(file_exists(DR."/pod_strony/_include_/pobr_parm.php")) include(DR."/pod_strony/_include_/pobr_parm.php"); else echo "Nie można załadować potrzebnego pliku - pobr_parm.php . Skontaktuj się z Adminstratorem!";
if (isset($_POST["zdjecie"]))
{
	$checked_img=TRUE;
	$checked=FALSE;
	for( $y = 1; $y<21; $y++ ) 
{
			if (is_uploaded_file($_FILES["obraz".$y]["tmp_name"])) {
									if ($checked_img==TRUE) {
								if(($_FILES["obraz".$y]["type"] == 'image/gif') || ($_FILES["obraz".$y]["type"] == 'image/jpeg') || ($_FILES["obraz".$y]["type"] == 'image/jpg') || ($_FILES["obraz".$y]["type"] == 'image/png') || ($_FILES["obraz".$y]["type"] == 'image/bmp') || ($_FILES["obraz".$y]["type"] == 'image/pjpeg')) 
																	{
																	$max_rozmiar = 8388608;
																	if ($_FILES["obraz".$y]["type"] < $max_rozmiar)
																		{
																		
																			$checked_img=TRUE;
																			$checked=TRUE;
																			
																		}
																		else {
																		$err_obraz_tab[$y]='<span style="color:red;">Za duży rozmiar obrazu</span> !';
																		$checked_img=FALSE;
																		}
																	} else { 
																			$err_obraz_tab[$y]='<span style="color:red;">Wskazany plik nie jest obrazem </span>!';
																			$checked_img=FALSE;
																			}
									}	else { 
																		$err_obraz_tab[$y]='<span style="color:red;">Błąd poprzedniego zdjęcia </span>!';
																		}
																			
						} else {
								$err_obraz_tab[$y]='<span style="color:red;">Nie wskazano obrazu.</span>';
								};
};

if (($checked==FALSE) || ($checked_img==FALSE)) $status_dodaj=0; else $status_dodaj=1;
  };
if ($status_dodaj==0)
{

echo '<form action="" method="POST" ENCTYPE="multipart/form-data" >';
echo '<div CLASS="DIV_DODAJ">';
for( $x = 1; $x<21; $x++ )
{
echo '<p CLASS="P_INPUT">Zdjęcie nr : <span CLASS="S_NG_DANE">'.$x.' </span><input type="file" name="obraz'.$x.'"/><font color="red">';
if(array_key_exists($x, $err_obraz_tab)) { echo $err_obraz_tab[$x]; }
        echo '</font></p>';
}
for ($tr=0; $tr<2; $tr++)
{
    echo '<input type="hidden" name="MAX_WIDTH_'.$tr.'" value="'.$max_width[$tr].'" />';
    echo '<input type="hidden" name="MAX_HEIGHT_'.$tr.'" value="'.$max_height[$tr].'" />';
}
echo '</div>';
echo '</p><p style="text-align: right;margin:20px;"><input type="submit" class="inp_button" value="Dodaj" name="zdjecie"/></p></form></center>';
//-------------------------------------------------------LEGENDA---------------------------------------------------------------------------------------------
$tab_legenda=array("Minimum (<span class=\"S_LEG_INFO\">JEDNO</span>) zdjęcie;","Zdjęcie (dozwolony TYP) : <span class=\"S_LEG_INFO\">JPG JPEG PNG BMP GIF</span>;","Zdjęcie (<span class=\"S_LEG_INFO\">MAX</span> Rozmiar ) ~ <span class=\"S_LEG_INFO\">8 MB</span> ;");
							echo '<p class="P_LEG">Legenda :</p>';
							echo "<ul class=\"UL_LEG\">";
							foreach ($tab_legenda as $key => $value){
								echo "<li class=\"LI_LEG\">$value</li>";
							};
							echo "</ul>";
//-------------------------------------------------------KONIEC-LEGENDA---------------------------------------------------------------------------------------------
echo '</div>';
}
else if ($status_dodaj==1)
{
    echo '<p style="Text-align:center;font-size:12px;font-weight:bold;">Twoje zdjęcia zostały dodane</p>';
    $ID_PERS = $_SESSION['id_user'];
    for( $y = 1; $y<21; $y++ )
    {
    if (is_uploaded_file($_FILES['obraz'.$y]['tmp_name']))
    {
    //--------------UPLOAD-FILE----------
        $uploads_dir=DR."/zdjecia/nasze/";
	$wynik_upload_file=upload_file($_FILES['obraz'.$y]['name'],$_FILES['obraz'.$y]['tmp_name'],$uploads_dir,"_our",$_POST["MAX_WIDTH_0"],$_POST["MAX_HEIGHT_0"],$_POST["MAX_WIDTH_1"],$_POST["MAX_HEIGHT_1"]);
	$filename=$_FILES['obraz'.$y]['name'];
	//--------------KONIEC-UPLOAD-FILE---
	$db->query("INSERT INTO OUR_PHOTO (NAZWA_O,NAZWA_I,ID_PERS,DAT_UTW,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT,WSK_V) VALUES ('$filename','$wynik_upload_file[2]','$ID_PERS',NOW(),'$wynik_upload_file[0]','$wynik_upload_file[1]','$wynik_upload_file[5]','$wynik_upload_file[3]','$wynik_upload_file[4]',0)");
	$db->insDbLog($_GET["IDM"],"DODAJ ID : ".$db->last());
																
																echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/nasze/".$wynik_upload_file[2]."','".$wynik_upload_file[2]."',$wynik_upload_file[0],$wynik_upload_file[1])\">";
																echo '<img src="'.APP_URL.'/zdjecia/nasze/'.$wynik_upload_file[5].'" alt="Zdjecie" style="width:'.$wynik_upload_file[3].'px; height:'.$wynik_upload_file[4].'px; border:0px;margin-top:10px" />';
																echo "</a>";
																
								}
                                                            else
                                           {
                                                               
                                                                echo '<p style="color:red;text-align:left;margin-left:20px;margin-top:0px;margin-bottom:5px;">Nie wskazano obrazu nr : <span style="color:black;">'.$y.'</span></p>'; }
};

echo '<a href="'.PAGE_URL.'&IDW=0"><p style="text-align:center; font-size:20px; margin:0px;">Powrót do MENU - NASZE ZDJĘCIA</p></a>';
}
echo "</div></center>";