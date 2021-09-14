<?php
class page extends database
{
    private $recOnPage=0;
    private $pages=0;
    private $mainPage='';
    private $tableRows=0;
    private $noPage=false;
    private $IDS=0;
    private $pageStart=0;
    private $pageEnd=0;
    private $link='';
    
    public function __construct()
    {
        parent::__construct();
        $this->loadDb();
        //$this->connect('nadrukireklamowe4','z7Z1h!&BzY#w','nadrukireklamowe4','localhost');
    }
    public function setPages($PAGE)
    {
         $this->log(0,"[".__METHOD__."] PAGE => ".$PAGE);
         $this->mainPage=$PAGE;
         self::checkRecords();
         self::countPages();
         self::generateLink();
    }
    private function checkRecords()
    {
        $this->log(0,"[".__METHOD__."] TABLE RECORDS => ".$this->tableRows);
        $this->log(0,"[".__METHOD__."] RECORDS ON PAGE => ".$this->recOnPage);
        /*
         * tableRows===recOnPage NO PAGE
         */
        if($this->tableRows===$this->recOnPage)
        {
            $this->log(0,"[".__METHOD__."] TABLE RECORDS AND RECORDS ON PAGE = 0 OR ARE EQUAL SETUP NO PAGE");
            $this->noPage=true;
            return '';
        }
        if($this->tableRows!==0 && $this->recOnPage===0)
        {
            $this->log(0,"[".__METHOD__."] TABLE RECORDS > 0 AND RECORDS ON PAGE = 0 SETUP REC ON PAGE = TABLE ROWS");
            $this->noPage=true;
            $this->recOnPage=$this->tableRows;
            return '';
        }
        if($this->recOnPage > $this->tableRows)
        {
            $this->log(0,"[".__METHOD__."] NO PAGES, TABLE RECORDS ARE LOWER THAN PAGE COUNT");
            $this->noPage=true;
        }
    }
    public function setRecOnPage($rec)
    {
        $this->recOnPage=intval($rec);
        $this->log(0,"[".__METHOD__."] RECORDS ON PAGE => ".$rec);
    }
    public function setDbRec($sql)
    {
        $this->tableRows=intval(mysqli_fetch_row($this->query($sql))[0],10);
        $this->log(0,"[".__METHOD__."] TABE ROWS => ".$this->tableRows);
    }
    private function countPages()
    {
        if($this->noPage) {return'';}
        //if($this->tableRows===0) {$this->tableRows=1;}
        $this->pages=ceil($this->tableRows/$this->recOnPage);
        $this->log(0,"[".__METHOD__."] PAGES CEIL() => ".$this->pages);   
    }
    private function generateLink()
    {
        if($this->noPage) {return'';}
        /*
         * CHECK IDS
         */
        self::checkIDS();
        $this->log(0,"[".__METHOD__."]");   
        $j=1;
        $this->link='<p class="P_ZAKL"> Przejdź do strony - ';
        
        for($i=0;$i<$this->pages;$i++)
        {
            $this->link.='<a style="margin-left:1px;margin-right:1px;" class="A_BACK" href="'.$this->mainPage.'&IDS='.$j.'">'.self::setBoldPage($j).'</a>';
            $j++;
        }
        $this->link.='</p>';
        $this->log(0,"[".__METHOD__."] LINK => ".$this->link);   
    }
    public function getPageLink($hrPosition)
    {
        $this->log(0,"[".__METHOD__."]"); 
        if($this->noPage) {return'';}
        return  self::setHRposition($hrPosition);
    }
    private function setHRposition($hrPosition)
    {
        $hr='<hr CLASS="HR_MAIN"></hr>';
        $hrLink=$this->link.$hr;
        if($hrPosition!=='s')
        {
            $hrLink=$hr.$this->link;
        }
        return $hrLink;
    }
    private function setBoldPage($j)
    {
        if($this->IDS===$j || ($this->IDS===0 && $j===1))
        {
            return('<span style="font-size:24px;">'.$j.'</span>');
        }
        return $j;
    }
    private function checkIDS()
    {
        $this->IDS=intval(filter_input(INPUT_GET,'IDS'),10);
        $this->log(0,"[".__METHOD__."] CURRENT IDS => ".$this->IDS);
    }
    public function getStartLimit()
    {
        if($this->noPage)
        {
            /* return 0 */
        }
        if($this->IDS>0)
        {
            $this->pageStart=($this->recOnPage*($this->IDS-1));
            //$this->pageStart=($this->recOnPage*($this->IDS)-$this->recOnPage);
        }
        $this->log(0,"[".__METHOD__."] RETURN => ".$this->pageStart);
        return $this->pageStart;
    }
    public function getEndLimit()
    {
        if($this->noPage)
        {
            /* return table rows */
            $this->pageEnd=$this->tableRows;
        }
        if($this->IDS>=0)
        {
            $this->pageEnd=$this->recOnPage;  
        }
        $this->log(0,"[".__METHOD__."] RETURN => ".$this->pageEnd);
        return $this->pageEnd;
    }
    public function getIDS()
    {
        return $this->IDS;
    }
    public function __destruct()
    {
        
    }
}
