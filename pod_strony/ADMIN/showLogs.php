<?php
if(!defined('ADMIN133')) { exit('NO PERMISSION');}

$checkVar->checkOnlyGet($SEL_SQL,'SEL_SQL');

 echo '<p class="P_M_NAGL">LOG - logowania do systemu</p>';

                                                      
							echo '<div style="width:800px; height:60px; border: 1px solid purple;">';
							echo '<FORM action="" method="GET" ENCTYPE="multipart/form-data" >';
							if (!isset($_GET["SEL_SQL"]))
                                                        {  //$SEL_SQL="ID_PERS>0";
													//echo "SEL_SQL - BRAK</br>";
													$poz_id=0;
													$poz_nazwa="Wszyscy";
													$SEL_SQL="ID_PERS>0"; 
							} 
							else {
									//echo "SEL_SQL - JEST</br>";
									$SEL_SQL=$_GET["SEL_SQL"];
									$poz_id=$_GET["poz_id"];
									$poz_nazwa=$_GET["poz_nazwa"];
							};
							if(filter_input(INPUT_GET,'uzytkownik')!='')
                                                        {
															//echo "FILTR jest</br>";
															list($poz_id,$poz_nazwa) = explode('|', $_GET["uzytkownik"]);
															if($poz_id==0) $SEL_SQL="ID_PERS>0"; else {
															$SEL_SQL="ID_PERS=".$poz_id;
															};
															
							}
                                                        else
                                                        { //echo "Filtr brak</br>";
							
                                                        }
							//echo "poz id=$poz_id poz_nazwa = $poz_nazwa SEL_SQL = $SEL_SQL</br>";
							echo '<p style="text-align:right;font-weight:bold;font-size:16px;">Filtruj dziennik logowania według użytkownika : ';
							echo '<select name="uzytkownik" class="SELECT_WYSZ" >';//onchange="location = this.value;"
							echo '<optgroup label="Aktualny :" class="OPTGROUP">';
							echo '<option value="'.$poz_id.'|'.$poz_nazwa.'" class="OPTION">'.$poz_nazwa.'</option>';
							echo '</optgroup><optgroup label="Dostępni :" class="OPTGROUP">';
							
							$SEL_POZ_ALL = $db->query("select ID,NAZWA FROM PERS WHERE WSK_U=0 AND ID!=$poz_id  order by ID");
							while($r_pozycja = mysqli_fetch_array($SEL_POZ_ALL))
                                                        {
                                                            echo '<option value="'.$r_pozycja[0].'|'.$r_pozycja[1].'" class="OPTION">';
                                                            echo $r_pozycja[1];
                                                            echo '</option>';												
							}
							if ($poz_id!=0) {echo '<option value="0|Wszyscy" class="OPTION">Wszyscy</option></option>';};
							echo '</optgroup></select>';
							echo '<input type="hidden" name="IDM" value="'.$IDM.'" />';
							echo '<input type="hidden" name="IDW" value="'.$IDW.'" />';
							echo '<input type="hidden" name="SEL_SQL" value="'.$SEL_SQL.'" />';
							echo '<input class="button_wysz" type="submit" value="Filtruj" name="filtr"></FORM></p>';
							echo '</div>';
							//-----------------------------------------------------------------------------------KONIEC-FILTR----------------------------------------------------------------
							echo '<table class="TAB_LOG">';
							echo '<tr class="TR_LOG" style="background:#80aaff;">';
							echo '<td class="TD_LOG" WIDTH="140PX"><p class="P_TAB_NAGL">Zalogowano :</p></td>';
							echo '<td class="TD_LOG" WIDTH="140PX"><p class="P_TAB_NAGL">Wylogowano :</p></td>';
							echo '<td class="TD_LOG" WIDTH="110PX"><p class="P_TAB_NAGL">Użytkownik :</p></td>';
							echo '<td class="TD_LOG" WIDTH="110PX"><p class="P_TAB_NAGL">IP :</p></td>';
							echo '<td class="TD_LOG" WIDTH="200PX"><p class="P_TAB_NAGL">Przeglądarka :</p></td>';
							echo '<td class="TD_LOG" WIDTH="100PX"><p class="P_TAB_NAGL">OS :</p></td>';
							echo '</tr>';
							/* COUNT PAGES */
                                                            require(DR.'/class/page.php');
                                                            $page=NEW page();
                                                            $page->setDbRec("select COUNT(*) FROM `LOG` WHERE $SEL_SQL");
                                                            $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
                                                            $page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
                                                            $IDS=$page->getIDS();
                                                        /* END COUNT PAGES */
							$SEL_LOG=$db->query("select l.ID,l.ID_PERS,p.NAZWA,l.ADRES_IP,l.DAT_LOG,l.BROWSER,l.BROWSER_V,l.SYS,l.DAT_LOGU FROM LOG l, PERS p where l.ID_PERS=p.ID AND l.$SEL_SQL ORDER BY ID DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
							
                                                        $i_color=true;
                                                        echo '<tr class="TR_LOG"><td colspan="6">'.$page->getPageLink('s').'</td></td>';     
							while($rek_log = mysqli_fetch_array($SEL_LOG))
									{
									if ($rek_log[2]!="admin") $uzytkownik='<span style="color:red;"><b>'.$rek_log[2].'</b></span>'; else $uzytkownik='<span style="color:black;">'.$rek_log[2].'</span>';
									
									
									if ($i_color==true) {
														$i_color=false;
														$tr_color="#cceeff";
														
									} 
									else {
											$i_color=true;
											$tr_color="white";
									};
									echo '<tr class="TR_LOG" style="background:'.$tr_color.';">';
									//echo '<td class="TD_LOG">'.$rek_log[0].'</td>';
									echo '<td class="TD_LOG">'.$rek_log[4].'</td>';
									echo '<td class="TD_LOG">'.$rek_log[8].'</td>';
									echo '<td class="TD_LOG">'.$uzytkownik.'</td>';
									echo '<td class="TD_LOG">'.$rek_log[3].'</td>';
									echo '<td class="TD_LOG">'.$rek_log[5].' '.$rek_log[6].'</td>';
									echo '<td class="TD_LOG">'.$rek_log[7].'</td>';
									echo '</tr>';
									
									
									}
                                                          echo '<tr class="TR_LOG"><td colspan="6">'.$page->getPageLink('e').'</td></td>';             
							echo "</table>";
                                                         