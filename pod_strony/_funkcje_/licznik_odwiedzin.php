<?php

function licznik_odwiedzin($tabela,$polaczenie)
{
    IF (isset($_SESSION["id_user"]) AND ($_SESSION["id_user"]==1)){echo  "Uruchomiono funkcję lidznik odwiedzin</br>";};
    mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_general_ci`");
    $date = date('Y-m-d');    //Data Rok-Miesiąc-Dzień
    $time = date('H:i:s');    //Czas Godzina:Minuta:Sekunda
    //$date_h=date('H');
    //$date_i=date('i');
    $IP = $_SERVER['REMOTE_ADDR']; //Pobiera IP odwiedzającego
    //echo "IP Adres - $IP</br>";
    $link = mysqli_query($polaczenie,"SELECT IP FROM LOG_ODW WHERE IP='$IP' and DATA='$date' AND STRN='$tabela'"); //Zapytanie.
    $ile = mysqli_num_rows($link); //Pobiera ilość wyników
//echo "ILE - $ile</br>";
if ($ile == 0) {   //Jeżeli ilość wyników = 0
				$ZAP_SQL="INSERT INTO `LOG_ODW` (`IP`,`DATA`,`GODZ`,`MIN`,`STRN`) VALUES (\"".$IP."\",\"".$date."\",".date('H').",".date('i').",\"".$tabela."\")";//".date('H')." ".date('i')."
				$INS_ADDR = mysqli_query($polaczenie,$ZAP_SQL); // or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL [$INS_ADDR] - <span class=\"S_SQL\"> $ZAP_SQL - ".mysql_error()."</span></p>");
				if (!$INS_ADDR) {  //Jeżeli nie udało się dodać naszych danych
     //   echo('Błąd bazy danych. <br />'); //Pojawia się komunikat o błędzie
	 if (isset($_SESSION["id_user"]) AND ($_SESSION["id_user"]==1)){
			echo "<p class=\"P_SQL_ERR\">Błąd zapytania SQL [INS_ADDR] - <span class=\"S_SQL\"> $ZAP_SQL - ".mysql_error()."</span></p>";
	 };
		}
	if (isset($_SESSION["id_user"]) AND ($_SESSION["id_user"]==1)){
																	echo "Status INS_ADDR - $INS_ADDR</br>";
	};
} 
else { //Jeżeli ilość wyników <> 0
		$UPD_ADDR = mysqli_query($polaczenie,"UPDATE LOG_ODW SET DATA='$date', GODZ='" . date('H') . "', MIN='" . date('i') . "' WHERE IP='$IP' and DATA='$date' AND STRN='$tabela'"); //Odświeża dane użytkownika w tabeli

    if (!$UPD_ADDR) { //Jeżeli nie udało się odświerzyć naszych danych
					if ($_SESSION["id_user"]==1){
					echo('Blad bazy danych. <br />'); //Pojawia się komunikat o błędzie
					};
    }
	if (isset($_SESSION["id_user"]) AND ($_SESSION["id_user"]==1)){
	echo "Status UPD_ADDR - $UPD_ADDR</br>";
	};
}

$wczoraj = (int) date('d'); //Pobiera dzień
$wczoraj = $wczoraj - 1;  //odejmuje 1 dzień
$miesiac = (int) date('m'); //Pobiera miesiąc
if ($wczoraj == 0) { //Jeżeli wczoraj = 0
    if (date('m') == 4 || date('m') == 6 || date('m') == 8 || date('m') == 9 || date('m') == 11) {
        $wczoraj = "31";
        $miesiac -= "1";
    }
    if (date('m') == 3) {
        $wczoraj = "28";
        $miesiac -= "1";
    }
    if (date('m') == 5 || date('m') == 7 || date('m') == 10 || date('m') == 12) {
        $wczoraj = "30";
        $miesiac -= "1";
    }
    if (date('m') == 2) {
        $wczoraj = "31";
        $miesiac -= "12";
    }
}
if ($wczoraj <= 9) { //Jeżeli wczoraj jest mniejsze lub równe 9
    $wczoraj = "0" . $wczoraj;
}
if ($miesiac <= 9) { //Jeżeli miesiac jest mniejsze lub równe 9
    $miesiac = "0" . $miesiac;
}
$wczoraj = date('Y') . "-" . $miesiac . "-" . $wczoraj;
$all=0;
$time = date('H'); //Pobiera godzine
$time2 = date('i') - 5; //Pobiera minuty odejmując 5
$link = mysqli_query($polaczenie,"SELECT * FROM LOG_ODW"); //Pobiera dane z tabeli 'online_sklep'
$online = 0; //ustawia zmienna na = 0
$dzis = 0; //ustawia zmienna na = 0
$wczorajlicz = 0; //ustawia zmienna na = 0
while ($wynik = mysqli_fetch_array($link)) { //Pętla
    if ($wynik['DATA'] == $date) { //jeżeli wynik równa się z dzisiejszą datą
        if ($wynik['GODZ'] >= $time) { //
            if ($wynik['MIN'] >= 5) { //jeżeli wynik minut jest większy lub równy od 5
                $minuta = $wynik['MIN'] - 5;
            } else {
                $minuta = $wynik['MIN'];
            }
            if ($minuta >= $time2) { 
                $online++; //Dodaje osobę online_sklep
            }
        }
        $dzis++; //Dodaje osobę odwiedzającą do dziś
    }
    if ($wynik['DATA'] == $wczoraj) {
        $wczorajlicz++; //dodaje osobę odwiedzającą do wczoraj
    }
    $all++; //Dodaje osobę do wszystkich
}
return ($all);
};
?>