<?php
@include ("en.extra.inc.php");
@include ("en.pagination.inc.php");
@include ("en.upload.inc.php");

/****** DEFAULT *****/
define("LANG_ADMIN","Administrator Panel");
define("LANG_ADMIN_HOME","Admin Home");
define("LANG_ADMIN_SUBMIT","Submit");
define("LANG_ADMIN_CONFIG_PAGE","Configuration");
define("LANG_ADMIN_LOG_OFF","Logout");
define("LANG_ADMIN_MODIFY","Modify");
define("LANG_ADMIN_DELETE","Delete");
define("LANG_ADMIN_ERROR_OCCURED","Error occured on file. Please contact your software provider.");
define("LANG_ADMIN_REQUIRED","Required");
define("LANG_ADMIN_NODATA","The table is empty. No data are returned.");
define("LANG_ADMIN_CONFIRMDELETE","Are you sure you want to delete?");
define("LANG_ADMIN_ACTIVATE","Activate");
define("LANG_ADMIN_DEACTIVATE","Deactivate");
define("LANG_ADMIN_ADD","Add");
define("LANG_ADMIN_LIST","List");
define("LANG_ADMIN_UPLOAD","Upload "); 
define("LANG_ADMIN_CHECK_TO_EDIT","Check this if you wish to edit the picture after upload");
define("LANG_ADMIN_BIGIMAGE","Large image (scroll if you need)");
define("LANG_ADMIN_IMAGEUPLOADEDSUCCESS","Picture uploaded successfully!");
define("LANG_ADMIN_IMAGEUPLOADEDSUCCESS_HELP","Select a part of the image. On the right, you will see the preview of the selected part.");
define("LANG_ADMIN_THUMBIMAGE_CREATE","Create Thumbnail");
define("LANG_ADMIN_THUMBIMAGE_PREVIEW","Thumbnail Preview");
define("LANG_ADMIN_IMAGE_SAVE","Save final picture");
define("LANG_ADMIN_THUMBIMAGE","Create Thumbnail");

/****** AUTENTICATION *****/
define("LANG_ADMIN_AUTHENTICATION","Authentification");
define("LANG_ADMIN_LOGIN","Username");
define("LANG_ADMIN_PASSWORD","Password");
define("LANG_ADMIN_FAILED_LOGIN","Invalid User/Password");
define("LANG_ADMIN_PASSWORD_RECOVERY","Password recovery");
define("LANG_ADMIN_PASSWORD_SENTSUCCESSFULLY","Password was sent. Please check your email.");
define("LANG_ADMIN_GOBACK","go back");
define("LANG_ADMIN_ERROR_INVALID_ID","Invalid ID passed, please review the code.");

/****** JQUERY VALIDATION *****/
define("LANG_ADMIN_VALIDATION_MIN", "Min error message"); 
define("LANG_ADMIN_VALIDATION_MAX", "Max error message");
define("LANG_ADMIN_VALIDATION_MINLENGTH", "Min length  error message"); 
define("LANG_ADMIN_VALIDATION_MAXLENGTH", "Max length error message");
define("LANG_ADMIN_VALIDATION_EMAIL", "Email error message");
define("LANG_ADMIN_VALIDATION_REQUIRED", "Required error message");
define("LANG_ADMIN_VALIDATION_NUMBER", "Required only numbers");
define("LANG_ADMIN_VALIDATION_DATE", "Date is not correct ex: 27/12/2009");

/****** CONFIG *****/
define("LANG_CONF_ADMIN_CONFIGPAGE1", "Meta Tag variables and general site settings");
define("LANG_CONF_ADMIN_CONFIGPAGE2", "Developer side variables");
define("LANG_CONF_ADMIN_CONFIGPAGE3", "Home page variables");
define("LANG_CONF_ADMIN_CONFIGPAGE4", "Product, vendor and category variables");
define("LANG_CONF_ADMIN_CONFIGPAGE5", "News, FAQ and search variables");
define("LANG_CONF_ADMIN_CONFIGPAGE6", "Image width variables");
define("LANG_CONF_ERROR_NOMOD","YOU HAVE NO PERMISSION TO WRITE INTO /tmp/extraconfig.inc.php file!! Please CHMOD 0777 this file using your FTP client or a Control Panel, otherwise changes will not be applied!!!");
define("LANG_CONF_ERROR_SECURITY","IMPORTANT SECURITY ISSUE! <br />After making all the changes in the configuration page please CHMOD the /tmp/extraconfig.inc.php file 0664, to avoid access to this file from outside.");
define("LANG_CONF_META_KEYWORDS","Meta Keywords");
define("LANG_CONF_META_DESCRIPTION","Meta Description");
define("LANG_CONF_EMAIL_INREG","Admin email");
define("LANG_CONF_EMAIL_NAME","Admin name");
define("LANG_CONF_ERROR_DEBUG","Debug");
define("LANG_CONF_SESSION_ID","Session name");
define("LANG_CONF_AUTH_TYPE","Authentication type");
define("LANG_CONF_USER","Admin username");
define("LANG_CONF_PASSWORD","Admin password");
define("LANG_CONF_SITE_NAME","WebSite Name");
define("LANG_CONF_MAX_FILE_SIZE","Maximum upload file size");
define("LANG_CONF_TIMEOUTINMILLISECONDS","Time in miliseconds");
define("LANG_CONF_MULTILANGUAGE","Multilanguage site");
define("LANG_CONF_NONSEO","Search Engine Ready");
define("LANG_CONF_IMAGE_NEEDED","Captcha Image");
define("LANG_CONF_DATEFORMAT","Date format");
define("LANG_CONF_MULTILAYER_LEFTMENU","Multilayer menu");
define("LANG_CONF_MULTIADDRESS","Addresses");
define("LANG_CONF_TITLE","Configuration");
define("LANG_CONF_DATEFORMAT","Select the date format");
define("LANG_CONF_YYYY-MM-DD","YYYY-MM-DD");
define("LANG_CONF_YY-M-DD","YY-M-DD");
define("LANG_CONF_YY-MM-DD","YY-MM-DD");
define("LANG_CONF_M/DD/YY","M/DD/YY");
define("LANG_CONF_DD_MONTH_YYYY","DD MONTH YYYY");
define("LANG_CONF_DD_MON_YY","DD MON YY");
define("LANG_CONF_MON_DD_COMMA_YYYY","MON DD, YYYY");
define("LANG_CONF_DD-MON-YY","DD-MON-YY");
define("LANG_CONF_DDMONYY","DDMONYY");
define("LANG_CONF_D_NO","No");
define("LANG_CONF_D_YES","Yes");//  
define("LANG_CONF_D_AUTH_TYPE_BASE64","Base 64");
define("LANG_CONF_D_AUTH_TYPE_SESSION","Session");
define("LANG_CONF_EXAMPLE","Example");
define("LANG_CONF_NAME","Name");
define("LANG_CONF_VALUE","Value");

?>