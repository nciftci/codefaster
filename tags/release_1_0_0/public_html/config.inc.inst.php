<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) @ob_start("ob_gzhandler"); 
else @ob_start();
define("DB_USR", "INST_USR");
define("DB_PWD", "INST_PWD");
define("DB_NAME", "INST_NAME");
define("DB_HOST", "INST_HOST");

// define your database informations
define("DB_PREFIX", "tbprefix_");

// define where you index.php URL is, usually is http://www.yourdomain.com/
// always use ENDING SLASH
define("CONF_INDEX_URL", "INST_URL");

$license= "INST_KEY";

@include_once("defines.inc.php");
@include_once(INDEX_PATH."tmp/extraconfig.inc.php");
?>