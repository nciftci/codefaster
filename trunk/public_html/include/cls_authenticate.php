<?php
class Authenticate {
	public function check_authentification(){
		// begin authenticate part
		if(AUTH_TYPE == 1)
		{
			if (!$AUTHENTICATE)
			{
				header( "WWW-Authenticate: Basic realm=\"ADMIN ".CONF_SITE_NAME."\"");
				header( "HTTP/1.0 401 Unauthorized");
				$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
				$ft->define(array("main"=>"template_firstpage.html", "content"=>"authentication_failed.html"));
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
            if (empty($PHP_AUTH_USER))
            {
                    if (!empty($_SERVER) && isset($_SERVER['PHP_AUTH_USER'])) {
                        $PHP_AUTH_USER = $_SERVER['PHP_AUTH_USER'];
                    }
                    else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['PHP_AUTH_USER'])) {
                        $PHP_AUTH_USER = $HTTP_SERVER_VARS['PHP_AUTH_USER'];
                    }
                    else if (isset($REMOTE_USER)) {
                        $PHP_AUTH_USER = $REMOTE_USER;
                    }
                    else if (!empty($_ENV) && isset($_ENV['REMOTE_USER'])) {
                        $PHP_AUTH_USER = $_ENV['REMOTE_USER'];
                    }
                    else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['REMOTE_USER'])) {
                        $PHP_AUTH_USER = $HTTP_ENV_VARS['REMOTE_USER'];
                    }
                    else if (@getenv('REMOTE_USER')) {
                        $PHP_AUTH_USER = getenv('REMOTE_USER');
                    }
                    // Fix from Matthias Fichtner for WebSite Professional - Part 1
                    else if (isset($AUTH_USER)) {
                        $PHP_AUTH_USER = $AUTH_USER;
                    }
                    else if (!empty($_ENV) && isset($_ENV['AUTH_USER'])) {
                        $PHP_AUTH_USER = $_ENV['AUTH_USER'];
                    }
                    else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['AUTH_USER'])) {
                        $PHP_AUTH_USER = $HTTP_ENV_VARS['AUTH_USER'];
                    }
                    else if (@getenv('AUTH_USER')) {
                        $PHP_AUTH_USER = getenv('AUTH_USER');
                    }
        }
        if (empty($PHP_AUTH_PW)) {
            if (!empty($_SERVER) && isset($_SERVER['PHP_AUTH_PW'])) {
                $PHP_AUTH_PW = $_SERVER['PHP_AUTH_PW'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['PHP_AUTH_PW'])) {
                $PHP_AUTH_PW = $HTTP_SERVER_VARS['PHP_AUTH_PW'];
            }
            else if (isset($REMOTE_PASSWORD)) {
                $PHP_AUTH_PW = $REMOTE_PASSWORD;
            }
            else if (!empty($_ENV) && isset($_ENV['REMOTE_PASSWORD'])) {
                $PHP_AUTH_PW = $_ENV['REMOTE_PASSWORD'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['REMOTE_PASSWORD'])) {
                $PHP_AUTH_PW = $HTTP_ENV_VARS['REMOTE_PASSWORD'];
            }
            else if (@getenv('REMOTE_PASSWORD')) {
                $PHP_AUTH_PW = getenv('REMOTE_PASSWORD');
            }
            else if (isset($AUTH_PASSWORD)) {
                $PHP_AUTH_PW = $AUTH_PASSWORD;
            }
            else if (!empty($_ENV) && isset($_ENV['AUTH_PASSWORD'])) {
                $PHP_AUTH_PW = $_ENV['AUTH_PASSWORD'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['AUTH_PASSWORD'])) {
                $PHP_AUTH_PW = $HTTP_ENV_VARS['AUTH_PASSWORD'];
            }
            else if (@getenv('AUTH_PASSWORD')) {
                $PHP_AUTH_PW = getenv('AUTH_PASSWORD');
            }
        }
        // IIS
        if (empty($PHP_AUTH_USER) && empty($PHP_AUTH_PW)
            && function_exists('base64_decode')) {
            if (!empty($HTTP_AUTHORIZATION)
                && ereg('^Basic ', $HTTP_AUTHORIZATION)) {
                list($PHP_AUTH_USER, $PHP_AUTH_PW) = explode(':', base64_decode(substr($HTTP_AUTHORIZATION, 6)));
            }
            else if (!empty($_ENV)
                 && isset($_ENV['HTTP_AUTHORIZATION'])
                 && ereg('^Basic ', $_ENV['HTTP_AUTHORIZATION'])) {
                list($PHP_AUTH_USER, $PHP_AUTH_PW) = explode(':', base64_decode(substr($_ENV['HTTP_AUTHORIZATION'], 6)));
            }
            else if (!empty($HTTP_ENV_VARS)
                     && isset($HTTP_ENV_VARS['HTTP_AUTHORIZATION'])
                     && ereg('^Basic ', $HTTP_ENV_VARS['HTTP_AUTHORIZATION'])) {
                list($PHP_AUTH_USER, $PHP_AUTH_PW) = explode(':', base64_decode(substr($HTTP_ENV_VARS['HTTP_AUTHORIZATION'], 6)));
            }
            else if (@getenv('HTTP_AUTHORIZATION')
                     && ereg('^Basic ', getenv('HTTP_AUTHORIZATION'))) {
                list($PHP_AUTH_USER, $PHP_AUTH_PW) = explode(':', base64_decode(substr(getenv('HTTP_AUTHORIZATION'), 6)));
            }
        } // end IIS
      //error_log("user=$PHP_AUTH_USER passwd=$PHP_AUTH_PW");

        if (!empty($PHP_AUTH_USER) && !empty($PHP_AUTH_PW))
             {
               if (get_magic_quotes_gpc()) {
                $PHP_AUTH_USER = stripslashes($PHP_AUTH_USER);
                $PHP_AUTH_PW   = stripslashes($PHP_AUTH_PW);
               }
             $AUTHENTICATE = ($PHP_AUTH_USER == USER && $PHP_AUTH_PW == PASSWORD);
             }
             else
                 $AUTHENTICATE=0;
?>