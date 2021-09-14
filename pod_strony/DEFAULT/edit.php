<?php
if(!defined('DEFAULT_2')) { exit('NO PERMISSION - NOT DEFINED'); }

/*
 * RECORD DATA
 */

if (intval($ID)!==0)
{
    echo '<div class="DIV_MAIN">';
    echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Powrót</p></a>';
    $log->log(0,' ID EXSIT => '.$ID);
    $result=mysqli_fetch_assoc ($db->query("SELECT COUNT(*) AS 'c' FROM `".$REK_MODUL['SKROT']."` WHERE WSK_U=0 AND `ID`=".$ID));
    $log->logMulti(0,$result,__FILE__."::".__LINE__);
    if(intval($result['c'])!==1)
    {
        echo '<p class="P_ERR"> Podana pozycja została usunięta!</p>';
        return false;
    }
    $record = mysqli_fetch_assoc($db->query("SELECT ID,TRESC,KOLOR_HEX,KOLOR_NAZWA,KOLOR_ID,ROZMIAR,POZYCJA,POZYCJA_NAZWA,POZYCJA_ID,CSS,DAT_UTW,WSK_V FROM `".$REK_MODUL['SKROT']."` WHERE WSK_U=0 AND ID=".$ID));
    $recordParm=array
            (
                'kolor'=>array
                (
                    'NAZWA' => $record['KOLOR_NAZWA'],
                    'WART' => $record['KOLOR_HEX'],
                    'WSKD' => $record['KOLOR_ID']
                ),
                'pozycja'=>array
                (
                    'NAZWA' => $record['POZYCJA_NAZWA'],
                    'WART' => $record['POZYCJA'],
                    'WSKD' => $record['POZYCJA_ID']
                ),
                'css'=> array
                (
                    'WART' => 'BOLD|ITALIC|UNDERLINE',
                    'WSKD' => $record['CSS']
                )
            );
    $log->logMulti(0,$record,__FILE__."::".__LINE__); 
}
/*
 * MODUL PARAMETERS
 */

require_once(DR.'/class/modulParm.php');
$parm=NEW modulParm();
$parmData=$parm->get($IDM);

/*
 * CSS PARSER
 */
require_once(DR.'/class//parseCSS.php');
$parseC=NEW parseCSS();

$err='';
$add=false;

echo '<p CLASS="P_MAIN_CEL">Edytuj pozycję : </p>';

//$log->logMulti(0,$_POST,__FILE__."::".__LINE__);

if(filter_input(INPUT_POST,"artykul")==='Edytuj')
{ 
    $add=true;
    $log->log(0,' CHECK DATA POST');
    require_once(DR.'/class/parseData.php');
    $parse=NEW parseData();
    $parse->setAvaliableCharacter("/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ\ \\”\\„\+\\-\\-\/\_\,\*\\.\\;\!\\:\&\#\@\(\)\t\r\n]+$/");
    $parse->setMinCharacter(5);
    $parse->setMaxCharacter(4000);
    $err=$parse->checkData(filter_input(INPUT_POST,"dane0"));
    if($parse->errorExist())
    {
        $err=$parse->errorInfo();
        echo "ERR => ".$err."</br>";
        $add=false;
    }
    
}
if ($add===false)
{
    /* CSS POZYCJA DICTIONARY */
    $pozycjaSlo=$db->query("select ID,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=1 order by ID_OPCJ");
    /* CSS COLOR DICTIONARY */
    $kolorSlo=$db->query("select ID,NAZWA,HEX FROM COLOR WHERE WSK_U=0 order by ID");
    /* CSS FONT SIZE DICTIONARY */
    $rozmiarSlo=range(12, 32,2);
    /* CSS FONT */
    $cssSlo= $db->query("select ID,NAZWA,WART FROM CSS WHERE WSK_V=1 AND ID_GROUP=0 ORDER BY ID");
    echo '<form action="" method="POST" ENCTYPE="multipart/form-data" name="form1" id="form1" >';//target="_blank"
	
   
        $cssPozycja=$parseC->createSelectArray(filter_input(INPUT_POST,"pozycja0"),$recordParm['pozycja'],$pozycjaSlo,'pozycja0');
        $cssKolor=$parseC->createColorSelect(filter_input(INPUT_POST,"kolor0"),$recordParm['kolor'],$kolorSlo,'kolor0');
        $cssRozmiar=$parseC->createSelect(filter_input(INPUT_POST,"rozmiar0"),$record['ROZMIAR'],$rozmiarSlo,'rozmiar0');
        $cssText=$parseC->createCheckBox(filter_input_array(INPUT_POST),$recordParm['css'],$cssSlo,'0');
        
        echo '<p class="NG_DANE">';
        echo 'Zawatość <span class="S_NG_DANE">*</span>: ';
        echo $err;       
        echo '</p>';
        echo '<div class="DIV_DODAJ">';
	echo '<textarea name="dane0" rows="20" cols="85" class="TEXTAREA" style="color:#000000;;">';
	if (filter_input(INPUT_POST,"dane0")!='')
        {     
            echo filter_input(INPUT_POST,"dane0");
        }
        else if($record['TRESC']!=='')
        {
            echo htmlspecialchars_decode(strip_tags($record['TRESC']));
        }
        else
        {
            // EMPTY VALUE
        }
	echo '</textarea>';
	echo '<table><tr><td class="TD_L">';
	echo '<p CLASS="P_CSS_NG_L">Kolor tekstu : '.$cssKolor.'</p>';
	echo '<p CLASS="P_CSS_NG_L">Pozycja tekstu : '.$cssPozycja.'</p>';
	echo '<p CLASS="P_CSS_NG_L">Rozmiar czcionki : '.$cssRozmiar.'</p>';
	echo '</td><td class="TD_R">';
	echo '<p CLASS="P_CSS_NG_R">Wskaż opcję formatowania :</p>'.$cssText;
	echo '</td></tr></table>';	
	echo '</div>';

    echo '<DIV style="width:800px; height:30px; ">';
    echo '<input type="hidden" value="'.$ID.'" name="ID">';
    echo '<input type="hidden" value="'.$IDS.'" name="IDS">';
    echo '<input type="hidden" value="1" name="CSS-DEFAULT">';
    echo '<input class="button" type="submit" value="Edytuj" name="artykul">';
    echo '<input class="button" type="submit" value="Podgląd" name="podglad_artykul">';					
    echo '</FORM>';
    echo '</DIV>';
    //---------------------------------------------------------------LEGENDA----------------------------
    echo '<DIV style="width:800px; ">'; // DIV LEGEND DODAJ 	border: 1px solid green;
    $tab_legenda=array(
			"pola z GWIAZDKĄ (<span class=\"S_LEG_INFO\">*</span>) wymagane;",
			"Zawartość pozycji musi zawierać min (<span class=\"S_LEG_INFO\">5</span>) znaków;",
			"Zawartość pozycji może zawierać max (<span class=\"S_LEG_INFO\">4000</span>) znaków;"
    );
    echo '<p class="P_LEG">Legenda :</p>';
    echo "<ul class=\"UL_LEG\">";
    foreach ($tab_legenda as $value){
        echo "<li class=\"LI_LEG\">$value</li>";
    }
    echo "</ul>";
    echo '</DIV>';
    //---------------------------------------------------------------KONIEC-LEGENDA----------------------------

} 
else if ($add===true)
{
    echo '<p class="P_MAIN">Twoja pozycja została edytowana</p>';
    $log->logMulti(0,filter_input_array(INPUT_POST),__METHOD__);
    $d=array();
    $CSS=array();
    	
        $d['dane0']=nl2br(htmlspecialchars(filter_input(INPUT_POST,"dane0")),true);
	$d['kolor0']=explode('|',filter_input(INPUT_POST,"kolor0"));
	$d['pozycja0']=explode('|',filter_input(INPUT_POST,"pozycja0"));
        $d['rozmiar0']=filter_input(INPUT_POST,"rozmiar0");
        /*
         * PARSE CSS
         */
        for($c=1;$c<4;$c++)
        {
            $log->log(0,"CSS[".$c."] => ".filter_input(INPUT_POST,"CSS|0|".$c));
            if(trim(filter_input(INPUT_POST,"CSS|0|".$c))==='')
            {
                array_push($CSS,'0');
            }
            else
            {
                array_push($CSS,'1');
            }
        }
        $d['CSS']=implode('|',$CSS);
        $db->query("UPDATE `".$REK_MODUL['SKROT']."` SET "
                . "`TRESC` = '".$d['dane0']."', "
                . "`KOLOR_HEX` = '".$d['kolor0'][2]."', "
                . "`ROZMIAR` = '".$d['rozmiar0']."', "
                . "`KOLOR_NAZWA` = '".$d['kolor0'][1]."', "
                . "`KOLOR_ID` = '".$d['kolor0'][0]."', "
                . "`POZYCJA` = '".$d['pozycja0'][2]."', "
                . "`POZYCJA_ID` = '".$d['pozycja0'][0]."', "
                . "`POZYCJA_NAZWA` = '".$d['pozycja0'][1]."', "
                . "`CSS` = '".$d['CSS']."', "
                . "`DAT_KOR` = NOW(), "
                . "`WSK_K` = `WSK_K`+1, "
                . "`WSK_V` = '".$parmData['WID_PO_EDYCJI_'.$REK_MODUL['SKROT']]['WART']."', "
                . "`UID` = '".$_SESSION['uid']."', "
                . "`ID_PERS` = '".$_SESSION['id_user']."' "
                . "WHERE `ID`=".filter_input(INPUT_POST,"ID"));
               
        $db->insDbLog($IDM,"EDYTOWANO POZYCJĘ - dodano ID : ".filter_input(INPUT_POST,"ID"));
    
    echo '<a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p style="text-align:center; font-size:20px; margin:0px;">Powrót do MENU</p></a>';								
}
else
{
    // NO
}
echo "</div>";