<?php
class Search extends Database {
	var $module   ;
	public function __construct($module,$table=NULL){
		$this->module = (empty($table))?$module:$table  ;
		 parent::__construct((empty($table))?$module:$table );
		// $this->parent_table  = 'news_categories';
		//$this->parent_primary_key = 'id';
	}
	
// function for rate  /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
///////////////////////////////////////////////////////////////////////////////////
	public function getSearchSize($search_modules, $keyword){
		$keyword = str_replace(' ','%',$keyword);
		$sql_union = array();
		$cnt = 0 ;
		$all_result = 0 ;
		foreach($search_modules as $key =>$val ){
				if($val['allow']){
				$field_list = '';
				$table = $val['table'] ;
				$module =$key ;
				foreach($val['fields'] as $k => $f){
					$field_list .= " ".$table.".".$f." LIKE  '".$keyword."%' " ;
					if($k<count($val['fields'])-1){
						$field_list .=' || ' ;
					}
				}// foreach
				$this->sql = " SELECT * FROM ".$table." WHERE ".$field_list." "; 
				$all_result += $this->select('size') ;
				$cnt++;
			}// if allow
		}// foreach
		return $all_result  ;
	}
	
	public function getSearchAll($search_modules, $keyword){
		$keyword = str_replace(' ','%',$keyword);
		$sql_union = array();
		$results = array() ;
		$cnt = 0 ;
		foreach($search_modules as $key =>$val ){
			if($val['allow']){
				$field_list = '';
				$table = $val['table'] ;
				$module =$key ;
				foreach($val['fields'] as $k => $f){
					$field_list .= " ".$table.".".$f." LIKE  '".$keyword."%' " ;
					if($k<count($val['fields'])-1){
						$field_list .=' || ' ;
					}
				}// foreach
				$this->sql = " SELECT ".$val['show']." FROM ".$table." WHERE ".$field_list." "; 
				$this->select() ;
				if(!empty($this->rows)){
					foreach($this->rows as $row){
						$results[$cnt]['data'] = $row;
						$results[$cnt]['module'] = $module ;
						$results[$cnt]['link'] = $val['link'] ;
						$cnt++ ;
					}
				}
			}// if allow
		}// foreach
		return  $results ;
	}
	
	public function getSearch($id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		//echo $this->sql  ;
		$this->select();
		return  $this->rows[0] ;
	}
}
?>