<?php 
session_start();
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
define('TITLE','CMS - Logowanie');
$CSS='login.css';

require(DR."/.cfg/konfiguracja.php");
require(DR."/class/logToFile.php");
require(DR."/class/database.php");
require(DR.'/class/session.php');
require(DR.'/class/router.php');

$route=NEW router(APP_URL);
$route->setMainPage(APP_URL.'/menu_cms.php');
$route->setPage();
$route->getRedirectPage();
$log=NEW logToFile();

$userSession=NEW session();

$db=NEW database();
$db->loadDb();

$errLoginForm='';
$logIn=false;

if($userSession->checkSession())
{    
    $log->log(0,"[".__FILE__."] SESSION EXIST => REDIRECT");
    header('Location: '.$route->getRedirectPage());
}
$log->log(0,"[".__FILE__."] SESSION NOT EXIST => LOAD LOGIN FORM");
if(isset($_POST["wyslij"]))
{
    require(DR.'/class/checkLogin.php');
    $logToApp=NEW checkLogin('login','haslo');
    $logIn=$logToApp->check();
    $errLoginForm=$logToApp->getErr();
}

if($logIn)
{
    require(DR.'/class/userPerm.php');
    $perm=NEW userPerm();    
    $userSession->setSession($logToApp->getUserData('login'),$logToApp->getUserData('userId'),time(),uniqid(),$perm->getPermissions($logToApp->getUserData('userId')));
    //echo "<pre>";
    //print_r($_SESSION);
    //echo "</pre>";
    
    //die();
    header('Location: '.$route->getRedirectPage());
}
else
{
    require(DR.'/view/v_head.php');
    echo '<body><center>';
    require(DR.'/view_public/loginForm.php');
    echo '</center></body/></html>';
}