<?php
class Galleries extends Database {
	var $module;
	public function __construct($params=NULL){
		$this->module = $params['module']  ;
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
	function datePickerToTime($in_date){
		list($date,$time) = explode(' ',$in_date);
		list($month,$day,$year) = explode('/',$date);
		return $year.'-'.$month.'-'.$day.' '.$time.':00';
	}
	
	function timeToDatePicker($in_date){
		list($date,$time) = explode(' ',$in_date) ;
		list($year,$month,$day) = explode('-',$date);
		list($h,$m,$s)=explode(':',$time);
		return $month.'/'.$day.'/'. $year.' '.$h.':'.$m;
	}
	// function for categories /////////////////////////////////////////////////
	// Develop by iQuickweb.com 28/06/2012
	///////////////////////////////////////////////////////////////////////////////////
	public function getCategoriesAll($search,$order,$limit){
		$this->sql = "select * from $this->table where level >0  $search $order $limit";
		$this->select();
		return $this->rows;
	}
	public function getCategoriesTreeAll($where=NULL){
		return  $this->get_tree($where);
	}
	public function getCategoriesSize(){
		$this->sql = "select $this->primary_key from $this->table where level >0 ";
		return $this->select('size');
	}
	public function getCategory($id){
		$this->sql = "select id, parent_id, name, slug, description, image, status from $this->table where $this->primary_key=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function  insert_categories($parent_id,$name,$slug,$description,$image,$params,$user_id,$status){
		if((int)$parent_id==0){
			$parent_id = $this->check_root_node();
		}
		$this->sql = "insert into $this->table (parent_id,name,slug,description,image,params,user_id,status,mdate,cdate) ";
		$this->sql .="values ($parent_id,'$name','$slug','$description','$image','$params',$user_id,$status,NOW(),NOW())";
		$this->insert();
		$id = $this->insert_id();
		$this->insert_node($parent_id,$id);
		return $id;
	}
	public function  update_categories($id,$parent_id,$name,$slug,$description,$image,$params, $user_id,$status){
		if($parent_id==0){
			$parent_id = $this->check_root_node();
		}else{
			if ($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id, name='$name', slug='$slug', description='$description', image='$image',params='$params', user_id=$user_id,status=$status, mdate=NOW() where $this->primary_key=$id " ;
		$this->update();
	}
	public function duplicate_categories($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id " ;
		$this->select();
		$data = $this->rows[0];
		$new_id= $this->insert_categories($data['parent_id'],$data['name'],$data['slug'],$data['description'],$data['image'],$data['params'],$user_id,$data['status']);
		// translate
		$this->sql = "select * from $this->translate_table where $this->primary_key=$id " ;
		$this->select();
		$data = $this->rows;
		if(!empty($data)){
			foreach($data as $d){
				$this->saveCategoriesTranslate( $d['lang'],$new_id,$d['name'],$d['description'],$d['image'],$d['params']);
			}
		}
	}
	public function deleteCategoryTranslate($id){
		$this->sql = "select * from $this->translate_table where $this->primary_key=$id ";
		$this->select();
		$data = $this->rows;
		if(!empty($data)){
			$this->sql = "delete from $this->translate_table where $this->primary_key=$id ";
			$this->delete();
		}
	}
	public function update_category_status($id,$status){
		$this->sql ="update $this->table set status=$status where id=$id";
		$this->update();
	}
	// translate function
	public function getTranslateCategory($id,$lang){
		$this->sql = "select $this->table.id as category_id, $this->table.name as translate_from ,$this->translate_table.* from $this->table left join $this->translate_table on $this->table.id=$this->translate_table.id and $this->translate_table.lang='$lang'  where $this->table.id=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function saveCategoriesTranslate( $categories_lang,$categories_id,$categories_name,$categories_description,$categories_images,$param){
		$this->sql ="select id from $this->translate_table where lang='$categories_lang' and id=$categories_id ";
		$this->select();
		$chk = (empty($this->rows[0]['id']))?true:false;
		if($chk){
			$this->sql ="insert into  $this->translate_table(lang, id, name, description , image,params ) values ('$categories_lang',$categories_id,'$categories_name','$categories_description','$categories_images','$param') ";
			$this->insert();
		}else{
			$this->sql = "update  $this->translate_table set lang ='$categories_lang' , name='$categories_name', description='$categories_description' , image='$categories_images',params='$param' where id=$categories_id ";
			$this->update();
		}
	}
	// function for galleries  /////////////////////////////////////////////////
	// Develop by iQuickweb.com 28/06/2012
	///////////////////////////////////////////////////////////////////////////////////
	public function getSize(){
		$this->sql = "select $this->primary_key from $this->table ";
		return $this->select('size');
	}
	public function getAll($search,$order,$limit){
		if(!empty($language)&&$language!=$this->site_language){
			$this->sql = "SELECT $this->table.*, $this->translate_table , $this->parent_translate_table.name as category FROM $this->table LEFT JOIN $this->parent_translate_table ON $this->table.category_id=$this->parent_translate_table.$this->parent_primary_key LEFT JOIN $this->translate_table ON $this->table.$this->primary_key = $this->translate_table.$this->primary_key  $search $order $limit";
		}else{
			$this->sql = "SELECT $this->table.*, $this->parent_table.name as category FROM $this->table LEFT JOIN $this->parent_table ON $this->table.category_id=$this->parent_table.$this->parent_primary_key  $search $order $limit";
		}
		$this->select();
		return $this->rows;
	}
	public function getData($id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$gallery = $this->rows[0];
		$this->sql = "select * from galleries_images where gallery_id = $id order by sequence asc";
		$this->select();
		$gallery['images_list'] = $this->rows;
		return $gallery;
	}
	public function getTranslate($id,$lang){
		$this->sql = "select $this->table.name as translate_from , $this->table.id as main_id , $this->table.images as default_images , $this->translate_table.* from $this->table left join $this->translate_table on $this->translate_table.id=$this->table.id and $this->translate_table.lang='$lang' where $this->table.$this->primary_key=$id ";
		$this->select();
		$gallery = $this->rows[0];
		$this->sql = "select id, image, sequence from galleries_images where gallery_id = $id";
		$this->select();
		$gallery['images_list'] = $this->rows;
		foreach($gallery['images_list'] as $ml_key=> $ml ){
			$ml_id = $ml['id'];
			$this->sql = "select galleries_images_translate.title,galleries_images_translate.description from  galleries_images_translate where galleries_images_translate.image_id = $ml_id and galleries_images_translate.lang='$lang'";
			$this->select();
			if(!empty($this->rows)){
				$gallery['images_list'][$ml_key]['title'] = $this->rows[0]['title'];
				$gallery['images_list'][$ml_key]['description'] = $this->rows[0]['description'];
			}else{
				$gallery['images_list'][$ml_key]['title'] = '';
				$gallery['images_list'][$ml_key]['description'] = '';
			}
		}
		return $gallery;
	}
	public function insertData($category_id,$name,$content,$slug,$cover,$params,$meta_key,$meta_description,$user_id,$status){
		$this->sql ="insert into $this->table (category_id,name,slug,content,cover,params,meta_key,meta_description,user_id,status,mdate,cdate,sequence) ";
		$this->sql .=" values($category_id,'$name','$slug','$content','$cover','$params','$meta_key','$meta_description',$user_id,$status,NOW(),NOW(),0) ";
		$this->insert();
	}
	public function updateData($id,$category_id,$name,$content,$slug,$cover,$params,$meta_key,$meta_description,$user_id,$status){
		$this->sql ="update $this->table set category_id=$category_id, name='$name', content='$content', slug='$slug',cover='$cover', params='$params', meta_key='$meta_key', meta_description='$meta_description', user_id=$user_id, status=$status, mdate=NOW() where $this->primary_key = $id ";
		$this->update();
	}
	public function insertDataImage($gallery_id,$image,$title,$description,$sequence){
		$this->sql ="insert into galleries_images (gallery_id,image,title,description,sequence) ";
		$this->sql .=" values($gallery_id,'$image','$title','$description',$sequence) " ;
		$this->insert();
	}
	public function deleteDataImage($image_id){
		$this->sql = "delete from galleries_images where id=$image_id ";
		$this->delete();
		$this->sql = "delete from galleries_images_translate where image_id=$image_id ";
		$this->delete();
	}
	public function updateDataImage($gallery_id,$image_id,$image,$title,$description,$sequence){
		/*$this->sql = "select id from galleries_images where id=$image_id";
		echo $this->sql ;
		$this->select();*/
		if(!empty($image_id)){
			$this->sql ="update galleries_images  set title='$title',description='$description',sequence=$sequence where id=$image_id ";
			$this->update();
		}else{
			$this->sql ="insert into galleries_images (gallery_id,image,title,description,sequence) ";
			$this->sql .=" values($gallery_id,'$image','$title','$description',$sequence) ";
			$this->insert();
		}
	}
	public function insertDataImageTranslate($lang,$image_id,$title,$description){
		$this->sql ="insert into galleries_images_translate (lang,image_id,title,description) ";
		$this->sql .=" values('$lang',$image_id,'$title','$description') ";
		$this->insert();
	}
	public function updateDataImageTranslate($lang,$image_id,$title,$description){
		$this->sql ="update galleries_images_translate  set title='$title',description='$description' where image_id=$image_id and lang='$lang' ";
		$this->update();
	}
	public function saveDataImageTranslate($lang,$image_id,$title,$description){
		$this->sql = "select  id from galleries_images_translate where lang='$lang' and image_id = $image_id";
		$this->select();
		if(!empty($this->rows)){
			$this->updateDataImageTranslate($lang,$image_id,$title,$description);
		}else{
			$this->insertDataImageTranslate($lang,$image_id,$title,$description);
		}
	}
	public function saveTranslate($lang,$id,$name,$content,$images,$cover,$params,$meta_key,$meta_description){
		$this->sql =" select id from $this->translate_table where id = $id and lang = '$lang' ";
		$this->select();
		$result = $this->rows;
		if(empty($result)){
			$this->sql = "insert into $this->translate_table (lang,id,name,content,cover,params,meta_key,meta_description) values ('$lang',$id,'$name','$content','$cover','$params','$meta_key','$meta_description') ";
			$this->insert();
			$gallery_id = $this->insert_id();
			if(!empty($images)){
				foreach($images as $img){
					$this->saveDataImageTranslate($lang,$img['id'],$img['src'],$img['title'],$img['description']);
				}
			}
		}else{
			$this->sql = "update $this->translate_table set name='$name',content='$content',cover='$cover',params='$params',meta_key='$meta_key',meta_description='$meta_description' ";
			$this->update();
			foreach($images as $img){
				$this->saveDataImageTranslate($lang,$img['id'],$img['title'],$img['description']);
			}
		}
	}
	public function duplicateData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key = $id ";
		$this->select();
		$data = $this->rows[0];
		$this->insertData($data["category_id"],$data["name"],$data["content"],$data["slug"],$data["cover"],$data["params"],$data["meta_key"],$data["meta_description"],$data["user_id"],$data["status"]);
		$new_gallery_id = $this->insert_id();
		$this->sql = "select * from galleries_images where gallery_id =$id";
		$this->select();
		$rows = $this->rows;
		foreach($rows as $rs){
			$img_id = $rs['id'];
			$this->insertDataImage($new_gallery_id,$rs['image'],$rs['title'],$rs['description'],$rs['sequence']);
			$new_image_id = $this->insert_id();
			$this->sql ="select * from galleries_images_translate where image_id = $img_id ";
			$this->select();
			$img_tr_rows = $this->rows;
			if(!empty($img_tr_rows)){
				foreach($img_tr_rows as $img_tr){
					$this->sql ="insert into galleries_images_translate (lang,image_id,title,description) values('".$img_tr['lang']."',".$new_image_id.",'".$img_tr['title']."','".$img_tr['description']."') ";
					$this->insert();
				}
			}
		}
		// duplicate
		$this->sql = "select * from galleries_translate where id =$id";
		$this->select();
		$data = $this->rows;
		if(!empty($data)){
			foreach($data as $d){
				$gallery_translate_id =$d['id'];
				$this->sql = "insert into $this->translate_table(lang,id,name,content,images,params,meta_key,meta_description) values ('".$d['lang']."',".$new_gallery_id.",'".$d["name"]."','".$d["content"]."','".$d["images"]."','".$d["params"]."','".$d["mata_key"]."','".$d["mata_description"]."')";
				$this->insert();
			}
		}
	}
	public function deleteData($id){
		$this->sql = "select id from galleries_images where gallery_id=$id";
		$this->select();
		$rows = $this->rows;
		if($rows){
			foreach($rows as $row){
				$img_id = $row['id'];
				$this->sql = "delete from galleries_images_translate where image_id=$img_id ";
				$this->delete();
			}
		}
		$this->sql = "delete from galleries_images where gallery_id=$id ";
		$this->delete();
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
		$this->sql = "delete from $this->translate_table where $this->primary_key=$id " ;
		$this->delete();
	}
	public function updateGalleriesStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	//////////////  order function  /////////////////////////////
	public function reOrderDataDragDrop($ids,$sort){
		$min = min($sort);
		foreach($ids as $id){
			if($id!='start'){
				$this->sql="update $this->table set sequence=$min where $this->primary_key=$id ";
				$this->update();
				$min++;
			}
		}
	}
	public function switchOrder($id,$sort){
		$this->sql = "select sequence from $this->table where  $this->primary_key=$id ";
		$this->select();
		$old_sequence = $this->rows[0]['sequence'];
		if($sort>$old_sequence){ // move up
			$this->sql="update $this->table set sequence=sequence-1 where sequence <= $sort and sequence > $old_sequence ";
			$this->update();
			$this->sql="update $this->table set sequence=$sort where $this->primary_key=$id ";
			$this->update();
		}else{ // move down
			$this->sql="update $this->table set sequence=sequence+1 where sequence >= $sort and sequence < $old_sequence ";
			$this->update();
			$this->sql="update $this->table set sequence=$sort where $this->primary_key=$id ";
			$this->update();
		}
	}
	
	function setReorderAll($column,$direction){
			$this->sql = " UPDATE   $this->table
											JOIN     (SELECT    p.$this->primary_key,
																@curRank := @curRank + 1 AS rank
													  FROM   $this->table p
													  JOIN      (SELECT @curRank := 0) r 
													  ORDER BY  p.$column $direction
													 ) ranks ON (ranks.$this->primary_key = $this->table.$this->primary_key)
											SET    $this->table.sequence = ranks.rank " ;
				//echo $this->sql ;
				$this->update();
	}
	
	function  changeCategory($id,$category_id){
		$this->sql = "update $this->table set category_id=$category_id where $this->primary_key=$id  ";
		$this->update();
	}
	
	function getGalleriesImages($id,$language){
		if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
			$this->sql = " select * from galleries_images where gallery_id = $id ";
			$this->select();
			return $this->rows;
		}else{
			$this->sql = " select galleries_images.*, galleries_images_translate.title, galleries_images_translate.description from galleries_images left join galleries_images_translate on galleries_images.id = galleries_images_translate.image_id where galleries_images.gallery_id = $id and lang = '$language' ";
			$this->select();
			return $this->rows;
		}
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////  find  /////////////////////////////////////////////////	
	
// function for pages frontend /////////////////////////////////////////////////
// Develop by iQuickweb.com 13/08/2012
///////////////////////////////////////////////////////////////////////////////////
	function find($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$order=NULL,$sort=NULL,$separate=false,$pagenate=false,$page=NULL,$length=10,$oParent=NULL){
		/* type
			1. one = fine one item by id
			3. all  =  fine all item and category
			4. category_all = fine  category_id
			6. category_one = find item in category 
			7. in_category = fine all and relation incateogry
		*/
		return $this->_find($type,$key,$slug,$status,$language,$search,$filter,$order,$sort,$separate,$pagenate,$page,$length,$oParent) ; 
		
	}
	function findcount($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$oParent=NULL){
		 return  $this->_findcount($type,$key,$slug,$status,$language,$search,$filter,$oParent); 
	}

}
?>