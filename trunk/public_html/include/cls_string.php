<?php
/*
# Copyright (c) 1998-2005 by GraFX Software Solutions.
# Copyright (c) www.grafxsoftware.com
# All rights reserved
# $Id: cls_string.php 8391 2008-01-17 20:57:22Z zelteto $
*/

class String
{
	public $intvars = array();

	public function __construct()
	{
	}

	// change the entry string in MYSQL
    public function setIntVars($name)
	{
    	$this->intvars[] = $name;
    }

	public function encode($passwd)
    {
    	return md5(input_text($passwd));
    }

   	public function generatePasswd()
    {
    	return substr(md5(time()),0,10);;
    }

	public function js_ready($text)
	{
		$text=str_replace("\n", "", $text);
		$text=str_replace("\r", "", $text);
    	return addslashes($text);
    }

	public function strips_univ($text)
	{
		if (get_magic_quotes_gpc()) {
    	   return stripslashes($text);
		} else {
    	   return $text;
		}
    }

    /*-------------------------------------------------------------------------*/
    // Makes incoming info "safe"
    /*-------------------------------------------------------------------------*/
    function parse_all()
    {
		$return            = array();
		$request_variables = array_merge($_GET, $_POST);

    	if(is_array($request_variables)) {
			while( list($k, $v) = each($request_variables) ) {
				 // Unset the globals
                unset($GLOBALS[$k]);

				if( is_array($request_variables[$k]) ) {
					while( list($k2, $v2) = each($request_variables[$k]) ) {
						$return[$k][$this->clean_key($k2)] = $this->clean_value($v2);
					}
				} else {
					$return[$k] = $this->clean_value($v);
				}
			}
		}

		$return['IP_ADDRESS']     = $_SERVER['REMOTE_ADDR'];
		$return['request_method'] = strtolower($_SERVER['REQUEST_METHOD']);
		$return['HTTP_REFERER']   = $_SERVER['HTTP_REFERER'];

		// cleanup
		foreach($this->intvars as $value) {
		   	if(isset($return[$value])) {
				$return[$value] = intval($return[$value]);  	
			} else {
				$return[$value] = 0;
			}
		}

		return $return;
	}

    // to check, not used
	function parse_all_no_check()
    {
    	$return            = array();	
		$request_variables = array_merge($_GET, $_POST);

		if(is_array($request_variables) ) {
			while( list($k, $v) = each($request_variables)) {

            	unset($GLOBALS[$k]);

				if(is_array($request_variables[$k])) {
					while(list($k2, $v2) = each($request_variables[$k])) {
						$return[$k][$this->clean_key($k2) ] = $v2;
					}
				} else {
					$return[$k] = $v;
				}
			}
		}

		$return['IP_ADDRESS']     = $_SERVER['REMOTE_ADDR'];
		$return['request_method'] = strtolower($_SERVER['REQUEST_METHOD']);
		$return['HTTP_REFERER']   = $_SERVER['HTTP_REFERER'];
		return $return;
	}

    /*-------------------------------------------------------------------------*/
    // Key Cleaner - ensures no hackers entry with form elements
    /*-------------------------------------------------------------------------*/

    // to check, not used
	function clean_key($key) {
    	if ($key == "")	{
    		return "";
    	}
    	$key = preg_replace( "/\.\./"           , ""  , $key );
    	$key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
    	$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
    	return $key;
    }

    // to check, not used
	function clean_value($val) {
    	if ($val == "") {
    		return "";
    	}
    	$val = str_replace( "&#032;",      " ",            $val);
    	$val = str_replace( "&",           "&amp;",        $val);
    	$val = str_replace( "<!--",        "&#60;&#33;--", $val);
    	$val = str_replace( "-->",         "--&#62;",      $val);
    	$val = preg_replace( "/<script/i", "&#60;script",  $val);
    	$val = str_replace( ">",           "&gt;",         $val);
    	$val = str_replace( "<",           "&lt;",         $val);
    	$val = str_replace( "\"",          "&quot;",       $val);
        $val = preg_replace( "/\n/",       "<br>",         $val); // Convert literal newlines
    	$val = preg_replace( "/\\\$/",     "&#036;",       $val);
    	$val = preg_replace( "/\r/",       "",             $val); // Remove literal carriage returns
    	$val = str_replace( "!",           "&#33;",        $val);
    	$val = str_replace( "'",           "&#39;",        $val); // IMPORTANT: It helps to increase sql query safety.


		$val = $this->strips_univ($val);
    	// Swop user inputted backslashes
    	$val = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $val );
    	return $val;
    }

	// change back things if needed
	function cleanDescription2($val) {
    	if ($val == "") {
    		return "";
    	}
		$val = str_replace( "<br>"         , ""           , $val );
		$val = str_replace( "&lt;br /&gt;<br>" , "<br />", $val );
    	$val = str_replace( "&amp;"         , "&"           , $val );
    	$val = str_replace( "&#60;&#33;--"  ,"<!--"         , $val ); //-->
    	$val = str_replace( "--&#62;"       ,"-->"          , $val );
		$val = str_replace( "&#60;script"   ,"/<script/i"  , $val );
    	$val = str_replace( "&quot;"        , "\""           , $val );
    	$val = str_replace( "/\r/"        , ""              , $val );
    	$val = str_replace( "&#33;"         , "!"            , $val );
    	$val = str_replace( "&#39;"         , "'"            , $val );
		$val = str_replace( "&nbsp;"         , " "            , $val );
		$val = str_replace( "&amp;nbsp;"      , " "            , $val );
		$val = str_replace( "&gt;"            , ">"          , $val );
    	$val = str_replace( "&lt;"            , "<"          , $val );
		$val = str_replace( "\'"         , "'"           , $val );
    	return $val;
    } // function cleanDescription2

	// Link cleaner for SEO URL
	function CleanLink($link)
	{
		if ($link == "") {
			return "";
		} else {
			$link = str_replace("&amp;#259;","a",$link);
			$link = str_replace("&amp;#351;","s",$link);
			$link = str_replace("&amp;#355;","t",$link);
			$link = str_replace( "&#39;","",$link);
			$link = str_replace( "`","",$link);
			$link = str_replace( "~","",$link);
			$link = str_replace( "!","",$link);
			$link = str_replace( "@","",$link);
			$link = str_replace( "#","",$link);
			$link = str_replace( "$","",$link);
			$link = str_replace( "%","",$link);
			$link = str_replace( "^","",$link);
			$link = str_replace( "&","",$link);
			$link = str_replace( "*","",$link);
			$link = str_replace( "(","",$link);
			$link = str_replace( ")","",$link);
			$link = str_replace( "+","",$link);
			$link = str_replace( "=","",$link);
			$link = str_replace( "|","",$link);
			$link = str_replace( "{","",$link);
			$link = str_replace( "}","",$link);
			$link = str_replace( "[","",$link);
			$link = str_replace( "]","",$link);
			$link = str_replace( "/","",$link);
			$link = str_replace( "?","",$link);
			$link = str_replace( "<","",$link);
			$link = str_replace( ">","",$link);
			$link = str_replace( ",","",$link);
			$link = str_replace( ".","",$link);
			$link = str_replace( "'","",$link);
			$link = str_replace( "\"","",$link);
			$link = str_replace( ";","",$link);
			$link = str_replace( ":","",$link);
			$link = trim($link);
			$link = str_replace( " ","-",$link);
			$link = str_replace( "--","-",$link);
			return $link;
		}
	}// Function CleanLink 2004-02-10 iborbely

	// to check, not used
	
	// change entry string into MYSQL
	// chop spaces from parameters
    function input_text($str)
    {	//filter of spaces
        $str = clean_value($str);
    	$str = str_replace("'","`",$str);
    	$str = ereg_replace("[' ']+"," ",$str);
    	$str = ereg_replace("^ ","",$str);	//at the beginning
    	$str = ereg_replace(" $","",$str);	//at the end
    	$str = str_replace("$","&#036;",$str);
    	$str = addslashes($str);
        $str = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $str );
        $str = nl2br($str);
    	return $str;
    }

	// to check, not used
	//chages html code into a js typed string
    function jsReturn($str)
    {
 		$ar = explode("\n", $str);

		$text = "";
		foreach($ar as $value) {
		   $text .="document.write('".str_replace("'","\'",trim($value))."');\n";
		}
    	return $text;
    }

        /**
         *
         * @param <string> $string
         * @return <string> encoded string 
         */
        function str_hex($string){
		$hex='';
		for ($i=0; $i < strlen($string); $i++){
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}

        /**
         *
         * @param <string> $hex
         * @return <string> return decoded string
         */
	function hex_str($hex){


            $string='';
            for ($i=0; $i < strlen($hex)-1; $i+=2) {
                $string .= chr(hexdec(substr($hex, $i, 2)));
            }

           

            return $string;
	}
}

?>