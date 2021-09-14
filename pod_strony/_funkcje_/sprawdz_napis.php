<?php
function sprawdz_napis($napis,$typ_pola,$dlugosc_min,$dlugosc_max)
{
	$check=TRUE;
	$err="";
	$ile_znakow=mb_strlen($napis, "UTF-8");
	if ($_SESSION['id_user']==1)
	{
		echo "[".basename(__FILE__)."]napis : ".$napis."</br>";
		echo "[".basename(__FILE__)."]ile znakow : ".$ile_znakow."</br>";
	};
if($ile_znakow<$dlugosc_min){
$check=FALSE;
$err="<span class=\"S_ERR_RED\">Proszę wypełnić (<span CLASS=\"S_ERR_BLACK\">minimalna ilość znaków - ".$dlugosc_min."</span>)</span>";	
} else if ($ile_znakow>$dlugosc_max){
$check=FALSE;	
$err="<span class=\"S_ERR_RED\">Pole za długie (<span CLASS=\"S_ERR_BLACK\">maksymalna ilość znaków - ".$dlugosc_max."</span>)</span>";	
} else {
echo "Sprawdz znaki </br>";
//OK dlugosc napisu	
$ile_cyfr=0;
$ile_znak=0;
$zly_znak="";
$ch_znak=FALSE;
$pierwszy_blad=FALSE;
$arr_napis = array();
for ($i = 0; $i < $ile_znakow; $i++)
{
            $arr_napis[$i] = mb_substr($napis, $i, 1, "UTF-8");
};
$arr_alfabet=array("a","ą","b","c","ć","d","e","ę","f","g","h","i","j","k","l","ł","m","n","ń","o","ó","p","q","r","s","ś","t","u","w","x","y","z","ź","ż","A","Ą","B","C","Ć","D","E","Ę","F","G","H","I","J","K","L","Ł","M","N","Ń","O","Ó","P","Q","R","S","Ś","T","U","W","X","Y","Z","Ź","Ż");
$array_main[0]=$arr_alfabet;
$arr_cyfry=array("0","1","2","3","4","5","6","7","8","9");
$array_main[1]=$arr_cyfry;
$arr_znaki=array(",",".","/"," ","(",")");
$array_main[2]=$arr_znaki;
$znak_m="-";
$znak_pozostale=array("!","?");
//$TAB_DANE = array ("Login"=>"", "Hasło"=>"", "Imię"=>"", "Nazwisko"=>"", "Ulica"=>"", "Kod-Pocztowy"=>"", "Miasto"=>"","TEKST");
$i_min=0;
$i_max=1;
switch ($typ_pola):
				case "TEKST":
				case "Imię":
				case "Nazwisko";
				case "Miasto";
					break;
				case "oboz";
				case "klasa";	
				case "Login":
				case "Ulica":
						$i_max=3;
					break;
				case "Kod-Pocztowy";
						$i_min=1;
						$i_max=2;
					break;
				default:
					break;
endswitch;
$i_petla=1;
foreach($arr_napis as $klucz => $wartosc){
//echo "i_petla : ".$i_petla."</br>";
										if($pierwszy_blad==FALSE){
											for ($i=$i_min;$i<$i_max;$i++){
																if($ch_znak==FALSE){
																					foreach($array_main[$i] as $k_alfabet => $w_alfabet){
																						if ($wartosc==$w_alfabet) {
																								//echo "OK s : ".$wartosc." a : ".$w_alfabet."</br>";
																								$ch_znak=TRUE;
																								if(($typ_pola=="Ulica") && ($i_petla==1) && ($i==2)) {
																								$ch_znak=FALSE;
																								};
																								
																						}; 
																					};
																};
											};
										};
										if(($typ_pola=="Kod-Pocztowy") && ($i_petla==3)&& ($wartosc===$znak_m) ) {
																								//echo "OK s : ".$wartosc." a : ".$znak_m."</br>";
																								$ch_znak=TRUE;
										} else if (($typ_pola=="Kod-Pocztowy") && ($i_petla==3)&& ($wartosc!=$znak_m)){
											$err="<span class=\"S_ERR_RED\">Dozwolony format - <span CLASS=\"S_ERR_BLACK\">XX-XXX</span></span>";
											$check=FALSE;
											$pierwszy_blad=TRUE;
										};
										//------SPECJALNY-WARUNEK-DLA-OBOZ-KLASA-----------------------
										$z=0;
										foreach($znak_pozostale as $k_pozostale => $w_pozostale){
										if((($typ_pola=="oboz") || ($typ_pola=="klasa")) && ($i_petla>1) && ($wartosc===$znak_pozostale[$z]) && ($ch_znak==FALSE) ) {
																						if ($wartosc==$w_pozostale) {
																								//echo "OK s : ".$wartosc." a : ".$znak_m."</br>";
																								$ch_znak=TRUE;
																						};
										};
										};
										//------KONIEC-SPECJALNY-WARUNEK-DLA-OBOZ-KLASA-----------------------
										if ($ch_znak==FALSE && $check==TRUE) {
												$zly_znak=$wartosc;
												echo "Zły znak :".$wartosc." | ".$zly_znak."</br>";
												$err="<span class=\"S_ERR_RED\">Proszę usunąć niedozwolony znak - [<span CLASS=\"S_ERR_BLACK\">".$zly_znak."</span>]</span>";
												$pierwszy_blad=TRUE;
												$check=FALSE;
										};
										$ch_znak=FALSE;
$i_petla++;										
};
};
return array($check,$err);
}