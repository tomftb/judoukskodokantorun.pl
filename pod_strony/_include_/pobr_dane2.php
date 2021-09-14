<?php
//if($_SESSION['id_user']==1) echo '<p style="text-align:left;margin-left:20px;">ID artykułu - '.$_GET["IDV"].'</p>';
													//-------POBR-DANE--
													$SEL_STAT = $db->query("select n.WSK_V,n.TYTUL,n.TRESC,n.K_TYTUL,n.R_TYTUL,n.K_TRESC,n.R_TRESC,n.P_TYT,n.P_TRE,n.CSS_TYT,n.CSS_TRE,n.ILOZD,n.VER FROM NEWS n WHERE n.ID='$_GET[ID]'");
													$REK_STAT = mysqli_fetch_row($SEL_STAT);
													//-------KONIEC-POBR-DANE
													//----TRESC
													$css_rek=9;
													$font_weight=array("font-weight:normal;","font-weight:normal;");
													$font_style=array("font-style: normal;","font-style: normal;");
													$text_dec=array("","");
													for ($i=0; $i<2;$i++){
													list($css_tyt_b,$css_tyt_i,$css_tyt_u) = explode('|', $REK_STAT[$css_rek]);
																if ($css_tyt_b==1) $font_weight[$i]='font-weight:bold;';
																if ($css_tyt_i==1) $font_style[$i]='font-style:italic;';
																if ($css_tyt_u==1) $text_dec[$i]='text-decoration: underline;';
													$css_rek++;
													};
													echo '<DIV class="DIV_TRESC">';
													if($REK_STAT[12]==4)
													{
														$REK_STAT[1]=htmlspecialchars_decode($REK_STAT[1]);
														$REK_STAT[2]=htmlspecialchars_decode($REK_STAT[2]);
													};
													echo '<p style="color:'.$REK_STAT[3].';font-size:'.$REK_STAT[4].'px; text-align:'.$REK_STAT[7].';'.$font_weight[0].$font_style[0].$text_dec[0].'">'.$REK_STAT[1].'</p>';
													echo '<p style="font-face: Times New Roman ;margin:10px;color:'.$REK_STAT[5].';font-size:'.$REK_STAT[6].'px; text-align:'.$REK_STAT[8].';'.$font_weight[1].$font_style[1].$text_dec[1].'">'.$REK_STAT[2].'</p>';
													echo '</DIV>';
													//-------------KONIEC-TRESC
													//-----KONIEC-TRESC
													echo '<DIV class="DIV_ZDJ">';
								
								if ($REK_STAT[11]!=''){ //Wyświetlanie zdjęć
										$max_height=0;
										$max_height_top=0;
										$licz=0;
										$zap_img = $db->query("select M_HEIGHT FROM NEWS_IMG WHERE WSK_U=0 AND ID_NEWS='$_GET[ID]'");
										while($rek_img = mysqli_fetch_array($zap_img)){
											if ($max_height_top<$rek_img[0] && $licz<2) $max_height_top=$rek_img[0];
											if($max_height<$rek_img[0]) $max_height=$rek_img[0];
											$licz++;
										};
										//echo "MAX HEIGHT $max_height </br>";
										$licz=0;
										$zap_img = $db->query("select KATALOG,NAZWA_I,WIDTH,HEIGHT,NAZWA_I_M,M_WIDTH,M_HEIGHT FROM NEWS_IMG WHERE WSK_U=0 AND ID_NEWS='$_GET[ID]'");
										while($rek_img = mysqli_fetch_array($zap_img)){
											if ($REK_STAT[11]==1) $pozycja="center"; else if ($REK_STAT[11]>1 && $REK_STAT[11]<5 )$pozycja="left"; else echo 'BŁĄD ! skontaktuj sie z Administratorem ILOZD ['.$_GET["ID"].']';
											if($licz<2) {
															$HEIGHT_DIV_IMG=$max_height_top;
															if($rek_img[6]<$max_height_top) $margin_top=($max_height_top-$rek_img[6])/2; else $margin_top=0;
											}
											else {
													$HEIGHT_DIV_IMG=$max_height;
													if($rek_img[6]<$max_height) $margin_top=($max_height-$rek_img[6])/2; else $margin_top=0;
											}
											echo '<DIV CLASS="DIV_IMG" style="float:'.$pozycja.';height='.$HEIGHT_DIV_IMG.'px">';
											//echo "margin top : $margin_top </br>";
											echo '<a HREF="javascript:displayWindow(&#39;../../zdjecia/artykul/'.$rek_img[0].'/'.$rek_img[1].'&#39;,'.$rek_img[2].','.$rek_img[3].')">';
											echo '<img src="../../zdjecia/artykul/'.$rek_img[0].'/'.$rek_img[4].'" alt="Zdjecie" style="width:'.$rek_img[5].'px; height:'.$rek_img[6].'px; border:0px; margin-top:'.$margin_top.'px; margin-bottom:'.$margin_top.'px;" />';
											echo '</a></DIV>';
											$licz++;
										};
								};
								
								
								//$film = '<center>'.$REK_STAT[3].'</center>';
								//echo $film;
								echo '</DIV><DIV class="DIV_CLEAR"></DIV>';