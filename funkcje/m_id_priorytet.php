<?php
//--------------------------------------------------------SQL-INSERT-DZIENNIK--------------------------------------------------------------------
//INS_DZIENNIK($_SESSION["id_user"],$_GET["IDM"],"Uruchomiono funkcję - m_id_priorytet [".$_GET["ID"]."]");
//--------------------------------------------------------KONIEC-INSERT-DZIENNIK-----------------------------------------------------------------
// $REK_MODUL[0] -- SKROT
// $REK_MODUL[1] -- TABELA

IF($_SESSION["id_user"]==1)
{
	echo "Priotytet ZMIANA NA IDPZ - ".$_GET["IDPZ"].".</br>";
	echo "Priotytet AKTUALNY IDP - ".$_GET["IDP"].".</br>";
	echo "Priotytet AKTUALNY ID - ".$_GET["ID"].".</br>";
};
// SELECT ID PIERWOTNY

$SEL_DANE = $db->query("select `ID`,`ID_P` FROM `ZWDK` WHERE `ID`='".$_GET['ID']."'");
$SEL_REK = mysqli_fetch_array($SEL_DANE);
$ID_PIERWOTNY=$SEL_REK[0];

// SELECT ID ZMIANA
$SEL_DANE1 = $db->query("select `ID`,`ID_P` FROM ZWDK WHERE `ID_P`='".$_GET['IDPZ']."'");
$SEL_REK1 = mysqli_fetch_array($SEL_DANE1);
$ID_ZMIANA=$SEL_REK1[0];


IF($_GET["IDP"]==$_GET["IDPZ"])
{
	// WARTOSCI ROWNE
	// NIE jEST MOZLIWE ICH OSIAGNIECIE ZE WZGLEDU NA FORMUALRZ
}
ELSE IF($_GET["IDP"]<$_GET["IDPZ"])
{
	// IDP ZMIANY WIEKSZY OD IDP PIERWOTNY
	// USTALENIE NIZSZEGO PRIORYTETU
	$db->query("UPDATE `ZWDK` SET `ID_P`='$_GET[IDPZ]' WHERE `WSK_U`=0 AND `ID`=$ID_PIERWOTNY"); // UPDATE PO ID
	$db->query("UPDATE `ZWDK` SET `ID_P`=`ID_P`-1 WHERE `WSK_U`=0 AND `ID_P`>='".$_GET['IDP']."' AND `ID_P`<='".$_GET['IDPZ']."' AND `ID`!='$ID_PIERWOTNY'");
	$ZMIANA=TRUE;
}
ELSE
{
	// IDP ZMIANY MNIEJSZY OD IDP PIERWOTNEGO
	// USTALENIE WYZSZEGO PRIORYTETU
	$db->query("UPDATE `ZWDK` SET `ID_P`='".$_GET['IDPZ']."' WHERE `WSK_U`=0 AND `ID`='$ID_PIERWOTNY'"); // UPDATE PO ID
	$db->query("UPDATE `ZWDK` SET `ID_P`=`ID_P`+1 WHERE `WSK_U`=0 AND `ID_P`>='".$_GET['IDPZ']."' AND `ID_P`<'".$_GET['IDP']."'+1 AND `ID`!='$ID_PIERWOTNY'");
	$ZMIANA=TRUE;
}
