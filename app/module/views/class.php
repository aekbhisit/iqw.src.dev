<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class Views extends Database {
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
	public function getCategoriesAll($search,$order,$limit){
		$this->sql = "select * from $this->table where level>0 $search $order $limit";
		$this->select();
		return $this->rows;
	}
	public function getCategoriesTreeAll($where=NULL){
		return $this->get_tree($where);
	}
	public function getCategoriesSize(){
		$this->sql = "select $this->primary_key from $this->table where level>0 ";
		return $this->select('size');
	}
	public function getCategory($id){
		$this->sql = "select id,parent_id,name,description,image,status from $this->table where $this->primary_key=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function  insert_categories($parent_id,$name,$slug,$description,$image,$params,$user_id,$status){
		if((int)$parent_id==0){
			$parent_id = $this->check_root_node();
		}
		$this->sql = "insert into $this->table (parent_id,name,slug,description,image,params,user_id,status,mdate,cdate) ";
		$this->sql .= "values ($parent_id,'$name','$slug','$description','$image','$params',$user_id,$status,NOW(),NOW())";
		$this->insert();
		$id = $this->insert_id();
		$this->insert_node($parent_id,$id);
	}
	public function  update_categories($id,$parent_id,$name,$slug,$description,$image,$params, $user_id,$status){
		if($parent_id==0){
			$parent_id = $this->check_root_node();
		}else{
			if($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id,name='$name',slug='$slug',description='$description',image='$image',params='$params',user_id=$user_id,status=$status,mdate=NOW() where $this->primary_key=$id ";
		$this->update();
	}
	public function duplicate_categories($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$data = $this->rows[0];
		$this->insert_categories($data['parent_id'],$data['name'],$data['slug'],$data['description'],$data['image'],$data['params'],$user_id,$data['status']);
	}
	public function update_category_status($id,$status){
		$this->sql ="update $this->table set status=$status where id=$id";
		$this->update();
	}
	// translate function
	public function getTranslateCategory($id,$lang){
		$this->sql = "select $this->table.id as category_id,$this->table.name as translate_from,$this->translate_table.* from $this->table left join $this->translate_table on $this->table.id=$this->translate_table.id and $this->translate_table.lang='$lang' where $this->table.id=$id ";
		$this->select();
		return$this->rows[0];
	}
	public function saveCategoriesTranslate( $categories_lang,$categories_id,$categories_name,$categories_description,$categories_images,$param){
		$this->sql = "select id from $this->category_translate_table where lang='$categories_lang' and id=$categories_id ";
		$this->select();
		$chk = (empty($this->rows[0]['id']))?true:false;
		if($chk){
			$this->sql = "insert into $this->translate_table (lang,id,name,description,image,params) values ('$categories_lang',$categories_id,'$categories_name','$categories_description','$categories_images','$param') ";
			$this->insert();
		}else{
			$this->sql = "update $this->translate_table set lang='$categories_lang',name='$categories_name',description='$categories_description',image='$categories_images',params='$param' where id=$categories_id ";
			$this->update();
		}
	}
	public function getSize(){
		$this->sql = "select $this->primary_key from $this->table ";
		return $this->select('size');
	}
	public function getAll($search,$order,$limit){
		$this->sql = "SELECT $this->table.*,$this->parent_table.name as category FROM $this->table LEFT JOIN $this->parent_table ON $this->table.category_id=$this->parent_table.$this->parent_primary_key $search $order $limit";
		$this->select();
		return $this->rows;
	}
	public function getOne($id,$language='th'){
		$this->sql = "select * from $this->table where $this->table.$this->primary_key=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function insertData($category_id,$name,$slug,$content,$params,$javascript,$css,$meta_key,$meta_description,$user_id,$status){
		$slug = urldecode($slug);
		$this->sql ="insert into $this->table (category_id,name,slug,content,params,javascript,css,meta_key,meta_description,user_id,status,mdate,cdate,sequence) ";
		$this->sql .=" values($category_id,'$name','$slug','$content','$params','$javascript','$css','$meta_key','$meta_description',$user_id,$status,NOW(),NOW(),0) ";
		$this->insert();
	}
	public function updateData($id,$category_id,$name,$slug,$content,$params,$javascript,$css,$meta_key,$meta_description,$user_id,$status){
		$slug = urldecode($slug);
		$this->sql ="update $this->table set category_id=$category_id,name='$name',slug='$slug',content='$content',params='$params',javascript='$javascript',css='$css',meta_key='$meta_key', meta_description='$meta_description',user_id=$user_id,status=$status,mdate=NOW() where $this->primary_key=$id ";
		$this->update();
	}
	public function duplicateData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$data = $this->rows[0];
		$this->insertData($data['category_id'],$data['name'],$data['slug'],$data['content'],$data['params'],$data['javascript'],$data['css'],$data['meta_key'],$data['meta_description'],$user_id,$data['status']);
	}
	public function deleteData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
	}
	public function updateStatus($id,$status){
		$this->sql = "update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	// page translate
	public function getTranslate($id,$lang){
		$this->sql = "select $this->table.id as page_id,$this->table.name as translate_from,$this->table.id as page_id,$this->translate_table.* from $this->table left join $this->translate_table on $this->table.id=$this->translate_table.id and $this->translate_table.lang='$lang' where $this->table.id=$id ";
		$this->select();
		return $this->rows[0];
	}
  	public function saveTranslate( $lang,$id,$name,$content,$params,$meta_key,$meta_description){
		$this->sql = "select id from $this->translate_table where lang='$lang' and id=$id ";
		$this->select(); 
		$chk = (empty($this->rows[0]['id']))?true:false;
		if($chk){
			$this->sql = "insert into $this->translate_table (lang,id,name,content,params,meta_key,meta_description) values ('$lang',$id,'$name','$content','$params','$meta_key','$meta_description') ";
			$this->insert();
		}else{
			$this->sql = "update $this->translate_table set lang='$lang',name='$name',content='$content',params ='$params',meta_key='$meta_key',meta_description='$meta_description' where id=$id ";
			$this->update();
		}
	}
	public function find($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$order=NULL,$sort=NULL,$separate=false,$pagenate=false,$page=NULL,$length=10,$oParent=NULL){
		/* type
		1. one = fine one item by id
		3. all  =  fine all item and category
		4. category_all = fine  category_id
		6. category_one = find item in category 
		7. in_category = fine all and relation incateogry
		*/
		return $this->_find($type,$key,$slug,$status,$language,$search,$filter,$order,$sort,$separate,$pagenate,$page,$length,$oParent); 
	}
	public function findcount($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$oParent=NULL){
		return $this->_findcount($type,$key,$slug,$status,$language,$search,$filter,$oParent); 
	}

}
?>