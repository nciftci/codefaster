CREATE TABLE `config` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(250) NOT NULL default '',
  `name` varchar(200) NOT NULL default '0',
  `value` text NOT NULL,
  `comment` text,
  `type` tinyint(4) default '0',
  `pagenr` tinyint(1) NOT NULL,
  `position` tinyint(1) NOT NULL,
  `image` varchar(50) NOT NULL,
  `help_description` text NOT NULL,
  `url` varchar(250) NOT NULL,
  `template` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM ;

insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (1,'LANG_CONF_META_KEYWORDS','CONF_META_KEYWORDS','meta tags here, meta tags here',NULL,2,1,3,'','','','template_firstpage.html,template_index.html');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (2,'LANG_CONF_META_DESCRIPTION','CONF_META_DESCRIPTION','Description here, by metatags',NULL,2,1,2,'','','','template_firstpage.html,template_index.html');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (25,'LANG_CONF_EMAIL_INREG','EMAIL_INREG','yourmail@domain.com',NULL,1,1,5,'','','','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (26,'LANG_CONF_EMAIL_NAME','EMAIL_NAME','Site Name',NULL,1,1,4,'','','','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (36,'LANG_CONF_ERROR_DEBUG','ERROR_DEBUG','2','2|LANG_CONF_D_YES|0|LANG_CONF_D_NO',3,2,1,'','','','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (37,'LANG_CONF_SESSION_ID','SESSION_ID','SID',NULL,1,2,2,'','','','admintool/');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (38,'LANG_CONF_AUTH_TYPE','AUTH_TYPE','2','1|LANG_CONF_D_AUTH_TYPE_BASE64|2|LANG_CONF_D_AUTH_TYPE_SESSION',3,1,6,'','','','admintool/');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (40,'LANG_CONF_USER','USER','demo',NULL,1,1,7,'','','admintool/login.php,admintool/mailtoallusers.php,conect.php','include/authenticate.php');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (41,'LANG_CONF_PASSWORD','PASSWORD','demo',NULL,1,1,8,'','','admintool/login.php,admintool/mailtoallusers.php,admintool/password_recovery.php,activation.php,forgetpassword.php,login.php,registration.php,resendactivationemail.php,conect.php','include/authenticate.php,cls_regbusiness.php');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (42,'LANG_CONF_SITE_NAME','CONF_SITE_NAME','Site Demo',NULL,1,1,1,'','','','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (43,'LANG_CONF_MAX_FILE_SIZE','MAX_FILE_SIZE','409600',NULL,1,2,3,'','','','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (44,'LANG_CONF_TIMEOUTINMILLISECONDS','TIMEOUTINMILLISECONDS','10000',NULL,1,2,4,'','','admintool/mailtoallusers.php,admintool/newsletter.php','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (46,'LANG_CONF_MULTILANGUAGE','MULTILANGUAGE','1','1|LANG_CONF_D_NO|0|LANG_CONF_D_YES',3,1,9,'','','admintool,add,mod-phps','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (47,'LANG_CONF_NONSEO','NONSEO','0','',4,2,6,'','','','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (60,'LANG_CONF_IMAGE_NEEDED','IMAGE_NEEDED','1','1|LANG_CONF_D_YES|0|LANG_CONF_D_NO',3,0,0,'','','contact.php','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (64,'LANG_CONF_DATEFORMAT','DATEFORMAT','d F Y','Y-m-d|LANG_CONF_YYYY-MM-DD|y-n-d|LANG_CONF_YY-M-DD|y-m-d|LANG_CONF_YY-MM-DD|n/d/y|LANG_CONF_M/DD/YY|d F Y|LANG_CONF_DD_MONTH_YYYY|d M y|LANG_CONF_DD_MON_YY|M d,Y|LANG_CONF_MON_DD_COMMA_YYYY|d-M-y|LANG_CONF_DD-MON-YY|dMy|LANG_CONF_DDMONYY',3,1,13,'','','','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (69,'LANG_CONF_MULTILAYER_LEFTMENU','MULTILAYER_LEFTMENU','1','1|LANG_CONF_D_YES|0|LANG_CONF_D_NO',3,1,9,'','','cls_left_menu.php','');
insert  into `config`(`id`,`description`,`name`,`value`,`comment`,`type`,`pagenr`,`position`,`image`,`help_description`,`url`,`template`) values (70,'LANG_CONF_MULTIADDRESS','MULTIADDRESS','Billing Support|billing@yourdomain.com#Techical  Support|support@yourdomain.com#Sales Department|sales@yourdomain.com',NULL,2,1,3,'','','','contact.html');

CREATE TABLE `modules` (                                
  `module_id` tinyint(2) NOT NULL auto_increment,           
  `module_name` varchar(60) collate utf8_bin default NULL, 
  `availability` tinyint(1) default '0',  
  `position` tinyint(2) default '0',
  `filename` varchar(100) NOT NULL,
  `extra_menu` text NOT NULL,
  PRIMARY KEY  (`module_id`),
  UNIQUE KEY `module_name` (`module_name`)    
) ENGINE=MyISAM; 

CREATE TABLE `history` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`item_id` int(11) NOT NULL, 
`tablename` varchar(100) COLLATE utf8_bin DEFAULT NULL,
`command` text COLLATE utf8_bin,
`date` datetime DEFAULT NULL,
`userid` int(11) DEFAULT NULL,
`valid` tinyint(4) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM;
