<?php
define("VERSION","1.2.0");
// define where you root is, usually is ... your-path/program-name/
// always use ENDING SLASH
if (!strstr(PHP_OS, 'WIN'))
    define("SEPARATOR", "/");
else
    define("SEPARATOR", "\\");
define("INDEX_PATH", dirname(__FILE__).SEPARATOR);

// define your language file, ex: en; ro; de;
$LANG = "en";

define("INDEX_URL","");
define("DEFAULT_PATH",dirname(__FILE__)."/");
define("TEMPLATE_PATH",DEFAULT_PATH."programtemplates/");
define("INCLUDE_PATH",DEFAULT_PATH."include/");
define("XML_PATH",DEFAULT_PATH."schema/");
define("EMAIL_CONTACT","support@grafxsoftware.com");

// copy PATHS for generating.
define("TMP_PATH",INDEX_PATH."tmp/");
define("GEN_ADMIN_PATH",INDEX_PATH."public_html/admintool/");
define("GEN_ADMIN_PRGTEMPLATES_PATH",INDEX_PATH."public_html/admintool/programtemplates/");

define("GEN_USER_PATH",INDEX_PATH."public_html/");
define("GEN_USER_PRGTEMPLATES_PATH",INDEX_PATH."public_html/programtemplates/");

define("GEN_INCLUDE_PATH",INDEX_PATH."public_html/include/");
define("GEN_LANGUAGE_PATH",INDEX_PATH."public_html/language/");
define("GEN_USER_PATH",INDEX_PATH."public_html/");
define("GEN_USER_PRGTEMPLATES_PATH",INDEX_PATH."public_html/programtemplates/");

error_reporting (E_ALL & ~E_NOTICE);
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
?>