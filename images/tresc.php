<link rel="stylesheet" href="style.css" type="text/css">
<div id="AKTUALNOSCI" style="background: url(images/tlo.jpg) repeat-y ; margin: 0px; padding: 0px ; vspace: 0px ; hspace: 0px; display: block;">
<?php include('menu.php'); ?>

<div id="TRESC">
<div id=HERB><p style="font-size:28pt; font-family: 'Times New Roman';">"TORU&#323; MIASTEM SPORTU"</p><img src="images/herb/herb.jpg" alt="Herb_miasta_Torun" /><p style="font-size:14pt;"><a href="http://www.torun.pl" target="_blank">www.torun.pl</a></p></div>
<!--<img class="grupa" src="images/grupa_uks_kodokan_1.jpg" alt="part1" />
<img class="grupa" src="images/grupa_uks_kodokan_2.jpg" alt="part2" />
<img class="grupa" src="images/grupa_uks_kodokan_3.jpg" alt="part3" />
<img class="grupa" src="images/grupa_uks_kodokan_4.jpg" alt="part4" />
<img class="grupa" src="images/grupa_uks_kodokan_5.jpg" alt="part5" />
<img class="grupa" src="images/grupa_uks_kodokan_6.jpg" alt="part6" />
<img class="grupa" src="images/grupa_uks_kodokan_7.jpg" alt="part7" />
<img class="grupa" src="images/grupa_uks_kodokan_8.jpg" alt="part8" />
<img class="grupa" src="images/grupa_uks_kodokan_9.jpg" alt="part9" />
<img class="grupa" src="images/grupa_uks_kodokan_10.jpg" alt="part10" />
<img class="grupa" src="images/grupa_uks_kodokan_11.jpg" alt="part11" />
-->
<img src="images/kal800x600.png" alt="Kalendarz Judo" width="800" height="813"/>
<br/>


<p style="font-weight: bold; font-size: 16pt; text-align: center; color: #006699;">

AKTUALNY PLAN ZAJ&#280;&#262; :<br /><br />

<span style="font-size:14pt;">ZAJ&#280;CIA ODBYWAJ&#260; SI&#280; W SZE&#346;CIU GRUPACH :</span></p>

<table class="TABELA" border="1px" bordercolor="black">



<tr style="background:#C0C0C0;">
					<td class="KOLUMNA1"><span class="zawartosc1">NAZWA GRUPY:</span></td> 	
					<td align="center" class="KOLUMNA2"><span class="zawartosc1">WIEK-ROCZNIK</br>
							UCZESTNIKA ZAJ&#280;&#262;</span></td>		
					<td align="center" class="KOLUMNA3"><span class="zawartosc1">DNI I GODZINY ZAJ&#280;&#262;</span></td>		
					<td align="center" class="KOLUMNA4"><span class="zawartosc1">ZAPISY</span></td>
					</tr>
<?php
mysql_connect('localhost', 'nadrukireklamowe4' , 'ZNRl8KQOjuq');
mysql_select_db("nadrukireklamowe4");
$query = mysql_query("select * from Treningi_test order by id");
while($rekord = mysql_fetch_array($query))
{
$tekst .= '<tr style="background:'.$rekord[5].';"><td width="100"><span style="font-size:'.$rekord[6].'; color:'.$rekord[10].'"><b>'.$rekord[1].'</b></span></td><td width="150"><span style="font-size:'.$rekord[7].'; color:'.$rekord[10].'"><b>'.$rekord[2].'</b></span></td><td width="220"><span style="font-size:'.$rekord[8].'; color:'.$rekord[10].'"><br/><b>'.$rekord[3].'</b><br/><br/></span></td><td width="220"><center><span style="font-size:'.$rekord[9].'; color:'.$rekord[10].'"><br/><b>'.$rekord[4].'</b><br/><br/></span></center></td></tr>';
}
echo $tekst;

?>
</table>
</div>
