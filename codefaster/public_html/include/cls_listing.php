<?php
	class Listing {
		var $sql;
		var $rs;
		var $numrows;
		var $numfields;
		var $fields;
		var $limit;
		var $firstid;
		var $activatelisting;
		var $activename;
		var $noofpage;
		var $offset;
		var $page;
		var $style;
		var $parameter;
		var $activestyle;
		var $buttonstyle;
		var $limit_display;
		
		function Listing($query) {
			$this->offset=0;
			$this->page=1;
			$this->sql=$query;
			$this->fields[0]="";
			$this->limit_display=150;
			$this->rs=mysql_query($this->sql);
			$this->numrows=mysql_num_rows($this->rs);
			$this->numfields=mysql_num_fields($this->rs);
		}
		function getNumRows() {
			return $this->numrows;
		}
		function setLimit($no) {
			$this->limit=$no;
		}
		// ID field name
        function setFirstID($id) {
            $this->firstid=$id;
        }
		function getFirstID() {
			return $this->firstid;
		}	
		// ID field name
		// end item activate exist	
        function setActivateListing($idlist) {
            $this->activatelisting=$idlist;
        }
		function getActivateListing() {
			return $this->activatelisting;
		}
		// Active field name
        function setActivateFieldName($name) {
            $this->activename=$name;
        }
		function getActivateFieldName() {
			return $this->activename;
		}		
		// end item activate exist			
		function getLimit() {
			return $this->limit;
		}
		function getNoOfPages() {
			return ceil($this->noofpage=($this->getNumRows()/$this->getLimit()));
		}

		function getOffset($page) {
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
		function getPage() {
			return $this->page;
		}
		function listPages() {
			$limited=$this->sql." limit ".$this->offset." , ".$this->limit;
			$this->rs=mysql_query($limited) or die($limited);
			
			if(strstr(mysql_field_flags($this->rs, 0),"primary_key")==false || mysql_field_type($this->rs,0)!="int")
				return false;
			$stringutil = new String();
			
			$data="<table width=\"99%\" cellspacing=\"0\" align=\"center\" id=\"listingtable\">";
			$data.="<tr class=\"theader\">";
			$n=0;
			
			$max=sizeof($this->fields);
			//echo $max."sd";
			//print_R($this->fields);
			while($n < $max)
			{
				if(trim($this->fields[$n])=="")
					$data.="<th nowrap>&nbsp;</th>";
				else 
					$data.="<th nowrap>".$this->fields[$n]."</th>";
				$n++;
			}
			//+2
		    
			$data.="<th nowrap>&nbsp;</th>";
			$data.="<th nowrap>&nbsp;</th>";
			
			$data.="</tr>";
			$col=0;
			if (mysql_num_rows($this->rs)==0) return "<br />".LANG_ADMIN_NODATA."<br />";
			
			
			while($rows=mysql_fetch_array($this->rs,MYSQL_NUM))
			{	
				if($col%2)
					$data.="<tr class=\"darkcolor\">";
				else 
					$data.="<tr class=\"lightcolor\">";
				$i=0;	
               
				$max=($this->activatelisting==1)?sizeof($rows)-1:sizeof($rows);
				
				while($i<$max)
				{	
					$str = $stringutil->clean_value($rows[$i]);
					if(strlen($str) > $this->limit_display)
					{
						$str=substr($str,0,$this->limit_display)." ...";
					}

					if($str != "")
						$data.="<td class=\"bodytext\">".$stringutil->cleanDescription2($str)."</td>";
					else
						$data.="<td class=\"bodytext\">"."&nbsp;"."</td>";
					
					$i++;
				}
				
				// TODO: activ
				if ($this->getActivateListing() == 1) {
					if ( $rows[$max] == 1) { //if is active, green
					$data.="<td class=\"bodytext\"><a class=\"activate\"  href=\"".$_SERVER['PHP_SELF']."?do=activate&".$this->getFirstID()."=".$rows[0]."\"><img src=\"".CONF_INDEX_URL."images/admin/activate1.ico\" title=\"".LANG_ADMIN_DEACTIVATE."\"></a></td>";
					}
					else { //if inactive red
					$data.="<td class=\"bodytext\"><a class=\"activate\"  href=\"".$_SERVER['PHP_SELF']."?do=activate&".$this->getFirstID()."=".$rows[0]."\"><img src=\"".CONF_INDEX_URL."images/admin/activate0.ico\" title=\"".LANG_ADMIN_ACTIVATE."\"></a></td>";
					}
				}
				
				$data.="<td class=\"bodytext\"><a class=\"mod\"  href=\"".$_SERVER['PHP_SELF']."?do=mod&".$this->getFirstID()."=".$rows[0]."\"><img src=\"".CONF_INDEX_URL."images/admin/mod.ico\" title=\"".LANG_ADMIN_MODIFY."\"></a></td>";
				

				
				$data.="<td class=\"bodytext\"><a class=\"del\"  href=\"".$_SERVER['PHP_SELF']."?do=del&".$this->getFirstID()."=".$rows[0]."\" onclick=\"return confirm('".LANG_ADMIN_CONFIRMDELETE."')\"><img src=\"".CONF_INDEX_URL."images/admin/del.ico\" title=\"".LANG_ADMIN_DELETE."\" ></a></td>";
				$data.="</tr>\n";
				$col++;
			}

			$data.="</table>";
			return $data;
		}
		function setFields($fields) {
			$this->fields=$fields;
		}
		function getFields() {
			return $this->fields;
		}
		function getFieldCount() {
			return $this->numfields();
		}
		function setLimitDisplay($disp) {
			$this->limit_display=$disp;
		}
		function setStyle($style) {
			$this->style=$style;
		}
		function getStyle() {
			return $this->style;
		}
		function setActiveStyle($style) {
			$this->activestyle=$style;
		}
		function getActiveStyle() {
			return $this->activestyle;
		}
		function setButtonStyle($style) {
			$this->buttonstyle=$style;
		}
		function getButtonStyle() {
			return $this->buttonstyle;
		}
		function setParameter($parameter) {
			$this->parameter=$parameter;
		}
		function getParameter() {
			return $this->parameter;
		}
	}
?>