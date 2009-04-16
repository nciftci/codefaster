<?php
include("config.inc.php");
include_once(INCLUDE_LANGUAGE_PATH.$LANG.".inc.php");
include_once(INCLUDE_PATH."connection.php");
include_once(INCLUDE_PATH."cls_fast_template.php");
include_once(INCLUDE_PATH."cls_string.php");
require_once("product.common.php");

/*
include_once("product.common.php");
$ft->assign('XAJAX', $xajax->getJavascript(CONF_INDEX_URL."javascript/xajax/"));

html-be
<script>
function submitProductId(value)
{
	xajax_ShowProduct(value);
}
</script>
{XAJAX}


 onClick="submitProductId(this.value);

//product.common.php
<?php
include_once("./config.inc.php");
require_once (TEMPLATE_PATH_JAVASCRIPTS."xajax/xajax.inc.php");

$xajax = new xajax("product.server.php");
$xajax->registerFunction("ShowProduct");
//$xajax->debugOn();
?>
*/
function ShowProduct($id_product)
{
    $objResponse = new xajaxResponse();
    $bError = false;
	$stringutil = new String("");
	$tempname = "en_title";
	$tempdescription = "en_detaileddescription";
	$SQL = "SELECT $tempname,$tempdescription FROM ".DB_PREFIX."product WHERE id_product='".$id_product."'";
	//$objResponse->addAlert($SQL);
	$retid = mysql_query($SQL);
	if (!$retid) { echo( mysql_error()); }
	if ($row = mysql_fetch_array($retid))
	{
		$name = $row[$tempname];
		$description = $stringutil->cleanDescription2($row[$tempdescription]);
	}

	$ft = new FastTemplate(TEMPLATE_PATH);
	$ft->define(array("main"=>"product_rightmenu.html"));
	
	$ft->assign("NAME",$name);
	//if (NONSEO==1) $ft->assign("URL_TYPE", "product.php?name=".$stringutil->CleanLink($name)."&id=".$id_product."");
	//else $ft->assign("URL_TYPE", "product.php/".$stringutil->CleanLink($name)."/".$id_product."/");
	$ft->assign("DESCRIPTION",$description);
	
	$ft->multiple_assign_define("LANG_");
	$ft->parse("mainContent", "main");
	$ft->showDebugInfo(ERROR_DEBUG);
	$c = $ft->fetch("mainContent");
	
	//$objResponse->addAlert($c);
    $objResponse->addAssign("body_firstpage_background_right","innerHTML",$c);
	
    return $objResponse;
}
$xajax->processRequests();
?>