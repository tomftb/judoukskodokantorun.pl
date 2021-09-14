<?php   
class database extends logToFile
{
    private $dbParm=array(
        'user'=>'',
        'pass'=>'',
        'db'=>'',
        'host'=>''
    );
    private $lastId=0;
    private $dbLink;
    
    public function __construct()
    {
        parent::__construct();
    }
    public function connect($u,$p,$d,$h)
    {
        $this->log(0,"[".__METHOD__."]");
        $this->dbParm['user']=$u;
        $this->dbParm['pass']=$p;
        $this->dbParm['db']=$d;
        $this->dbParm['host']=$h;
        /*
        $host = "localhost"; //host bazy danych
        $user = "nadrukireklamowe4"; //nazwa użytkownika bazy danych
        $pass = "z7Z1h!&BzY#w"; //hasło użytkownika bazy danych
        $baza = "nadrukireklamowe4"; //baza danych
         */
        
        $this->dbLink = new mysqli($this->dbParm['host'], $this->dbParm['user'], $this->dbParm['pass'],$this->dbParm['db']);
        self::setCharset('utf8','utf8_polish_ci');
    }
    public function loadDb()
    {
        $this->log(0,"[".__METHOD__."]");
        /*
         * GET CONST FROM .cfg/konfguracja
         */
        self::checkDbConst();
        $this->dbLink = new mysqli(dbHost, dbUser, dbPass,database);
        self::setCharset('utf8','utf8_polish_ci');
    }
    private function checkDbConst()
    {
        $this->log(2,"[".__METHOD__."]");
        if(!defined('dbHost') || !defined('dbUser') || !defined('dbPass') || !defined('database'))
        {
            $this->log(0,"DATABASE CONSTANT NOT DEFINED !");
            $this->log(2,"\r\ndbHost => ".dbHost."\r\ndatabase => ".database."\r\ndbUser =>".dbUser."\r\ndbPass =>".dbPass);
            die('APPLICATION DATABASE ERROR');
        }
    }
    private function setCharset($name,$collate)
    {
        $this->log(1,"[".__METHOD__."] NAMES => $name, COLLATE => $collate");
        mysqli_set_charset ($this->dbLink,"SET NAMES `".$name."` COLLATE `".$collate."`");
        mysqli_query($this->dbLink,"SET NAMES `".$name."` COLLATE `".$collate."`");
    }
    public function query($query)
    {
        $this->log(0,"[".__METHOD__."] ".$query);
        mysqli_query($this->dbLink,"SET AUTOCOMMIT=0");
        mysqli_query($this->dbLink,"START TRANSACTION");
        $q=mysqli_query($this->dbLink,$query);
        $this->lastId=mysqli_insert_id($this->dbLink);
        if($q===false)
        {
            $this->log(0,"[".__METHOD__."] SQL ERROR => ".mysqli_error($this->dbLink));
            mysqli_query($this->dbLink,"ROLLBACK");
            die("<p class=\"P_SQL_ERR\">Błąd zapytania SQL !</p>");
        }
        mysqli_query($this->dbLink,"COMMIT");
        
        return $q;
    }
    public function last()
    {
        return $this->lastId;
    }
    public function insDbLog($IDM,$DATA)
    {
        self::query("INSERT INTO DZIEN (ID_PERS,MODUL,ZADANIE,DAT_UTW) VALUES ('".$_SESSION["id_user"]."','".$IDM."','".$DATA."',NOW())");
        $this->log(0,$DATA);
    }
    public function getDbLink()
    {
        return $this->dbLink;
    }
    public function __destruct()
    {
        
    }
}
