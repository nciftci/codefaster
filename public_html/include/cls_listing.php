<?php

	class Listing {
		protected $sql;
                protected $sql_sort_query;
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
                protected $array_data;
                private $field_results;

                protected $sort_column;
                protected $sort_reverse;

                protected $search_term;

		private $object_results;
		private $mode;

		public function __construct() {
			$this->offset=0;
			$this->page=1;
			$this->limit_display=150;
			$this->enableDel = 0;
			$this->enableMod = 0;
			$this->replace_table=array();
			$this->extra_fields=array();
			$this->rs=null;
			$this->numrows=0;
			$this->numfields=0;
			$this->mode="";
                        $this->sort_column=null;
                        $this->sort_reverse=false;
                        $this->search_term="";
		}
                
                
/**
 * Set sort column
 *
 * @param int $sort_column The order of the column
 * @param boolean $sort_reverse Sort order: (true for reverse)
 */
                public function set_sort($sort_column,$sort_reverse=false){
                        $this->$sort_column=$sort_column;
                        $this->$sort_reverse=$sort_reverse;
                        if ($this->mode=="sql"){
                            $this->sql_sort_query="ORDER BY $sort_column ";
                            if ($sort_reverse) $this->sql_sort_query.="DESC ";
                        };

                }

/**
 * Disable sorting
 */

                public function reset_sort(){
                        $this->sort_column=null;
                        $this->sort_reverse=false;
                        $this->sql_sort_query="";
                }

/**
 * Set search term
 * @param string $search_term
 */
                public function set_search($search_term){
                        $this->search_term=$search_term;
                }

/**
 * Reset searching
 */
                public function reset_search(){
                        $this->search_term=null;
                }

/**
 * This funciton makes cls_listing to get the data from mysql table
 *
 * @param string $source_table_or_query The table which is used
 * @param array $fields The fields which are used from the table; the first is primary key
 *
 * if the user wants to use own sql command for the first parameter, the second parameter must be null
 */
		public function init_mysql($source_table_or_query,$fields=NULL){
			$this->mode="mysql";
                        $query="";
                        $query_sort="";
                        if ($fields==NULL){
                            $query=$source_table_or_query;
                            //@todo: if it already contains "order by", the sorting will not work
                        }else{
                            $query="SELECT ".implode(",",$fields)." FROM $source_table_or_query ";
                            $query_sort="ORDER BY 1 DESC ";
                        };
			$this->sql=$query;
                        $this->sql_sort_query=$query_sort;
			$this->fields[0]="";
			$this->rs=mysql_query($this->sql);
			$this->numrows=mysql_num_rows($this->rs);
			$this->numfields=mysql_num_fields($this->rs);


                        $fields=array();
                        for ($k=0;$k<$this->numfields;$k++){
                            $tmp=mysql_fetch_field($this->rs,$k);
                            $fields[$k]=$tmp->name;
                        };

                        $this->field_results=$fields;
		}

/**
 * This function makes cls_listing to get the data from a method from the object
 * The method must returns an array (for single column listing) or an array of arrays (for multiple columns). The
 * first column is always the "primary key".
 *
 * @param object $source_object
 * @param string $method_name
 * @param array $method_arguments
 */
		public function init_object(&$source_object,$method_name, $method_arguments){
			$this->mode="object";
			$this->source_object=$source_object;

			//print($method_name."   ".$parameter);
			$result=call_user_func_array(array($source_object,$method_name),$method_arguments);
			$this->object_results=$result;
                }
/**
 *
 * @param <string> $filename File path and name
 * @param <type> $used_fields array which contains the fields used in the array
 */
                public function init_base64_array_from_file($filename,$used_fields){
                        $this->mode="base64_array";
                        $mf=new MiniFile($filename);
                        $this->array_data=array();

                        $input_array=$mf->getArray();

                        $outk=0;
                        foreach($input_array as $id => $line){
                            $output_array=array($id);
                            $k=1;
                            foreach ($line as $column_key=>$column_value){
                                if (in_array($column_key,$used_fields)){
                                    $output_array[$k]=$this->getRealValue($column_value, $column_key);
                                    $k=$k+1;
                                };
                            };
                            $this->array_data[$outk]=$output_array;
                            $outk=$outk+1;
                        };
                       // print_r($this->array_data);exit;


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

/**
 * Obtain the data which will be shown in the listing
 *
 * @return array
 */
		private function get_data($search_columns,$search_columns_names){
			$stringutil = new String();
			$result=array();
			if ($this->mode=="mysql"){
                                $sql=$this->sql;
                                if ($this->sort_column) $sql.=$this->sql_sort_query;
                                $search_where="";
                                foreach($search_columns as $key=>$search_column){
                                    $name=$search_columns_names[$key];
                                    if (!empty($search_column)) {
                                        if (!empty($search_where)) $search_where.="AND ";
                                        $search_where.="`$name` LIKE '%$search_column%' ";
                                    }
                                };
                                if (!empty($search_where)) {
                                    if (!stristr($this->sql," where ")) $search_where=" WHERE ".$search_where;
                                        else $search_where="";
                                }

				$limited=$sql.$search_where." limit ".$this->offset." , ".$this->limit;
                                
				$this->rs=mysql_query($limited) or die(mysql_error());

				if(strstr(mysql_field_flags($this->rs, 0),"primary_key")==false || mysql_field_type($this->rs,0)!="int")
					return false;
				if (mysql_num_rows($this->rs)==0) return false;

				$k=0;
				while($rows=mysql_fetch_array($this->rs,MYSQL_NUM)){
					$max=sizeof($rows);
					$i=0;
					while($i<$max) {
						$field_name=mysql_field_name($this->rs,$i);
						$resultrow[$i]=$stringutil->clean_value($this->getRealValue($rows[$i],$field_name));
						$i=$i+1;
					};
					//////+++++++++++++++++++
					$result[$k]=$resultrow;
					$k=$k+1;
				}

			};

			if ($this->mode=="object"){
				$result=array();
				$k=0;
				foreach($this->object_results as $r){
					$resultrow=array();
					if (is_array($r)){
						$k2=0;
						foreach ($r as $r_item){
							$resultrow[$k2]=$r_item;
							$k2=$k2+1;
						};
					}else{
						$resultrow[0]=$r;
					};
					$result[$k]=$resultrow;
					$k=$k+1;
				};

//				print("<pre>");print_r($result);print("</pre>");
			};

                        if ($this->mode=="base64_array") return $this->array_data;
		//	print("<pre>");print_r($result);print("</pre>");

			return $result;

		}

		public function listPages() {

                    $search_columns=$_REQUEST["search_columns"];
                    $search_columns_names=$_REQUEST["search_columns_name"];

                    $alldata=$this->get_data($search_columns,$search_columns_names);

                    $stringutil = new String();

                    $self_page=$_SERVER['REQUEST_URI'];

                    $data="<form action='$self_page' method='post' id='form1'><table width=\"99%\" cellspacing=\"0\" align=\"center\" id=\"listingtable\">";
                    $data.="<tr class=\"theader\">";
                    $n=0;

                    $max_fields=sizeof($this->fields);
                    $max_extra_fields=sizeof($this->extra_fields);
                    //$max=sizeof($this->fields);
                    $max=$max_fields+$max_extra_fields;

                    $all_fields_array=array();
                    for ($i=0;$i<$max_fields;$i++) {
                        $field=array();
                        $field["mode"]="field";
                        $field["index"]=$i;
                        $tmp=sprintf("%05d",$i);
                        $all_fields_array["$tmp"."_0"]=$field;
                    };

                    $i=0;
                    foreach ($this->extra_fields as $key => $ef) {
                        $field=array();
                        $field["mode"]="extra";
                        $field["index"]=$key;
                        $tmp=sprintf("%05d",$ef["pos"]);
                        $all_fields_array["$tmp"."_1"."$i"]=$field;
                        $i=$i+1;
                    };
                    ksort($all_fields_array);

                    foreach($all_fields_array as $field) {
                        $n_field=$field["index"];
                        if ($field["mode"]=="field") {
                            if(trim($this->fields[$n_field])=="")
                                $data.="<th nowrap>&nbsp;</th>";
                            else
                                $data.="<th nowrap>".$this->fields[$n_field]."</th>";
                        };

                        if ($field["mode"]=="extra") {
                            $data.="<th nowrap>".$this->extra_fields[$n_field]["title"]."</th>";
                        }
                    }
                    //+2

                    if ($this->getActivateListing() == 1) {
                        $data.="<th nowrap>&nbsp;</th>";
                    }
                    if ($this->enableMod == 0) {
                        $data.="<th nowrap>&nbsp;</th>";
                    }
                    if ($this->enableDel == 0) {
                        $data.="<th nowrap>&nbsp;</th>";
                    }

                    $data.="</tr>";
                    $col0;

                    if (!$alldata) "<br />".LANG_ADMIN_NODATA."<br />";

                    //search columns
                    $ncolumns=sizeof($alldata[0]);

                    $data.="<tr><td> </td>";

                    for($i=1;$i<$this->numfields;$i++) {
                        $field_name="";
                        foreach($all_fields_array as $field){
                            if (($field["index"]==$i)&&($field["mode"]=="field")){
                                $field_name=$this->field_results[$i];
                            };
                        };
                        $data.="<td><input type=text size=10 id='search_columns[$i]' name='search_columns[$i]' value='".$search_columns[$i]."'></input><input type=hidden id='search_columns_name[$i]' name='search_columns_name[$i]' value='$field_name'></input></td>";
                    }
                    $data.="<td><input value='S' type='submit'/></td></tr>";

                    $item_k=1;
                    foreach($alldata as $datarow) {
                        $normal_fields=array();
                        if($col%2)
                            $data.="<tr class=\"darkcolor\">";
                        else
                            $data.="<tr class=\"lightcolor\">";
                        $i=0;

                        //$max=($this->activatelisting==1)?sizeof($rows)-1:sizeof($rows);
                        //$max=($this->activatelisting==1)?sizeof($datarow)-1:sizeof($rows);
                        $max=sizeof($datarow);


                        while($i<$max) {
                        //$str = $stringutil->clean_value($this->getRealValue($rows[$i],$field_name));
                        //$str = $stringutil->clean_value($this->getRealValue($datarow[$i]['data'],$field_name));
                            $str = $datarow[$i];
                            if(strlen($str) > $this->limit_display) {
                                $str=substr($str,0,$this->limit_display)." ...";
                            };

                            $normal_fields[$i]=$str;

                            $i++;
                        }


                        $item_id=$stringutil->str_hex($datarow[0]);

                        foreach($all_fields_array as $field) {
                            $str="";
                            $n_field=$field["index"];

                            if ($field["mode"]=="field") {
                                $str=$normal_fields[$n_field];
                            };
                            if ($field["mode"]=="extra") {
                                $function=$this->extra_fields[$n_field]["function"];
                                $function_class_or_object=$function["class_or_object"];
                                $function_name=$function["function"];
                                $parameter=$datarow[0];
                                $function_call="";
                                if (empty($function_class_or_object)) {
                                    $function_call=$function_name;
                                }else {
                                    $function_call=array($function_class_or_object,$function_name);
                                };


                                if (is_callable($function_call)) {
                                    $str=call_user_func_array($function_call,array($parameter));
                                }else {
                                    $str="";//the function could not be called
                                };
                            };
                            if($str != "") {
                                $data.="<td class=\"bodytext\">".$stringutil->cleanDescription2($str)."</td>";
                            }else {
                                $data.="<td class=\"bodytext\">"."&nbsp;"."</td>";
                            };

                        };

                        // TODO: activ
                        if ($this->getActivateListing() == 1) {
                            if ( $datarow[$max-1] == 1) { //if is active, green
                                $data.="<td class=\"bodytext\" width=\"24\"><a class=\"activate\"  href=\"".$_SERVER['PHP_SELF']."?do=activate&".$this->getFirstID()."=".$item_id."\"><img src=\"".CONF_INDEX_URL."images/admin/activate1.ico\" title=\"".LANG_ADMIN_DEACTIVATE."\"></a></td>";
                            }
                            else { //if inactive red
                                $data.="<td class=\"bodytext\" width=\"24\"><a class=\"activate\"  href=\"".$_SERVER['PHP_SELF']."?do=activate&".$this->getFirstID()."=".$item_id."\"><img src=\"".CONF_INDEX_URL."images/admin/activate0.ico\" title=\"".LANG_ADMIN_ACTIVATE."\"></a></td>";
                            }
                        }

                        if ($this->enableMod == 0) {
                            $data.="<td class=\"bodytext\" width=\"24\"><a class=\"mod\"  href=\"".$_SERVER['PHP_SELF']."?do=mod&".$this->getFirstID()."=".$item_id."\"><img src=\"".CONF_INDEX_URL."images/admin/mod.ico\" title=\"".LANG_ADMIN_MODIFY."\"></a></td>";
                        }


                        if ($this->enableDel == 0) {
                            $data.="<td class=\"bodytext\" width=\"24\"><a class=\"del\"  href=\"".$_SERVER['PHP_SELF']."?do=del&".$this->getFirstID()."=".$item_id."\" onclick=\"return confirm('".LANG_ADMIN_CONFIRMDELETE."')\"><img src=\"".CONF_INDEX_URL."images/admin/del.ico\" title=\"".LANG_ADMIN_DELETE."\" ></a></td>";
                        }


                        $data.="</tr>\n";
                        $col++;
                        $item_k=$item_k+1;
                    }

                    $data.="</table></form>";



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

                /**
                 * Convert the data according to the replace rules
                 *
                 * @param <string> $id_data
                 * @param <string> $field_name
                 * @return <string>
                 */
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

                /**
                 * Makes cls_listing to replace the column with data from a database
                 * If you use file as a database, you must call this function before init_base64_array_from_file()
                 * @param <string> $field The field from where data will be replaced
                 * @param <string> $table The table from where the data will be replace
                 * @param <string> $id_column The id which is linked with $field
                 * @param <string> $data_column The data which replace the field
                 */

		public function setReplaceColumnIdFromDatabase($field,$table,$id_column,$data_column){
			$rpl=array();
			$rpl["mode"]="database";
			$rpl["table"]=$table;
			$rpl["id_column"]=$id_column;
			$rpl["data_column"]=$data_column;

			$this->replace_table[$field]=$rpl;
		}

                /**
                 * Makes cls_listing to replace the column with data from a array
                 * If you use file as a database, you must call this function before init_base64_array_from_file()
                 * @param <string> $field The field from where data will be replaced
                 * @param <array> $array The array which will be used to replace the $field data
                 */
		public function setReplaceColumnIdFromArray($field,$array){
			$rpl=array();
			$rpl["mode"]="array";
			$rpl["array"]=$array;
			$this->replace_table[$field]=$rpl;
		}

                /**
                 * Add a new column which with data returned by a function or method
                 *
                 * @param <int> $position The position where the new column will be added
                 * @param <string> $title The title of the new column
                 * @param <string> $function_or_method The function which will take the first column as the parameter
                 * @param <string or object> $class_or_object The class name or the object where the $function_or_method is.
                 */
		public function addNewFunctionColumn($position,$title,$function_or_method,$class_or_object){
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
