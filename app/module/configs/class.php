<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class Configs extends Database {
	var $module;
	public function __construct($module,$table=NULL){
		$this->module = (empty($table))?$module:$table;
		parent::__construct((empty($table))?$module:$table);
	}
	public function getConfigsSite(){
		$this->sql = "select * from $this->table limit 0,1 ";
		$this->select();
		$configs = $this->rows[0];
		$configs['languages'] =	$this->getLanguageAll();
		return $configs;
	}
	public function getConfigsSiteFront(){
		$this->sql = "select * from $this->table limit 0,1 ";
		$this->select();
		$configs = $this->rows[0];
		$configs['languages'] = $this->getLanguageUsage();
		return $configs;
	}
	public function insertConfigsSite($opensite,$sitename,$meta_keywords,$meta_description,$favicon_url,$underconstruction_text,$background_url){
		$this->sql = "insert into $this->table (opensite,sitename,meta_keywords,meta_description,favicon_url,underconstruction_text,background_url) ";
		$this->sql .= " values($opensite,'$sitename','$meta_keywords','$meta_descritption','$favicon_url','$underconstruction_text','$background_url') ";
		$this->insert();
	}
	public function updateConfigsSite($id,$opensite,$sitename,$meta_keywords,$meta_description,$favicon_url,$underconstruction_text,$background_url){
		$this->sql = "update $this->table set opensite=$opensite,sitename='$sitename',meta_keywords='$meta_keywords',meta_description='$meta_description',favicon_url='$favicon_url',underconstruction_text='$underconstruction_text',background_url='$background_url' where $this->primary_key=$id ";
		$this->update();
	}
	// configs email
	public function getConfigsEmail(){
		$this->sql = "select * from $this->table limit 0,1 ";
		$this->select();
		return $this->rows[0];
	}
	public function insertConfigsEmail($smtp,$smtp_secure,$smtp_server,$smtp_port,$smtp_user,$smtp_password){
		$this->sql = "insert into $this->table (smtp,smtp_secure,smtp_server,smtp_port,smtp_user,smtp_password) ";
		$this->sql .= " values($smtp,'$smtp_server','$smtp_port','$smtp_user','$smtp_password') ";
		$this->insert();
	}
	public function updateConfigsEmail($id,$smtp,$smtp_secure,$smtp_server,$smtp_port,$smtp_user,$smtp_password){
		$this->sql = "update $this->table set smtp=$smtp,smtp_secure='$smtp_secure',smtp_server='$smtp_server',smtp_port='$smtp_port',smtp_user='$smtp_user',smtp_password='$smtp_password' where $this->primary_key=$id ";
		$this->update();
	}
}
?>