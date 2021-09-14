<?php if(!defined('AKT23')) { exit('NO PERMISSION');}?>
<div class="DIV_MAIN">
<?php
    echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Anuluj</p></a>';
                                              
    $end='<p class="P_BACK">Twój artykuł został usunięty !<br/>';
    $end.='<span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span>';
    $end.='<span class="S_BACK2">MENU - Aktualności.</span></p>';
    $end.='<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'"\', 500);}window.onload=init;</script>';
	
    /*
     * CHECK GET
     */
    if (isset($_GET["ID"]))
    {
        $log->log(0," LOAD WITH GET[ID] = ".$_GET["ID"]);
	echo '<p class="P_MAIN">Napewno chcesz usunąć artykuł nr<span class="S_MAIN_NG">[</span>'.$_GET["ID"].'<span class="S_MAIN_NG">]</span> ? </p>';
	require_once ($DR."/pod_strony/_include_/pobr_dane2.php");
	echo '<div class="DIV_OPCJ">';
	echo '<p class="P_POZ_BUT">';
        echo '<a href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><span class="s_button">NIE</span></a>';
	echo '<a href="'.PAGE_URL.'&IDW='.$IDW.'&IDS='.$IDS.'&ID='.$ID.'&USN=T'.'"><span class="s_button">TAK</span></a>';
	echo '</p></div>';
	if (isset($_GET["USN"]))
        {
            $db->query("UPDATE `NEWS` SET `WSK_U`='1' WHERE `NEWS`.`ID`=$ID");
            $db->insDbLog($IDM,"USUŃ ARTYKUŁ - usunięto ID : ".$ID);
            echo $end;						
	}
    } 
    else
    {	
        /* POST */
        $log->log(0," LOAD WITH POST[ID]");
	echo '<p CLASS="P_MAIN_CEL">Usuwanie artykułu : </p>';
	echo '<form Name="USUN" action="" method="POST" >';
	echo '<p class="P_NG_INF">Podaj numer ID artykułu : ';
	echo '<input type="text" name="ID" size="5" value="';
        if(isset($_POST['ID'])) { echo $_POST['ID'];}
        echo '" maxlength="5"  class="TEXTAREA" /></p>';
	echo '<div class="DIV_OPCJ">';
	echo '<input type="hidden" name="IDM" value="'.$IDM.'" />';
	echo '<input class="button" type="submit" value="Usuń" name="USUN"/></form>';
	echo '</div>';
	if(isset($_POST['USUN']))
	{
            $log->log(0," ISSET POST[USUN]");
            if (($_POST["ID"]!=''))
            {
                $log->log(0,"POST[ID] NOT EMPTY => ".$_POST["ID"]);
		       
		if (mysqli_num_rows($db->query("select ID from NEWS where ID='$_POST[ID]'")) == 0) 
                {
                    echo '<p style="color:red">Nie znaleziono żadnego artykułu o wpisanym <span style="color:black;">ID</span>!</p>';
                    $log->log(0,"Nie znaleziono żadnego artykułu o wpisanym ID!");
                }
		else
                {
                    
                    if (mysqli_num_rows($db->query("select ID from NEWS where ID='$_POST[ID]' AND WSK_U=1"))==0)
                    {				
			$db->query("UPDATE `NEWS` SET `WSK_U`='1' WHERE `NEWS`.`ID`=$_POST[ID]");
			$db->insDbLog($_GET["IDM"],"USUŃ ARTYKUŁ - usunięto ID : ".$_POST["ID"]);
                        $log->log(0,"USUŃ ARTYKUŁ - usunięto ID : ".$_POST["ID"]);
                        echo $end;
                    }
                    else 
                    {
                        echo '<p style="color:red">Istnieje artykuł o podanym <span style="color:black;">ID</span> ale został ono już prędzej usunięty !</p>';
                        $log->log(0,"Istnieje artykuł o podanym ID, ale został ono już prędzej usunięty !");
                    }
		}
            }
            else
            {
                echo '<p style="color:red;">Nie podałeś <span style="color:black;">ID</span> artykułu !</p>';
                $log->log(0,"Nie podałeś ID artykułu !");
            }
	}
    }
echo "</center>";
?>
</div>