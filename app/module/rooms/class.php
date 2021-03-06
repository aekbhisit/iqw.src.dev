<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class Rooms extends Database {
	var $module   ;
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
	public function insert_categories($parent_id,$name,$description,$image,$params,$user_id,$status){
		if((int)$parent_id==0){
			$parent_id = $this->check_root_node();
		}
		$this->sql = "insert into $this->table (parent_id,name,description,image,params,user_id,status,mdate,cdate) ";
		$this->sql .= "values ($parent_id,'$name','$description','$image','$params',$user_id,$status,NOW(),NOW()) ";
		$this->insert();
		$id = $this->insert_id();
		$this->insert_node($parent_id,$id);
	}
	public function update_categories($id,$parent_id,$name,$description,$image,$params, $user_id,$status){
		if($parent_id==0){
			$parent_id = $this->check_root_node();
		}else{
			if($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id,name='$name',description='$description',image='$image',params='$params',user_id=$user_id,status=$status,mdate=NOW() where $this->primary_key=$id ";
		$this->update();
	}
	public function update_category_status($id,$status){
		$this->sql ="update $this->table set status=$status where id=$id";
		$this->update();
	}
	// translate function
	public function getTranslateCategory($id,$lang){
		$this->sql = "select $this->table.id as category_id,$this->table.name as translate_from,$this->table_translate.* from $this->table left join $this->table_translate on $this->table.id=$this->table_translate.id and $this->table_translate.lang='$lang' where $this->table.id=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function saveCategoriesTranslate($categories_lang,$categories_id,$categories_name,$categories_description,$categories_images,$param){
		$this->sql ="select id from $this->table_translate where lang='$categories_lang' and id=$categories_id ";
		$this->select();
		$chk = (empty($this->rows[0]['id']))?true:false;
		if($chk){
			$this->sql ="insert into $this->table_translate(lang,id,name,description,image,params) values ('$categories_lang',$categories_id,'$categories_name','$categories_description','$categories_images','$param') ";
			$this->insert();
		}else{
			$this->sql = "update $this->table_translate set lang='$categories_lang',name='$categories_name',description='$categories_description',image='$categories_images',params='$param' where id=$categories_id ";
			$this->update();
		}
	}
	public function getSize($status=NULL){
		if(!empty($status)&&$status==1){
			$this->sql = "select $this->primary_key from $this->table where $this->table.status=$status ";
		}else{
			$this->sql = "select $this->primary_key from $this->table ";
		}
		return $this->select('size');
	}
	public function getAll($search,$order,$limit,$language=NULL){
		if(!empty($language)&&$language!=$this->site_language){
			$this->sql = "SELECT $this->table.*,$this->table_translate,$this->parent_table_translate.name as category FROM $this->table LEFT JOIN $this->parent_table_translate ON $this->table.category_id=$this->parent_table_translate.$this->parent_primary_key LEFT JOIN $this->translate_table ON $this->table.$this->primary_key=$this->translate_table.$this->primary_key $search $order $limit";
		}else{
			$this->sql = "SELECT $this->table.*,$this->parent_table.name as category FROM $this->table LEFT JOIN $this->parent_table ON $this->table.category_id=$this->parent_table.$this->parent_primary_key $search $order $limit";
		}
		$this->select();
		return $this->rows;
	}
	public function getOne($key,$slug=false,$language=NULL){
		if($slug){
			if(!empty($language)&&$language!=$this->site_language){
					$this->sql = "select $this->table.*,$this->table_translate.* from $this->table left join $this->table_translate on $this->table.primary_key = $this->table_translate.id and $this->table_translate.lang=$language where $this->table.slug=$key ";
			}else{
					$this->sql = "select * from $this->table where $this->table.slug=$key ";
			}
		}else{
			if(!empty($language)&&$language!=$this->site_language){
				$this->sql = "select $this->table.*,$this->table_translate.* from $this->table left join $this->table_translate on $this->table.primary_key = $this->table_translate.id and $this->table_translate.lang=$language where $this->table.$this->primary_key=$key ";
			}else{
				$this->sql = "select * from $this->table where $this->primary_key=$key ";
			}
		}
		$this->select();
		return $this->rows[0];
	}
	public function getParentCategoryAll($status=NULL,$language=NULL){
		if(!empty($language)&&$language!=$this->site_language){
			$categories = $this->get_tree($status, $this->parent_table);
			if(!empty($categories)){
				foreach($categories as $key => $c){
					$id = $c['id'];
					$this->sql = "select * from $this->parent_table_translate where id=$id ";
					$this->select();
					$translate = $this->rows[0];
					$categories[$key] = array_merge($categories[$key],$translate);
				}
			}
		}else{
			$categories = $this->get_tree($status,$this->parent_table);
		}
		return $categories;
	}
	public function getParentCategory($key,$language=NULL){
		if(!empty($language)&&$language!=$this->site_language){
			$this->sql = " select * from $this->parent_table_translate where id=$key ";
		}else{
			$this->sql = " select * from $this->parent_table where id=$key ";
		}
		$this->select();
		return $this->rows[0];
	}
	public function insertData($category_id,$name,$slug,$concept,$description,$content,$image,$gallery,$facility,$price,$discount,$currency,$params,$meta_key,$meta_description,$user_id,$status){
		$this->sql = "insert into $this->table (category_id,name,slug,concept,description,content,image,gallery,facility,price,discount,currency,params,javascript,css,meta_key,meta_description,user_id,status,mdate,cdate,sequence) ";
		$this->sql .= " values($category_id,'$name','$slug','$concept','$description','$content','$image','$gallery','$facility',$price,$discount,'$currency','$params','$javascript','$css','$meta_key','$meta_description',$user_id,$status,NOW(),NOW(),0) ";
		$this->insert();
	}
	public function updateData($id,$category_id,$name,$slug,$concept,$description,$content,$image,$gallery,$facility,$price,$discount,$currency,$params,$meta_key,$meta_description,$user_id,$status){
		$this->sql = "update $this->table set category_id=$category_id,name='$name',slug='$slug',concept='$concept',description='$description',content='$content',image='$image',gallery='$gallery',facility='$facility',price=$price,discount=$discount,currency='$currency',params='$params',javascript='$javascirpt',css='$css',meta_key='$meta_key',meta_description='$meta_description',user_id=$user_id,status=$status,mdate=NOW() where $this->primary_key=$id ";
		$this->update();
	}
	public function deleteData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
		if($this->is_translate){
			$this->sql = "delete from $this->table_translate where $this->table_translate.$this->primary_key=$id ";
			$this->delete();
		}
	}
	public function updateStatus($id,$status){
		$this->sql = "update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	public function getTranslate($id,$lang){
		$this->sql = "select $this->table.id as translate_id,$this->table.name as translate_from,$this->table.id as page_id,$this->table_translate.* from $this->table left join $this->table_translate on $this->table.id= $this->table_translate.id and $this->table_translate.lang='$lang' where $this->table.id=$id ";
		$this->select();
		return $this->rows[0];
	}
  	public function saveTranslate( $lang,$id,$name,$content,$content1,$content2,$params,$meta_key,$meta_description){
		$this->sql ="select id from $this->table_translate where lang='$lang' and id=$id ";
		$this->select(); 
		$chk = (empty($this->rows[0]['id']))?true:false ;
		if($chk){
			$this->sql ="insert into $this->table_translate (lang,id,name,content,content1,content2,params,meta_key,meta_description) values ('$lang',$id,'$name','$content','$content1','$content2','$params','$meta_key','$meta_description') ";
			$this->insert();
		}else{
			$this->sql = "update $this->table_translate set lang='$lang',name='$name',content='$content',content1='$content1',content2='$content2',params ='$params',meta_key='$meta_key',meta_description='$meta_description' where id=$id ";
			$this->update();
		}
	}
	public function front_getInCategory($categories,$search,$orderby,$limit){
		foreach($categories as $category){
			$new_search = $search.' and category_id=$category_id ';
			$pages[$category['id']] = $this->getPagesAll($new_search,$order,$limit);
		}
		return $pages;
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
}// class
?>