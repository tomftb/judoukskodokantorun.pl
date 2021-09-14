<?php if(!defined('PAGE_URL')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<a class="A_BACK" href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"><p class="P_HREF_BACK">Anuluj</p></a>';
if (!isset($_GET["UPD_ID_P"]))
{
	$SEL_REK = mysqli_fetch_row($db->query("select z.IMIE_N,z.ID_P,zi.NAZWA_I_M,zi.M_WIDTH,zi.M_HEIGHT FROM ZWDK z, ZWDK_IMG zi WHERE z.ID=zi.ID_ZWDK AND z.ID='".$_GET['ID']."'"));
	echo '<p class="P_MAIN">Zmiana priotytetu zawodnika : </p>';
	echo '<p class="P_MAIN2">'.$SEL_REK["0"].'</p>';
	echo '<img src="'.APP_URL.'/zdjecia/nasi_za/'.$SEL_REK[2].'" alt="'.$SEL_REK[0].'" style="width:'.$SEL_REK[3].'px; height:'.$SEL_REK[4].'px; border:0px;" />';
	
	//------------------------------------------------------- FORM -----------------------------------------------------------------------------------
	echo '<FORM Name="UPD_ID_P" action="" method="GET" >';
	echo '<p class="P_MAIN3">Zamień na : ';
	echo '<select name="IDPZ" class="SELECT2">';
	//mysqli_query($polaczenie,"SET NAMES `utf8` COLLATE `utf8_polish_ci`");
	$SEL_DANE2 = $db->query("select  ID,IMIE_N,ID_P FROM ZWDK WHERE WSK_U=0 AND ID!='".$_GET['ID']."' order by ID_P");
	while($SEL_REK2 = mysqli_fetch_array($SEL_DANE2))//
	{
		echo '<option value="'.$SEL_REK2["2"].'" class="OPTION_1">['.$SEL_REK2["2"].'] '.$SEL_REK2["1"].'</option>';
	}
	echo '</optgroup></select></p>';
	echo '<div class="DIV_OPCJ">';
	echo '<input type="hidden" name="IDM" value="'.$IDM.'" />';
	echo '<input type="hidden" name="IDW" value="'.$IDW.'" />';
	echo '<input type="hidden" name="ID" value="'.$ID.'" />';
	echo '<input class="button_dodaj"type="submit" value="Zamień" name="UPD_ID_P"/>';
	echo '</FORM>';
	//------------------------------------------------------- FORM -----------------------------------------------------------------------------------
	echo'</div>';
	echo '<p class="P_INFO_MIN_2">(Aktualny priorytet - <span class="S_INFO_2">'.$SEL_REK["1"].'</span>)</p>';
	echo "</div>";
}
ELSE
{
	//ECHO "TEST";																			
																				
};
if (isset($_GET["IDPZ"]))
{
    include(DR."/funkcje/m_id_priorytet.php");
    echo '<p class="P_BACK">Piorytet twojego zawodnika został zmieniony na - '.$_GET["IDPZ"].'<br/><span class="S_BACK">Za chwilę zostaniesz przekierowany na stronę </span><span class="S_BACK2">MENU - ZAWODNICY.</span></p>';
								UNSET ($_GET["IDPZ"]);
								UNSET ($_GET["IDW"]);
								UNSET ($_GET["IDP"]);
								UNSET ($_GET["ID"]);
								echo '<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'"\', 1500);}window.onload=init;</script>';
								
}