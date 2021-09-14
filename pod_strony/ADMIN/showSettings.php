<?php
if(!defined('ADMIN130')) { exit('NO PERMISSION');}
echo '<p class="P_M_NAGL">Aktualne ustawienia w systemie :</p>';
/* COUNT PAGES */
    require(DR.'/class/page.php');
    $page=NEW page();
    $page->setDbRec("select COUNT(*) FROM `MODUL`");
    $page->setRecOnPage(intval(mysqli_fetch_row($db->query("select `WART` FROM `PARM` WHERE `ID_MODUL`=".$IDM." AND `ID_GROUP`=6;"))[0],10));
    $page->setPages(PAGE_URL."&IDW=".$IDW."&IDM=".$IDM);
    $IDS=$page->getIDS();
/* END COUNT PAGES */							

$SEL_MODUL = $db->query("SELECT ID,NAZWA,SKROT FROM `MODUL` LIMIT ".$page->getStartLimit().",".$page->getEndLimit());
while($REK_MODUL=mysqli_fetch_array($SEL_MODUL))
{
    echo '<p style="text-align:left; font-size:18px; font-weight:bold;margin-left:10px;">';
    echo '<span style="color:purple;">MODUŁ ['.$REK_MODUL[0].'] </span>: '.$REK_MODUL[1].' [<a href="'.PAGE_URL.'&IDW=21&IDMZ='.$REK_MODUL[0].'" class="A_UST"> ZMIEŃ </a>] :</p>';
    echo "<table ID=\"UST\">";
    $DEF_POZ = $db->query("SELECT ID,ID_GROUP,ID_OPCJ,N_OPCJ,WART,OPIS,NAZWA FROM PARM WHERE WSK_U=0 AND ID_MODUL='$REK_MODUL[0]'");
    while($REK_POZ=mysqli_fetch_array($DEF_POZ))
    {
    	echo '<tr ID="UST">'; //style="background-color:red;"
	echo '<td ID="UST" width="400px" ><span style="margin-left:2px;float:left;"><b>[</b>'.$REK_POZ[0].'<b>]</b> '.$REK_POZ[5].'</span></td>';
        if ($REK_POZ[1]==3 || $REK_POZ[1]==0)
        {
            $WYSW_WART=$REK_POZ[6];
        }
        else if ($REK_POZ[1]==4 || $REK_POZ[1]==2)
        {
            $WYSW_WART=$REK_POZ[4]." px";
        }
        else if ($REK_POZ[1]==5)
        {
            if ($REK_POZ[4]==0) 
            {
                $WYSW_WART="<span style=\"color:red;\">NIE</span>";
            }
            else if ($REK_POZ[4]==1)
            {
                $WYSW_WART="<span style=\"color:blue;\">TAK</span>";
            }
            else
            {
                $WYSW_WART="<span style=\"color:red;\">BŁĄD !</span>";
            }
        }
        else
        {
            $WYSW_WART=$REK_POZ[4];
        }
	$i_css=0;
	if ($REK_POZ[1]==1)
        {
            $WYSW_WART="";					
            $SEL_PAM_CSS = $db->query("SELECT c.NAZWA FROM PARM_CSS pc, CSS c,PARM pm WHERE pc.ID_CSS=c.ID AND pc.ID_PARM=pm.ID AND c.ID_GROUP=0 AND pc.WSK_V=1 AND pc.WSK_U=0 AND pm.ID_MODUL='$REK_MODUL[0]' AND pc.ID_PARM='$REK_POZ[0]'");
            while($REK_PAM_CSS=mysqli_fetch_array($SEL_PAM_CSS))
            {
                $WYSW_WART=$WYSW_WART." ".$REK_PAM_CSS[0];
                $i_css++;
            }					
	}
	echo '<td ID="UST" ><span style="margin-left:2px;font-weight:bold;float:left;"> '.$WYSW_WART.'</span></td>';						
	echo '</tr>';//style="background-color:blue;"
    }
    echo "</table>";
}
							

