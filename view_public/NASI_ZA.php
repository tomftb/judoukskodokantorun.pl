<?php
$SEL_ZWDK=$db->query("SELECT ID,IMIE_N,INFO,K_IMIE_N,K_INFO,R_IMIE_N,R_INFO,ILOZD,P_IMI_N_WART,P_INFO_WART,CSS_IMI_N,CSS_INFO FROM ZWDK where WSK_U=0 AND WSK_V=1 order by ID_P ASC");
while($rekord = mysqli_fetch_array($SEL_ZWDK))
{
    $font_w_dane=array(0,0);
    $font_s_dane=array(0,0);
    $text_d_dane=array(0,0);								
									for ($i=0;$i<2;$i++)
									{
										if ($i==0) $rek_ccs=$rekord[10]; else if ($i==1) $rek_ccs=$rekord[11];
										list($css_b_dane,$css_i_dane,$css_u_dane) = explode('|', $rek_ccs);
										if ($css_b_dane==0) $font_w_dane[$i]='font-weight:normal;'; else $font_w_dane[$i]='font-weight:bold;';
										if ($css_i_dane==0) $font_s_dane[$i]='font-style: normal;'; else $font_s_dane[$i]='font-style:italic;';
										if ($css_u_dane==0) $text_d_dane[$i]=''; else $text_d_dane[$i]='text-decoration: underline;';
									};
									echo '<div class="DIV_MAIN">';
									echo '<div class="DIV_DANE">';
									//<p class="P_DANE">Imie, Nazwisko : </p>
									echo '<p style="font-face: Times New Roman ;margin:5px;color:'.$rekord[3].';font-size:'.$rekord[5].'px; text-align:'.$rekord[8].';'.$font_w_dane[0].$font_s_dane[0].$text_d_dane[0].'">'.$rekord[1].'</p>';
									echo '<p class="P_DANE">Największe osiągnięcia : </p>'; //Opis
									echo '<p style="font-face: Times New Roman ;margin:5px;color:'.$rekord[4].';font-size:'.$rekord[6].'px; text-align:'.$rekord[9].';'.$font_w_dane[1].$font_s_dane[1].$text_d_dane[1].'">'.$rekord[2].'</p>';
									echo '</div>';
									if ($rekord[7]!=0)
									{
										$ZAP_IMG = $db->query("select NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM ZWDK_IMG where WSK_U=0 AND ID_ZWDK='$rekord[0]'");
										while($rek_img = mysqli_fetch_array($ZAP_IMG))
										{
											echo '<div class="DIV_IMG">';
											echo '<center>';
											echo "<a HREF=\"javascript:displayWindow3('".APP_URL."/zdjecia/nasi_za/$rek_img[0]',$rek_img[1],$rek_img[2],'$rekord[1]')\">";
											echo '<img src="'.APP_URL.'/zdjecia/nasi_za/'.$rek_img[3].'" alt="'.$rek_img[0].'" style="width:'.$rek_img[4].'px; height:'.$rek_img[5].'px; border:0px;" />';
											echo '</a></center></div>';
										}
									};
									echo '<div class="DIV_CLEAR"></div>';
									echo "</div><hr></hr>";
								};
	

