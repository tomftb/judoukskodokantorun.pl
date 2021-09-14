<?php if(!defined('OBOZ_51')) { exit('NO PERMISSION'); } ?>
<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
$_googleurl='';
$_opis='';
$_img='';
$_submit_name='Dodaj';
//$SR=filter_input(INPUT_SERVER,'HTTP_HOST'); 
//if(function_exists('resize_image')) { include($DR."/pod_strony/_funkcje_/resize_image_new_v3.php");}
$SR=$_SERVER['HTTP_HOST'];
//$DR=filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
$DR=$_SERVER['DOCUMENT_ROOT'];
//echo $DR;
$IDE=0;
$_dir_db=0;
if(isset($_GET['IDE']))
{   
    $IDE=filter_input(INPUT_GET,'IDE',FILTER_VALIDATE_INT);
    $_submit_name='Edytuj';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<link rel="stylesheet" href="HTTP://<?php echo $SR;?>/css/cel_oboz.css" type="text/css">
<link rel="shortcut icon" href="HTTP://<?php echo $SR;?>/images/favicon.ico" type="image/x-icon">
<link rel="icon" href="HTTP://<?php echo $SR;?>/images/favicon.ico" type="image/x-icon">
</head> 
<?php } ?>
<script>
function checkFields()
{
    console.log('---checkFields()---');
    console.log(document.getElementById('fileInput'));
    console.log(document.getElementById('opis'));
    console.log(document.getElementById('url'));
    
    checkFile(document.getElementById('fileInput'),'IMAGE','8388608‬','err_img');
    parseFieldValue(document.getElementById('opis').value,'opis','err_text');
    parseFieldValue(document.getElementById('url').value,'url','err_url');
    checkIsError('submit_form');
}
function checkFieldsEditMode()
{
    parseFieldValue(document.getElementById('opis').value,'opis','err_text');
    parseFieldValue(document.getElementById('url').value,'url','err_url');
    checkIsError('submit_form');
}
function checkInput(input)
{
    console.log('===checkInput()===');
    console.log(input.type);
    if(!input.hasAttribute("id"))
    {
        alert('NO ID ATTRIBUITE');
        return false;
    }
    console.log(input.getAttribute('id'));
    if(input.type==='file')
    {
        checkFile(input,'IMAGE','8388608‬','err_img');
        checkIsError('submit_form');
    }
    else if(input.type==='text' || input.type==='textarea')
    {
        checkTextValue(input);
    }
    else
    {
        // not avaliable
    }
}
function checkIsError(submitId)
{
    console.log('===checkIsError()===');
    var submit=document.getElementById(submitId);
    if(checkIsErr())
    {
        console.log('ERR EXISTS');
        if(!submit.hasAttribute("disabled"))
        {
            submit.setAttribute("disabled", "");
        }
    }
    else
    {
        // check is attribute exist
        console.log('ERR NOT EXISTS');
        if(submit.hasAttribute("disabled"))
        {
            console.log('REMOVE DISABLED ATTRIBUTE ');
            submit.removeAttribute("disabled");
        }  
    }
    console.log(submit);
}
function checkTextValue(input)
{
    if(input.getAttribute('id')==='opis')
    {
            parseFieldValue(input.value,'opis','err_text');
            checkIsError('submit_form');
        }
        else if(input.getAttribute('id')==='url')
        {
            parseFieldValue(input.value,'url','err_url');
            checkIsError('submit_form');
        }
        else
        {
            //not avaliable
        }
}
</script>
<?php

if(file_exists($DR."/pod_strony/_include_/pobr_parm.php")) include($DR."/pod_strony/_include_/pobr_parm.php"); else echo "Nie można załadować potrzebnego pliku - pobr_parm.php . Skontaktuj się z Adminstratorem!";
if(file_exists($DR."/js/parseFieldValue.js")) { echo '<script type="text/javascript" src="HTTP://'.$_SERVER['HTTP_HOST'].'/js/parseFieldValue.js"></script>'; } else echo "Nie można załadować potrzebnego pliku - parseFieldValue.js . Skontaktuj się z Adminstratorem!";

if($IDE>0)
{ 
    // GET DATA FROM 
    
    //echo $IDE;
    mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
    $ZAP_SQL="select ID,NAZWA_I,OPIS,KATALOG,WSK_U,WIDTH,HEIGHT,URL FROM OBOZ WHERE ID=".$IDE." ";
    $SEL_DATA = mysql_query($ZAP_SQL);
    $RESULT=mysql_fetch_row($SEL_DATA);
    //var_dump($RESULT);
    $_googleurl=$RESULT[7];
    $_opis=$RESULT[2];
    $_dir_db=$RESULT[3];
    $_img='<img src="HTTP://'.$SR.'/zdjecia/obozy/'.$RESULT[3].'/'.$RESULT[1].'" width="'.$RESULT[5].'px" height="'.$RESULT[6].'px"/>';
}

// $max_width[$tr]
// $max_height[$tr]
// DEFAULT VALUES
$err_url='';
$err_img='';
$err_text='';
$err_overall='';
$star='<span class="S_LEG_INFO">*</span>';

$tab_legenda=array(
                    "pola z GWIAZDKĄ (".$star.") wymagane;",
                    "ZDJĘCIE, dozwolony TYP : (<span class=\"S_LEG_INFO\">JPG JPEG PNG BMP GIF</span>);",
                    "ZDJĘCIE, MAX Rozmiar = <span class=\"S_LEG_INFO\">8 MB</span>;",
                    "OPIS musi zawierać min (<span class=\"S_LEG_INFO\">3</span>) znaków;",
                    "OPIS może zawierać max (<span class=\"S_LEG_INFO\">1000</span>) znaków;",
                    "URL musi zawierać min (<span class=\"S_LEG_INFO\">12</span>) znaków;",
                    "URL może zawierać max (<span class=\"S_LEG_INFO\">100</span>) znaków;",
                    "URL nie może zawierać <span class=\"S_LEG_INFO\">polskich</span> znaków;"
    );
function createDir($dir)
{
    $i=1;
    $new_kat=FALSE;
    // test dir
    if ($handle = opendir($dir))
    {
        while ((false !== ($entry = readdir($handle))) && ($new_kat==FALSE))
        {
            if (!file_exists($dir.$i))
            {
                $new_kat=TRUE;
                if ($_SESSION['id_user']==1)
                {
                    echo "<p class=\"P_INFO\">NIE istnieje katalog : <span class=\"S_INFO\">".$dir.$i."</span></p>";
                    echo "<p class=\"P_INFO\">NEW KAT : <span class=\"S_INFO\">$new_kat</span></p>";
                }
            }
            else
            {
                $i++;
            }
        }
        if(mkdir($dir.$i, 0766))
        {
            if ($_SESSION['id_user']==1)
            {
                echo "<p class=\"P_INFO\">Utworzono katalog : <span class=\"S_INFO\">'.$dir.$i.'</span></p>";
            }
        }
        else
        {
            $err_overall.='Nie można utworzyć nowego katalogu. Skontaktuj się z Administratorem!';
            return 0;
        }
        closedir($handle);
    }
    else
    {
        $err_overall.='Błąd tworzenia uchwytu do katalogu. Skontaktuj się z Administratorem!';
        return 0;
    }
    return $i;
}
if(isset($_POST["TYP_DODAJ"]))
{
    $tabImg=array('image/gif','image/jpeg','image/jpg','image/png','image/bmp');
    $opis_exp = "/^^[a-zA-ZąĄćĆęĘłŁńŃśŚżŻźŹóÓ\\d][a-zA-ZąĄćĆęĘłŁńŃśŚżŻźŹóÓ\\d\\s\\-\\_]{2,999}$/";
    $url_exp = "/^(http\:\/\/|https\:\/\/)[a-z\d\/\.\-\/\#\&]{5,}$/i";
    $test=array(
        'opis'=>false,
        'url'=>false,
        'obraz'=>false,
        'obraz_edit'=>false,
        'obraz_new'=>false
    );

    $_opis=trim(filter_input(INPUT_POST,'opis'));
    $_googleurl=trim(filter_input(INPUT_POST,'googleurl'));
    if($_opis!='' && $_googleurl!='')
    {
        echo 'opis and googlerul not empty<br/>';
        if(!preg_match($opis_exp,$_opis))
        {
            $err_overall.='W opisie występuje niedozwolone symbole!<br/>';
        }
        else
        {
            $test['opis']=true;
        }
        if(!preg_match($url_exp,$_googleurl))
        {
            $err_overall.='W adresie url google drive występują niedozwolone symbole!<br/>';
        }
        else {
            $test['url']=true;
        }
        // parse
    }
    else
    {    
        $err_overall.='Nie wprowadzono opisu albo adresu url googlr drive!<br/>';
    }
    if (is_uploaded_file($_FILES["obraz"]["tmp_name"]))
    {
        echo 'file not empty<br/>';  
        $test['obraz_new']=true;
        if ($_FILES["obraz"]["size"] < 8388609)
        {
            echo 'file size ok<br/>'; 
            $_FILES["obraz"]["type"]=strtolower($_FILES["obraz"]["type"]);
            if (in_array($_FILES["obraz"]["type"],$tabImg))
            {
                echo 'file is image<br/>'; 
                $test['obraz']=true;
                $test['obraz_edit']=true;
                // upload
            }
            else
            {
                 $err_overall.='Plik nie jest obrazem!<br/>';
            }
        }
        else
        {
            $err_overall.='Za zaduż rozmiar pliku!<br/>';
        }
    }
    else
    {
        if($IDE>0)
        {
            $test['obraz']=true;
        }
        else
        {
            $err_overall.='Nie wskazano pliku!<br/>';
        }   
    }
    ECHO $test['obraz']."<br/>".$test['opis']."<br/>".$test['url']."<br/>";
    if($test['obraz']==true && $test['opis']==true && $test['url']==true)
    {
        $file_status=true;
        // upload and add to database
        // redirect
        $katalog=$DR.'/zdjecia/obozy/';
        if($IDE>0)
        {
            $nr_katalog=$_dir_db;
        }
        else
        {
            $nr_katalog=createDir($katalog);
        }
        
        if($nr_katalog==0)
        {
            exit(0);
        }
        $katalog=$katalog.$nr_katalog;
        $ext=strtolower(substr(strrchr($_FILES["obraz"]["type"], '/'), 1));
        //$filename="org_0_oboz.".$ext;
        $filename=uniqid('org_')."_oboz.".$ext;
        if($IDE==0 || $test['obraz_edit']==true)
        {
            if(!move_uploaded_file($_FILES["obraz"]["tmp_name"],$katalog."/".$filename ))
            {
                $file_status=false;
                $err_overall.='Nie udało się przenieść pliku. Skontaktuj się z Administratorem!';

            }
            else
            {
                // RESIZE
                list($width, $height) = getimagesize($katalog.'/'.$filename);
                if ($width> $max_width[1] || $height>$max_height[1] || ($ext_old=="bmp"))
                {
                    if(!function_exists('resize_image')) { include($DR."/pod_strony/_funkcje_/resize_image_new_v3.php");}
                    
                    $wynik_img_size = resize_image($max_width[1],$max_height[1],$ext,uniqid('min_')."_oboz.".$ext,$katalog.'/',$filename);
                    $new_width=round($wynik_img_size[0]);
                    $new_height=round($wynik_img_size[1]);
                    $new_image_name=$wynik_img_size[2];
                }
                else
                {
                    $new_image_name=$filename;
                    $new_height=round($height);
                    $new_width=round($width);
                }
            }
        }
        
        
        if($file_status)
        {
            // INSERT into DB
            if($IDE==0)
            {
                $INS_OB="INSERT INTO OBOZ (NAZWA_O,NAZWA_I,OPIS,KATALOG,ID_PERS,DAT_UTW,WIDTH,HEIGHT,VER,TYP,URL) VALUES ('$filename','$new_image_name','$_opis','$nr_katalog','$_SESSION[id_user]',NOW(),'$new_width','$new_height',2,'d','$_googleurl')";
            }
            else
            {
                // UPDATE
                if($test['obraz_new']==true)
                {
                    //with obraz
                    $INS_OB="UPDATE OBOZ SET NAZWA_O='$filename',NAZWA_I='$new_image_name',OPIS='$_opis',WIDTH='$new_width',HEIGHT='$new_height',URL='$_googleurl',ID_PERS='$_SESSION[id_user]',WSK_K=WSK_K+1,DAT_K=NOW() WHERE ID='$IDE'";
                }
                else
                {
                     $INS_OB="UPDATE OBOZ SET OPIS='$_opis',URL='$_googleurl',ID_PERS='$_SESSION[id_user]',WSK_K=WSK_K+1,DAT_K=NOW() WHERE ID='$IDE'";
                }
               
            }
            mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
            if(!mysql_query($INS_OB))
            {
                $err_overall.='Nie udało się dodać rekordu do bazy. '.mysql_error().' Skontaktuj się z Administratorem!';
                // or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL -  <span class=\"S_SQL\">INS_OB : ".mysql_error()."</span></p>");
            }	
            else
            {
                echo '<script>function init(){ setTimeout(\'document.location="cel_OBOZ.php?IDW=9&IDM='.$_GET["IDM"].'"\', 500);}window.onload=init;</script>';
            }
        }
    }
}        
?>
<div class="DIV_MAIN">
<center>
<p class="P_HREF_BACK"><a class="A_BACK" href="cel_OBOZ.php?IDW=0&IDM=5">Anuluj</a></p>
<p class="P_MAIN"><?=$_submit_name?> Obóz [GOOGLE DRIVE] : </p>
<form action="" method="POST" ENCTYPE="multipart/form-data" >
    <?php echo $_img; ?>
    <p class="P_INPUT" style="font-weight:bold;font-size:20px;"> <!-- onchange="checkFile(this,'IMAGE','8388608‬','err_img')" -->
    Wskaż zdjęcie<?=$star?>:<br/><input type="file" id="fileInput" onchange="checkInput(this)" name="obraz" accept="image/*"/></p><div style="display:none;" class="S_ERR_DANE"  id="err_img"><?=$err_img?></div><br/>
    <p class="P_INPUT" style="font-weight:bold;font-size:20px;"> <!-- onblur="parseFieldValue(this.value,'opis','err_text');" onkeyup="parseFieldValue(this.value,'tytul','err_text');" onclick="parseFieldValue(this.value,'tytul','err_text');" -->
    Wskaż opis<?=$star?>:</p><input id="opis" style="width:770px;" type="text"   name="opis" value="<?php if(isset($_POST['googleurl'])) { echo $_POST['opis']; } else { echo $_opis;} ?>" onkeyup="checkInput(this)" onblur="checkInput(this)"/><br/><div style="display:none;" class="S_ERR_DANE" id="err_text"><?=$err_text?></div></br>
    <p class="P_INPUT" style="font-weight:bold;font-size:20px;">  <!-- onblur="parseFieldValue(this.value,'url','err_url');" onkeyup="parseFieldValue(this.value,'url','err_url');" onclick="parseFieldValue(this.value,'url','err_url');" -->
    Wprowadź Google Drive URL<?=$star?>:</p><textarea id="url" onkeyup="checkInput(this)" onblur="checkInput(this)" style="width:770px; resize: none;" rows="5" type="text" id="googleDiskUrl" name="googleurl" value=""/><?php if(isset($_POST['googleurl'])) { echo $_POST['googleurl']; } else { echo $_googleurl;} ?></textarea><br/><div class="S_ERR_DANE" id="err_url"><?=$err_url?></div></p>
<input type="hidden" name="TYP_DODAJ" value="3" />
<input class="button" type="submit" value="<?=$_submit_name?>" name="googledisk" id="submit_form" 
<?php
if($IDE>0)
{
    //echo 'onclick="checkFields()"';  
    echo 'onclick="checkFieldsEditMode()"';
}
else
{
    echo 'onclick="checkFields()"';
}
?>/><br/>
</form>
<div style='width:100%;background-color:#ff9999;'><?=$err_overall?></div>
<p class="P_LEG">Legenda :</p>
<ul class="UL_LEG">
<?php
foreach ($tab_legenda as $key => $value)
{
    echo "<li class=\"LI_LEG\">$value</li>";
}
?>
</ul>
</center></div>