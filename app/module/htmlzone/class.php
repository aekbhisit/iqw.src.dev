<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class Zonehtml extends Database {
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
	public function datePickerToTime($in_date){
		list($date,$time) = explode(' ',$in_date);
		list($month,$day,$year) = explode('/',$date);
		return $year.'-'.$month.'-'.$day.' '.$time.':00';
	}
	public function timeToDatePicker($in_date){
		list($date,$time) = explode(' ',$in_date);
		list($year,$month,$day) = explode('-',$date);
		list($h,$m,$s)=explode(':',$time);
		return $month.'/'.$day.'/'. $year.' '.$h.':'.$m;
	}
	// function for module
	public function getOne(){
		$this->sql = "select * from $this->table where $this->primary_key=1 ";
		$this->select();
		return  $this->rows[0];
	}
	public function getOneHTML($id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0];
	}
	public function insertData($name,$description,$sequence,$status){
		$this->sql = "insert into $this->table (name,description,mdate,cdate,sequence,status) ";
		$this->sql .= " values('$name','$description',NOW(),NOW(),$sequence,$status) ";
		$this->insert();
	}
	public function updateData($id,$name,$description,$status){
		$this->sql ="update $this->table set name='$name',description='$description',status=$status,mdate=NOW() where $this->primary_key=$id ";
		$this->update();
	}
	public function getSize($status=NULL){
		$this->sql = "select $this->primary_key from $this->table ";
		return $this->select('size');
	}
	public function getAll($search,$order,$limit,$language=NULL){
		$this->sql = "select * from $this->table $search $order $limit ";
		$this->select();
		return $this->rows;
	}
	public function getBlockAll(){
		$this->sql = "select * from $this->table ";
		$this->select();
		return $this->rows;
	}
	public function duplicateData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$data = $this->rows[0] ;
		$this->insertData($data['name'],$data['description'],$data['sequence'],$data['status']);
		$new_id = $this->insert_id();
	}
	public function deleteData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
	}
	public function updateStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	public function setReorderAll($column,$direction){
		$this->sql = " UPDATE $this->table JOIN (SELECT p.$this->primary_key,@curRank := @curRank + 1 AS rank FROM $this->table p JOIN (SELECT @curRank := 0) r ORDER BY p.$column $direction) ranks ON (ranks.$this->primary_key = $this->table.$this->primary_key) SET $this->table.sequence = ranks.rank ";
		$this->update();
	}
	public function getTranslate($id,$lang){
		$this->sql = "select $this->table.id as translate_id,$this->table.name as translate_from,$this->table.id as page_id,$this->translate_table.* from $this->table left join $this->translate_table on $this->table.id=$this->translate_table.id and $this->translate_table.lang='$lang' where $this->table.id=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function getSizeHtmlZone($status=NULL){
		$this->sql = "select $this->primary_key from $this->table ";
		return $this->select('size');
	}
	public function getAllHtmlZone($search,$order,$limit,$language=NULL){
		$this->sql = "select $this->table.*,$this->parent_table.name as zone_name from $this->table left join $this->parent_table on $this->table.zone_id=$this->parent_table.zone_id $search $order $limit ";
		$this->select();
		return $this->rows;
	}
  	public function saveTranslate( $lang,$id,$name,$content,$image,$params,$meta_key,$meta_description){
		$this->sql ="select id from $this->translate_table where lang='$lang' and id = $id ";
		$this->select(); 
		$chk = (empty($this->rows[0]['id']))?true:false;
		if($chk){
			$this->sql ="insert into $this->translate_table (lang,id,name,content,image,params,meta_key,meta_description) values ('$lang',$id,'$name','$content','$image','$params','$meta_key','$meta_description') ";
			$this->insert();
		}else{
			$this->sql = "update $this->translate_table set lang='$lang',name='$name',content='$content',image='$image',params='$params',meta_key='$meta_key',meta_description='$meta_description' where id=$id ";
			$this->update();
			
		}
	}
	public function getOneHtmlZone($id) {
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function insertDataInput($zone_id,$name,$description,$type,$status){
		$this->sql = "insert into $this->table (zone_id,name,description,type,mdate,cdate,sequence,status) ";
		$this->sql .= " values($zone_id,'$name','$description',$type,NOW(),NOW(),0,$status) ";
		$this->insert();
	}
	public function updateDataInput($id,$zone_id,$name,$description,$type,$status){
		$this->sql = "update $this->table set zone_id=$zone_id,name='$name',description='$description',type=$type,status=$status,mdate=NOW() where $this->primary_key=$id ";
		$this->update();
	}
	public function duplicateHtmlZone($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$data = $this->rows[0];
		$this->insertDataInput($data['zone_id'],$data['name'],$data['description'],$data['type'],$data['status']);
		$new_id = $this->insert_id();
	}
	public function deleteDataHtmlZone($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
	}
	public function setStatusHtmlZone($id,$status){
		$this->sql = "update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	public function getAllInputInBlock($zone_id) {
		$this->sql = "select $this->table.*,html_zone_data.zone_data_id,html_zone_data.params from $this->table left join html_zone_data on $this->table.zone_input_id=html_zone_data.zone_input_id where $this->table.$this->parent_primary_key=$zone_id ";
		$this->select();
		return $this->rows;
	}
	public function getAllInputInBlock_lang($zone_id,$language) {
		$this->sql = "select $this->table.*,html_zone_data_translate.zone_data_id,html_zone_data_translate.params from $this->table left join html_zone_data_translate on $this->table.zone_input_id=html_zone_data_translate.zone_input_id where $this->table.$this->parent_primary_key=$zone_id and html_zone_data_translate.lang='$language' ";
		$this->select();
		return $this->rows;
	}
	public function setManagementData($data) {
		$this->sql = "select * from $this->table where zone_input_id=".$data['zone_input_id']." ";
		$this->select();
		$rows = $this->rows;
		if(!empty($rows)) {
			$this->sql = "update $this->table set zone_input_id=".$data['zone_input_id'].",zone_id=".$data['zone_id'].",params=".$data['params'].",mdate=".$data['mdate']." where $this->primary_key=".$data['zone_data_id']." and zone_input_id=".$data['zone_input_id']." ";
			$this->update();
		} else {
			$this->sql = "insert into $this->table (zone_id,zone_input_id,params,mdate,cdate) ";
			$this->sql .= " values(".$data['zone_id'].",".$data['zone_input_id'].",".$data['params'].",NOW(),NOW()) ";
			$this->insert();
		}
	}
	public function setManagementDataTranslate($data) {
		$this->sql = "select * from $this->translate_table where $this->primary_key=".$data['zone_data_id']." and zone_id=".$data['zone_id']." and zone_input_id=".$data['zone_input_id']." and lang=".$data['lang']." ";
		$this->select();
		$rows = $this->rows;
		if(!empty($rows)) {
			$this->sql = "update $this->translate_table set params=".$data['params'].",mdate=".$data['mdate']." where $this->primary_key=".$data['zone_data_id']." and zone_id=".$data['zone_id']." and zone_input_id=".$data['zone_input_id']." and lang=".$data['lang']." ";
			$this->update();
		} else {
			$this->sql = "insert into $this->translate_table (zone_data_id,zone_id,zone_input_id,lang,params,mdate,cdate) ";
			$this->sql .= " values(".$data['zone_data_id'].",".$data['zone_id'].",".$data['zone_input_id'].",".$data['lang'].",".$data['params'].",NOW(),NOW()) ";
			$this->insert();
		}
	}
	public function front_getInCategory($categories,$search,$orderby,$limit){
		foreach($categories as $category){
			$new_search = $search.' and category_id=$category_id ';
			$pages[$category['id']] = $this->getPagesAll($new_search,$order,$limit);
		}
		return $pages;
	}
	public function changeCategory($id,$category_id){
		$this->sql = "update $this->table set category_id=$category_id where $this->primary_key=$id ";
		$this->update();
	}
	public function find($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$order=NULL,$sort=NULL,$separate=false,$pagenate=false,$page=NULL,$length=10,$oParent=NULL){
		/* type
		1. one = fine one item by id
		3. all  =  fine all item and category
		4. category_all = fine  category_id
		6. category_one = find item in category 
		7. in_category = fine all and relation incateogry
		*/
		//$data = $this->_find($type,$key,$slug,$status,$language,$search,$filter,$order,$sort,$separate,$pagenate,$page,$length,$oParent);
		$data = '';
		if($type=='one') {
			$this->sql = "select zone_id from html_zone where html_zone.zone_id=$key ";
			$this->select();
			$data = $this->rows[0];
			if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
				$this->sql = "select html_zone_input.name,html_zone_data.params from html_zone_input left join html_zone_data on html_zone_input.zone_input_id=html_zone_data.zone_input_id where html_zone_input.zone_id=".$data['zone_id']." ";
				$this->select();
				$data['html_data'] = $this->rows;
			} else {
				$this->sql = "select html_zone_input.name,html_zone_data_translate.params from html_zone_input left join html_zone_data_translate on html_zone_input.zone_input_id=html_zone_data_translate.zone_input_id where html_zone_input.zone_id=".$data['zone_id']." and html_zone_data_translate.lang='$language' ";
				$this->select();
				$data['html_data'] = $this->rows;
			}
		} else {
			$data = $this->_find($type,$key,$slug,$status,$language,$search,$filter,$order,$sort,$separate,$pagenate,$page,$length,$oParent);
		}
		return $data;
	}
	public function findcount($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$oParent=NULL){
		return $this->_findcount($type,$key,$slug,$status,$language,$search,$filter,$oParent); 
	}
}
?>