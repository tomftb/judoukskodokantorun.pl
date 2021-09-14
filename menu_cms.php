<?php 
session_start();
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
define('RA',filter_input(INPUT_SERVER,"REMOTE_ADDR"));
require(DR.'/.cfg/konfiguracja.php');
require(DR.'/class/logToFile.php');
require(DR.'/class/session.php');


$log=NEW logToFile();
$session=new session();

if(!$session->checkSession())
{    
    $log->log(0,"[".__FILE__."] SESSION NOT EXIST => REDIRECT TO LOGIN PAGE");
    header('Location: '.APP_URL.'/logowanie.php');
}

$CSS="menu_cms.css";
define('TITLE',"JUDO - MENU CMS");

require(DR.'/view/v_head.php');
require(DR.'/class/database.php');
require(DR.'/pod_strony/_funkcje_/licznik_odwiedzin.php');
require(DR.'/class/browser.php');

$db= NEW database();
$db->connect(dbUser,dbPass,database,dbHost);

$browser=new browser();

?>
<body class="BODY_MAIN">
<script>
document.getElementById('div_main').setAttribute("style","width:500px");
function getWidth()
{ 
	var windowsWidth=window.screen.width;
	//window.screen.availHeight
	//window.screen.availWidth
	return windowsWidth;
};
function getHeight()
{ 
	var windowsHeight=window.screen.height;
	return windowsHeight;
}
</script>
<?php
$resWidth ='<script type="text/javascript">document.writeln(getWidth());</script>';
$resHeight ='<script type="text/javascript">document.writeln(getHeight());</script>';
echo "<center><div class=\"DIV_MAIN\" id=\"div_main\">";
 ?>
 <script>
 var windowsWidth=window.screen.width;
 var width_div="width:800px";
 if (windowsWidth>1024) width_div="width:1024px";
  document.getElementById('div_main').setAttribute("style",width_div);
  </script>
 <?php
$wynik_odwiedzin=licznik_odwiedzin("index.php",$db->getDbLink());
echo '<p class="P_INFO">Użytkownik : <span class="SPAN_INFO">'.$_SESSION['login'].'</span>';
echo '<span style="float:right;"><a href="logout.php">Wyloguj</a></span></br>';
echo 'Aktualna data : <span class="SPAN_INFO">'.date('Y-m-d H:i:s').'</span></br>';
echo "Licznik odwiedzin strony :<span class=\"SPAN_INFO\">$wynik_odwiedzin</span></br>";
echo 'Sesja wygasa : <span class="SPAN_INFO">'.date('Y-m-d H:i:s', $_SESSION['expire']) .'</span></p>';
if ($_SESSION['id_user']==1)
{ 
	echo '<p class="P_INFO"> ID USER : <span style="color:#088A4B; font-weight:bold;">'.$_SESSION['id_user'].'</span></br>';
	echo 'UID : <span class="SPAN_INFO">'.$_SESSION['uid'].'</span></br>';
	echo 'Twoje IP :  <span class="SPAN_INFO">'.RA.'</span></br>'; 
	echo  gethostbyaddr(RA)."</p>";
}
?>
<img src="<?php echo APP_URL;?>/images/judo_znak2.png"/>
<p style="text-align:center; font-size:31px; font-weight:bold;">Witaj w systemie zarządzania treścią (CMS).</p><br/><br/>
<?php 

echo '<ul>';

$zap_modul = $db->query("select NR_M,NAZWA,SKROT FROM MODUL WHERE WSK_U=0 AND WSK_V=1 order by ID");
while($rek_modul=mysqli_fetch_array($zap_modul))
{
    echo '<li><a href="'.APP_URL.'/pod_strony/menu.php?IDW=0&IDM='.$rek_modul[0].'" >'.$rek_modul[1].'</a></li>';
}
echo '</ul>';
?>
<br/><p class="P_STOPKA">System opracowany przez : <span class="SPAN_INFO">Tomasz Borczyński</span>, e-mail: <span class="SPAN_INFO">mass.hopto.org@gmail.com </span></p>
<?php	
if ($_SESSION['id_user']==1)
{ 
    echo '<p class="P_STOPKA">Używasz przeglądarki : <span class="SPAN_INFO">'.$browser->getBrowserType().'</span> PHP : <span class="SPAN_INFO">'.PHP_VERSION.'</span></p>';
};
?>
</div>
</center>
</body>
</html>
