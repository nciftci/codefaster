<?php
/**
*
* @author - Elteto Zoltan
* @desc - class for creating the menu (sidebar) in the admin page.
* @vers - 1.0
*/
class Sidebar{
	protected $table_name;
	
	public function Sidebar($table_name='modules'){
		$this->table_name=$table_name;
	}
	
	/**
	*
	* @author - Elteto Zoltan
	* @desc - get the sidebar from table
	* @vers - 1.0
	*/
	public function getSideBar(){
		
	$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
	$ft->define(array("main"=>"sidebar.html"));
	
	// only show if is available
	$SQL = "SELECT * FROM `".DB_PREFIX.$this->table_name."` WHERE availability=1  ORDER BY `position` ASC";
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
	
	if($nrmodules==0){
		$ft->assign("SIDEBAR_EXIST",0);
	}else{
		$ft->assign("SIDEBAR_EXIST",1);
		$ft->define_dynamic ( "sideex", "main" );	
		
		for($i=0;$i<$nrmodules;$i++)
		{
			$ft->assign("MODULE_NAME",constant($module_name[$i]));
			$ft->assign("FILENAME",$filename[$i]);
			$ft->assign("ADMIN_URL",ADMIN_URL);
			$ft->assign("LANG_ADMIN_ADD",LANG_ADMIN_ADD);
			$ft->assign("LANG_ADMIN_LIST",LANG_ADMIN_LIST);
			
			// could be extra details what admin want to show. This is in extra_menu field.
			if(!empty($extra_menu[$i]))
			{
				$ft->assign("ISEXTRA_MENU",1);
				$ft->assign("EXTRA_MENU",$extra_menu[$i] );
			}
			else 
			$ft->assign("ISEXTRA_MENU",0);
			
			$ft->parse ( "SIDEEX", ".sideex" );
		}
	}
	$ft->multiple_assign_define ( "LANG_" );
	$ft->multiple_assign_define ( "CONF_" );
	$ft->parse ( "mainContent", "main" );
	return $ft->fetch ( "mainContent" );
	}
}
?>