<?php

include_once("./config.inc.php");
include_once(INCLUDE_PATH."cls_fast_template.php");
include_once(INCLUDE_PATH."cls_string.php");

$ft = new FastTemplate(TEMPLATE_PATH);

$stringutil = new String();

$all_url_vars = $stringutil->parse_all();

if (empty($all_url_vars["page"])) {
	$all_url_vars["page"] = "first";
}

if($all_url_vars["page"]=="first") {
	$ft->define(array(
			"main"    => "template_body.html", 
			"content" => "template_firstpage.html"
		)
	);
} elseif ($all_url_vars["page"]=="sql") {
	$ft->define(array(
			"main"    => "template_body.html", 
			"content" => "sql.html"
		)
	);
} else {
	$ft->define(array(
			"main"    => "template_body.html", 
			"content" => "host.html"
		)
	);
}

$ft->parse("BODY", array("content", "main"));
$ft->FastPrint();

?>