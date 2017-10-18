<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class Search extends Database {
	var $module;
	public function __construct($params=NULL){
		$this->module = $params['module'];
		parent::__construct((empty($params['table']))?$module:$params['table']);
		if(isset($params['primary_key'])){
			$this->primary_key = $params['primary_key'];
		}
		if(isset($params['parent_table'])){
			$this->parent_table = $params['parent_table'];
		}
		if(isset($params['parent_translate_table'])){
			$this->parent_translate_table = $params['parent_translate_table'];
		}
		if(isset($params['parent_primary_key'])){
			$this->parent_primary_key = $params['parent_primary_key'];
		}
		if(isset($params['site_language'])){
			$this->site_language = $params['site_language'];
		}
		if(isset($params['is_translate'])){
		 	$this->is_translate = $params['is_translate'];
	 	}
		if(isset($params['translate_table'])){
			$this->translate_table = $params['translate_table'];
		}
	}
	public function getSearchSize($search_modules, $keyword){
		$keyword = str_replace(' ','%',$keyword);
		$sql_union = array();
		$cnt = 0;
		$all_result = 0;
		foreach($search_modules as $key =>$val ){
				if($val['allow']){
				$field_list = '';
				$table = $val['table'];
				$module =$key ;
				foreach($val['fields'] as $k => $f){
					$field_list .= " ".$table.".".$f." LIKE '".$keyword."%' ";
					if($k<count($val['fields'])-1){
						$field_list .=' || ' ;
					}
				}// foreach
				$this->sql = " SELECT * FROM ".$table." WHERE ".$field_list." "; 
				$all_result += $this->select('size');
				$cnt++;
			}// if allow
		}// foreach
		return $all_result;
	}
	public function getSearchAll($search_modules,$keyword){
		$keyword = str_replace(' ','%',$keyword);
		$sql_union = array();
		$results = array();
		$cnt = 0;
		foreach($search_modules as $key => $val){
			if($val['allow']){
				$field_list = '';
				$table = $val['table'];
				$module = $key;
				foreach($val['fields'] as $k => $f){
					$field_list .= " ".$table.".".$f." LIKE  '".$keyword."%' ";
					if($k<count($val['fields'])-1){
						$field_list .=' || ' ;
					}
				}// foreach
				$this->sql = " SELECT ".$val['show']." FROM ".$table." WHERE ".$field_list." "; 
				$this->select();
				if(!empty($this->rows)){
					foreach($this->rows as $row){
						$results[$cnt]['data'] = $row;
						$results[$cnt]['module'] = $module;
						$results[$cnt]['link'] = $val['link'];
						$cnt++;
					}
				}
			}// if allow
		}// foreach
		return  $results;
	}
	public function getSearch($id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0];
	}
}
?>