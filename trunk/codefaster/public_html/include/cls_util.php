<?php
class Util{
	function make_left_menu($ft,$menuleft,$menuleft,$menuright,$addon_menu,$all_url_vars){
		$ft->assign("ADMIN_URL", ADMIN_URL);
		$ft->multiple_assign_define("LANG_");
		$ft->multiple_assign_define("CONF_");
	}

	function check_authentification(){
		// begin authenticate part
		if(AUTH_TYPE == 1)
		{
			if (!$AUTHENTICATE)
			{
				header( "WWW-Authenticate: Basic realm=\"ADMIN ".CONF_SITE_NAME."\"");
				header( "HTTP/1.0 401 Unauthorized");
				$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
				$ft->define(array("main"=>"template_firstpage.html", "content"=>"authentication_failed.html"));
				$ft->assign("ADMIN_URL", ADMIN_URL);
				$ft->multiple_assign_define("LANG_");
				$ft->multiple_assign_define("CONF_");
				$ft->parse("BODY", array("content","main"));
				$ft->showDebugInfo(ERROR_DEBUG);
				$ft->FastPrint();
				exit;
			}
		}
		else if(AUTH_TYPE == 2)
		{
			include_once(INCLUDE_PATH.'cls_session.php');
			$sess = new MYSession();
			if (!$sess->get(SESSION_ID))
			{
				$sess->set('session_url_before',$_SERVER['REQUEST_URI']);
				header("Location: login.php");
				exit;
			}
		}// end authenticate part

	}
}
?>