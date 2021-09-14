<?php 
session_start(); 
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
require(DR.'/.cfg/konfiguracja.php');
require(DR.'/class/logToFile.php');
require(DR.'/class/session.php');
require(DR."/class/checkGlobalVar.php");

$start_time_page = floatval(microtime(true));

$log=NEW logToFile();
$checkVar=NEW checkGlobalVar();
$session=new session();

$checkVar->checkOnlyGet($IDM,'IDM');
$checkVar->checkOnlyGet($IDW,'IDW');

if(!$session->checkSession($IDM))
{    
    $log->log(0,"[".__FILE__."] SESSION NOT EXIST => REDIRECT TO LOGIN PAGE");
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/NASZE_ZD/cel_NASZE_ZD.php&IDM='.$IDM."&IDW=".$IDW);
}

$css="cel_modul.css";
require(DR.'/view/v_head_cel.php');
require(DR.'/class/database.php');

$db= NEW database();
$db->loadDb();

/* CHECK PERMISSION */
if(!array_key_exists($IDW, $_SESSION['perm'][$IDM]))
{
    $db->insDbLog($IDM,'>BRAK uprawnienia IDW = '.$IDW);
    echo '<div class="DIV_MAIN"><p class="P_ERR">BRAK uprawnienia</p></div>';
    return '';
}
$db->insDbLog($IDM,'Uruchomiono funkcję - '.$_SESSION['perm'][$IDM][$IDW]);
define('ACT_PERM',$_SESSION['perm'][$IDM][$IDW]);
define('ACT_URL',APP_URL.'/pod_strony/NASZE_ZD/');
define('PAGE_URL',APP_URL.'/pod_strony/NASZE_ZD/cel_NASZE_ZD.php?IDM='.$IDM);
define('NASZE_ZD_'.$IDM.$IDW,'y');
/* END CHECK PERMISSION */

require(DR.'/pod_strony/_funkcje_/resize_image_new_v3.php');
require(DR.'/pod_strony/_funkcje_/upload_file.php');

$checkVar->checkOnlyGet($ID,'ID');
$checkVar->checkOnlyGet($IDS,'IDS');

?>
<body bgcolor="#FFFAFA" onLoad="init('e')">
<div id="loading" style=" width:100%; text-align:left; top:0px; left:0px;">
<img src="<?=APP_URL?>/images/loadProgressBar.gif" border="0" width="128px" height="15px"></div>
<?php
switch ($IDW):
					
					case 0: 
                                            require(DR.'/pod_strony/NASZE_ZD/showAll.php');
                                            break;
					case 1:
                                            require(DR.'/pod_strony/NASZE_ZD/add.php');
						break;
                                        case 2: /* EDYTUJ-NASZE-ZDJĘCIE */
                                                if($ID!='')
                                                {
                                                    require(DR.'/pod_strony/NASZE_ZD/edytuj_op.php');
                                                }
                                                else
                                                {
                                                    require(DR.'/pod_strony/NASZE_ZD/edit.php');
                                                }
						
						
						break;							
                                        case 3: /* USUN-ZDJĘCIE */
                                                require(DR.'/pod_strony/NASZE_ZD/delete.php');
						break;	
					case 4: /* */
							echo '<center><p style="font-weight:bold;font-size:34px;color:black;">Wszystkie Obozy (<font color="#0099FF">GALERIE</font>): </p></center>';
							mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
							$wyswietl = mysqli_query($polaczenie,"select * from OBOZ WHERE WSK_US=0 AND TYP='g' order by id DESC");
												
						while($rekord = mysqli_fetch_array($wyswietl))
							{
							$STAT=$rekord[10];
							if ($STAT==1){$STAT_W ='<font style="color:black"> TAK </font>';} else {$STAT_W='<font style="color:red"> NIE </font>';};
							if ($_SESSION['login']==admin)	{
															
							//----------------------------------------------------Galeria------------------------------------------
														     $WYSWIETL = '<div style="border:1px; border-style:solid; border-radius:10px; margin:5px; width:800px; height:370px;">
																	<p><span style="float:left; text-align:left; color: #0099FF ;">ID Obozu: <span style="color: black; font-size: 18px; font-weight: bold;">'.$rekord[0].'</span> (<span style="color: black; font-size: 18px;">Galeria</span>) Data utworzenia : <span style="color: black; font-size: 18px; font-weight: bold;">'.$rekord[11].'</span></span>
																	<span style="float:right; text-align:right; color: #0099FF ;">Widoczny : <span style="color: black; font-size: 18px; font-weight: bold;">'.$STAT_W.' </span><a  href="ustawVo_2.php?ID='.$rekord[0].'"><span class="button_ustaw"> USTAW</span></a></span></br></p>
																	<center><div style="width:798px; height:235px; margin-top:10px; min-height: 10em;display: table-cell;vertical-align: middle;"><A HREF="javascript:displayWindow(&#39;../../../zdjecia/obozy/'.$rekord[2].'&#39;,660,500)"><img src="../../zdjecia/obozy/'.$rekord[6].'/'.$rekord[3].'"  style="height:auto; width:auto; max-width:250px; max-height:235px; border:0px;" /></a></br>'.$rekord[5].'</div></center><div style="height:20px;display:table-cell; vertical-align:top;margin-bottom:15px;padding-top:0px;float:right;margin-right:10px;"><a  href="edytujo.php?ID='.$rekord[0].'"><p class="button">Edytuj</p></a><a  href="usuno_2.php?ID='.$rekord[0].'"><p class="button">Usuń</p></a></div><br/></div>';
															}
							else 							{
							
															
														//----------------------------------Galeria------------------------------------------
															$WYSWIETL = '<div style="border:1px; border-style:solid; border-radius:10px; margin:5px; width:800px; height:370px;">
															<p><span style="float:left; text-align:left; color: #0099FF ;">ID Obozu : <span style="color: black; font-size: 18px; font-weight: bold;">'.$rekord[0].'</span> (<span style="color: black; font-size: 18px;">Galeria</span>)</span>
															<span style="float:right ;text-align:right; color: #0099FF ;">Widoczny : <span style="color: black; font-size: 18px; font-weight: bold;">'.$STAT_W.' </span><a  href="ustawVo_2.php?ID='.$rekord[0].'"><span class="button_ustaw"> USTAW</span></a></span></br></p>
															<center><div style="width:798px; height:235px; margin-top:10px; min-height: 10em;display: table-cell;vertical-align:middle;"><A HREF="javascript:displayWindow(&#39;../../zdjecia/obozy/'.$rekord[2].'&#39;,660,500)"><img src="../../zdjecia/obozy/'.$rekord[6].'/'.$rekord[3].'"  style="height:auto; width:auto; max-width:250px; max-height:235px; border:0px;" /></a></br>'.$rekord[5].'</div></center><div style=" height:20px;display:table-cell; vertical-align:top;margin-bottom:15px;padding-top:0px;float:right;margin-right:10px;"><a  href="edytujo.php?ID='.$rekord[0].'"><p class="button">Edytuj</p></a><a  href="usuno_2.php?ID='.$rekord[0].'"><p class="button">Usuń</p></a></div><br/></div>';
															}
							echo $WYSWIETL;
							}					 
							break;
						
						case 6: /* WIDOCZNOŚĆ-NASZE-ZDJĘCIA */
							require(DR.'/pod_strony/NASZE_ZD/visibility.php');
						break;
                                            default:
                                                echo 'WRONG IDW';
                                                break;
endswitch;
echo "<p class=\"P_INFO_OP\">Strona załadowana w czasie : ".round((floatval(microtime(true))-$start_time_page),2)." s.</p>";?>		
</body>
</html>