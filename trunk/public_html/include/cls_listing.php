<?php

	class Listing {
		protected $sql;
		protected $rs;
		protected $numrows;
		protected $numfields;
		protected $fields;
		protected $limit;
		protected $firstid;
		protected $activatelisting;
		protected $activename;
		protected $noofpage;
		protected $offset;
		protected $page;
		protected $style;
		protected $parameter;
		protected $activestyle;
		protected $buttonstyle;
		protected $limit_display;
		protected $enableMod;
		protected $enableDel;
		protected $replace_table;
		protected $extra_fields;

		public function __construct($query) {
			$this->offset=0;
			$this->page=1;
			$this->sql=$query;
			$this->fields[0]="";
			$this->limit_display=150;
			$this->rs=mysql_query($this->sql);
			$this->numrows=mysql_num_rows($this->rs);
			$this->numfields=mysql_num_fields($this->rs);
			$this->enableDel = 0;
			$this->enableMod = 0;
			$this->replace_table=array();
			$this->extra_fields=array();

		}

		public function getEnableMod()
		{
			return $this->enableMod;
		}

		public function setEnableMod($mod)
		{
			$this->enableMod = $mod;
		}

		public function getEnableDel()
		{
			return $this->enableDel;
		}

		public function setEnableDel($del)
		{
			$this->enableDel = $del;
		}

		public function getNumRows() {
			return $this->numrows;
		}

		public function setLimit($no) {
			$this->limit=$no;
		}
		// ID field name
        public function setFirstID($id) {
            $this->firstid=$id;
        }
		public function getFirstID() {
			return $this->firstid;
		}
		// ID field name
		// end item activate exist
        public function setActivateListing($idlist) {
            $this->activatelisting=$idlist;
        }
		public function getActivateListing() {
			return $this->activatelisting;
		}
		// Active field name
        public function setActivateFieldName($name) {
            $this->activename=$name;
        }
		public function getActivateFieldName() {
			return $this->activename;
		}
		// end item activate exist
		public function getLimit() {
			return $this->limit;
		}
		public function getNoOfPages() {
			return ceil($this->noofpage=($this->getNumRows()/$this->getLimit()));
		}

		public function getOffset($page) {
			if($page>$this->getNoOfPages()) {
				$page=$this->getNoOfPages();
			}
			if($page=="") {
				$this->page=1;
				$page=1;
			}
			else {
				$this->page=$page;
			}
			if($page=="1") {
				$this->offset=0;
				return $this->offset;
			}
			else {
				for($i=2;$i<=$page;$i++) {
					$this->offset=$this->offset+$this->getLimit();
				}
				return $this->offset;
			}
		}
		public function getPage() {
			return $this->page;
		}
		public function listPages() {
			$limited=$this->sql." limit ".$this->offset." , ".$this->limit;
			$this->rs=mysql_query($limited) or die(mysql_error());

			if(strstr(mysql_field_flags($this->rs, 0),"primary_key")==false || mysql_field_type($this->rs,0)!="int")
				return false;
			$stringutil = new String();

			$data="<table width=\"99%\" cellspacing=\"0\" align=\"center\" id=\"listingtable\">";
			$data.="<tr class=\"theader\">";
			$n=0;

			$max_fields=sizeof($this->fields);
			$max_extra_fields=sizeof($this->extra_fields);
			//$max=sizeof($this->fields);
			$max=$max_fields+$max_extra_fields;

			$all_fields_array=array();
			for ($i=0;$i<$max_fields;$i++){
				$field=array();
				$field["mode"]="field";
				$field["index"]=$i;
				$tmp=sprintf("%05d",$i);
				$all_fields_array["$tmp"."_0"]=$field;
			};
			
			$i=0;
			foreach ($this->extra_fields as $key => $ef){
				$field=array();
				$field["mode"]="extra";
				$field["index"]=$key;
				$tmp=sprintf("%05d",$ef["pos"]);
				$all_fields_array["$tmp"."_1"."$i"]=$field;
				$i=$i+1;
			};	
			ksort($all_fields_array);

			//echo $max."sd";
			//print_R($this->fields);
//			$n_field=0;
//			$n_extra_field=0;

			foreach($all_fields_array as $field)
			{
				$n_field=$field["index"];
				if ($field["mode"]=="field"){
					if(trim($this->fields[$n_field])=="")
						$data.="<th nowrap>&nbsp;</th>";
					else
						$data.="<th nowrap>".$this->fields[$n_field]."</th>";
				};

				if ($field["mode"]=="extra"){
					$data.="<th nowrap>".$this->extra_fields[$n_field]["title"]."</th>";
				}
			}
			//+2


			if ($this->enableMod == 0)
			{
				$data.="<th nowrap>&nbsp;</th>";
			}
			if ($this->enableDel == 0)
			{
				$data.="<th nowrap>&nbsp;</th>";
			}

			$data.="</tr>";
			$col=0;
			if (mysql_num_rows($this->rs)==0) return "<br />".LANG_ADMIN_NODATA."<br />";



			while($rows=mysql_fetch_array($this->rs,MYSQL_NUM))
			{
				$normal_fields=array();
				if($col%2)
					$data.="<tr class=\"darkcolor\">";
				else
					$data.="<tr class=\"lightcolor\">";
				$i=0;

				$max=($this->activatelisting==1)?sizeof($rows)-1:sizeof($rows);

				while($i<$max)
				{
					$field_name=mysql_field_name($this->rs,$i);
					$str = $stringutil->clean_value($this->getRealValue($rows[$i],$field_name));
					if(strlen($str) > $this->limit_display)
					{
						$str=substr($str,0,$this->limit_display)." ...";
					};

					$normal_fields[$i]=$str;

					$i++;
				}

				foreach($all_fields_array as $field)
				{
					$str="";
					$n_field=$field["index"];
					if ($field["mode"]=="field"){
						$str=$normal_fields[$n_field];
					};
					if ($field["mode"]=="extra"){
						$function=$this->extra_fields[$n_field]["function"];
						$function_class_or_object=$function["class_or_object"];
						$function_name=$function["function"];
						$parameter=$rows[0];
						$function_call="";
						if (empty($function_class_or_object)){
							$function_call=$function_name;
						}else{
							$function_call=array($function_class_or_object,$function_name);
						};


						if (is_callable($function_call)){
							$str=call_user_func_array($function_call,array($parameter));
						}else{
							$str="";//the function could not be called
						};
					};
					if($str != "")
						$data.="<td class=\"bodytext\">".$stringutil->cleanDescription2($str)."</td>";
					else
						$data.="<td class=\"bodytext\">"."&nbsp;"."</td>";
				};

				// TODO: activ
				if ($this->getActivateListing() == 1) {
					if ( $rows[$max] == 1) { //if is active, green
						$data.="<td class=\"bodytext\" width=\"24\"><a class=\"activate\"  href=\"".$_SERVER['PHP_SELF']."?do=activate&".$this->getFirstID()."=".$rows[0]."\"><img src=\"".CONF_INDEX_URL."images/admin/activate1.ico\" title=\"".LANG_ADMIN_DEACTIVATE."\"></a></td>";
					}
					else { //if inactive red
						$data.="<td class=\"bodytext\" width=\"24\"><a class=\"activate\"  href=\"".$_SERVER['PHP_SELF']."?do=activate&".$this->getFirstID()."=".$rows[0]."\"><img src=\"".CONF_INDEX_URL."images/admin/activate0.ico\" title=\"".LANG_ADMIN_ACTIVATE."\"></a></td>";
					}
				}

				if ($this->enableMod == 0)
				{
					$data.="<td class=\"bodytext\" width=\"24\"><a class=\"mod\"  href=\"".$_SERVER['PHP_SELF']."?do=mod&".$this->getFirstID()."=".$rows[0]."\"><img src=\"".CONF_INDEX_URL."images/admin/mod.ico\" title=\"".LANG_ADMIN_MODIFY."\"></a></td>";
				}


				if ($this->enableDel == 0)
				{
					$data.="<td class=\"bodytext\" width=\"24\"><a class=\"del\"  href=\"".$_SERVER['PHP_SELF']."?do=del&".$this->getFirstID()."=".$rows[0]."\" onclick=\"return confirm('".LANG_ADMIN_CONFIRMDELETE."')\"><img src=\"".CONF_INDEX_URL."images/admin/del.ico\" title=\"".LANG_ADMIN_DELETE."\" ></a></td>";
				}

				$data.="</tr>\n";
				$col++;
			}

			$data.="</table>";
			return $data;
		}
		public function setFields($fields) {
			$this->fields=$fields;
		}
		public function getFields() {
			return $this->fields;
		}
		public function getFieldCount() {
			return $this->numfields();
		}
		public function setLimitDisplay($disp) {
			$this->limit_display=$disp;
		}
		public function setStyle($style) {
			$this->style=$style;
		}
		public function getStyle() {
			return $this->style;
		}
		public function setActiveStyle($style) {
			$this->activestyle=$style;
		}
		public function getActiveStyle() {
			return $this->activestyle;
		}
		public function setButtonStyle($style) {
			$this->buttonstyle=$style;
		}
		public function getButtonStyle() {
			return $this->buttonstyle;
		}
		public function setParameter($parameter) {
			$this->parameter=$parameter;
		}
		public function getParameter() {
			return $this->parameter;
		}
		protected function getRealValue($id_data,$field_name){
			if (isset($this->replace_table[$field_name])){
				if (($this->replace_table[$field_name]["mode"]=="database")){
					$id_column=$this->replace_table[$field_name]["id_column"];
					$data_column=$this->replace_table[$field_name]["data_column"];
					$table=$this->replace_table[$field_name]["table"];
					$SQL_RV = "SELECT `$data_column` FROM `".DB_PREFIX."$table` WHERE `$id_column`='$id_data'";
					$retidrv = mysql_query($SQL_RV);
					if (!$retidrv) { echo( mysql_error()); }

					if($rows=mysql_fetch_array($retidrv,MYSQL_NUM)){
						return $rows[0];
					}else{
						return $id_data;
					};
				};
				if (($this->replace_table[$field_name]["mode"]=="array")){
					return $this->replace_table[$field_name]["array"][$id_data];
				};
			};
			return $id_data;
		}
		public function setReplaceColumnIdFromDatabase($field,$table,$id_column,$data_column){
			$rpl=array();
			$rpl["mode"]="database";
			$rpl["table"]=$table;
			$rpl["id_column"]=$id_column;
			$rpl["data_column"]=$data_column;

			$this->replace_table[$field]=$rpl;
		}
		public function setReplaceColumnIdFromArray($field,$array){
			$rpl=array();
			$rpl["mode"]="array";
			$rpl["array"]=$array;
			$this->replace_table[$field]=$rpl;
		}
		
		public function addNewFunctionColumn($position,$title,$function_or_method,&$class_or_object){
			$new_index=0;
			if (!empty($this->extra_fields)) $new_index=sizeof($this->extra_fields);
			$ef["pos"]=$position;
			$ef["title"]=$title;
			$ef["function"]=array("class_or_object" => $class_or_object,
								  "function" => $function_or_method);
			$this->extra_fields[$new_index]=$ef;
		}

	}
?>
