<?php

require_once(filter_input(INPUT_SERVER,'DOCUMENT_ROOT').'/class/database.php');
class userPerm extends database
{
    //put your code here
    private $IDM='';

    public function __construct()
    {
        parent::__construct();
        $this->loadDb();
    }
    public function getUserPerm($IDM)
    {
        $this->IDM=$IDM;
        //-----------------------------------------UPRAWNIENIA------------------------------------------
	$TAB_PRAW=array();
	echo "<p class=\"P_INFO\"> Uprawnienia :";
	$ZAP_FUNKCJA = $this->query("select ID,IDW,NAZWA FROM FUNKCJA WHERE ID_MODUL='$this->IDM' ORDER BY IDW");
	while($REK_FUNKCJA=mysqli_fetch_array($ZAP_FUNKCJA))
        {												
            $REK_PRAW=mysqli_fetch_row($this->query("select p.WSK_V FROM PERS_FUNKCJA p WHERE p.ID_FUNKCJA='$REK_FUNKCJA[0]' AND p.ID_PERS='$_SESSION[id_user]' LIMIT 1"));
            $TAB_PRAW[$REK_FUNKCJA[2]]['IDX'] = $REK_FUNKCJA[1];
            $TAB_PRAW[$REK_FUNKCJA[2]]['VAL'] = $REK_PRAW[0];
            
            if ($REK_PRAW[0]==1) $upraw="<span style=\"color:blue\">TAK</span>"; else if ($REK_PRAW[0]==0) $upraw="<span style=\"color:red\">NIE</span>"; else $upraw="<span style=\"color:red\">BŁĄD !</span>";
            if ($_SESSION["id_user"]==1)
            {
                echo " [<span class=\"S_INFO\">".$REK_FUNKCJA[2]."</span> ] - ".$upraw;
            }
	}							
	echo "</p>";
        return $TAB_PRAW;
																				
    }
    public function getPermissions($userId)
    {
        $perm=array();
        foreach(mysqli_fetch_all($this->query("SELECT f.`ID_MODUL` as 'IDM',f.`IDW` as 'IDW',f.`NAZWA` as 'NAZWA' FROM `FUNKCJA` f,`PERS_FUNKCJA` p WHERE f.`ID`=p.`ID_FUNKCJA` AND p.`WSK_V`='1' AND p.`ID_PERS`=".$userId),MYSQLI_ASSOC) as $F)
        {
            /*
             * IDM
             * IDW
             * NAZWA
             */
            $perm[$F['IDM']][$F['IDW']]=$F['NAZWA'];
        }
        return $perm;
    }
}
