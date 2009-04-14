<?php

include_once("config.inc.php");
include_once(INCLUDE_PATH."cls_fast_template.php");
include_once(INCLUDE_PATH."cls_string.php");

session_start();

$stringutil = new String();

$all_url_vars = $stringutil->parse_all();

if (empty($all_url_vars["action"])) {
	$all_url_vars["action"] = "add";
}

if ($all_url_vars["action"] == "add")
{

	$ft = new FastTemplate(TEMPLATE_PATH);
	$ft->define(array(
			"main"    => "template_body.html", 
			"content" => "fields.html"
		)
	);

	if (!empty($_SESSION['host']) 
		&& !empty($_SESSION['logid']) 
			&& !empty($_SESSION['dbname']) 
				&& !empty($all_url_vars["tables"]))
	{
	
		$cid = mysql_connect($_SESSION["host"],$_SESSION["logid"],$_SESSION["pasw"]) or die("Database settings error!</br> Go back <a href='index.php?page=host'> prev</a>.</br> Write correct information!");
		mysql_select_db($_SESSION["dbname"]) or die("Database settings error!</br> Go back <a href='index.php?page=host'> prev</a>.</br> Write correct information!");

		$fres = mysql_list_fields($_SESSION["dbname"],$all_url_vars["tables"]);

		$buffer  = "";
		$dbuffer = "";

		$cols = mysql_num_fields($fres);

		for ($i = 0; $i < $cols; $i++) {
			$t         = mysql_field_name($fres, $i);
			$fieldtype = mysql_field_type ($fres, $i);	
			$buffer   .= "<option value=\"$t\">$t</option>";
			$ft->assign("FIELDS", $buffer);
			$dbuffer .= $t;
			$dbuffer .= ";";
		}
	}

	$_SESSION['dbuffer'] = $dbuffer;
	$_SESSION['table']   = $all_url_vars["tables"];

	$ft->parse("BODY", array("content", "main"));
	$ft->FastPrint();
}

?>

