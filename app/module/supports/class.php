<?php
class Supports extends Database {
	var $module   ;
	var $security_code ;
	public function __construct($module,$table=NULL){
		$this->module = (empty($table))?$module:$table  ;
		 parent::__construct((empty($table))?$module:$table );
		 $this->parent_table  = 'users_categories';
		 $this->parent_primary_key = 'id';
		 $this->security_code = SECURITY_CODE ;
	}
	
	public function siteConfig(){
		$this->sql = " select * from configs_site  limit 0,1 ";
		$this->select();
		return $this->rows[0];
	}
	
}
?>