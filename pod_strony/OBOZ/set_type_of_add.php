<?php if(!defined('OBOZ_51')) { exit('NO PERMISSION'); } ?>
<DIV class="DIV_MAIN">
<p class="P_HREF_BACK"><a class="A_BACK" href="<?php echo PAGE_URL."&IDW=0";?>">Anuluj</a></p>
<p class="P_MAIN">Kreator dodawania obozu :</p>
<form action="" name ="wybor" method="GET" >
<p class="P_NG_INF">Wybierz co chcesz dodać  :
<input type="radio" name="TYP_DODAJ" value="1" checked="checked" />Galeria
<input type="radio" name="TYP_DODAJ" value="2" />Film
<input type="radio" name="TYP_DODAJ" value="3" />Google Drive</p>
<input type="hidden" name="IDW" value="1" />
<input type="hidden" name="IDM" value="<?=$_GET["IDM"];?>" />
<div class="DIV_OPCJ">
<input type="submit" name="submit_wybor" class="button" value="Dalej"/></div></form>
</div>