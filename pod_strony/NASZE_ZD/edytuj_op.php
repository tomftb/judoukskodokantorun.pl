<?php if(!defined('NASZE_ZD_62')) { exit('NO PERMISSION');}?>
<div class="DIV_MAIN">
<?php
$checked=TRUE;
$zdjecia=FALSE;
$err_obraz='';
$status_popraw=0;
echo '<a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p style="text-align: left;margin:20px;">Anuluj</p></a>';
//------------------------------------------------------------------POBIERANIE-PARM-Z-BAZY------------------------------------------------------
if(file_exists(DR."/pod_strony/_include_/pobr_parm.php")) include(DR."/pod_strony/_include_/pobr_parm.php"); else echo "Nie można załadować potrzebnego pliku - pobr_parm.php . Skontaktuj się z Adminstratorem!";
//------------------------------------------------------------------KONIEC-POBIERANIE-PARM-Z-BAZY------------------------------------------------------
if (isset($_POST["edytuj"]))
{
    $checked=FALSE;
    if (is_uploaded_file($_FILES['obraz1']["tmp_name"]))
    {									
	if(($_FILES['obraz1']["type"] == 'image/gif') || ($_FILES['obraz1']["type"] == 'image/jpeg') || ($_FILES['obraz1']["type"] == 'image/jpg') || ($_FILES['obraz1']["type"] == 'image/png') || ($_FILES['obraz1']["type"] == 'image/bmp') || ($_FILES['obraz1']["type"] == 'image/pjpeg')) 
	{
            $max_rozmiar = 8388608;
																	if ($_FILES['obraz1']["type"] < $max_rozmiar)
																		{
																		 $checked=TRUE;
																		}
																		else { $err_obraz='<span style="color:red;">Za duży rozmiar obrazu</span> !';}
																	} else { $err_obraz='<span style="color:red;">Wskazany plik nie jest obrazem </span>!';}												
						} else {$err_obraz='<span style="color:red;">Nie wskazano obrazu.</span>';};
if ($checked==FALSE) $status_popraw=0; else $status_popraw=1;
  };
if ($status_popraw==0){
echo '<form action="" method="POST" ENCTYPE="multipart/form-data">';
echo '<p  CLASS="P_MAIN_CEL">Edytowanie zdjęcia nr [<span style="color:purple;">'.$_GET["ID"].'</span>]</p>';
									$zap_img = $db->query("select ID,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT,WSK_K FROM OUR_PHOTO where ID='$_GET[ID]'");
									$rek_img = mysqli_fetch_array($zap_img);
									echo '<center><div style="border:1px; border-style:solid; margin:10px;">';
									echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/nasze/$rek_img[1]',' $rek_img[1] ',$rek_img[2],$rek_img[3])\">";
									echo '<img src="'.APP_URL.'/zdjecia/nasze/'.$rek_img[4].'" alt="Zdjecie" style="WIDTH:'.$rek_img[5].'px; HEIGHT:'.$rek_img[6].'px; border:0px; margin:10px;" />';
									echo '</a>';
									echo '<input type="file" name="obraz1"/>'.$err_obraz.'</div></center>';
echo '<input type="hidden" name="ID" value="'.$_GET["ID"].'" />';
echo '<input type="hidden" name="WSK_K" value="'.$rek_img[7].'" />';
echo '<input type="hidden" name="IDM" value="'.$_GET["IDM"].'" />';
							
							
for ($tr=0; $tr<2; $tr++){
							echo '<input type="hidden" name="MAX_WIDTH_'.$tr.'" value="'.$max_width[$tr].'" />';
							echo '<input type="hidden" name="MAX_HEIGHT_'.$tr.'" value="'.$max_height[$tr].'" />';
};
echo '<div style="height:30px;">';
echo '<input type="submit" class="inp_button" value="Edytuj" name="edytuj"/></form>';
echo '</div>';
} else if ($status_popraw==1){
$ID= $_POST["ID"];
$y=1;
							if (is_uploaded_file($_FILES['obraz'.$y]['tmp_name'])) {
																				//--------------UPLOAD-FILE----------
																				$uploads_dir=DR."/zdjecia/nasze/";
																				$wynik_upload_file=upload_file($_FILES['obraz'.$y]['name'],$_FILES['obraz'.$y]['tmp_name'],$uploads_dir,"_our",$_POST["MAX_WIDTH_0"],$_POST["MAX_HEIGHT_0"],$_POST["MAX_WIDTH_1"],$_POST["MAX_HEIGHT_1"]);
																				$filename=$_FILES['obraz'.$y]['name'];
																				//--------------KONIEC-UPLOAD-FILE---
																$db->query("UPDATE `OUR_PHOTO` SET `NAZWA_O`='$filename' ,`NAZWA_I`='$wynik_upload_file[2]', `ID_PERS`='".$_SESSION['id_user']."',`WIDTH`='$wynik_upload_file[0]', `HEIGHT`='$wynik_upload_file[1]',`NAZWA_I_M`='$wynik_upload_file[5]', `M_WIDTH`='$wynik_upload_file[3]', `M_HEIGHT`='$wynik_upload_file[4]', `WSK_K`='WSK_K+1', `WSK_V`='$WSK_V' WHERE `ID`='$ID'");
																
																
																echo "<center><a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/nasze/".$wynik_upload_file[2]."',' $wynik_upload_file[2] ',$wynik_upload_file[0],$wynik_upload_file[1])\">";
																echo '<img src="'.APP_URL.'/zdjecia/nasze/'.$wynik_upload_file[5].'" alt="Zdjecie" style="width:'.$wynik_upload_file[3].'px; height:'.$wynik_upload_file[4].'px; border:0px;margin-top:'.$margin_top.'px" />';
																echo "</a></center>";
																
								} else { echo '<p style="color:red;text-align:left;margin-left:20px;margin-top:0px;margin-bottom:5px;">Nie wskazano obrazu !</p>'; }
echo '<center><p>Twój zdjęcie zostało uaktualnione</p>';
echo '<a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p style="text-align:center; font-size:20px; margin:0px;">Powrót do MENU - NASZE ZDJĘCIA</p></a>';
};
?>
</div>