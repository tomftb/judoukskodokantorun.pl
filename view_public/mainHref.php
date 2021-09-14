<?PHP
$SEL_1=$db->query("SELECT `NAZWA`,`LINK`,`IMG_A`,`IMG_B` FROM `MODUL` WHERE `WSK_U`=0 AND `WSK_P`=1 ORDER BY `ID` ASC");
while($REK_1 = mysqli_fetch_array($SEL_1))
{
    $dlugosc=mb_strlen($REK_1[0]);
    if( $dlugosc<=15) $divH=34; 
    else
    {
	$divH=($dlugosc / 15|0)*34;
    	if($dlugosc % 15!=0) $divH+=34;
    }
    echo '<li class="nawigacja_li">'; 
    echo '<div class="buttonTop"></div>';
    echo '<div class="buttonMiddle" style="height:'.$divH.'px;"><p class="p_button"><a class="button" href="iframe.php?IDW='.$REK_1[1].'" target="iframe" >'.$REK_1[0].'</a><p></div>';
    echo '<div class="buttonBottom"></div>';
    echo '</li>';
} // END WHILE
	

				