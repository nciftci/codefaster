<?php
/*
######################  CODE GENERATOR  ####################
############################################################
Revision		$Revision: 11674 $
Author			$Author: lvalics $
Created 03/01/03        $Date: 2009-03-22 13:39:39 +0200 (D, 22 mar. 2009) $
Writed by               GraFX (webmaster@grafxsoftware.com)
Scripts Home:           http://www.grafxsoftware.com
############################################################
*/

if (@substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) @ob_start("ob_gzhandler"); 
else @ob_start();
//define("DB_USR", "root");//INST_USR
//define("DB_PWD", "");//INST_PWD
//define("DB_NAME", "test");//INST_NAME
//define("DB_HOST", "localhost");//INST_HOST

// define your database informations
//define("DB_PREFIX", "");

// define where you index.php URL is, usually is http://www.yourdomain.com/
// always use ENDING SLASH
//define("CONF_INDEX_URL", "http://localhost/Company-Website-Builder/trunk/");//INST_URL

@include_once("defines.inc.php");
@include_once(INDEX_PATH."tmp/extraconfig.inc.php");
?>