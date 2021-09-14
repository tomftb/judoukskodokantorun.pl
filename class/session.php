<?php
class session extends logToFile
{
    private $IDM='';
    private $ID='';
    private $IDW='';
    private $APP_URL='';  
    private $noErr=true;
    
    public function __construct()
    {
        parent::__construct();
    }
    public function checkSession($IDM='')
    {   
        $this->log(0,__METHOD__);
        $this->IDM=$IDM;
        /*
         * SET DEFAULT
         */
        $this->noErr=true;
        self::checkSessionData('login');
        self::checkSessionData('id_user');
        self::checkSessionData('expire');
        self::checkSessionTimeOut();
        self::destroySessionData();
        //self::redirectToLoginPage();
        $this->log(0,__METHOD__." NO ERROR STATUS => ".$this->noErr);
        return $this->noErr;
    }
    public function checkInsideSession($ID,$IDM,$IDW,$APP_URL)
    {
        $this->IDM=$IDM;
        $this->ID=$ID;
        $this->IDW=$IDW;
        $this->APP_URL=$APP_URL;
        self::checkSessionData('login');
        self::checkSessionData('expire');
        self::checkSessionData('id_user');
        if(!$this->noErr)
        {
            echo "<center><p class=\"P_MAIN_NG\">Musisz się zalogować, aby korzystać z tej części serwisu !</p>"; // CSS nie ładuje
            echo '<a href="'.$APP_URL.'/logowanie.php?ID='.$ID.'&IDM='.$IDM.'&IDW='.$IDW.'" class="A_BACK_MAIN"><span class="S_MAIN_NG">[</span>Zaloguj się ponownie<span class="S_MAIN_NG">]</span></a></center>';   
            die();
        }
        self::checkSessionTimeOut();
        self::destroySessionData();
        self::showSessionTimeOut();
    }
    private function checkSessionData($data)
    {
        $this->log(0,__METHOD__);
        if(!$this->noErr) { return ''; }

        if(!isset($_SESSION[$data]))
        {
            $this->log(0,__METHOD__." NOT ISSET => ".$data.", SET NO ERROR FALSE");
            $this->noErr=false;  
        }
    }
    private function checkSessionTimeOut()
    {
        $this->log(0,__METHOD__);
        if(!$this->noErr) { return ''; }
        if (time() > $_SESSION['expire'])
	{
            $this->noErr=false;       
	}
        else
        {
            $this->log(0,__METHOD__." UPDATE EXPIRE");
            /* UPDATE SESSION */
            $_SESSION['expire'] = time() + 900; // zwiększono czas o 30 minut (30 * 60 = 1800)
        }
    }
    private function destroySessionData()
    {
        $this->log(0,__METHOD__);
        if(!$this->noErr)  
        {
            $this->log(0,__METHOD__." ERROR EXIST, REMOVE SESSION DATA");
            foreach ($_SESSION as $key => $value)
            {
                UNSET($_SESSION[$key]);
            }
            //session_destroy();   
            //session_start();   
        }
    }
    public function redirectTo()
    {
        if(!$this->noErr) 
        {
            echo '<script>setTimeout(function(){location.href="../logowanie.php?IDM='.$this->IDM.'"} , 5); </script>';
        }
    }
    private function showSessionTimeOut()
    {
        if(!$this->noErr)
        {
            echo '<center><p class="P_MAIN_NG">Sesja wygasła, musisz się ponownie zalogować !</p>'; // CSS nie ładuje
            echo '<a href="'.$this->APP_URL.'/logowanie.php?ID='.$this->ID.'&IDM='.$this->IDM.'&IDW='.$this->IDW.'" class="A_BACK_MAIN"><span class="S_MAIN_NG">[</span>Zaloguj się ponownie<span class="S_MAIN_NG">]</span></a></center>';    
            die();
        }
   }
    public function setSession($login,$id_user,$expire,$uid,$perm)
    {
        $_SESSION['login']=$login;
        $_SESSION['id_user']=$id_user;
        $_SESSION['expire']=$expire;
        $_SESSION['uid']=$uid;
        $_SESSION['perm']=$perm;
    }
    public function __destruct()
    {
        
    }
}
