<?php

class router extends database
{
    private $checkIDM=true;
    private $checkIDW=true;
    private $checkURL=true;
    private $IDW=0;
    private $IDM=0;
    private $mainPage='';
    private $redirectPage='';
    private $mainUrl='';
    private $URL='';
    
    public function __construct($mURL='')
    {
        parent::__construct();
        $this->loadDb();
        self::checkMainUrl($mURL);
        self::checkUrlData();
        self::getModuldata();
        
    }
    private function getModulData()
    {
        $this->log(0,"[".__METHOD__."]");
        if(!$this->checkIDM) {return false;}
        $modul = mysqli_fetch_assoc($this->query("SELECT `ID`,`SKROT`,`NAZWA` FROM `MODUL` WHERE `NR_M`='".$this->IDM."' LIMIT 1"));
        if(is_null($modul))
        {
            $this->log(0,"[".__METHOD__."] MODUL NOT FOUND ".$this->IDM);
            $this->checkIDM=false;
        }
        $this->logMulti(0,$modul,__METHOD__);
    }
    private function checkUrlData()
    {
        $this->IDW=filter_input(INPUT_GET,'IDW',FILTER_VALIDATE_INT);
        $this->IDM=filter_input(INPUT_GET,'IDM',FILTER_VALIDATE_INT);
        $this->URL=filter_input(INPUT_GET,'URL');
        
        $this->log(0,"[".__METHOD__."] IDW => ".$this->IDW);
        $this->log(0,"[".__METHOD__."] IDM => ".$this->IDM);
        $this->log(0,"[".__METHOD__."] URL => ".$this->URL);
        
        if(is_null($this->IDM) || !$this->IDM)
        {
            $this->log(0,"[".__METHOD__."] IDM is_null or false");
            $this->checkIDM=false;
        }
        if(is_null($this->IDW) || !$this->IDW)
        {
            $this->log(0,"[".__METHOD__."] IDW is_null or false");
            $this->checkIDW=false;
        }
        if(is_null($this->URL) || $this->URL==='')
        {
            $this->log(0,"[".__METHOD__."] URL is_null or empty");
            $this->checkURL=false;
        }
    }
    public function setPage()
    {
        $this->log(0,"[".__METHOD__."]");
        if(!$this->checkURL)
        //if(!$this->checkIDM && !$this->checkIDW)
        {
            $this->redirectPage=$this->mainPage;
        }
        else
        {
            $this->redirectPage=$this->mainUrl.'/'.$this->URL.'?IDM='.$this->IDM."&IDW=".$this->IDW;
            //$this->redirectPage=$this->mainUrl.'/pod_strony/menu.php?IDM='.$this->IDM."&IDW=".$this->IDW;
        }
    }
    public function getRedirectPage()
    {
        $this->log(0,"[".__METHOD__."] ".$this->redirectPage);
        return $this->redirectPage;
    }
    public function setMainPage($mPage='')
    {
        if($mPage==='')
        {
            die(__METHOD__.':: SET MAIN PAGE ERROR');
        }
        $this->mainPage=$mPage;
        $this->log(0,"[".__METHOD__."] ".$this->mainPage);
    }
    private function checkMainUrl($mURL='')
    {
        if($mURL==='')
        {
            die(__METHOD__.':: SET MAIN URL ERROR');
        }
        $this->mainUrl=$mURL;
        $this->log(0,"[".__METHOD__."] ".$this->mainUrl);
    }
    public function __destruct()
    {
        
    }
}
