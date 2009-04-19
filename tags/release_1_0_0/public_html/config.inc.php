<?php
if (@substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) @ob_start("ob_gzhandler"); 
else @ob_start();

$configType = (isset($_SERVER['SERVER_NAME']) & in_array($_SERVER['SERVER_NAME'], array('127.0.0.1', 'localhost'))) ? 'development' : 'production';
    define('PROJECT_STAGE', $configType);

    switch (PROJECT_STAGE) {
        case 'production':
            error_reporting(0);

            define("DB_HOST",        "INST_HOST");
            define("DB_USR",         "INST_USER");
            define("DB_PWD",    	 "INST_PASS");
            define("DB_NAME",        "INST_DB");
			define("CONF_INDEX_URL", "INST_URL");
            
            break;

        case 'development':
        default:
            error_reporting(E_ALL ^ E_NOTICE);

			define("DB_USR", "root"); //
			define("DB_PWD", ""); //
			define("DB_NAME", "codefaster");
			define("DB_HOST", "localhost");
			define("CONF_INDEX_URL", "http://localhost/PHP-JAVA-Class-code-generator/trunk/public_html/");

        break;
    } 
	
// define your database informations
define("DB_PREFIX", "");

$license= "INST_KEY";

@include_once("defines.inc.php");
@include_once(INDEX_PATH."tmp/extraconfig.inc.php");
?>	