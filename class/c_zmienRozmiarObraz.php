<?php
/*
Klasa pomniejsza wskazany obraz.
- jako parametr konstruktora [0] przyjmuje adres wraz z nazwa pliku zrodlowego
- jako parametr [0] funkcji zmienRozmiarObraz przyjmuje nazwe nowego pliku
- jako parametr [1] funkcji zmienRozmiarObraz przyjmuje lokalizacje dla nowego pliku
- jako parametr [2] funkcji zmienRozmiarObraz przyjmuje max wysokosc nowego pliku
- jako parametr [3] funkcji zmienRozmiarObraz przyjmuje max szerokosc nowego pliku
Klasa zwraca lokalizacje wraz z nazwa nowego pliku
Klasa zwraca komunikat bledu poprzez metode zwrocKomunikatBledu
*/
class zmienRozmiarObraz
{
	private $plikZrodlowy=array();
	private $przekazaneDane=array();
	#[0] $przekazanaNowaNazwa;
	#[1] $lokalizacjaNowegoPliku;
	#[2] $szerokoscNowegoPliku=0;
	#[3] $wysokoscNowegoPliku=0;
	private $danePlikuZrodlowego=array();
	#[0] $nazwaPlikuZrodlowego
	#[1] $typZrodlowegoPliku;
	#[2] $rozszerzenieZrodlowegoPliku;
	#[3] $szerokoscZrodlowegoPliku
	#[4] $wysokoscZrodlowegoPliku
	private $status=0;
	private $komunikatBlad_arg="";
	private $komunikatBlad_r="";
	private $komunikatBlad_przekazaneDane=array();
	private $dopuszczalnyRozmiar;// =8388608; // 8MB
	private $komunikat_poziom=""; 
	private $typZmiennej="string";
	private $typPrzekazanejZmiennej="";
	private $test=TRUE;
	private $testowanyPlik="";
	private $sciezkaWymiar="",$sciezkaZmienRozmiar="";
	private $wskZmiany=0;
	private $tmpRoznicaSzerokosc=0, $tmpRoznicaWysokosc=0;
	private $wynikProporcja;
	private $nowaSzeroksoc,$nowaWysokosc;
	private $wskZmiana=FALSE,$wskNowyPlik=FALSE;
	private $nazwaNowyPlik="";
	private $tmpDane="",$tmpDane2="",$tmpNazwa="",$tmpLicznik=0,$tmpLicznik2=0,$tmpRozszerzenie="";
	
	private $tab_style=array("</span>","<span style=\"color:#ff0000;font-weight:bold;font-size:14px;\">","<span style=\"color:#000000;font-weight:bold;font-size:14px;\">","<span style=\"color:#4000ff;font-weight:bold;font-size:14px;\">","<span style=\"color:#009900;font-weight:bold;font-size:14px;\">"); 
	// [1]#ff0000 - czerwony , [2] #000000 - czarny, [3] niebieski, [4] zielony
	// Kontruktor ustawia parametry do wykonania testu
	public function __construct($przekazanaLokalizacjaPlikDoKonwersji="",$przekazanaNazwaPlikDoKonwersji="")
	{
		IF(func_num_args()<2)
		{
			$this->ustawBlad(0,$this->tab_style[2]."[WYMAGANE - 2] Nie wystarczajaca ilość argumentów konstruktora - ".$this->tab_style[1]."0".$this->tab_style[0].$this->tab_style[0]."</br>");
			$this->wyswietlKomunikatBledu();
			$this->test=FALSE;
		}
		else
		{
			$this->plikZrodlowy[0]=$przekazanaLokalizacjaPlikDoKonwersji;
			$this->plikZrodlowy[1]=$przekazanaNazwaPlikDoKonwersji;
			IF(!$this->sprawdzTypArgumentu(0,$this->typZmiennej,$this->plikZrodlowy[0].$this->plikZrodlowy[1]))
			{
					$this->wyswietlKomunikatBledu();
					$this->test=FALSE;
			}
		};
		//echo "Konstruktor - ".func_num_args()."</br>";
		//echo "Konstruktor</br>";
	}
	private function sprawdzTypArgumentu($indeks,$typ,$argument)
	{
		//echo "$indeks - $typ</br>";
		$this->typPrzekazanejZmiennej=gettype($argument);
		if($this->typPrzekazanejZmiennej!=$typ)
		{
			$this->ustawBlad(0,$this->tab_style[2]."[Argument - ".$indeks."][Wymagany - $typ] Niepoprawny typ przekazanego argumentu - ".$this->tab_style[1]."$this->typPrzekazanejZmiennej".$this->tab_style[0].$this->tab_style[0]."</br>");
			return false;
		}
		else
		{
			return true;
		}
	}
	// Funkcja zwracajaca wyniki testu dostepnosci do przekazanego pliku. 
	// TESTY:
	// a. czy plik/katalog istnieje
	// b. czy plik jest do odczytu
	// c. dla katalogu, czy jest do zapisu
	private function sprawdzDostep($rodzaj,$plik)
	{
		IF($rodzaj=='p') $this->testowanyPlik="plik";
		ELSE $this->testowanyPlik="katalog";
		//echo "Przekazany plik - $plik</br>";
		if (file_exists($plik))
		{
			if (is_readable($plik))
			{
				IF($rodzaj=='k')// rodzaj = katalog
				{
					IF(is_writable($plik))
					{
						return TRUE;
					}
					else
					{
						$this->ustawBlad(0,$this->tab_style[2]."Brak uprawnienia do zapisu w katalogu  - ".$this->tab_style[1]."$plik".$this->tab_style[0].$this->tab_style[0]."</br>");
						return FALSE;
					}
				}
				else
				{
				return TRUE;
				};
			}
			else
			{
				$this->ustawBlad(0,$this->tab_style[2]."Wskazany $this->testowanyPlik nie jest do odczytu  - ".$this->tab_style[1]."$plik".$this->tab_style[0].$this->tab_style[0]."</br>");
				return FALSE;
			}
		}
		else
		{
			$this->ustawBlad(0,$this->tab_style[2]."Wskazany $this->testowanyPlik nie istnieje - ".$this->tab_style[1]."$plik".$this->tab_style[0].$this->tab_style[0]."</br>");
			return FALSE;
		}
	return TRUE;
	}
	// Funkcja pokazDostRozsz zwraca przekazane jako parametr dostepne rozszerzenia
	function pokazPrzekazaneDane()
	{
		echo "Przekazane parametry :</br>";
		foreach($this->przekazaneDane as $key => $wartosc)
		{
			echo $this->tab_style[3]."$key -> '".$this->tab_style[2].$wartosc.$this->tab_style[0]."'".$this->tab_style[0]."</br>";
		};
	}
	// Funkcja wyodrebnia nazwe pliku
	private function wyodrebnijNazwe($nazwa)
	{
		for($i=0; $i <= strlen ($nazwa);$i++)
		{
			IF(substr($nazwa,$i,1)==".")
			{
				$this->tmpLicznik++;
				//echo $this->tmpLicznik." kropka</br>";
			}
		}
		$this->tmpLicznik2=$this->tmpLicznik;
		$this->tmpLicznik=0;
		for($i=0; $i <= strlen ($nazwa);$i++)
		{
			IF(substr($nazwa,$i,1)==".")
			{
				$this->tmpLicznik++;
				//echo $this->tmpLicznik." kropka</br>";
			}
			IF($this->tmpLicznik<$this->tmpLicznik2)
			{
				//echo $this->tmpLicznik." kropka</br>";
				$this->tmpNazwa=$this->tmpNazwa.substr($nazwa,$i,1);
				//echo $this->tmpNazwa."</br>";
			}
		}
	$this->danePlikuZrodlowego[0]=$this->tmpNazwa;
	}
	// Funkcja ustawTyp ustawia typ pliku do porownania z dostepnymi
	private function ustawTyp()
	{
		$this->danePlikuZrodlowego[1]=mime_content_type($this->plikZrodlowy[0].$this->plikZrodlowy[1]);
	}
	private function ustawRozszerzenie()
	{
		$this->danePlikuZrodlowego[2]=strtolower(substr(strrchr($this->danePlikuZrodlowego[1], '/'), 1)); //Rozszerzenie
	}
	// Funkcja pokazUstawPlik wyswietla przekazany typ pliku
	private function sprawdzWymiaryPlikuZrodlowego()
	{
		list($this->danePlikuZrodlowego[3],$this->danePlikuZrodlowego[4]) = getimagesize($this->plikZrodlowy[0].$this->plikZrodlowy[1]);// szerokosc, wysokosc
	}
	// Funkcja pokazUstawPlik wyswietla przekazany typ pliku
	function pokazDane($wskaznik=0)
	{
		IF(func_num_args()<1)
		{
			$this->tmpDane="Nie podano argumentu funkcji ";
		}
		else 
		{
		IF ($this->test!=FALSE)
		{
			switch($wskaznik):
							case 0:							
									$this->tmpDane="Przekazana nazwa pliku zrodlowego -";
									$this->tmpDane2=$this->danePlikuZrodlowego[0];
								break;
							case 1:
									$this->tmpDane="Typ przekazanego pliku -";
									$this->tmpDane2="'".$this->danePlikuZrodlowego[1]."'";
								break;
							case 2:
									$this->tmpDane="Rozszerzenie pliku zrodlowego -";
									$this->tmpDane2="'".$this->danePlikuZrodlowego[2]."'";
								break;
							case 3:
									$this->tmpDane="Wymiary pliku zrodlowego -";
									$this->tmpDane="(".$this->danePlikuZrodlowego[3].",".$this->danePlikuZrodlowego[4].")";
								break;
							case 4:
								break;
							default:
									$this->tmpDane="Nieoblugiwany wskaznik.";
								break;
			endswitch;
		}
		else
		{
			$this->tmpDane="Wystapil blad z plikiem zrodlowym -";
			$this->tmpDane2=" nie jest mozliwe podanie wskazanej wlasciwosci";
		}
		}
		echo $this->tab_style[3].$this->tmpDane.$this->tab_style[2].$this->tmpDane2.$this->tab_style[0].$this->tab_style[0]."</br>";
	}
	// Główna Funkcja pomnijeszObraz testuje typ i rozmiar przekazany jako parametr z danymi z konstruktora
	// Funkcja zwraca status
	public function zmienRozmiarObraz($przekazanaNowaNazwa="",$przekzazanaLokalizacja="",$przekazanaSzerokosc=0,$przekazanaWysokosc=0)// 
	{
		//$arg = func_num_args();
		IF(func_num_args()<4)
		{
				$this->ustawBlad(0,"Nie wystarczajaca ilość argumentów funkcji!");
				$this->ustawBlad(0,$this->tab_style[2]."[WYMAGANE - 4] Nie wystarczajaca ilość argumentów funkcji - ".$this->tab_style[1].func_num_args().$this->tab_style[0].$this->tab_style[0]."</br>");
				$this->wyswietlKomunikatBledu();
		}
		else
		{
			$this->przekazaneDane[0]=$przekazanaNowaNazwa;
			$this->przekazaneDane[1]=$przekzazanaLokalizacja;
			$this->przekazaneDane[2]=$przekazanaSzerokosc;
			$this->przekazaneDane[3]=$przekazanaWysokosc;

			foreach ($this->przekazaneDane as $key => $value)
			{
				IF($key>1) $this->typZmiennej="integer";
				IF(!$this->sprawdzTypArgumentu($key,$this->typZmiennej,$this->przekazaneDane[$key]))
				{
					$this->test=FALSE;
					$this->wyswietlKomunikatBledu();
				}
			};
			$this->typZmiennej="string";
			IF($this->test==TRUE)
			{
				IF(!$this->sprawdzDostep('p',$this->plikZrodlowy[0].$this->plikZrodlowy[1]))
				{
					$this->wyswietlKomunikatBledu();
					$this->test=FALSE;
				}
			}
			IF($this->test==TRUE)
			{
				IF(!$this->sprawdzDostep('k',$this->przekazaneDane[1]))
				{
					$this->wyswietlKomunikatBledu();
					$this->test=FALSE;
				}
			}
			IF($this->test==TRUE)
			{
				$this->wyodrebnijNazwe($this->plikZrodlowy[1]);
				$this->ustawTyp();
				$this->ustawRozszerzenie();
				$this->sprawdzWymiaryPlikuZrodlowego();
				IF($this->ustawWymiarPlikuDocelowego()==1)
				{
					$this->zmienRozmiar();
				}
			}
		};
		return $this->zwrocStatus();
	}
	// Funkcja zwrocStatus zwraca status pracy nad przekaqzanyumi danymi, 0 - dla nieprawidlowych danych, 1 - dla prawidlowych danych
	// Funkcja zwraca status
	function zwrocStatus()
	{
		if($this->test==FALSE) 
		{
			$this->status=0;
			//echo "status = 0 ";
		}
		else
		{
			$this->status=1;
			//echo "status = 1 ";
		};
		return $this->status;
	}
	//---
	private function ustawNazweNowegoPliku($wskaznik,$nazwa,$rozszerzenie="")
	{
		IF($wskaznik==0)
		{
			$this->nazwaNowyPlik=$nazwa;
		}
		else
		{
			$this->nazwaNowyPlik=$nazwa.".".$rozszerzenie;
		}
	}
	
	//---
	public function zwrocNazweNowegoPliku()
	{
		return $this->nazwaNowyPlik;
	}
	public function zwrocWymiarNowegoPliku($wymiar)
	{
		if($this->test==FALSE) 
		{
			//echo "TEST FALSE";
			return 0;
		}
		else
		{
			switch($wymiar):
						case 's': // Argument
							return $this->nowaSzeroksoc;
							break;
						case 'w': // rozmiar
							return $this->nowaWysokosc;
						default:
							return 0;
							break;
			endswitch;
		}
	return 0;
	}
	// Funkcja ustawBlad w zaleznosci od rodzaju przekazanego bledu ustawia odpowiedni komunikat do odpowiedniej zmiennej
	private function ustawBlad($rodzaj,$komunikat)
	{
		switch($rodzaj):
						case 0: // Argument
							$this->komunikatBlad_arg=$komunikat;
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
		return $this->komunikatBlad_arg."</br>".$this->komunikatBlad_r;
	}
	function wyswietlKomunikatBledu()
	{
		echo $this->komunikatBlad_arg."</br>".$this->komunikatBlad_r;
	}
	//----------------------------
	//	danePlikuZrodlowego[3] - szerokosc pliku zrodlowego podanego w konstruktorze
	//	danePlikuZrodlowego[4] - wysokosc pliku zrodlowego podanego w kosntruktorze
	//
	//
	private function ustawWymiarPlikuDocelowego()
	{
	IF( $this->danePlikuZrodlowego[3] > $this->przekazaneDane[2] || $this->danePlikuZrodlowego[4] > $this->przekazaneDane[3] )
	{
			$this->sciezkaWymiar.=$this->tab_style[4]."[0]".$this->tab_style[0]." szerokosc pliku zrodlowego (".$this->danePlikuZrodlowego[3].")> szerokosc pliku docelowego (". $this->przekazaneDane[2] .") || ";
			$this->sciezkaWymiar.="wysokosc pliku zrodlowego  ".$this->danePlikuZrodlowego[4]."> wysokosc pliku docelowego ".$this->przekazaneDane[3]."</br>";
			// liczymy roznice
			$this->tmpRoznicaSzerokosc=$this->danePlikuZrodlowego[3]-$this->przekazaneDane[2];
			$this->tmpRoznicaWysokosc=$this->danePlikuZrodlowego[4]-$this->przekazaneDane[3];
			$this->sciezkaWymiar.=$this->tab_style[4]."[1]".$this->tab_style[0]."szerokosc roznica = $this->tmpRoznicaSzerokosc , wysokosc roznica = $this->tmpRoznicaWysokosc </br>";
			// sprawdzamy gdzie jest wieksza roznica
			IF($this->tmpRoznicaSzerokosc>$this->tmpRoznicaWysokosc || $this->tmpRoznicaSzerokosc===$this->tmpRoznicaWysokosc) // mniejszy badz rowny
			{
				IF($this->tmpRoznicaSzerokosc>$this->tmpRoznicaWysokosc)
				{
					$this->sciezkaWymiar.=$this->tab_style[4]."[2a]".$this->tab_style[0]." liczymy od roznicy szerokosci ($this->tmpRoznicaSzerokosc) </br>";
				}
				else
				{
					$this->sciezkaWymiar.=$this->tab_style[4]."[2]".$this->tab_style[0]."szerokosc roznica ($this->tmpRoznicaSzerokosc)== wysokosc roznica ($this->tmpRoznicaWysokosc) </br>";
				}
				$this->wynikProporcja=$this->przekazaneDane[2]*100/$this->danePlikuZrodlowego[3]; // nie ma znaczenie ktore wartosci bedziemy liczyc
				$this->sciezkaWymiar.=$this->tab_style[4]."[3]".$this->tab_style[0]." proporcja = $this->wynikProporcja </br>";
				// $this->przekazaneDane[2]; szerokosc
				$this->nowaWysokosc=round(($this->danePlikuZrodlowego[4]*$this->wynikProporcja/100),2);
				$this->nowaSzeroksoc=$this->przekazaneDane[2];
				
			}
			else // wiekszy
			{
				$this->sciezkaWymiar.=$this->tab_style[4]."[2b]".$this->tab_style[0]." liczymy od roznicy wysokosci ($this->tmpRoznicaWysokosc) </br>";
				$this->wynikProporcja=$this->przekazaneDane[3]*100/$this->danePlikuZrodlowego[4];
				$this->sciezkaWymiar.=$this->tab_style[4]."[3b]".$this->tab_style[0]." proporcja = $this->wynikProporcja </br>";
				// $this->przekazaneDane[3]; wysokosc porzekazanego pliku
				$this->nowaSzeroksoc=round(($this->danePlikuZrodlowego[3]*$this->wynikProporcja/100),2);
				$this->nowaWysokosc=$this->przekazaneDane[3];
			}
			$this->sciezkaWymiar.=$this->tab_style[4]."[4]".$this->tab_style[0].$this->tab_style[1]." NOWE WYMIARY ".$this->tab_style[0].": szerokosc = ".$this->nowaSzeroksoc." , wysokosc = ".$this->nowaWysokosc." </br>";
			// ustawiamy nazwe zwracanego pliku
			$this->ustawNazweNowegoPliku(1,$this->przekazaneDane[0],$this->danePlikuZrodlowego[2]);
			return $this->wskZmiany=1;
	}
	else
	{
		// plik zrodlowy mniejszy badz rowny plikowi docelowemu, nic nie rob
		$this->sciezkaWymiar.=$this->tab_style[4]."[0a]".$this->tab_style[0]." plik zrodlowy mniejszy od pliku docelowego. Nie wykonuje pomniejszenia</br>";
		$this->nowaSzeroksoc=$this->danePlikuZrodlowego[3];
		$this->nowaWysokosc=$this->danePlikuZrodlowego[4];
		$this->sciezkaWymiar.=$this->tab_style[4]."[4a]".$this->tab_style[0]."WYMIARY : szerokosc = ".$this->nowaSzeroksoc." , wysokosc = ".$this->nowaWysokosc." </br>";
		// ustawiamy nazwe zwracanego pliku
		$this->ustawNazweNowegoPliku(0,$this->plikZrodlowy[1]);
	}	
	}
	// Wyswietla wspolczyninik zmiany 1=TRUE, 0=FALSE
	public function pokazWspolczynikZmiany()
	{
		echo $this->tab_style[3]."Wspołczynnik pomniejszenia - '".$this->tab_style[2].$this->wskZmiany.$this->tab_style[0]."'".$this->tab_style[0]."</br>";
	}
	// Wyswietla sciezka ustawienia nowego wymiaru
	public function pokazSciezkeWykonania ($wskaznik=0)
	{
		switch($wskaznik):
			default:
			case 0:
					echo $this->tab_style[1]."Nie podano wskaznika wyboru sciezki".$this->tab_style[0];
				break;
			case 1:
					echo "$this->sciezkaWymiar";
				break;
			case 2:
					echo "$this->sciezkaZmienRozmiar";
				break;
		endswitch;
	}
	//-----------------------------------
	private function zmienRozmiar()
	{
		switch($this->danePlikuZrodlowego[2])
		{
			case "jpeg":
			case "jpg":
						$img_resize=imagecreatefromjpeg($this->plikZrodlowy[0].$this->plikZrodlowy[1]);
						$this->wskZmiana=TRUE;
					break;
			case "x-ms-bmp":
						$this->wskZmiana=TRUE;
						$img_resize= imagebmp($this->plikZrodlowy[0].$this->plikZrodlowy[1]);
					break;
			case "png":
						$this->wskZmiana=TRUE;
						$img_resize=imagecreatefrompng($this->plikZrodlowy[0].$this->plikZrodlowy[1]);
					break;
			case "gif":
						$this->wskZmiana=TRUE;
						$img_resize=imagecreatefromgif($this->plikZrodlowy[0].$this->plikZrodlowy[1]);
					break;
			case "bmp":
						$this->wskZmiana=TRUE;
						$img_resize= imagebmp($this->plikZrodlowy[0].$this->plikZrodlowy[1]);
					break;																		
			default:
					  $this->wskZmiana=FALSE;
					  $this->test=FALSE;	// USTAWIA test na FALSE
				  break;
		};
		if($this->wskZmiana==TRUE)
		{
			$this->sciezkaZmienRozmiar.=$this->tab_style[4]."[0]".$this->tab_style[0]." Rozszerzenie - ".$this->danePlikuZrodlowego[2]."</br>";
			$newim = imagecreatetruecolor($this->nowaSzeroksoc,$this->nowaWysokosc);
			/*
			bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
			*/
			imagecopyresampled($newim, $img_resize, 0, 0, 0, 0, $this->nowaSzeroksoc, $this->nowaWysokosc, $this->danePlikuZrodlowego[3], $this->danePlikuZrodlowego[4]);
			imagejpeg($newim, $this->przekazaneDane[1].$this->nazwaNowyPlik, 100);
			$this->sciezkaZmienRozmiar.=$this->tab_style[4]."[0]".$this->tab_style[0].$this->tab_style[3]."Nowy plik".$this->tab_style[0]." (".$this->nowaSzeroksoc.",".$this->nowaWysokosc.") - ".$this->nazwaNowyPlik."</br>";
			$this->wskNowyPlik=TRUE;
		} 
		else
		{
			$this->sciezkaZmienRozmiar.=$this->tab_style[4]."[0a]".$this->tab_style[0].$this->tab_style[1]." Rozszerzenie NIE obsługiwane".$this->tab_style[0]." - ".$this->danePlikuZrodlowego[2]."</br>";
			$this->ustawBlad(1,$this->sciezkaZmienRozmiar);
		};
	}
	//-----------------------------------
	// Destruktor klasy
	public function __destruct()
	{
      //echo 'Obiekt klasy [sprawdzImportowanyPlik] został zniszczony.<br/>';
	} // koniec __destruct();
};

?>

