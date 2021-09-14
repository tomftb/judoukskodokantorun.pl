<?php
ob_start();
session_start();
require_once("konfiguracja.php");
?>
<html>
<head style="height: 100%">
<title>UKS Kodokan Toruń</title>

<meta name="Description" content="Wyniki, zawody, turnieje - UKS Kodokan Toruń" />

<meta name="Keywords" content="judo zawody rzut rzuty randori techniki kano mata toruń sekcja torun walki" />

<meta name="Author" content="Tomasz Talarczyk; tomasz[.]talarczyk [@] wp [.] pl; Tomasz Borczynski ; tomek[.]b82[@]wp[.]pl" />

<meta name="Robots" content="all" />

<meta http-equiv="Content-language" content="pl" />

<meta http-equiv="Creation-date" content="Mon, 12 Jul 2004 00:00:00 +0200" />

<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />


<link rel="stylesheet" href="css/index.css" type="text/css">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
<style type="text/css">

table ,td,tr {
	border:0px;
	border-collapse: collapse;

}

</style>
	
	
</head>

<body>
<center>
<table style="margin:0px;padding:0px; width: 1025px;border-collapse: collapse; " border="0px">
<!---------------------------------------------------------------------------NAGLÓWEK-------------------------------------------------------->
<tr id="NAGLOWEK" style="border: 0px; width: 1024px;  background:url(images/tlo_naglowek2.jpg);">
<td colspan="3">
<p style="text-align:right ; font-size:12px ; font-weight:bold; margin:0px ; padding:0px;">
<a href="logowanie.php">Zaloguj</a></p>
<img id="nagl_img" src="images/tlo_judo.png" alt="strona_uks_kodokan_1"/>
</td></tr>
<!---------------------------------------------------------------------------KONIEC-NAGŁÓWEK------------------------------------------------------->
<!---------------------------------------------------------------------------TRESC-------------------------------------------------------->
<tr>
<td style="background-image:url(images/side_left.png); background-repeat:repeat-y;" >
<center>
<ul class="menu">
<li class="menu"><a href="aktualnosci.php"><img src="images/przycisk/aktualnosci.jpg" 
																					onmouseover="this.src = 'images/przycisk/aktualnosci_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/aktualnosci.jpg'"   
																					style="border:0px"/></a></li>
<li class="menu"><a href="wladze_klubu.php"><img src="images/przycisk/wladze_klubu.jpg" 
																					onmouseover="this.src = 'images/przycisk/wladze_klubu_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/wladze_klubu.jpg'"   
																					style="border:0px"/></a></li>
<li class="menu"><a href="trenerzy.php" ><img src="images/przycisk/trenerzy.jpg" 
																					onmouseover="this.src = 'images/przycisk/trenerzy_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/trenerzy.jpg'"   
																					style="border:0px"/></a></li>

																					
<li class="menu"><a href="obozy.php"><img src="images/przycisk/obozy.jpg" 
																					onmouseover="this.src = 'images/przycisk/obozy_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/obozy.jpg'"   
																					style="border:0px"/></a></li>

																					
<li class="menu"><a href="nasze_fotki.php"><img src="images/przycisk/nasze_zdjecia.jpg" 
																					onmouseover="this.src = 'images/przycisk/nasze_zdjecia_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/nasze_zdjecia.jpg'"   
																					style="border:0px"/></a></li>
<li class="menu"><a href="pomagaja_nam.php"><img src="images/przycisk/pomagaja_nam.jpg" 
																					onmouseover="this.src = 'images/przycisk/pomagaja_nam_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/pomagaja_nam.jpg'"   
																					style="border:0px"/></a></li>
<li class="menu"><a href="nasi_zawodnicy.php"><img src="images/przycisk/nasi_zawodnicy.jpg" 
																					onmouseover="this.src = 'images/przycisk/nasi_zawodnicy_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/nasi_zawodnicy.jpg'"   
																					style="border:0px"/></a></li>
<li class="menu"><a href="linki.php"><img src="images/przycisk/linki.jpg" 
																					onmouseover="this.src = 'images/przycisk/linki_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/linki.jpg'"   
																					style="border:0px"/></a></li>
<li class="menu"><a href="kontakt.php"><img src="images/przycisk/kontakt.jpg" 
																					onmouseover="this.src = 'images/przycisk/kontakt_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/kontakt.jpg'"   
																					style="border:0px"/></a></li>
<li class="menu"><a href="klasa_sportowa.php"><img src="images/przycisk/klasa_sportowa.jpg" 
																					onmouseover="this.src = 'images/przycisk/klasa_sportowa_2.jpg'" 
																					onmouseout="this.src = 'images/przycisk/klasa_sportowa.jpg'"   
																					style="border:0px"/></a></li>

																				
<li class="menu"><a href="dla_najmlodszych.php"><img src="images/przycisk/dla_najmlodszych_2.jpg" 
																					onmouseover="this.src = 'images/przycisk/dla_najmlodszych.jpg'" 
																					onmouseout="this.src = 'images/przycisk/dla_najmlodszych_2.jpg'"   
																					style="border:0px"/></a></li>
</ul></center>
</td>

<td id="TRESC" style="width: 730px; background-color: white;">
<p style="font-size:28pt; font-family: 'Times New Roman';">"TORUŃ MIASTEM SPORTU"</p><img src="images/herb/herb.jpg" alt="Herb_miasta_Torun" /><p style="font-size:14pt;"><a href="http://www.torun.pl" target="_blank">www.torun.pl</a></p>
<img src="images/new_kal800x600.jpg" alt="Kalendarz Judo" max-width="710" max-height="722"/>
<br/>


<p style="font-weight: bold; font-size: 16pt; text-align: center; color: #006699;">

AKTUALNY PLAN ZAJĘĆ :<br /><br />

<span style="font-size:14pt;">ZAJĘCIA ODBYWAJĄ SIĘ W SZEŚCIU GRUPACH :</span></p>


</td>
<td style="width: 80px; background:url(images/side_right.png); background-repeat:repeat-y;">

</td>

<!--------------------------------------------------------------------------KONIEC-TRESC-------------------------------------------------->
<!--------------------------------------------------------------------------STOPKA-------------------------------------------------->
</tr><tr><td colspan="3">
<img src="images/uks_stopka.jpg" alt="6"/>
</td></tr>
<!--------------------------------------------------------------------------KONIEC-STOPKA-------------------------------------------------->

</table>
</center>

</body>
</html>
<?php
ob_end_flush();
?>