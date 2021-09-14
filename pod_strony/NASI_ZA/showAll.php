<?php
if(!defined('PAGE_URL')) { exit('NO PERMISSION');}
echo '<p class="P_MAIN">Wszyscy Zawodnicy : </p>';
	
/* COUNT PAGES */
require(DR.'/class/page.php');
$page=NEW page();
$page->setDbRec("select COUNT(*) FROM `ZWDK` WHERE `WSK_U`=0");
$page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
$page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
$IDS=$page->getIDS();
echo $page->getPageLink('s');
/* END COUNT PAGES */	
	
	$SEL_ZWDK_W=$db->query("SELECT z.ID,z.IMIE_N,z.INFO,z.K_IMIE_N,z.K_INFO,z.R_IMIE_N,z.R_INFO,z.DAT_UTW,z.ILOZD,z.WSK_V,z.P_IMI_N_WART,z.P_INFO_WART,z.CSS_IMI_N,z.CSS_INFO,p.NAZWA,z.DAT_K,(select p2.NAZWA FROM PERS p2 WHERE p2.id=z.ID_PERS_KOR),z.ID_P FROM ZWDK z, PERS p where z.id_pers=p.id AND z.WSK_U=0 order by z.ID_P ASC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
	
		while($rekord = mysqli_fetch_array($SEL_ZWDK_W))
		{
			if ($rekord[9]==1)
			{
				$STAT_W="<span class=\"S_STAT\">TAK</span>";
			} 
			else
			{
				$STAT_W="<span class=\"S_STAT_N\">NIE</span>";
			};
			$font_w_dane=array(0,0);
			$font_s_dane=array(0,0);
			$text_d_dane=array(0,0);
			for ($i=0;$i<2;$i++)
			{
				if ($i==0) $rek_ccs=$rekord[12]; else if ($i==1) $rek_ccs=$rekord[13];
				list($css_b_dane,$css_i_dane,$css_u_dane) = explode('|', $rek_ccs);
				if ($css_b_dane==0)
					$font_w_dane[$i]='font-weight:normal;'; 
				else 
					$font_w_dane[$i]='font-weight:bold;';
				if ($css_i_dane==0)
					$font_s_dane[$i]='font-style:normal;'; 
				else
					$font_s_dane[$i]='font-style:italic;';
				if ($css_u_dane==0) 
					$text_d_dane[$i]='';
				else
					$text_d_dane[$i]='text-decoration: underline;';
			};
			echo "<div class=\"DIV_MAIN\" ID=\"ID".$rekord[0]."\">";
//-------------------------------------------- BLOK DIV INFO NAGLOWEK ------------------------------------------------------------------------------------
			echo '<div class="DIV_OPCJ">'; 
			echo '<span class="S_NG_MAIN">';
			echo 'ID : <span class="S_NG_INF">'.$rekord[0].' </span>';
			echo ' Autor : <span class="S_NG_INF">'.$rekord[14].'</span>';
			echo ' Priorytet : <span class="S_NG_INF">'.$rekord[17].'</span>';
			echo '</span>';
			echo '<span style="float:right ;text-align:right; color: #0099FF ;margin-top:10px;margin-right:10px;"> Widoczny : '.$STAT_W;
			if(array_key_exists(6, $_SESSION['perm'][$IDM]))
			{
				echo '<a href="'.PAGE_URL.'&IDW=6&ID='.$rekord[0].'"><span class="s_button">Ustaw</span></a></span>';
			}
			else
			{
				echo '<button class="s_button_off">Ustaw</button>';
			};
			echo '</div>';

//-------------------------------------------- KONIEC BLOK DIV INFO NAGLOWEK ------------------------------------------------------------------------------
//-------------------------------------------- DIV TRESC --------------------------------------------------------------------------------------------------
			echo '<div class="DIV_DANE_ZAW"">';
			echo '<p class="P_DANE_ZAW">Imię i Nazwisko : </p>';
			echo '<p style="font-face: Times New Roman ;margin:5px;color:'.$rekord[3].';font-size:'.$rekord[5].'px; text-align:'.$rekord[10].';'.$font_w_dane[0].$font_s_dane[0].$text_d_dane[0].'">'.$rekord[1].'</p>';
			echo '<p class="P_DANE_ZAW">Opis : </p>';
			echo '<p style="font-face: Times New Roman ;margin:5px;color:'.$rekord[4].';font-size:'.$rekord[6].'px; text-align:'.$rekord[11].';'.$font_w_dane[1].$font_s_dane[1].$text_d_dane[1].'">'.$rekord[2].'</p>';
			echo '</div>';	
//-------------------------------------------- KONIEC DIV TRESC -------------------------------------------------------------------------------------------	
//-------------------------------------------- DIV ZDJECIA ------------------------------------------------------------------------------------------------			
			// $rekord[0]; ID zawodnika w bazie  $rekord[8]; Ilość zdjęć
			if ($rekord[8]!=0)
			{
				$ZAP_IMG =$db->query("select NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM ZWDK_IMG where WSK_U=0 AND ID_ZWDK='".$rekord[0]."'");
				
					
					while($rek_img = mysqli_fetch_array($ZAP_IMG))
					{
						//if ($rekord[8]==1) $pozycja="center"; else $pozycja="left";
						echo '<div class="DIV_IMG_ZAW" ><center>';
						echo "<a HREF=\"javascript:displayWindow('".APP_URL."/zdjecia/nasi_za/$rek_img[0]',' $rekord[1] ',$rek_img[1],$rek_img[2])\">";
						echo '<img src="'.APP_URL.'/zdjecia/nasi_za/'.$rek_img[3].'" alt="'.$rek_img[0].'" style="width:'.$rek_img[4].'px; height:'.$rek_img[5].'px; border:0px;" />';
						echo '</a><p class="P_INFO_IMG">(kliknij na zdjęcie aby je powiększyć)</p></center></div>';
					};
				
			};
//-------------------------------------------- KONIEC DIV ZDJECIA -----------------------------------------------------------------------------------------	
			echo '<div class="DIV_CLEAR"></div>';
//-------------------------------------------- DIV OPCJE --------------------------------------------------------------------------------------------------				
			echo '<div class="DIV_OPCJ">';
			if(array_key_exists(3, $_SESSION['perm'][$IDM]))
			{
				echo '<a href="'.PAGE_URL.'&IDW=3&ID='.$rekord[0].'"><span class="s_button" style="margin-right:10px;">Usuń</span></a>';
			}
			else
			{
				echo '<button class="s_button_off" style="margin-right:10px;">Usuń</button>';
			};
			if(array_key_exists(2, $_SESSION['perm'][$IDM]))
			{
				echo '<a href="'.PAGE_URL.'&IDW=2&ID='.$rekord[0].'"><span class="s_button">Edytuj</span></a>';
			}
			else
			{
				echo '<button class="s_button_off">Edytuj</button>';
			}
			if(array_key_exists(7, $_SESSION['perm'][$IDM]))
			{
				echo '<a href="'.PAGE_URL.'&IDW=7&ID='.$rekord[0].'"><span class="s_button">Priorytet</span></a>';
			}
			else
			{
				echo '<button class="s_button_off">Priorytet</button>';
			};
			echo '</div>';	
			echo '<div class="DIV_CLEAR"></div>';
			echo '<DIV class="DIV_OPCJ_MIN">';
			echo '<p class="P_INFO_MIN">Data utworzenia : '.$rekord[7].', Data modyfikacji : '.$rekord[15].', Modyfikował : '.$rekord[16].'</p>';
			echo '</DIV>';
//-------------------------------------------- KONIEC DIV OPCJE -------------------------------------------------------------------------------------------	
			echo "</div>";
		} // koniec pętli WHILE
                echo $page->getPageLink('e');