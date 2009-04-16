<?php
global $LANG;
	$SQL = "SELECT * FROM `".DB_PREFIX."modules` ORDER BY `position` ASC";
	$retid = mysql_query($SQL);
	if (!$retid) { echo( mysql_error()); }
	$i=0;
	if ($row = mysql_fetch_array($retid))
	do{
		$module_id[$i] = $row["module_id"];
		$module_name[$i] = $row["module_name"];
		$availability[$i] = $row["availability"];
		$filename[$i] = $row["filename"];
		$extra_menu[$i] = $row["extra_menu"];
		$i++;
	}while ($row = mysql_fetch_array($retid));
	$nrmodules = $i;

$sidebar_start = "<div id=\"accordion\">";
$sidebar_[$i]="
			<div class=\"".$sidebar_[$i]."\">
				<h3><a href=\"#\">".$module_name[$i]."</a></h3>
                <ul>
                <li><a href=\"".ADMIN_URL . $filename.".php\">".LANG_1111."</a></li>
				<li><a href=\"".ADMIN_URL . $filename.".php?do=list\">".LANG_1111."</a></li>
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