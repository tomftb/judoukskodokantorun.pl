//mysql_query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
													//$SEL_P_CSS= mysql_query("select pc.ID_PARM,pc.ID_CSS,pc.WSK_V,c.NAZWA FROM PARM pm,PARM_CSS pc, CSS c WHERE pm.ID=pc.ID_PARM AND pc.ID_CSS=c.ID AND c.ID_GROUP=0 AND pm.ID_MODUL='$_GET[IDMZ]' ORDER BY c.ID") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_P_CSS - ".mysql_error()."</span></p>");
													//$SEL_P_CSS=mysql_query("select c.ID,c.NAZWA FROM CSS c WHERE exists (SELECT pc.ID_PARM,pc.WSK_V FROM PARM_CSS pc, PARM pm WHERE pm.ID=pc.ID_PARM AND c.ID=pc.ID_CSS AND pc.WSK_U=0 AND pm.ID_MODUL='$_GET[IDMZ]' AND pc.ID_PARM='$REK_PARM[0]') AND c.ID_GROUP=0 ORDER BY c.ID") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_P_CSS - ".mysql_error()."</span></p>");
													//while($REK_P_CSS = mysql_fetch_array($SEL_P_CSS)){
														//if($REK_P_CSS[2]=="0" ||$REK_P_CSS[2]=="") $USTAWIONY=""; else IF ($REK_P_CSS[2]=="1") $USTAWIONY='checked="checked"';
													//	echo '<input type="checkbox" name="CSS_'.$REK_P_CSS[0].'" value="1" checked="checked" class="CSS_CHBOX"  /><span class="S_CSS">'.$REK_P_CSS[1].'</span> ';
													//};
													
													//$SEL_P_CSS=mysql_query("select c.ID,c.NAZWA FROM CSS c WHERE exists (SELECT pc.ID_PARM,pc.WSK_V FROM PARM_CSS pc, PARM pm WHERE pm.ID=pc.ID_PARM AND c.ID=pc.ID_CSS AND pc.WSK_U=0 AND pm.ID_MODUL='$_GET[IDMZ]' AND pc.ID_PARM='$REK_PARM[0]') AND c.ID_GROUP=0 ORDER BY c.ID") or die ("<p class=\"P_SQL_ERR\">Błąd zapytania SQL - <span class=\"S_SQL\">SEL_P_CSS - ".mysql_error()."</span></p>");
														