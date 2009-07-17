<?php 
define("CONF_META_KEYWORDS","meta tags here, meta tags here");
define("CONF_META_DESCRIPTION","Description here, by metatags");
define("EMAIL_INREG","yourmail@domain.com");
define("EMAIL_NAME","Site Name");
// 2|LANG_CONF_D_YES|0|LANG_CONF_D_NO
define("ERROR_DEBUG","2");
define("SESSION_ID","SID");
// 1|LANG_CONF_D_AUTH_TYPE_BASE64|2|LANG_CONF_D_AUTH_TYPE_SESSION
define("AUTH_TYPE","2");
define("USER","demo");
define("PASSWORD","demo");
define("CONF_SITE_NAME","Site Demo");
define("MAX_FILE_SIZE","409600");
define("TIMEOUTINMILLISECONDS","10000");
// 1|LANG_CONF_D_NO|0|LANG_CONF_D_YES
define("MULTILANGUAGE","1");
define("NONSEO","0");
// 1|LANG_CONF_D_YES|0|LANG_CONF_D_NO
define("IMAGE_NEEDED","1");
// Y-m-d|LANG_CONF_YYYY-MM-DD|y-n-d|LANG_CONF_YY-M-DD|y-m-d|LANG_CONF_YY-MM-DD|n/d/y|LANG_CONF_M/DD/YY|d F Y|LANG_CONF_DD_MONTH_YYYY|d M y|LANG_CONF_DD_MON_YY|M d,Y|LANG_CONF_MON_DD_COMMA_YYYY|d-M-y|LANG_CONF_DD-MON-YY|dMy|LANG_CONF_DDMONYY
define("DATEFORMAT","d F Y");
// 1|LANG_CONF_D_YES|0|LANG_CONF_D_NO
define("MULTILAYER_LEFTMENU","1");
define("MULTIADDRESS","Billing Support|billing@yourdomain.com#Techical  Support|support@yourdomain.com#Sales Department|sales@yourdomain.com");


 // if Error Reporting Disable, then set to not report any errors on screen
 if (ERROR_DEBUG==0 || ERROR_DEBUG==2)

 error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

// Disable magic_quotes_runtime

set_magic_quotes_runtime(0);
header("Content-Type: text/html; charset=".CONF_CHARSET);

?>