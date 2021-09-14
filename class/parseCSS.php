<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of parseCSS
 *
 * @author tborczynski
 */

require_once (filter_input(INPUT_SERVER,"DOCUMENT_ROOT")."/class/logToFile.php");

class parseCSS extends logToFile
{
    private $fWeight="font-weight:normal;";
    private $fStyle="font-style: normal;";
    private $tDecoration="text-decoration: none;";
    private $CSS=array();
    private $ISSETCSS=array();
    private $defaultCSS=array();
    
    public function __construct()
    {
        parent::__construct();
    }
    public function setCSS($CSS)
    {
        $this->log(0,"[".__METHOD__."] CSS => ".$CSS);
        self::setDefault();
        $tmpCSS=explode('|', $CSS);
        /*
         * 0 => font weight => normal,bold
         * 1 => font style
         * 2 => text decoration
         */
        self::setFontWeight($tmpCSS[0]);
        self::setTextDecoration($tmpCSS[1]);
        self::setFontStyle($tmpCSS[2]);
    }
    private function setFontWeight($c)
    {
        if(intval($c)===1)
        {
            $this->log(0,"[".__METHOD__."] SET => font-weight:bold;");
            $this->fWeight='font-weight:bold;';
        }
    }
    private function setTextDecoration($c)
    {
        if(intval($c)===1)
        {
            $this->log(0,"[".__METHOD__."] SET => text-decoration: underline;");
            $this->tDecoration='text-decoration: underline;';
        }
    }
    private function setFontStyle($c)
    {
        if(intval($c)===1)
        {
            $this->log(0,"[".__METHOD__."] SET => font-style:italic;");
            $this->fStyle='font-style:italic;';
        }
    }
    public function getFontWeight()
    {
        return ($this->fWeight);
    }
    public function getTextDecoration()
    {
        return ($this->tDecoration);
    }
    public function getFontStyle()
    {
        return ($this->fStyle);
    }
    public function getAllCSS()
    {
        return ($this->fWeight.$this->tDecoration.$this->fStyle);
    }
    private function setDefault()
    {
        $this->fWeight="font-weight:normal;";
        $this->fStyle="font-style: normal;";
        $this->tDecoration="text-decoration: none;";
    }
    public function setRecStat($v)
    {
        if (intval($v)===1)
        {
            return ("<span class=\"S_STAT\">TAK</span>");     
        }
        return ("<span class=\"S_STAT_N\">NIE</span>");
    }
    public function createSelectArray($post,$default,$data,$name)
    {
        $select='<select name="'.$name.'" class="SELECT">';
	$select.='<optgroup label="Aktualny :" class="OPTGROUP">';
        if($post!='')
        {
            $tmp=explode('|',$post);          
            $select.='<option value="'.$post.'" class="OPTION">'.$tmp[1].' (ustawiony)</option>';
        }
        else
        {
            $select.='<option value="'.$default['WSKD'].'|'.$default['NAZWA'].'|'.$default['WART'].'" class="OPTION">'.$default['NAZWA'].' (domyślny)</option>';
        }
        $select.='</optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
        foreach($data as $value)
        {
            $select.='<option value="'.implode("|",$value).'" class="OPTION">'.$value['NAZWA'].'</option>';
        }
	$select.='</optgroup></select>';
	return $select;
    }
    public function createSelect($post,$default,$data,$name)
    {
        $select='<select name="'.$name.'" class="SELECT">';
	$select.='<optgroup label="Aktualny :" class="OPTGROUP">';
        if($post!='')
        {
            $select.='<option value="'.$post.'" class="OPTION">'.$post.' (ustawiony)</option>';
        }
        else
        {
            
            $select.='<option value="'.$default.'" class="OPTION">'.$default.' (domyślny)</option>';
        }
        $select.='</optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
        foreach($data as $value)
        {
            
            $select.='<option value="'.$value.'" class="OPTION">'.$value.'</option>';
        }
	$select.='</optgroup></select>';
	return $select;
    }
    public function createColorSelect($post,$default,$data,$name)
    {
        $txt='#FFFFFF';
        $select='<select name="'.$name.'" class="SELECT">';
	$select.='<optgroup label="Aktualny :" class="OPTGROUP">';
        if($post!='')
        {
            $tmp=explode('|',$post);
            self::setSelectBackground($txt,$tmp[2]);
            $select.='<option value="'.$post.'" style="color:'.$txt.'; background: none repeat scroll 0%  0% '.$tmp[2].';" class="OPTION">'.$tmp[1].' (ustawiony)</option>';
        }
        else
        {
            self::setSelectBackground($txt,$default['WART']);
            
            $select.='<option value="'.$default['WSKD'].'|'.$default['NAZWA'].'|'.$default['WART'].'" style="color:'.$txt.'; background: none repeat scroll 0%  0% '.$default['WART'].';" class="OPTION">'.$default['NAZWA'].' (domyślny)</option>';
        }
        $select.='</optgroup><optgroup label="Dostępne :" class="OPTGROUP">';
        foreach($data as $value)
        {
            self::setSelectBackground($txt,$value['HEX']);
            $select.='<option value="'.implode("|",$value).'" style="color:'.$txt.'; background: none repeat scroll 0%  0% '.$value['HEX'].';" class="OPTION">'.$value['NAZWA'].'</option>';           
        }
	$select.='</optgroup></select>';
	return $select;
    }
    public function createCheckBox($post,$default,$data,$name)
    {
        /*
         * post is array
         * name is array
         */
        self::getCSS($post,"CSS\|".$name,$this->CSS);
        self::getCSS($post,"CSS\-",$this->ISSETCSS);
        self::getDefaultCSS($default);
        //$this->logMultidimensional(0,$post,__METHOD__."::post",0);
        //$this->logMultidimensional(0,$default,__METHOD__."::default",0);
        
        $this->logMultidimensional(0,$this->CSS,__METHOD__."::this->CSS",0);
        $this->logMultidimensional(0,$this->ISSETCSS,__METHOD__."::this->ISSETCSS",0);
        
        $checked=''; 
	//$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(ustawiony)</span>';
        //$domyslny_kom='<span style="color:#A4A4A4; font-size:12px;font-weight:bold">(domyślny)</span>';
        $box='';
        foreach($data as $value)
        {
            //$this->logMultidimensional(0,$value,__METHOD__,0);
            self::checkWithPostCSS($checked,$name,$value['ID']);
            self::checkWithDefaultCSS($checked,$value['WART']);
            $box.='<input type="checkbox" name="CSS|'.$name.'|'.$value['ID'].'" value="'.$value['ID'].'|'.$value['WART'].'" class="CSS_CHBOX" '.$checked.' /><span class="S_CSS">'.$value['NAZWA'].'</span><br/>';
        }
        return $box;
    }
    private function getCSS($post,$mask,&$data)
    {
         $this->log(0," MASK => ".$mask);
        if(!isset($post)) { return ''; }
        foreach($post as $k => $v)
        {
            //$this->log(0,"preg match $k => ".preg_match('/^'.$mask.'(.)*/i',$k));
            if(preg_match("/^".$mask."(.)*/i",$k)===1)
            {
                $this->log(0,"[".__METHOD__."] FOUND => ".$k);
                $data[$k]=$v;
            }
        }
    }
    private function getDefaultCSS($default)
    {
        $defaultCSSname=explode('|',$default['WART']);
        $defaultCSS=explode('|',$default['WSKD']);
        foreach($defaultCSS as $k => $v)
        {
            if(intval($v)===1)
            {
                array_push($this->defaultCSS,$defaultCSSname[$k]);
            }
        }
        $this->logMultidimensional(0,$this->defaultCSS,__METHOD__,0);
    } 
    private function checkWithDefaultCSS(&$checked,$value)
    {
        $this->log(0,"[".__METHOD__."]");
        if(array_key_exists('CSS-DEFAULT',$this->ISSETCSS))
        {
            $this->log(0,"[".__METHOD__."] ARRAU KEY CSS-DEFAULT EXIST ");
            return '';     
        }
        if(in_array($value, $this->defaultCSS))
        {
            $checked='checked="checked"'; 
        }
        else
        {
            $checked='';
        }
    }
    private function checkWithPostCSS(&$checked,$name,$id)
    {
        $this->log(0,"[".__METHOD__."]");
        if(!array_key_exists('CSS-DEFAULT',$this->ISSETCSS))
        {
            $this->log(0,"[".__METHOD__."] ARRAU KEY CSS-DEFAULT NOT EXIST ");
            return '';     
        }
        if(array_key_exists("CSS|".$name."|".$id, $this->CSS))
        {
            $this->log(0,"[".__METHOD__."] ARRAY KEY EXIST => CSS|".$name."|".$id);
            $checked='checked="checked"'; 
        }
        else
        {
            $this->log(0,"[".__METHOD__."] ARRAY KEY NOT EXIST => CSS|".$name."|".$id);
            $checked='';
        }
    }
    private function setSelectBackground(&$txt,$color)
    {
        if($color==='#000000')
        {
            $txt='#FFFFFF';
        }
        else
        {
            $txt='#000000';
        }
    }
    public function __destruct()
    {
        
    }
}
