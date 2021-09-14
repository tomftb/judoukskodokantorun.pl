<?php
session_start();
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
require(DR."/.cfg/konfiguracja.php");
require(DR.'/class/logToFile.php');
require(DR.'/class/database.php');

$db= NEW database();
$db->connect(dbUser,dbPass,database,dbHost);

$now = time();
if((!isset($_SESSION['login'])) || ( (isset($_SESSION['login'])) && ($now > $_SESSION['expire']) ))
{ 
  header('location:logowanie.php');
  
}
else
{
    $CSS='login.css';
    define('TITLE','CMS - Wylogowanie');
    require_once(DR.'/view/v_head.php');
?>
<body>
<center>

<fieldset class="FIELD">
    <legend align="left"><span class="F_TYTUL">Opuściłeś system - CMS</span></legend>
    <img src="images/judo_logo_40.bmp" align="right" alt="logo" style="margin:0px;"/><br/>
    <p class="P_INF_LOG">Powrót do : <a href="<?php echo APP_URL;?>/index.php" >UKS Kodokan Judo</a></span></p>
    <p class="P_INF_LOG">Powrót do : <a href="<?php echo APP_URL;?>/logowanie.php" >System - CMS</a></span></p>

<?php
if(isset($_SESSION["id_user"]))
{
    echo '<p class="F_ERROR">Wylogowano użytkownika : <span CLASS="S_ERROR">'.$_SESSION["login"].'</span></p>';
    $db->query("UPDATE `LOG` SET `DAT_LOGU`=NOW() WHERE UID='$_SESSION[uid]'");
    foreach($_SESSION as $key => $value)
    {
        unset($_SESSION[$key]);   
    }
    session_destroy();
}
else
{
    echo '<p class="F_ERROR">Nie można wylogować niezalogowanego użytkownika!</p>';
}
?>
</fieldset>
</center>
</body>
</html>
<?php
}
?>