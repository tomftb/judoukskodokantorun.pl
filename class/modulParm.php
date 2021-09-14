<?php
require_once (filter_input(INPUT_SERVER,"DOCUMENT_ROOT")."/class/database.php");

class modulParm extends database
{
    //private $dbLink;
    public function __construct()
    {
        parent::__construct();
        $this->log(0,"[".__METHOD__."]");
        $this->connect('nadrukireklamowe4','z7Z1h!&BzY#w','nadrukireklamowe4','localhost');
    }
    public function get($IDM)
    {
        $this->log(0,"[".__METHOD__."] IDM => ".$IDM);
        $p=array();
        $SEL_PARM = $this->query("select `N_OPCJ`,`NAZWA`,`WART`,`WSK_D`,`OPIS` FROM `PARM` WHERE ID_MODUL=$IDM order by ID_GROUP");
        //$this->logMultidimensional(0,mysqli_fetch_all($SEL_PARM,MYSQLI_ASSOC),__METHOD__,0);
        foreach(mysqli_fetch_all($SEL_PARM,MYSQLI_ASSOC) as $v)
        {
            $p[$v['N_OPCJ']]['NAZWA']=$v['NAZWA'];
            $p[$v['N_OPCJ']]['WART']=$v['WART'];
            $p[$v['N_OPCJ']]['WSKD']=$v['WSK_D'];
            $p[$v['N_OPCJ']]['OPIS']=$v['OPIS'];
        }
        //$this->logMultidimensional(0,$p,__METHOD__,0);
        return $p;
    }
    public function getModulInfo($IDM)
    {
        $this->log(0,"[".__METHOD__."] IDM => ".$IDM);
	$REK_MODUL=mysqli_fetch_row($this->query("SELECT SKROT,N_TABELA FROM MODUL WHERE ID='$IDM'"));
        $this->log(0,"[".__METHOD__."] SKRÓT => ".$REK_MODUL[0]);
        $this->log(0,"[".__METHOD__."] TABELA => ".$REK_MODUL[1]);
        
        return $REK_MODUL;
    }
    public function __destruct()
    {
        
    }
}
