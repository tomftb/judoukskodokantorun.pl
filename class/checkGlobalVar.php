<?php
if(!isset($log))
{
    $DR=filter_input(INPUT_SERVER,"DOCUMENT_ROOT");	
    require_once($DR."/class/logToFile.php");
}
class checkGlobalVar extends logToFile {
    private $exist=false;
    function __construct()
    {
        parent::__construct();
        $this->log(0,"[".__METHOD__."] LOAD CLASS");
    }
    public function checkVar(&$var,$key)
    {
        $this->log(0,"[".__METHOD__."] VAR => ".$var." KEY => ".$key);
        $this->exist=false;
        if(isset($var))
        {
            $this->log(0,"[".__METHOD__."] already defined");
            $this->exist=true;
            return $var;
        }
        self::checkGet($var,$key);
        self::checkPost($var,$key);
        self::checkSession($var,$key);
        if($this->exist===false)
        {
            $this->log(0,"[".__METHOD__."] $key not defined, return empty string");
            return '';
        }
        else
        {
            $this->log(0,"[".__METHOD__."] return :");
            $this->logMultidimensional(0,$var,__METHOD__." $key ",0);        
            return $var;
        }
    }
    private function checkPost(&$var,$key)
    {
        if($this->exist===true) {return '';}
        self::checkOnlyPost($var,$key);
    }
    private function checkGet(&$var,$key)
    {
        if($this->exist===true) {return '';}
        self::checkOnlyGet($var,$key);
        
    }
    private function checkSession(&$var,$key)
    {
        if($this->exist===true) {return '';}
        self::checkOnlySession($var,$key);
    }
    public function checkOnlySession(&$var,$key)
    {
        if(isset($_SESSION[$key]))
        {
            $this->logMultidimensional(0,$_SESSION[$key],__METHOD__." $key ",0);        
            $var=$_SESSION[$key];
        }
        else
        {
            $this->exist=true;
            $this->log(0,"[".__METHOD__."] ${key} not isset"); 
            $var='';
        }
    }
    public function checkOnlyPost(&$var,$key)
    {
        if(array_key_exists($key, $_POST))
        {
            if(is_array($_POST[$key]) || is_object($_POST[$key]) || is_resource($_POST[$key]))
            {
                $this->log(2,"[".__METHOD__."] $key is_array or is_object or is_resource"); 
                $this->exist=true;
                $var=$_POST[$key];
            }
            else
            {
                $post=filter_input(INPUT_POST,$key);
                if(!isset($post) || $post==='')
                {
                    $this->log(0,"[".__METHOD__."] ${key} not isset"); 
                    $var='';   
                }
                else
                {
                    $this->exist=true;
                    $this->logMultidimensional(0,$post,__METHOD__." $key ",0);        
                    $var=$post;
                }
            } 
        }
        else
        {
            $this->log(0,"[".__METHOD__."] ${key} not exist"); 
            $var='';
        }
    }
    public function checkOnlyGet(&$var,$key)
    {
        $get=filter_input(INPUT_GET,$key);
        if(!isset($get) || $get==='')
        {
            $this->log(0,"[".__METHOD__."] ${key} not isset"); 
            $var='';        
        }
        else
        {
            $this->exist=true;
            $this->logMultidimensional(0,$get,__METHOD__." $key ",0);        
            $var=$get;
        }
    }
    public function checkServer(&$var,$key)
    {
        if(!isset($var))
        {
            $var=filter_input(INPUT_SERVER,$key);
            $this->log(0,"[".__METHOD__."] ".$key." not defined, set => ".$var);
        }
        else
        {
            $this->log(0,"[".__METHOD__."]  ".$key." already defined => ".$var);   
        }
    }
    public function clearExist()
    {
        $this->exist=false;
    }
    function __destruct()
    {
        $this->log(0,"[".__METHOD__."]");
    
        parent::__destruct();
    }
}
       