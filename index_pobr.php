<?php
$download=TRUE;
if(isset($_GET['dok']) && isset($_GET['typ']))
{
	if($_GET['dok']=='deklaracja')
	{
		
		if($_GET['typ']=='docx')
		{
			$file = '/PLIKI/Deklaracja_czlonkowska.docx';
			$contentType='application/vnd.openxmlformats-officedocument.wordprocessingml.document';
		}
		else if($_GET['typ']=='pdf')
		{
			$file = '/PLIKI/Deklaracja_czlonkowska.pdf';
			$contentType='application/pdf';
		}
		else
		{
			echo "Niepoprawny typ pliku<br/>";
			$download=FALSE;
		}
	}
	else if($_GET['dok']=='Dane_osobowe')
	{
		if($_GET['typ']=='docx')
		{
			$file = '/PLIKI/Dane_osobowe_dziecka.docx';
			$contentType='application/vnd.openxmlformats-officedocument.wordprocessingml.document';
		}
		else if($_GET['typ']=='pdf')
		{
			$file = '/PLIKI/Dane_osobowe_dziecka.pdf';
			$contentType='application/pdf';
		}
		else
		{
			echo "Niepoprawny typ pliku<br/>";
			$download=FALSE;
		}
	}
	else
	{
		echo "Niepoprawny plik<br/>";
		$download=FALSE;
	};


	if (file_exists($file) && $download==TRUE)
	{
		header('Content-Description: File Transfer');
		header('Content-Type: '.$contentType."'");
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		exit;
	}

	echo "<p>Dane_osobowe_dziecka.docx</p>";
}
else
{
	echo "not isset";
	// BRAK UZUPELNIONYCH DANYCH
}
