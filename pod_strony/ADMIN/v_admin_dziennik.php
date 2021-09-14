<?php
if(!defined('ADMIN1314')) { exit('NO PERMISSION');}

$checkVar->checkOnlyGet($SEL_SQL,'SEL_SQL');
echo '<div style="width:800px; height:60px; border: 1px solid purple;">';
							echo '<FORM action="" method="GET" ENCTYPE="multipart/form-data" >';
							
							//$SEL_SQL="ID_PERS>0";
							if (!isset($_GET["SEL_SQL"])){ 
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
															
							} else { //echo "Filtr brak</br>";
							};
							//echo "poz id=$poz_id poz_nazwa = $poz_nazwa SEL_SQL = $SEL_SQL</br>";
							echo '<p style="text-align:right;font-weight:bold;font-size:16px;">Filtruj dziennik logowania według użytkownika : ';
							echo '<select name="uzytkownik" class="SELECT_WYSZ" >';//onchange="location = this.value;"
							echo '<optgroup label="Aktualny :" class="OPTGROUP">';
							echo '<option value="'.$poz_id.'|'.$poz_nazwa.'" class="OPTION">'.$poz_nazwa.'</option>';
							echo '</optgroup><optgroup label="Dostępni :" class="OPTGROUP">';
							
							$SEL_POZ_ALL = $db->query("select ID,NAZWA FROM PERS WHERE WSK_U=0 AND ID!=$poz_id  order by ID");
							while($r_pozycja = mysqli_fetch_array($SEL_POZ_ALL)){
																				echo '<option value="'.$r_pozycja[0].'|'.$r_pozycja[1].'" class="OPTION">';
																				echo $r_pozycja[1];
																				echo '</option>';
																			
							};
							if ($poz_id!=0) {echo '<option value="0|Wszyscy" class="OPTION">Wszyscy</option></option>';};// | $poz_id = $poz_id | SEL_SQL = $SEL_SQL
							echo '</optgroup></select>';
							echo '<input type="hidden" name="IDM" value="'.$IDM.'" />';
							echo '<input type="hidden" name="IDW" value="'.$IDW.'" />';
							echo '<input type="hidden" name="SEL_SQL" value="'.$SEL_SQL.'" />';
							echo '<input class="button_wysz" type="submit" value="Filtruj" name="filtr"></FORM></p>';
							echo '</div>';
							//-----------------------------------------------------------------------------------KONIEC-FILTR----------------------------------------------------------------
							/* PAGINATION */
                                                            require(DR.'/class/page.php');
                                                            $page=NEW page();
                                                            $page->setDbRec("select COUNT(*) FROM `LOG` WHERE $SEL_SQL");
                                                            $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
                                                            $page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
                                                            $IDS=$page->getIDS();
                                                        /* END PAGINATION */
							echo '<table class="TAB_LOG" width="814PX">';
							echo '<tr class="TR_LOG" style="background:#80aaff;">';
							echo '<td class="TD_LOG" WIDTH="140PX"><p class="P_TAB_NAGL">Data :</p></td>';
							echo '<td class="TD_LOG" WIDTH="100PX"><p class="P_TAB_NAGL" >Użytkownik:</p></td>';
							echo '<td class="TD_LOG" WIDTH="140PX"><p class="P_TAB_NAGL">Moduł :</p></td>';
							echo '<td class="TD_LOG" ><p class="P_TAB_NAGL">Zadanie :</p></td>'; //300
							echo '</tr>';
							$i_color=TRUE;
							$ZAP_LOGI=$db->query("select d.ID,p.ID,p.NAZWA,m.NAZWA,d.ZADANIE,d.DAT_UTW FROM DZIEN d, PERS p, MODUL m WHERE d.MODUL=m.ID AND d.ID_PERS=p.ID AND d.$SEL_SQL  ORDER BY d.ID DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
							echo '<tr class="TR_LOG"  ><td colspan="4">'.$page->getPageLink('s').'</td></tr>';
                                                        
							
                                                        while($rek_log = mysqli_fetch_array($ZAP_LOGI))
									{
									if ($rek_log[1]!=1) $USR_COL="red"; else $USR_COL="black";
									if ($i_color==true) {
														$i_color=false;
														$tr_color="#cceeff";
														
									} 
									else {
											$i_color=true;
											$tr_color="white";
									};
									echo '<tr class="TR_LOG" style="background:'.$tr_color.';">';
									
									echo '<td class="TD_LOG">'.$rek_log[5].'</td>'; //$rek_log[0]
									echo '<td class="TD_LOG"><span style="margin-left:10px;color:'.$USR_COL.';">'.$rek_log[2].'</span></td>';
									echo '<td class="TD_LOG">'.$rek_log[3].'</td>';
									echo '<td class="TD_LOG">'.$rek_log[4].'</td>';
									echo '</tr>';
									}
                                                         echo '<tr class="TR_LOG" ><td colspan="4">'.$page->getPageLink('e').'</td></tr>';               
							echo "</table>";
                                                        echo "<br/>";
                                                       