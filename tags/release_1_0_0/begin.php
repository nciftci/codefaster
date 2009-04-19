<?php

include_once("config.inc.php");
include_once(INCLUDE_PATH."cls_fast_template.php");
include_once(INCLUDE_PATH."cls_string.php");

session_start();

$stringutil   = new String();
$all_url_vars = $stringutil->parse_all();

$_SESSION["host"]   = $all_url_vars["url"];
$_SESSION["logid"]  = $all_url_vars["logid"];
$_SESSION["pasw"]   = $all_url_vars["pasw"];
$_SESSION["dbname"] = $all_url_vars["dat"];

if($all_url_vars["action"] != "add") {
	$ft = new FastTemplate(TEMPLATE_PATH);
	$ft->define(array(
			"main"    => "template_body.html", 
			"content" => "index.html"
		)
	);
	$ft->parse("BODY", array("content","main"));
	$ft->FastPrint();

} else if ($all_url_vars["action"] == "add") {

	$ft = new FastTemplate(TEMPLATE_PATH);
	$ft->define(array(
			"main"    => "template_body.html", 
			"content" => "table.html"
		)
	);

	$cid = @mysql_connect($all_url_vars["url"], $all_url_vars["logid"], $all_url_vars["pasw"]) or die("I cannot select the database because the database name does not exist!</br> Go back <a href='index.php?page=host'> prev</a>.</br> Write correct information!");
	@mysql_select_db($all_url_vars["dat"]) or die("I cannot select the database because the database name does not exist!</br> Go back <a href='index.php?page=host'> prev</a>.</br> Write correct information!");

	$buffer = "";

	$tres     = mysql_list_tables($all_url_vars["dat"]);
	$num_rows = mysql_num_rows($tres);

	for ($i = 0; $i < $num_rows; $i++) {
  		$buffer.= "<option value='" . mysql_tablename($tres, $i)."'>" . mysql_tablename($tres, $i) . "</option>"; 
	}
}

$ft->assign("TABLES",$buffer);
$ft->parse("BODY", array("content","main"));
$ft->FastPrint();

?>

