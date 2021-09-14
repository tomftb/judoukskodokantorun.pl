<?php if(!defined('TREN42')) { exit('NO PERMISSION');} ?>
<div class="DIV_MAIN">
<?php
echo '<p class="P_HREF_BACK"><a class="A_BACK"href="'.PAGE_URL.'&IDW=0&IDS='.$IDS.'#ID'.$ID.'">Anuluj</a></p>';
$status_edytuj=0;
$err_session='';
if (isset($_GET["EDIT"]))
{
    if (($ID!=''))
    {
	if (mysqli_num_rows($db->query("SELECT ID FROM TREN WHERE ID='".$ID."'"))!=0)
        {
            if (mysqli_num_rows($db->query("SELECT ID FROM TREN where ID='".$ID."' AND WSK_U=1"))==0)
            {
		$status_edytuj=1;
            } // KONIEC istnieje rekord usuniety
        else { $err_session='<p class="P_ERR_E"><span class="S_ERROR">Istnieje</span> Trener o podanym <span class="S_ERROR">ID</span> ale został on już prędzej usunięty !</p>';}
	} // KONIEC istnieje rekord
																									else $err_session='<p class="P_ERR_E">Nie <span class="S_ERROR">istnieje</span> Trener o podanym <span class="S_ERROR">ID</span> !</p>';
    } // KONIEC wprowadzono wartość GET_["IDE"]
    else { $err_session='<p class="P_ERR_E">Nie wprowadzono  <span class="S_ERROR">ID</span> Zawodnika !</p>';	}
};
if ($status_edytuj==0)
{
    echo '<p class="P_MAIN">Edytowanie Trenera :</p>';
    echo '<form action="" method="GET" name="EDYTUJ">';
							echo '<p class="P_NG_INF">Podaj numer ID Trenera : ';
							echo '<input type="text" name="ID" size="5" value="'.$ID.'" maxlength="5" /></p>';
							echo $err_session;
							echo '<div class="DIV_OPCJ">';
							echo '<input type="hidden" name="IDW" value="2" />';
							echo '<input type="hidden" name="IDM" value="'.$IDM.'" />';
							echo '<input class="button" type="SUBMIT" value="Edytuj" name="EDIT"/>';
							}
                                                        else
{
    echo '<script>function init(){ setTimeout(\'document.location="'.PAGE_URL.'&IDW=2&ID='.$ID.'"\', 1);}window.onload=init;</script>';
}
echo '</div>';
echo "</form>";
echo "</div>";