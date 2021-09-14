<?php
class logToFile
{
    protected $filename='';
    private $log=1;
    protected $logName='';
    protected $DR='';
    
    function __construct()
    {
        $this->DR=filter_input(INPUT_SERVER,"DOCUMENT_ROOT");
        self::setLogName();
        self::open();   
        //fwrite($this->filename, "[".date("Y.m.d H:i:s")."] [".__METHOD__."] LOG INITIALISED".PHP_EOL);
    }
    
    public function log($l=0,$d='')
    {
        /*
         * l -> lvl of log
         * d -> data to write
         */
        if($this->log>=$l)
        {
            fwrite($this->filename, "[".date("Y.m.d H:i:s")."] ".$d.PHP_EOL);
        }
    }
    protected function open()
    {       
        $this->filename = fopen($this->logName, "a") or die("Unable to open file!");
    }
    protected function getLogLvl()
    {
        return $this->log;
    }
    protected function setLogName()
    {
        $this->logName=$this->DR."/.log/log-".date("Y-m-d").".php";
    }
    public function logMulti($l,$data,$m)
    {
        self::logMultidimensional($l,$data,$m,0);
    }
    public function logMultidimensional($l,$data,$m,$nLvl=0)
    {
        /*
         * $l -> level of log
         * $data -> data to write
         * $m -> called method
         * $nLvl -> nesting lvl
         */
        if(is_array($data))
        {
            $nLvl++;
            self::log($l, "[".__METHOD__."][${m}][${nLvl}][A]");
            self::logMultidimensionaA($l,$data,$m,$nLvl);
        }
        else if(is_object($data))
        {
            $nLvl++;
            self::log($l, "[".__METHOD__."][${m}][${nLvl}][O]");
            self::logMultidimensional($l,get_object_vars($data),$m,$nLvl);
        }
        else if(is_resource($data))
        {
            self::log($l, "[".__METHOD__."][${m}][${nLvl}][R]");
        }
        else
        {
            self::log($l, "[".__METHOD__."][${m}][${nLvl}][V] ".$data);
        }
    }
    private function logMultidimensionaA($l,$data,$m,$nLvl)
    {
        foreach($data as $k => $v)
        {
            self::log($l, "[".__METHOD__."][${m}][${nLvl}][K] ".$k);
            self::logMultidimensional($l,$v,$m,$nLvl);
        }
    }
    function __destruct()
    {
        $this->log(0,"[".__METHOD__."]");
        fclose($this->filename);   
    }
}