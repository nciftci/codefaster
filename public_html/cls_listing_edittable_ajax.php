<?php
session_start();


include_once(dirname(__FILE__)."/config.inc.php");
include_once(INCLUDE_PATH . "connection.php");

$stringutil = new String();
$all_url_vars = $stringutil->parse_all();

//error_log(print_r($all_url_vars,true));
if ($all_url_vars['do']=="change"){
	$table=$all_url_vars['table'];
	$idname=$all_url_vars['idname'];
	$idvalue=$all_url_vars['idvalue'];
	$columnname=$all_url_vars['columnname'];
	$value=$all_url_vars['value'];
	$securestring=$all_url_vars['securestring'];

	$securestringserver=md5("#$table#$idname#$idvalue#$columnname#".USER."#".PASSWORD."#");
	if ($securestringserver!=$securestring){
		print "0";
		error_log("ERROR: Wrong securestring\n".print_r($all_url_vars,true));
		exit;
	};

	$table=mysql_real_escape_string($table);
	$idname=mysql_real_escape_string($idname);
	$idvalue=mysql_real_escape_string($idvalue);
	$columnname=mysql_real_escape_string($columnname);
	$value=mysql_real_escape_string($value);




	$SQL="UPDATE `$table` SET `$columnname` = '$value' WHERE `$idname`='$idvalue' LIMIT 1";
	
	$result=mysql_query($SQL);
	if ($result) {
		print 1;
		exit;
	}else{
		error_log("ajax sql error: $SQL");
	};
};
	print 0;
?>
