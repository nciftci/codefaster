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
		protected $disable_search_columns;
		private $disable_searching;

		private $object_results;
		private $mode;
		private $disable_sorting;

		private $editable_table_ajax_url;
		private $editable_table_columns_array; 

		private $search_howto_text;
		private $secondary_sort;


		public function __construct() {
			$this->offset=0;
			$this->page=1;
			$this->limit_display=150;
			$this->enableDel = 0;
			$this->enableMod = 0;
			$this->replace_table=array();
			$this->search_dropdown_table=array();
			$this->extra_fields=array();
			$this->rs=null;
			$this->numrows=0;
			$this->numfields=0;
			$this->mode="";
			$this->sort_column=null;
			$this->sort_reverse=false;
			$this->search_term="";
			$this->disable_search_columns=array();
			$this->disable_sorting=false;
			$this->disable_searching=false;
			$this->disable_id_column=false;

			$this->editable_table_ajax_url="";
			$this->editable_table="";
			$this->editable_table_columns_array=array();
			
			$this->default_search_columns=array();
			

			//check if search is modified	
			$this->search_columns_changed=false;	
			$search_columns=serialize($_REQUEST["search_columns"]);
			$search_columns_hash=md5($search_columns);
			$search_session_name=$this->getSearchSessionName();
			
			if (($search_columns_hash!=$_SESSION[$search_session_name]['hash'])&&
			!empty($_SESSION[$search_session_name]['hash'])&& !empty($_REQUEST["search_columns"])){
				$this->search_columns_changed=true;	
			};
			$_SESSION[$search_session_name]['hash']=$search_columns_hash;

			//search special modes

			$this->special_modes_search=array();

			//other search info
			
			$this->search_howto_text=LANG_SEARCH_HOWTO;
			
			$this->secondary_sort=array("ORDER" => array(), "REVERSE" => array());
		}



		/**
		 *
		 * Set search text howto
		 *
		 * @param array $howto
		 *
		 */

		function setSearchHowto($howto){
			$this->search_howto_text=$howto;
		}

		/**
		 * Set special modes of searching (date mode, age range,etc)
		 * 
		 * @param array $modes
		 * the format of the array is 
		 * array( $n_column => "mode1",...)
		 *
		 * modes are:
		 * "DATE_DDMMYYYY","AGE_RANGE"
		 * "LIKE", "LIKE_LEFT","LIKE_RIGHT","LIKE_BOTH"
		 *
		 */

		function setSpecialSearchModes($special_modes){
			$this->special_modes_search=$special_modes;
		}

		/**
		 * Verifies if search filter is modified
		 */

		public function isSearchModified(){
			return $this->search_columns_changed;
		}


		/**
		 * Set default search columns
		 *
		 * @param array $search_columns
		 *
		 */

		public function setDefaultSearchColumns($search_columns){
			$this->default_search_columns=$search_columns;
		}


		/**
		 *  Enable editable table 
 *
 *  @param string $ajax_url  The url of the AJAX
 *  @param string $table the mysql table where edit takes place
 *  @param array $columns_array the array which contains which columns are editable
 */
		public function enable_editable_table($ajax_url,$table,$columns_array){
			$this->editable_table_ajax_url=$ajax_url;
			$this->editable_table=$table;
			$this->editable_table_columns_array=$columns_array;
		}

		/**
		 * Set sort column
		 *
		 * @param string $sort_column The column
		 * @param boolean $sort_reverse Sort order: (true for reverse)
 */
                public function set_sort($sort_column,$sort_reverse=false){
                        $this->sort_column=$sort_column;
                        $this->sort_reverse=$sort_reverse;

			if ($this->mode=="mysql")
                        {
                            //find out the column types
                            $sql=$this->sql." LIMIT 1";
                            $tmprs= mysql_query($sql);
                            $nfields = mysql_num_fields($tmprs);
                            $field_types=array();

                            for ($i=0; $i < $nfields; $i++) {
                                    $type  = mysql_field_type($tmprs, $i);
                                    $name  = mysql_field_name($tmprs, $i);
                                    $field_types[$name]=$type;
                            };
                        }
                        if ($this->mode=="mysql"){
							$this->sql_sort_query="";
							if (!empty($this->sort_column)){
                                if ($field_types[$sort_column] == "string")
                                {
                                    $this->sql_sort_query="ORDER BY LOWER($sort_column)";
                                }
                                else
                                {
                                    $this->sql_sort_query="ORDER BY $sort_column";
                                }
                            	if ($sort_reverse) $this->sql_sort_query.=" DESC ";
							}
							$secondary_sort_order=$this->secondary_sort["ORDER"];
							$secondary_sort_reverse=$this->secondary_sort["REVERSE"];
							if (!empty($secondary_sort_order)){
								foreach ($secondary_sort_order as $column){
									if ($column==$this->sort_column) continue;
									if (empty($this->sql_sort_query)) {
										$this->sql_sort_query="ORDER BY ";
									}else{
										$this->sql_sort_query.=", ";
									};
                                                                        if ($field_types[$column] == "string")
                                                                        {
                                                                            $this->sql_sort_query.="LOWER($column) ";
                                                                        }
                                                                        else
                                                                        {
                                                                            $this->sql_sort_query.="$column ";
                                                                        }
									if (in_array($column,$secondary_sort_reverse)) $this->sql_sort_query.=" DESC ";
								};
							};
                        };

                }

		/**
		 *
		 * Set secondary sort columns
		 *
		 * @param array secondary_columns_order
		 * @param array secondary_columns_reverse - which columns from 'secondary_columns' are reverse
		 *
		 *
		 * example: $obj->set_secondary_sort_columns(array("name","state"),array("state"))
		 *
		 */

		public function setSecondarySortColumns($secondary_columns_order=array(),$secondary_columns_reverse=array()){
			$this->secondary_sort=array("ORDER" => $secondary_columns_order, "REVERSE" => $secondary_columns_reverse);
		}

/**
 * Reset sorting
 */

                public function reset_sort(){
                        $this->sort_column=null;
                        $this->sort_reverse=false;
                        $this->sql_sort_query="";
                }

/*
 * Disable id column
 */
				public function disable_id_column(){
					$this->disable_id_column=true;
				}

/*
 * Disable sorting
 */
				public function disable_sorting(){
					$this->disable_sorting=true;
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
 * Disable search columns
 */
                public function set_disable_search_columns($disable_sort_columns_array){
                        $this->disable_search_columns=$disable_sort_columns_array;
                }

/*
 * Disable searching
 */
				public function disable_searching(){
					$this->setSearchHowto("");
					$this->disable_searching=true;
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

						//error_log($query);
						$this->sql=$query;
						$this->sql_sort_query=$query_sort;
						$this->fields[0]="";
						$this->rs=mysql_query($this->sql);
						$this->numrows=mysql_num_rows($this->rs);
						$this->numfields=mysql_num_fields($this->rs);


						$fields=array();
                        for ($k=0;$k<$this->numfields;$k++){
                            $tmp=mysql_fetch_field($this->rs,$k);
                            $name=$tmp->name;
                            //if ($tmp->numeric) $this->disable_search_columns[]=$name;
                            $fields[$k]=$name;
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

				//find out the column types
				$sql=$this->sql." LIMIT 1";
				$tmprs= mysql_query($sql);
				$nfields = mysql_num_fields($tmprs);
				$field_types=array();
				for ($i=0; $i < $nfields; $i++) {
					$type  = mysql_field_type($tmprs, $i);
					$name  = mysql_field_name($tmprs, $i);
					$field_types[$name]=$type;
				};

				//get the data
				$sql=$this->sql;
				$search_where="";

				foreach($search_columns as $key=>$search_column){
					$name=$search_columns_names[$key];
					if ($search_column!=="") {
						if (!empty($search_where)) $search_where.="AND ";
						$special_mode=$this->special_modes_search[$key];
						if (!empty($special_mode)){
							$srch=$search_column;
							if ($special_mode=="DATE_DDMMYYYY"){
								$srch=date("Y-m-d",strtotime($srch));
								$search_where.="$name = '".mysql_real_escape_string($srch)."' ";
							};
							if ($special_mode=="AGE_RANGE"){
								$ages_array=explode("-",$srch);
								if (count($ages_array)==1){
									$ages_array[1]=$ages_array[0];
								};
								$min=$ages_array[0];
								if (!is_numeric($min)) $min=0;
								$max=$ages_array[1];
								if (!is_numeric($max)) $max=0;

								$search_where=" (CAST(DATEDIFF(now(),date_of_birth)/365.25 AS UNSIGNED)) >= '$min' " ;
								$search_where.=" AND (CAST(DATEDIFF(now(),date_of_birth)/365.25 AS UNSIGNED)) <= '$max' " ;
								
							};
							if ($special_mode=="LIKE"){
								$search_where.="LOWER($name) LIKE LOWER('".mysql_real_escape_string($search_column)."') ";
							};
							if ($special_mode=="LIKE_LEFT"){
								$search_where.="LOWER($name) LIKE LOWER('%".mysql_real_escape_string($search_column)."') ";
							};
							if ($special_mode=="LIKE_RIGHT"){
								$search_where.="LOWER($name) LIKE LOWER('".mysql_real_escape_string($search_column)."%') ";
							};
							if ($special_mode=="LIKE_BOTH"){
								$search_where.="LOWER($name) LIKE LOWER('%".mysql_real_escape_string($search_column)."%') ";
							};
						}else{
							if ($field_types[$name]=="name") {
								$search_where.="$name = '".mysql_real_escape_string($search_column)."' ";
							}else{
								$search_where.="LOWER($name) LIKE LOWER('".mysql_real_escape_string($search_column)."%') ";
							};
						};
					}
				};

				if (!empty($search_where)) {
					if (!stristr($this->sql," where ")) {
						$search_where=" WHERE ".$search_where;
					} else {
						$split=preg_split('/ where /i',$this->sql);
						if (sizeof($split)==2){
							$split[1]="$search_where AND ".$split[1];
							$sql=implode(" WHERE ",$split);

						}
						$search_where="";
					}
				};

				$limited=$sql.$search_where;
				if (!empty($this->sql_sort_query)) $limited.=$this->sql_sort_query;
                                //error_log($limited);
				$allresult = mysql_query($limited) or die(mysql_error());
				$this->numrows = mysql_num_rows($allresult);
				$limited.=" LIMIT ".$this->offset." , ".$this->limit;


				//error_log($limited);

				$this->rs=mysql_query($limited) or die(mysql_error());
		//		if(strstr(mysql_field_flags($this->rs, 0),"primary_key")==false || mysql_field_type($this->rs,0)!="int")
		//      	return false;

				if (mysql_num_rows($this->rs)==0) return false;

				$k=0;
				while($rows=mysql_fetch_array($this->rs,MYSQL_NUM)){
					$max=sizeof($rows);
					$i=0;
					while($i<$max) {
						$field_name=mysql_field_name($this->rs,$i);
						$val=$this->getRealValue($rows[$i],$field_name,$rows[0]);
						if ($this->replace_table[$field_name]["mode"]!="function"){
							$stringutil->clean_value($val);
						};
						$resultrow[$i]=$val;
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
                                        if (!empty($search_columns)){
                                            $notfound=false;
                                            foreach($resultrow as $key=>$value){
                                                $search_column=$search_columns[$key];
                                                if (!empty($search_column)){
                                                    if (!stristr($value,$search_column)){
                                                        $notfound=true;
                                                    }
                                                }
                                            };
                                            if ($notfound) continue;
                                        }
					$result[$k]=$resultrow;
					$k=$k+1;
				};

			};

                        if ($this->mode=="base64_array") return $this->array_data;

			return $result;

		}


		private function getSearchSessionName(){
			return "search_".$_SERVER['PHP_SELF'];
		}

		public function listPages() {
                    $search_columns=$_REQUEST["search_columns"];
                    $search_columns_names=$_REQUEST["search_columns_name"];
                    $self_page=$_SERVER['REQUEST_URI'];
                    
                    $search_session_name=$this->getSearchSessionName();

					if (!empty($this->default_search_columns)){
						foreach ($this->default_search_columns as $name=>$value){
							if (!in_array($name,$_SESSION[$search_session_name]["columns_name"])){
								$_SESSION[$search_session_name]["columns_name"][]=$name;
								$_SESSION[$search_session_name]["columns"][]=$value;
							};
						};
					};

                    if ($search_columns==null) $search_columns=$_SESSION[$search_session_name]["columns"];
                        else $_SESSION[$search_session_name]["columns"]=$search_columns;
                    if ($search_columns_names==null) $search_columns_names=$_SESSION[$search_session_name]["columns_name"];
                        else  $_SESSION[$search_session_name]["columns_name"]=$search_columns_names;


					if ($this->isSearchModified()){
						$this->offset=0;
					};


                    $sort_column=$_REQUEST["sort_column"];
                    $sort_reverse=!(empty($_REQUEST["sort_reverse"]));
					if (empty($sort_column)) {
						$sort_column=$this->sort_column;
						$sort_reverse=$this->sort_reverse;
					};	
					$this->set_sort($sort_column,$sort_reverse);

                    $alldata=$this->get_data($search_columns,$search_columns_names);
//					if (empty($alldata)){
//						return "<p class=\"mWarning\">".LANG_NO_DATA_IN_DATABASE."</p>";
//					};

                    $stringutil = new String();


					$data="<form action='$self_page' method='post' id='form' name='form'>";
					if (!empty($this->search_howto_text)) {
						$data.="<div class='mInfo'>".$this->search_howto_text."</div><br class='clearBoth' />";
					};
					$data.="<table width=\"99%\" cellspacing=\"0\" align=\"center\" id=\"listingtable\">";
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


					$fk=0;
					foreach($all_fields_array as $field) {
						$fk+=1;
						//nou
						if (($fk==1) && ($this->disable_id_column)) $data.="<!--";
						$n_field=$field["index"];

						//make sort url
                                                $base_self_page=explode("?",$self_page);
						$self_page_array=explode("&",$base_self_page[1]);
						$new_self_page_array=array();
						foreach($self_page_array as $item){
							if (strstr($item,"sort_column=")) continue;
							if (strstr($item,"sort_reverse=")) continue;
							$new_self_page_array[]=$item;
						}
						$hrefurl=$base_self_page[0]."?".implode("&",$new_self_page_array);
						//if (strstr($hrefurl,"?")) $hrefurl.="&";
						//else $hrefurl.="?";
						$hrefurl.="&sort_column=".$this->field_results[$n_field];
						if ((!$sort_reverse)&&($this->field_results[$n_field]==$sort_column)) 
						{ 
							$hrefurl.="&sort_reverse=1";
							$ascdesc = "desc";
						}
						else 
						{
							$ascdesc = "asc";
						}


						if ($field["mode"]=="field") {
							if(trim($this->fields[$n_field])==""){
								$data.="<th nowrap>&nbsp;</th>";
							} else {
								if ($this->disable_sorting){
									$data.="<th nowrap>".$this->fields[$n_field]."	<span class='".$ascdesc."'></span></th>";
								}else{
                                                                	$data.="<th nowrap><a href='".$hrefurl."'>".$this->fields[$n_field]."<span class='".$ascdesc."'></span></a></th>";
								};
							};
						};

						if ($field["mode"]=="extra") {
							$data.="<th nowrap>".$this->extra_fields[$n_field]["title"]."</th>";
						}
						if (($fk==1) && ($this->disable_id_column))$data.= "-->";
					}
                    //+2

                    if ($this->getActivateListing() == 1) {
                        $data.="<th nowrap>&nbsp;</th>";
                    }
                    if ($this->enableMod == 0) {
                        $data.="<th nowrap>".LANG_EDIT."</th>";
                    }
                    if ($this->enableDel == 0) {
                        $data.="<th nowrap>&nbsp;</th>";
                    }

                    $data.="</tr>";
                    $col0;

                    if (!$alldata) "<br />".LANG_ADMIN_NODATA."<br />";

                    //search columns
                    $ncolumns=sizeof($alldata[0]);
					if (!$this->disable_searching){
						$data.="<tr>";
						$k=0;
						$fk=0;
						foreach($all_fields_array as $field) {
							$fk+=1;
							if (($fk==1) && ($this->disable_id_column))$data.= "<!--";

							$n_field=$field["index"];
							//verify to be only the first column with text search.
							if ($k!=0) {
								$no_url_data="<td class='noborder'>&nbsp;</td>";
							}else{
								$no_url_data.="<td class='noborder'>".LANG_ADMIN_SEARCH."</td>";
							};
							if ($field["mode"]=="field"){
								$field_name=$this->field_results[$k];
								if (in_array($field_name,$this->disable_search_columns)){
									$data.=$no_url_data;
								}else{
									if (empty($this->search_dropdown_table[$field_name])){
										$data.="<td class='noborder'><input onchange='document.form.submit()' class=\"clslisting\" type=text size=10 id='search_columns[$n_field]' name='search_columns[$n_field]' value='".$search_columns[$n_field]."'></input><input type=hidden id='search_columns_name[$n_field]' name='search_columns_name[$n_field]' value='$field_name'></input></td>";
									}else{
										$data.="<td class='noborder'><select class=\"clslisting\" id='search_columns[$n_field]' name='search_columns[$n_field]' onchange='document.form.submit()'>";
										$selected=($search_columns[$n_field]=="")?"selected":"";
										$data.="<option value='' $selected>".LANG_ADMIN_SELECT_ALL_DROPDOWN."</option>";

										foreach($this->search_dropdown_table[$field_name]["array"] as $key=>$val){
											$selected="";	
											if (($search_columns[$n_field]==$key)&&($search_columns[$n_field]!="")) {
												$selected="selected";
											};
											
											$pos=array_search($field_name,$_SESSION[$search_session_name]["columns_name"]);
											if ($pos!==False){
												if ($key==$_SESSION[$search_session_name]["columns"][$pos] && ($key!="")) $selected="selected";
											};
											$data.="<option value='$key' $selected>$val</option>";
										};
										$data.="</input><input type=hidden id='search_columns_name[$n_field]' name='search_columns_name[$n_field]' value='$field_name' ></input></td>";
									};
								}
								$k=$k+1;
							}else{
								$data.=$no_url_data;
							};

							if (($fk==1) && ($this->disable_id_column))$data.= "-->";
						}
						$data.="<td class='noborder'>
							&nbsp;</td>";
						// colomns at the end to close the search.
						if ($this->getActivateListing() == 1) {
							$data.="<td class='noborder'>&nbsp;</td><td class='noborder'>&nbsp;</td>";
						}
						else {
							$data.="<td class='noborder'>&nbsp;</td>";
						} 
						$data.="</tr>";
					};
                    $item_k=1;
                    foreach($alldata as $datarow) {
                        $normal_fields=array();
                         $max=sizeof($datarow);
                       $inactive = "";
                        if ( $datarow[$max-1] == 0 || $datarow[$max-1] == 4 || $datarow[$max-1] == 5 || $datarow[$max-1] == 7)
                        {
                            $inactive = "inactive";
                        }
                        if ( $datarow[$max-1] == 2)
                        {
                            $inactive = "p_freeze";
                        }
                        if ( $datarow[$max-1] == 3)
                        {
                            $inactive = "freezed";
                        }
						if (strpos($datarow[$max-2],"Active")!==False){
							$inactive="";
						};
                        if($col%2)
                            $data.="<tr class=\"darkcolor $inactive\" onmouseover=\"style.backgroundColor='#E0E5E9';\"
onmouseout=\"style.backgroundColor='#F2F6F7'\">"; 
                        else
                            $data.="<tr class=\"lightcolor $inactive\" onmouseover=\"style.backgroundColor='#E0E5E9';\"
onmouseout=\"style.backgroundColor='#F2F6F7'\">";
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

						$fk=0;
                        foreach($all_fields_array as $field) {
							$fk+=1;
							if (($fk==1) && ($this->disable_id_column)) $data.="<!--";
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
							$editable="";
							$columnname=$this->field_results[$n_field];
							if (in_array($columnname,$this->editable_table_columns_array)){
								$idname=$this->field_results[0];
								$idvalue=$datarow[0];
								$edittableurl=$this->editable_table_ajax_url."?do=change&table=".$this->editable_table."&idname=$idname&idvalue=$idvalue&columnname=$columnname&securestring=".md5("#$this->editable_table#$idname#$idvalue#$columnname#".USER."#".PASSWORD."#");
								$editable="ondblclick='EditTableCell(this,\"$edittableurl\")'";
							};
							if($str != "") {
								$data.="<td class=\"bodytext\" $editable >".$stringutil->cleanDescription2(htmlspecialchars_decode($str))."</td>";
                            }else {
                                $data.="<td class=\"bodytext\" $editable>"." "."</td>";
                            };

							if (($fk==1) && ($this->disable_id_column)) $data.="-->";
                        };

                        // TODO: activ
                        if ($this->getActivateListing() == 1) {
                            if ( $datarow[$max-1] == 1) { //if is active, green
                                $data.="<td class=\"bodytext\"><a class=\"activate\"  href=\"".$_SERVER['PHP_SELF']."?do=activate&".$this->getFirstID()."=".$item_id."\" onclick=\"return confirm('".LANG_ADMIN_CONFIRMDEACTIVATE."')\"><img src=\"".CONF_INDEX_URL."images/admin/activate1.gif\" title=\"".LANG_ADMIN_DEACTIVATE."\"></a></td>";
                            }
                            else { //if inactive red
                                $data.="<td class=\"bodytext\"><a class=\"activate\"  href=\"".$_SERVER['PHP_SELF']."?do=activate&".$this->getFirstID()."=".$item_id."\" onclick=\"return confirm('".LANG_ADMIN_CONFIRMACTIVATE."')\"><img src=\"".CONF_INDEX_URL."images/admin/activate0.gif\" title=\"".LANG_ADMIN_ACTIVATE."\"></a></td>";
                            }
                        }

                        if ($this->enableMod == 0) {
                            $data.="<td class=\"bodytext\"><a class=\"mod\"  href=\"".$_SERVER['PHP_SELF']."?do=mod&".$this->getFirstID()."=".$item_id."\"><img src=\"".CONF_INDEX_URL."images/admin/mod.gif\" title=\"".LANG_ADMIN_MODIFY."\"></a></td>";
                        }


                        if ($this->enableDel == 0) {
                            $data.="<td class=\"bodytext\"><a class=\"del\"  href=\"".$_SERVER['PHP_SELF']."?do=del&".$this->getFirstID()."=".$item_id."\" onclick=\"return confirm('".LANG_ADMIN_CONFIRMDELETE."')\"><img src=\"".CONF_INDEX_URL."images/admin/del.gif\" title=\"".LANG_ADMIN_DELETE."\" ></a></td>";
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
				 * @param <id> $id
                 * @return <string>
                 */
		protected function getRealValue($id_data,$field_name,$id_0){


			if (isset($this->replace_table[$field_name])){
				if (($this->replace_table[$field_name]["mode"]=="function")){
					 $f=$this->replace_table[$field_name];
					 $str=call_user_func_array($f['function'],array($id_0,$id_data,$field_name));
					 return $str;

				};

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
                 * Makes cls_listing to replace the column with data from a function 
                 */

		public function setReplaceColumnIdFromFunction($field,$function){
			$rpl=array();
			$rpl["mode"]="function";
			$rpl["function"]=$function;

			$this->replace_table[$field]=$rpl;
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
			if ($title=="") $ef["title"]="&nbsp;";
			else $ef["title"]=$title;
			$ef["function"]=array("class_or_object" => $class_or_object,
								  "function" => $function_or_method);
			$this->extra_fields[$new_index]=$ef;
		}


		/**
                 * Use search with dropdown on a column 
                 * 
                 * @param <string> $field The field for which the search textbox  will be replaced with the dropdown
                 * @param <array> $array The array which will be used to replace the $field data
                 */
		public function setSearchDropdownArray($field,$array){
			$rpl=array();
			$rpl["mode"]="array";
			$rpl["array"]=$array;
			$this->search_dropdown_table[$field]=$rpl;
		}
	}
?>
