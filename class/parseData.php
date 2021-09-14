<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of parseData
 *
 * @author tborczynski
 */

require_once (filter_input(INPUT_SERVER,"DOCUMENT_ROOT")."/class/database.php");

class parseData extends database
{
    private $avaliableCharacter='';
    private $maxCharacter=0;
    private $minCharacter=0;
    private $error='';
    private $data='';
    
    public function __construct()
    {
        parent::__construct();
        $this->connect('nadrukireklamowe4','z7Z1h!&BzY#w','nadrukireklamowe4','localhost');
    }
    public function setAvaliableCharacter($c)
    {
        $this->avaliableCharacter=$c;
        $this->log(0," AVALIABLE CHARACTER => ".$c);
    }
    public function checkPost()
    {
        $this->logMultidimensional(0,$_POST,__METHOD__,0);
    }
    public function checkData($d)
    {
        $this->data=$d;
        self::checkLength();
        self::checkCharacter();
    }
    private function checkLength()
    {
        $length=strlen($this->data);
        if($length<$this->minCharacter)
        {
            $this->error='<span class="S_ERR_DANE">Wprowadzono za mało znaków - '.$length.'  (minimum - <span CLASS="S_ERR_DANE2">'.$this->minCharacter.'</span>)</span>';
        }
        else if($length>$this->maxCharacter)
        {
            $this->error='<span class="S_ERR_DANE">Wprowadzono za duzo znaków - '.$length.'  (minimum - <span CLASS="S_ERR_DANE2">'.$this->maxCharacter.'</span>)</span>';
        }
        else
        {
            // OK
        }
    }
    private function checkCharacter()
    {
        if(!preg_match($this->avaliableCharacter,$this->data))
        {
            $this->error.='<span class="S_ERR_DANE">Usuń niedozwolone znaki.</span>';
        }
    }
    public function errorExist()
    {
        if($this->error!='')
        {
            return true;
        }
        return false;
    }
    public function errorInfo()
    {
        return $this->error;
    }
    public function setMaxCharacter($m)
    {
        $this->maxCharacter=intval($m);
    }
    public function setMinCharacter($m)
    {
        $this->minCharacter=intval($m);
    }
    public function __destruct()
    {
        
    }
}
