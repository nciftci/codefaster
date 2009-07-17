<?php
/*
##################   GraFX Press   #################
############################################################
GraFX Press			    Version 1.0
Writed by               GraFX (webmaster@grafxsoftware.com)
Created 04/04/04        Last Modified $Date: 2008-09-01 18:13:53 +0300 (L, 01 sep. 2008) $
Scripts Home:           http://www.grafxsoftware.com
############################################################
File name               cls_configwrapper.php
File purpose            CLASS Config Wrapper
File created by         GraFX (webmaster@grafxsoftware.com)
############################################################
*/
/*
$cw=new ConfigWrapper();

$cw->getId();
$cw->getDescription();
$cw->getName();
$cw->getValue();
$cw->getComment();
$cw->getType();
$cw->setId();
$cw->setDescription();
$cw->setName();
$cw->setValue();
$cw->setComment();
$cw->setType();
$cw->save();
*/
class ConfigWrapper
{
 protected  $id;
 protected  $description;
 protected  $name;
 protected  $value;
 protected  $comment;
 protected  $type;
public function __construct($id)
	{


	 if($id<>0)
	  {

		$this->id = $id;
		$SQL = " SELECT `description`,`name`,`value`,`comment`,`type` FROM `".STAT_DB_INDEX."config`  WHERE `id`='".$this->id."'";
		$retid = mysql_query($SQL) or die($MYSQL);
		if ($row = mysql_fetch_array($retid))
		{

 	 	  $this->description = $row["description"];
 	 	  $this->name = $row["name"];
 	 	  $this->value = $row["value"];
 	 	  $this->comment = $row["comment"];
 	 	  $this->type = $row["type"];

		}
		else
		{

 	 	  $this->description = "";
 	 	  $this->name = "";
 	 	  $this->value = "";
 	 	  $this->comment = "";
	 	  $this->type = "";
		}
      }
	}//end constructor ConfigWrapper
public function getId()
{
  return $this->id;
} // end getId()
public function getDescription()
{
  return $this->description;
} // end getDescription()
public function getName()
{
  return $this->name;
} // end getName()
public function getValue()
{
  return $this->value;
} // end getValue()
public function getComment()
{
  return $this->comment;
} // end getComment()
public function getType()
{
  return $this->type;
} // end getType()
public function setId($id)
{
  $this->id=$id;
} // end setId()
public function setDescription($description)
{
  $this->description=$description;
} // end setDescription()

public function setName($name)
{
  $this->name=$name;
} // end setName()

public function setValue($value)
{
  $this->value=$value;
} // end setValue()

public function setComment($comment)
{
  $this->comment=$comment;
} // end setComment()
public function setType($type)
{
  $this->type=$type;
} // end setType()

public function save() {
		if (($this->id)==0)
		{
			$SQL = "INSERT INTO `".STAT_DB_INDEX."config` (`description`,`name`,`value`,`comment`,`type`)";
			$SQL .= " VALUES('$this->description','$this->name','$this->value','$this->comment','$this->type')";
			$retid = mysql_query($SQL);
			if (!$retid) { echo( mysql_error()); }
			$this->id = mysql_insert_id();
		}
		else
		{
			$SQL = "UPDATE `".STAT_DB_INDEX."config` SET `description`='$this->description',`name`='$this->name',`value`='$this->value',`comment`='$this->comment',`type`='$this->type'";
			$SQL .= " WHERE `id`='".$this->id."'";
			$retid = mysql_query($SQL);
			if (!$retid) { echo( mysql_error()); }
		}

}//end save()
public function delete($id)
{
	$SQL = "DELETE FROM `".STAT_DB_INDEX."config` WHERE `id`=".$id."";
	$retid = mysql_query($SQL);
	if (!$retid) { echo( mysql_error()); }
} // end delete()
public function generateConfig($path){
// checking the file rights
	if (!file_exists($path))
	    return 0;
	 else {
			$SQL = "SELECT `name`,`value`,`comment` FROM `".DB_PREFIX."config`";
			$retid = mysql_query($SQL);
        	$buffer="";
			if (!$retid) { echo( mysql_error()); }

			 while ($row = mysql_fetch_array($retid)) {

				if(!empty($row['comment']))
				$buffer.="// ".str_replace("\n","// ",$row['comment'])."\n";
				$buffer.="define(\"".str_replace("\n","",$row['name'])."\",\"". addslashes(str_replace("\n","",$row['value']))."\");\n";
		   }
		   if($buffer<>"")
		   {
				$handle = @fopen($path, "w");
			$buffer="<?php \n".$buffer."\n\n // if Error Reporting Disable, then set to not report any errors on screen\n if (ERROR_DEBUG==0 || ERROR_DEBUG==2)\n\n error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);\n\n//error_reporting(E_ALL|E_NOTICE);\n\n// Disable magic_quotes_runtime\n\nset_magic_quotes_runtime(0);\nheader(\"Content-Type: text/html; charset=\".CONF_CHARSET);\n\n?>";
				if (@fwrite($handle, $buffer) === FALSE) {
					return 0;
		   		}
    		fclose($handle);
			return 1;
		}
   }
 }
} // end class ConfigWrapper
?>