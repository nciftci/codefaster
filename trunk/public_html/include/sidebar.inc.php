<?php
global $LANG;
//$tempnewstype = $LANG."_name";
//$tempfaqtype = $LANG."_name";
//$tempproduct = $LANG."_title";
	$SQL = "SELECT * FROM `".DB_PREFIX."modules` ORDER BY `position` ASC";
	$retid = mysql_query($SQL);
	if (!$retid) { echo( mysql_error()); }
	$i=0;
	if ($row = mysql_fetch_array($retid))
	do{
		$module_id[$i] = $row["module_id"];
		$module_name[$i] = $row["module_name"];
		$availability[$i] = $row["availability"];
		$i++;
	}while ($row = mysql_fetch_array($retid));
	$nrmodules = $i;

$sidebar_start = "<div id=\"accordion\">";
$sidebar_1="
			<div>
				<h3><a href=\"#\">".LANG_ADMIN_TESTPRODUCT_TESTPRODUCT."</a></h3>
                <ul>
                <li><a href=\"".ADMIN_URL."testproduct.php\">- Add new product(s)</a></li>
				<li><a href=\"".ADMIN_URL."testproduct.php?do=list\">- List/Modify/Delete product(s)</a></li>
                </ul>
			</div>
";
$sidebar_2="
			<div>
				<h3><a href=\"#\">".LANG_ADMIN_TESTPRODUCT_TESTPRODUCT."2</a></h3>
                <ul>
                <li><a href=\"".ADMIN_URL."testproduct1.php\">- Add new product(s)</a></li>
				<li><a href=\"".ADMIN_URL."testproduct1.php?do=list\">- List/Modify/Delete product(s)</a></li>
                </ul>
			</div>
";
$sidebar_3="
			<div>
				<h3><a href=\"#\">".LANG_ADMIN_TESTPRODUCT."3</a></h3>
                <ul>
                <li><a href=\"".ADMIN_URL."testproduct.php\">- Add new product(s)</a></li>
				<li><a href=\"".ADMIN_URL."testproduct.php?do=list\">- List/Modify/Delete product(s)</a></li>
                </ul>
			</div>
";
$sidebar_4="
			<div>
				<h3><a href=\"#\">".LANG_ADMIN_TESTPRODUCT."4</a></h3>
                <ul>
                <li><a href=\"".ADMIN_URL."testproduct.php\">- Add new product(s)</a></li>
				<li><a href=\"".ADMIN_URL."testproduct.php?do=list\">- List/Modify/Delete product(s)</a></li>
                </ul>
			</div> 
";

$sidebar_end = "</div>";

$final_sidebar = "";
for($i=0;$i<$nrmodules;$i++)
{
	
	$temp_sidebar = "sidebar_".$module_id[$i];	
	if($availability[$i] == 1)
	$final_sidebar .= $$temp_sidebar;
}
$sidebar = $sidebar_start.$final_sidebar.$sidebar_end;
?>