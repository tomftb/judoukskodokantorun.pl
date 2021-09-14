<?php
if(!defined('DEFAULT_1')) { exit('NO PERMISSION'); }
echo '<div class="DIV_MAIN">';
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'"><p class="P_HREF_BACK">Powrót</p></a>';
echo '<p CLASS="P_MAIN_CEL">Dodaj pozycję : </p>';

require_once(DR.'/class/modulParm.php');
$parm=NEW modulParm();
$parmData=$parm->get($IDM);

$log->logMulti(0,$parmData,__METHOD__."::".__LINE__);
/*
echo "<pre>";
print_r($parmData);
echo "</pre>";
*/
require_once(DR.'/class/parseCSS.php');
$parseC=NEW parseCSS();

$iField=intval($parmData['ILE_INPUT_TRESC_'.$REK_MODUL['SKROT']]['WART']);

$err=array();

$add=false;

if(filter_input(INPUT_POST,"artykul")=='Dodaj')
{ 
    $add=true;
    $log->log(0,' CHECK DATA POST');
    require_once(DR.'/class/parseData.php');
    $parse=NEW parseData();
    $parse->setAvaliableCharacter("/^[A-Za-z0-9ąĄćĆęĘłŁńŃśŚżŻźŹóÓ\ \\”\\„\+\\-\\-\/\_\,\*\\.\\;\!\\:\&\#\@\(\)\t\r\n]+$/");
    $parse->setMinCharacter(5);
    $parse->setMaxCharacter(4000);
    for ($tr=0;$tr<$iField;$tr++)
    {
        $err['dane'.$tr]=$parse->checkData(filter_input(INPUT_POST,"dane".$tr));
        if($parse->errorExist())
        {
            $err['dane'.$tr]=$parse->errorInfo();
            $add=false;
        }
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
	
    for ($tr=0;$tr<$iField;$tr++)
    {
        $cssPozycja=$parseC->createSelectArray(filter_input(INPUT_POST,"pozycja".$tr),$parmData['POZ_TEXT_POZYCJA_'.$REK_MODUL['SKROT']],$pozycjaSlo,'pozycja'.$tr);
        $cssKolor=$parseC->createColorSelect(filter_input(INPUT_POST,"kolor".$tr),$parmData['KOL_TXT_POZYCJA_'.$REK_MODUL['SKROT']],$kolorSlo,'kolor'.$tr);
        $cssRozmiar=$parseC->createSelect(filter_input(INPUT_POST,"rozmiar".$tr),$parmData['ROZ_TEXT_POZYCJA_'.$REK_MODUL['SKROT']]['WART'],$rozmiarSlo,'rozmiar'.$tr);
        $cssText=$parseC->createCheckBox(filter_input_array(INPUT_POST),$parmData['STYL_TEXT_POZYCJA_'.$REK_MODUL['SKROT']],$cssSlo,$tr);
        
        echo '<p class="NG_DANE">';
        echo 'Zawatość <span class="S_NG_DANE">*</span>: ';
        if(array_key_exists('dane'.$tr,$err))
        {
            echo $err['dane'.$tr];
        }
        echo '</p>';
        echo '<div class="DIV_DODAJ">';
	echo '<textarea name="dane'.$tr.'" rows="20" cols="85" class="TEXTAREA" style="color:#000000;;">';
	if (filter_input(INPUT_POST,"dane".$tr)!=='') { echo filter_input(INPUT_POST,"dane".$tr); }
	echo '</textarea>';
	echo '<table><tr><td class="TD_L">';
	echo '<p CLASS="P_CSS_NG_L">Kolor tekstu : '.$cssKolor.'</p>';
	echo '<p CLASS="P_CSS_NG_L">Pozycja tekstu : '.$cssPozycja.'</p>';
	echo '<p CLASS="P_CSS_NG_L">Rozmiar czcionki : '.$cssRozmiar.'</p>';
	echo '</td><td class="TD_R">';
	echo '<p CLASS="P_CSS_NG_R">Wskaż opcję formatowania :</p>'.$cssText;
	echo '</td></tr></table>';	
	echo '</div>';
    } /* END FOR LOOP */
    echo '<DIV style="width:800px; height:30px; ">';
    echo '<input type="hidden" value="1" name="CSS-DEFAULT">';
    echo '<input class="button" type="submit" value="Dodaj" name="artykul">';
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
    echo '<p class="P_MAIN">Twoja pozycja została dodana</p>';
    $log->logMulti(0,filter_input_array(INPUT_POST),__METHOD__);
    $d=array();
    $CSS=array();
    for ($i=0;$i<$iField;$i++)
    {	
        $d['dane'.$i]=nl2br(htmlspecialchars(filter_input(INPUT_POST,"dane".$i)),true);
	$d['kolor'.$i]=explode('|',filter_input(INPUT_POST,"kolor".$i));
	$d['pozycja'.$i]=explode('|',filter_input(INPUT_POST,"pozycja".$i));
        $d['rozmiar'.$i]=filter_input(INPUT_POST,"rozmiar".$i);
        /*
         * PARSE CSS
         */
        for($c=1;$c<4;$c++)
        {
            $log->log(0,"CSS[".$c."] => ".filter_input(INPUT_POST,"CSS|".$i."|".$c));
            if(trim(filter_input(INPUT_POST,"CSS|".$i."|".$c))==='')
            {
                array_push($CSS,'0');
            }
            else
            {
                array_push($CSS,'1');
            }
        }
        $d['CSS']=implode('|',$CSS);
        $CSS=[];
      
        $db->query("INSERT INTO `".$REK_MODUL['SKROT']."` (`TRESC`,`KOLOR_HEX`,`ROZMIAR`,`KOLOR_NAZWA`,`KOLOR_ID`,`POZYCJA`,`POZYCJA_ID`,`POZYCJA_NAZWA`,`CSS`,`DAT_UTW`,`WSK_V`,`UID`,`ID_PERS`,`VER`)VALUES 
                    (
                    '".$d['dane'.$i]."','".$d['kolor'.$i][2]."','".$d['rozmiar'.$i]."','".$d['kolor'.$i][1]."','".$d['kolor'.$i][0]."','".$d['pozycja'.$i][2]."','".$d['pozycja'.$i][0]."','".$d['pozycja'.$i][1]."','".$d['CSS']."',NOW(),'".$parmData['WID_PO_DODANIU_'.$REK_MODUL['SKROT']]['WART']."','".$_SESSION['uid']."','".$_SESSION['id_user']."',3)");      
        $db->insDbLog($IDM,"DODAJ POZYCJĘ - dodano ID : ".$db->last());
    }
    echo '<a href="'.PAGE_URL.'&IDW=0"><p style="text-align:center; font-size:20px; margin:0px;">Powrót do MENU</p></a>';								
}																		
echo "</div>";
