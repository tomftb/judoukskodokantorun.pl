<?php
if(!defined('ADMIN1322')) { exit('NO PERMISSION');}
echo '<p class="P_M_NAGL">LOG - Odwiedziny strony judoukskodokantorun.pl</p>';



echo '<table class="TAB_LOG">';
							echo '<tr class="TR_LOG" style="background:#80aaff;">';
							echo '<td class="TD_LOG" colspan="3"><p class="P_TAB_NAGL">Suma odwiedzin : '.mysqli_fetch_row($db->query("SELECT MAX(ID) FROM LOG_ODW"))[0].'</p></td>';
							echo '</tr>';
							echo '<tr class="TR_LOG" style="background:#80aaff;">';
							echo '<td class="TD_LOG" WIDTH="110PX"><p class="P_TAB_NAGL">IP :</p></td>';
							echo '<td class="TD_LOG" WIDTH="200PX"><p class="P_TAB_NAGL">DATA :</p></td>';
							echo '<td class="TD_LOG" WIDTH="100PX"><p class="P_TAB_NAGL">GODZINA :</p></td>';
							echo '</tr>';
							/* PAGINATION */
                                                            require(DR.'/class/page.php');
                                                            $page=NEW page();
                                                            $page->setDbRec("select COUNT(*) FROM `LOG_ODW`");
                                                            $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
                                                            $page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
                                                            $IDS=$page->getIDS();
                                                        /* END PAGINATION */
							$SEL_LOG=$db->query("SELECT IP, DATA,GODZ,MIN FROM LOG_ODW ORDER BY ID DESC LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
							echo '<tr class="TR_LOG" ><td colspan="3">'.$page->getPageLink('s').'</td></tr>';
							$i_color=true;
							while($rek_log = mysqli_fetch_array($SEL_LOG))
                                                        {
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
									echo '<td class="TD_LOG">'.$rek_log[0].'</td>';
									echo '<td class="TD_LOG">'.$rek_log[1].'</td>';
									echo '<td class="TD_LOG">'.$rek_log[2].':'.$rek_log[3].'</td></tr>';
									}
                                                          echo '<tr class="TR_LOG" ><td colspan="3">'.$page->getPageLink('e').'</td></tr>';
							echo "</table>";
							
