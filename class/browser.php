<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of browser
 *
 * @author tborczynski
 */
class browser {
    
    private $browser='';
    
    public function __construct()
    {
        self::checkBrowser();
    }
    
    private function checkBrowser()
    {
        

    $x = $_SERVER['HTTP_USER_AGENT'];

    if(substr_count($x,"pera")!=0)
       { $this->browser = "Opera";  }
    else if(substr_count($x,"MSIE")!=0)
       { $this->browser = "Internet Explorer"; }
    else if(substr_count($x,"etscape6")!=0)
       { $this->browser = "Netscape 6<"; }
    else if(substr_count($x,"rv:1.")!=0)
       { $this->browser = "Mozilla 1.x"; }
    else if(substr_count($x,"4.7")!=0)
       { $this->browser = "Netscape 4.7x"; }
    else if(substr_count($x,"Firefox")!=0)
       { $this->browser = "Firefox"; }
    else if(substr_count($x,"Trident/7.0")!=0)
       { $this->browser = "Internet Explorer 11";  }
    else if(substr_count($x,"Chrome")!=0)
       { $this->browser = "Chrome";  }
    else
       { $this->browser = "inna"; }


    }
    public function getBrowserType()
    {
        return $this->browser;
    }
}
