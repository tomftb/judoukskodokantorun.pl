<?php
echo '<p style="color:#0099FF; font-size:20px;"><b>Wszystkie wpisy<b></p>';
$query=$db->query("select * from `NEWS_OLD` where WSK_U=0 AND WSK_V=1 order by ID desc");
while($rekord = mysqli_fetch_array($query))
{
    echo '<center><p style="color:'.$rekord[12].'; font-size:'.$rekord[13].';">'.$rekord[1].'</p>';
    echo '</center><pre><p style="font-face: Times New Roman ;color:'.$rekord[14].';font-size:'.$rekord[15].'; text-align:left; ">'.$rekord[3].'</p></pre>';
    echo '<center>'.$rekord[4].$rekord[5].$rekord[6].'</br></br>'.$rekord[7].'</center>';
}