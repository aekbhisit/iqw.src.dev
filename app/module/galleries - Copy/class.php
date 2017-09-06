<?php
class Galleries extends Database {
	var $module   ;
	public function __construct($module,$table=NULL){
		$this->module = (empty($table))?$module:$table  ;
		 parent::__construct((empty($table))?$module:$table );
		 $this->table_translate = 'galleries_translate' ;
		// $this->parent_table  = 'news_categories';
		// $this->parent_primary_key = 'id';
	}
	
	function datePickerToTime($in_date){
		list($date,$time) = explode(' ',$in_date) ;
		list($month,$day,$year) = explode('/',$date);
		return $year.'-'.$month.'-'.$day.' '.$time.':00' ;
	}
	
	function timeToDatePicker($in_date){
		list($date,$time) = explode(' ',$in_date) ;
		list($year,$month,$day) = explode('-',$date);
		list($h,$m,$s)=explode(':',$time);
		return $month.'/'.$day.'/'. $year.' '.$h.':'.$m ;
	}
	
// function for categories /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
///////////////////////////////////////////////////////////////////////////////////
	public function getCategoriesAll($search,$order,$limit){
		$this->sql = "select * from $this->table where level >0  $search $order $limit";
		$this->select() ;
		return $this->rows ;
	}
	
	public function getCategoriesTreeAll($where=NULL){
		return  $this->get_tree($where) ;
	}
	
	public function getCategoriesSize(){
		$this->sql = "select $this->primary_key from $this->table where level >0 ";
		return  $this->select('size') ;
	}
	
	public function getCategory($id){
		$this->sql = "select id, parent_id, name, description, images, status from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0] ;
	}
	
	public function  insert_categories($parent_id,$name,$description,$images,$params,$user_id,$status){
			if((int)$parent_id==0){
				$parent_id = $this->check_root_node() ;
			}
			$this->sql = "insert into $this->table (parent_id,name,description,images,params,user_id,status,mdate,cdate) " ;
			$this->sql .="values ($parent_id,'$name','$description','$images','$params',$user_id,$status,NOW(),NOW())";
			$this->insert() ;
			$id = $this->insert_id();
			$this->insert_node($parent_id,$id) ;
	}
	
	public function  update_categories($id,$parent_id,$name,$description,$images,$params, $user_id,$status){
		if($parent_id==0){
			$parent_id = $this->check_root_node() ;
		}else{
			if ($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id, name='$name', description='$description', images='$images',params='$params', user_id=$user_id,status=$status, mdate=NOW() where $this->primary_key=$id " ;
		$this->update() ;
	}
	
	public function update_category_status($id,$status){
			$this->sql ="update $this->table set status=$status where id=$id";
			$this->update();
	}
	
// function for galleries  /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
///////////////////////////////////////////////////////////////////////////////////
	public function getGalleriesSize(){
		$this->sql = "select $this->primary_key from $this->table ";
		return  $this->select('size') ;
	}
	
	public function getGalleriesAll($search,$order,$limit){
		$this->sql = "SELECT $this->table.* FROM $this->table  $search $order $limit";
		$this->select() ;
		return $this->rows ;
	}
	
	public function getGalleries($id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		//echo $this->sql  ;
		$this->select();
		return  $this->rows[0] ;
	}
	
	public function getGalleriesTranslate($id,$lang){
		$this->sql = "select $this->table.name as translate_from,  $this->table.id as main_id , $this->table.images as default_images , $this->table_translate.* from $this->table left join $this->table_translate on $this->table_translate.id=$this->table.id and $this->table_translate.lang='$lang'  where $this->table.$this->primary_key=$id ";
		//echo $this->sql  ;
		$this->select();
		return  $this->rows[0] ;
	}
	
	function insertGalleries($name,$content,$slug,$images,$params,$meta_key,$meta_description,$user_id,$status){
		$this->sql ="insert into $this->table (name,content,slug,images,params,meta_key,meta_description,user_id,status,mdate,cdate,sequence) ";
		$this->sql .=" values('$name','$content','$slug','$images','$params','$meta_key','$meta_description',$user_id,$status,NOW(),NOW(),0) " ;
		$this->insert();
	}
	
	function updateGalleries($id,$name,$content,$slug,$images,$params,$meta_key,$meta_description,$user_id,$status){
		$this->sql ="update $this->table set name='$name', content='$content', slug='$slug',images='$images', params='$params', meta_key='$meta_key', meta_description='$meta_description', user_id=$user_id, status=$status, mdate=NOW() where $this->primary_key = $id ";
		//echo $this->sql  ;
		$this->update() ;
	}
	
	function saveTranslate($lang, $id ,$name,$content,$images,$params,$meta_key,$meta_description){
		$this->sql =" select id from $this->table_translate where id=$id and lang='$lang' ";
		$this->select();
		$result = $this->rows;
		if(empty($result)){
			$this->sql = "insert into $this->table_translate(lang,id,name,content,images,params,meta_key,meta_description) values ('$lang',$id,'$name','$content','$images','$params','$meta_key','$meta_description')";
			$this->insert();
		}else{
			$this->sql = " update $this->table_translate set name='$name',content='$content',images='$images',params='$params',meta_key='$meta_key',meta_description='$meta_description' ";
			$this->update();
		}
	}
	
	function duplicateData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key = $id ";
		$this->select();
		$data = $this->rows[0] ;
		$this->insertGalleries($data["name"],$data["content"],$data["slug"],$data["images"],$data["params"],$data["meta_key"],$data["meta_description"],$data["user_id"],$data["status"]) ;
	}
	
	function deleteGalleries($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id " ;
		$this->delete();
		$this->sql = "delete from $this->translate_table where $this->primary_key=$id " ;
		$this->delete();
	}
	
	function updateGalleriesStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	
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