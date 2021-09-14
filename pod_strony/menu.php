<?php 
session_start();
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
define('RA',filter_input(INPUT_SERVER,"REMOTE_ADDR"));

require(DR.'/.cfg/konfiguracja.php');
require(DR.'/class/logToFile.php');
require(DR.'/class/session.php');
require(DR."/class/checkGlobalVar.php");

$log=NEW logToFile();
$session=new session();
$checkVar=NEW checkGlobalVar();

$checkVar->checkOnlyGet($IDM,'IDM');
$checkVar->checkOnlyGet($IDW,'IDW');

if(!$session->checkSession($IDM))
{    
    $log->log(0,"[".__FILE__."] SESSION NOT EXIST => REDIRECT TO LOGIN PAGE");
    header('Location: '.APP_URL.'/logowanie.php?URL=pod_strony/menu.php&IDM='.$IDM."&IDW=".$IDW);
}
define('TITLE',"JUDO - MENU CMS");

$CSS="menu_modul.css";

require(DR.'/class/database.php');
require(DR.'/view/v_head.php');
require(DR.'/class/userPerm.php');

$db= NEW database();
$db->connect(dbUser,dbPass,database,dbHost);

$userPermissions=new userPerm($db->getDbLink());	
$TAB_PRAW=$userPermissions->getUserPerm($IDM);

$db->insDbLog($IDM,'Zalogowano do modułu');

$REK_MODUL=mysqli_fetch_row($db->query("select `ID`,`SKROT`,`NAZWA` FROM `MODUL` WHERE `ID`='".$IDM."' LIMIT 1"));
$iframe="DEFAULT/iframe";

?>
<!-----------------------------------------------------------KONIEC-SESJA------------------------------------------------------------------------------------>
<body onload="setHeightFrame()">

<center>
<table class="T_GLOWNA" ><tr><td class="T_NAGLOWEK" colspan="2" >
<?php
$wiersz_opcji="";
$kolumna_opcji="";
foreach($TAB_PRAW as $klucz=>$wartosc)
{
    $REK_POZ=mysqli_fetch_row($db->query("SELECT f.IDW,f.NAZWA,f.MAIN,f.OPIS FROM FUNKCJA f, MODUL m WHERE m.ID=".$IDM." AND f.ID_MODUL=m.ID AND f.WSK_V=1 AND f.WSK_U=0 AND f.IDW='".$wartosc['IDX']."' AND f.IDW>-1 ORDER BY f.IDW"));
    if($REK_POZ[2]=='n')
    {
        if($wartosc['VAL']==1)
	{
            if(intval($IDM)===16 || intval($IDM)===17)
            {
                $iframe="DEFAULT/iframe";
            }
            else
            {
                $iframe=$REK_MODUL[1].'/cel_'.$REK_MODUL[1];
            }
            $wiersz_opcji.='<li><a href="'.$iframe.'.php?IDW='.$REK_POZ[0].'&IDM='.$IDM.'&IDB=0" target="cel">'.$REK_POZ[3].'</a></li>';
	}
	else
	{
            $wiersz_opcji.='<li><button class="BUT_OFF">'.$REK_POZ[3].'</button></li>';
	}
    }
    else if ($REK_POZ[2]=='y')
    {
        if(intval($IDM)===16 || intval($IDM)===17)
            {
                $iframe="DEFAULT/iframe";
            }
            else
            {
                $iframe=$REK_MODUL[1].'/cel_'.$REK_MODUL[1];
            }
	if($wartosc['VAL']==1)
	{
            $kolumna_opcji.='<li><a href="'.$iframe.'.php?IDW='.$REK_POZ[0].'&IDM='.$IDM.'&IDB=0" target="cel">'.$REK_POZ[1].'</a></li>'; 
	} 
	else
	{
            $kolumna_opcji.='<li><button class="BUT_OFF">'.$REK_POZ[1].'</button></li>';
	}
    }
    else {}
}
echo '<span style="float:left;margin:0px"><a href="'.APP_URL.'/menu_cms.php" style="text-decoration: none;">Menu - CMS</a></span><span style="float:right;margin:0px"><a href="../logout.php" style="text-decoration: none;">Wyloguj</a></span></br>';
echo '<p class="P_TYTUL">MENU - '.$REK_MODUL[2].'</p>';
echo '<p class="P_INFO">';
if($_SESSION['id_user']==1)
{
	echo 'ID USER - <span class="SPAN_INFO">'.$_SESSION['id_user'].'</span></br>';
	echo 'ID MOD - <span class="SPAN_INFO">'.$IDM.'</span></br>';
}	
echo 'Użytkownik : <span class="SPAN_INFO">'.$_SESSION['login'].'</span><span style="float:right;">Sesja wygasa o : <span class="SPAN_INFO">'.date('Y-m-d H:i:s', $_SESSION['expire']) .'</span></span></p>';
?>
</td><td style="empty-cells: hide"></td></tr>
<tr style="height:35px;"><td class="MENU" colspan="2">
<?php
/*
	WIERSZ OPCJI
*/
if ($TAB_PRAW['Dostęp']['VAL']==1)
{
    echo '<ul class="GLOWNE">'.$wiersz_opcji.'</ul>';
}
else {}
?>
</td><td style="empty-cells: hide"></td></tr>
</tr>
<tr><td class="T_NAVI" valign="TOP">	
<?php
/*
	KOLUMNA OPCJI
*/
if	($TAB_PRAW['Dostęp']['VAL']==1)
{
					echo '<ul>'.$kolumna_opcji.'</ul>';
}
else {}
?>
</td><td valign="TOP" style="background:white;">
<?php
/*
	iFRAME
*/
if ($TAB_PRAW['Dostęp']['VAL']==1)
{
    if(intval($IDM)===16 || intval($IDM)===17)
    {
        $log->log(0,' LOAD DEFAULT iFRAME, IDM => '.$IDM);
        $iframe="DEFAULT/iframe";
    }
    else
    {
        $iframe=$REK_MODUL[1].'/cel_'.$REK_MODUL[1];
    }
    echo '<iframe name="cel" src="'.$iframe.'.php?IDW='.$IDW.'&IDM='.$IDM.'&IDB=0" class="FRAME" id="M_FRAME"></iframe>';
} 
else
{
	echo '<div class="DIV_ERR"><p class="P_ERR">Nie masz uprawnień, aby korzystać z tej części serwisu !</p></div>';
}
?>
</td></tr>
</table>
</center>
</body>
</html>