<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of checkLogin
 *
 * @author tborczynski
 */
class checkLogin extends database
{
    private $login='';
    private $pass='';
    private $loginInp='';
    private $passInp='';
    private $err='';
    private $userId=0;
    
    public function __construct($loginInp='',$passInp='')
    {
        parent::__construct();
        self::setInput($this->loginInp,$loginInp,'LOGIN');
        self::setInput($this->passInp,$passInp,"PASSWORD");

        $this->loadDb();
    }
    public function check()
    {
        $this->log(0,"[".__METHOD__."]");
        $this->login=filter_input(INPUT_POST,$this->loginInp);
        $this->pass=filter_input(INPUT_POST,$this->passInp);
        self::isEmpty();
        self::checkInDb();
        self::getUserPerm();
        return (self::returnStatus()); 
    }
    public function setInput(&$inp,$field,$fieldName)
    {
        if(trim($field)!=='')
        {
            $inp=$field;
            $this->log(2,"[".__METHOD__."] ".$fieldName." INPUT NAME => ".$this->loginInp);
            return true;
        }
        die('SETUP INPUT FORM NAME => '.$fieldName);
    }
    private function isEmpty()
    {
        $this->log(0,"[".__METHOD__."]");
        if(trim($this->login)==='')
        {
            $this->err='Insert login<br/>';
        }
        if(trim($this->pass)==='')
        {
            $this->err.='Insert password<br/>';
        }
    }
    private function checkInDb()
    {
        $this->log(0,"[".__METHOD__."]");
        if($this->err!=='') { return false; }
        $this->login=mysqli_real_escape_string($this->getDbLink(),$this->login);
        $this->pass=mysqli_real_escape_string($this->getDbLink(),$this->pass);
        $userData= mysqli_fetch_assoc($this->query("select `ID`,`NAZWA`,`HASLO`,`WSK_U`,`WSK_V` FROM `PERS` WHERE `NAZWA`='".$this->login."' AND `HASLO`='".md5($this->pass)."'"));
        if(is_null($userData))
        {
            $this->err.='User not found or bad password<br/>';
            $this->log(0,"[".__METHOD__."] USER NOT FOUND :\r\nlogin => ".$this->login."\r\npass => ".md5($this->pass));
            return false;
        }
        if(intval($userData['WSK_U'])!==0)
        {
            $this->err.='User deleted<br/>';
            $this->log(0,"[".__METHOD__."] USER DELETED :\r\nlogin => ".$this->login."\r\npass => ".md5($this->pass));
            return false;
        }
        if(intval($userData['WSK_V'])!==1)
        {
            $this->err.='User disabled<br/>';
            $this->log(0,"[".__METHOD__."] USER DISABLED :\r\nlogin => ".$this->login."\r\npass => ".md5($this->pass));
            return false;
        }
        $this->userId=$userData['ID'];
        $this->logMulti(0,$userData,__METHOD__);
    }
    private function getUserPerm()
    {
        $this->log(0,"[".__METHOD__."]");
        if($this->err!=='') { return false; }
    }
    public function getUserData($field)
    {
        $this->log(0,"[".__METHOD__."] RETURN FIELD => ".$field);
        return $this->$field;
    }
    public function getErr()
    {
        return $this->err;
    }
    private function returnStatus()
    {
        if($this->err!=='')
        {
            return false;
        }
        return true;
    }
    public function __destruct()
    {
        
    }
}
