<?php
/*
Klasa sprawdzajaca przekazany plik.
- sprawdza typ pliku, czy wystepuje w tablicy rozszerzen przekazanych jako parametr [0]
- sprawdza rozmiar, czy zgadza sie z rozmiarem przekazanym jako parametr [1]
Klasa zwraca status sprawdzenia metoda sprawdzPlik od dwoch parametrow (typ,rozmiar) 1-plik ok, 0-plik nieprawidłowy
Klasa zwraca komunikat bledu poprzez metode zwrocKomunikatBledu
*/
class sprawdzImportowanyPlik
{
	private $dopuszczalneRozszerzenia;
	private $typDoSprawdzenia;
	private $status=0;
	private $stat_t=0;
	private $stat_r=0;
	private $komunikatBlad_t="";
	private $komunikatBlad_r="";
	private $dopuszczalnyRozmiar;// =8388608; // 8MB
	private $rozmiarPliku;
	private $tab_style=array("</span>","<span style=\"color:#ff0000;font-weight:bold;font-size:14px;\">","<span style=\"color:#000000;font-weight:bold;font-size:14px;\">","<span style=\"color:#4000ff;font-weight:bold;font-size:14px;\">"); 
	// [1]#ff0000 - czerwony , [2] #000000 - czarny, [3] niebieski
	// Kontruktor ustawia parametry do wykonania testu
	public function __construct($tab_rozszerzen,$rozmiar)
	{
		$this->dopuszczalneRozszerzenia=$tab_rozszerzen; //oplaca sie jak tylko bedzie jeden obiekt do sprawdzenia
		$this->dopuszczalnyRozmiar=$rozmiar;
		//echo "Konstruktor</br>";
		//echo "Max dopuszczalny rozmiar pliku - $this->dopuszczalnyRozmiar </br>";
	}
	// Funkcja pokazDostRozsz zwraca przekazane jako parametr dostepne rozszerzenia
	function pokazDostRozsz()
	{
			echo "Dopuszczalne rozszerzenia :</br>";
			foreach($this->dopuszczalneRozszerzenia as $wartosc)
			{
				echo $wartosc."</br>";
			};
	}
	// Funkcja ustawTyp ustawia typ pliku do porownania z dostepnymi
	private function ustawTyp($plik)
	{
		$this->typDoSprawdzenia=$plik;
	}
	// Funkcja pokazUstawPlik wyswietla przekazany typ pliku
	function pokazUstawTyp()
	{
		echo $this->tab_style[3]."Przekazany typ pliku - '".$this->tab_style[2].$this->typDoSprawdzenia.$this->tab_style[0]."'".$this->tab_style[0]."</br>";
	}
	// Funkcja ustawRozmiarPliku ustawia rozmiar przekazanego pliku do sprawdzenia
	private function ustawRozmiarPliku($plik)
	{
		$this->rozmiarPliku=$plik;
	}
	// Funkcja pokazRozmiarPliku wyswietla przekazany rozmiar pliku
	function pokazPrzekazRozmiar()
	{
		echo  $this->tab_style[3]."Rozmiar przekazanego pliku - '".$this->tab_style[2].$this->rozmiarPliku.$this->tab_style[0]."'".$this->tab_style[0]."</br>";
	}
	// Prywatna Funkcja sprawdzRozmiar porównuje przekazany rozmiar pliku z rozmiarem ustawionym w konstruktorze
	// Funkcja ustawia status rozmiaru 0 - przekroczony rozmiar, 1 - rozmiar dopuszczalny
	// Funkcja w razie statusu = 0 ustawia komunikat bledu
	private function sprawdzRozmiar()
	{
		if($this->rozmiarPliku>$this->dopuszczalnyRozmiar)
		{
			$this->stat_r=0;
			$this->konwertujRozmiar($this->rozmiarPliku);
			$this->ustawBlad(0,$this->tab_style[1]."Plik przekroczyl dopuszczalny rozmiar - ".$this->tab_style[0].$this->tab_style[2].$this->rozmiarPliku." MB".$this->tab_style[0]);
		}
		else
		{
			$this->stat_r=1;
		}
	}
	// Prywatna Funkcja sprawdzTyp porównuje przekazany typ pliku z dopuszczalnymi typami przekazanymi w konstruktorze
	// Funkcja ustawia status typu 0 - typ nie wystepuje, 1 - typ wystepuje
	// Funkcja w razie statusu = 0 ustawia komunikat bledu
	private function sprawdzTyp()
	{
		foreach($this->dopuszczalneRozszerzenia as $wartosc)
		{
				if ($wartosc==$this->typDoSprawdzenia)
				{
					$this->stat_t=1;
					echo "Znalazlem</br>";
				}
		};
		if($this->stat_t==0)
		{
			$this->ustawBlad(1,$this->tab_style[1]."Nieprawidłowy typ pliku - ".$this->tab_style[0].$this->tab_style[2].$this->typDoSprawdzenia.$this->tab_style[0]);
		}
	}
	// Główna Funkcja sprawdzPlik testuje typ i rozmiar przekazany jako parametr z danymi z konstruktora
	// Funkcja zwraca status
	function sprawdzPlik($typ,$rozmiar)
	{
		$this->komunikatBlad_t="";
		$this->komunikatBlad_r="";
		$this->ustawTyp($typ);
		$this->ustawRozmiarPliku($rozmiar);
		$this->sprawdzTyp();
		$this->sprawdzRozmiar();
		return $this->zwrocStatus();
	}
	// Funkcja zwrocStatus zwraca status pracy nad przekaqzanyumi danymi, 0 - dla nieprawidlowych danych, 1 - dla prawidlowych danych
	// Funkcja zwraca status
	function zwrocStatus()
	{
		if($this->stat_t==0 OR $this->stat_r==0)
		{
			$this->status=0;
		}
		else $this->status=1;
	
		return $this->status;
	}
	// Funkcja ustawBlad w zaleznosci od rodzaju przekazanego bledu ustawia odpowiedni komunikat do odpowiedniej zmiennej
	private function ustawBlad($rodzaj,$komunikat)
	{
		switch($rodzaj):
						case 0: // TYP
							$this->komunikatBlad_t=$komunikat;
							break;
						case 1: // rozmiar
							$this->komunikatBlad_r=$komunikat;
						default:
							break;
		endswitch;
	}
	// Funkcja zwrocKomunikatBledu zwraca komunikat bledu
	function zwrocKomunikatBledu()
	{
		return $this->komunikatBlad_t."</br>".$this->komunikatBlad_r;
	}
	private function konwertujRozmiar($rozmiar)
	{
		$this->rozmiarPliku=round($rozmiar/1048576,2); // 1024 * 1024 zaokraglenie do MB;
	}
	// Destruktor klasy
	public function __destruct()
	{
      //echo 'Obiekt klasy [sprawdzImportowanyPlik] został zniszczony.<br/>';
	} // koniec __destruct();
};
?>

