<?php
class Addon extends Database {
	var $module;
	public function __construct($params=NULL){
		$this->module = $params['module']  ;
		parent::__construct((empty($params['table']))?$module:$params['table'] );
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
		list($date,$time) = explode(' ',$in_date);
		list($year,$month,$day) = explode('-',$date);
		list($h,$m,$s)=explode(':',$time);
		return $month.'/'.$day.'/'. $year.' '.$h.':'.$m;
	}
	// function for module
	public function getOne(){
		$this->sql = "select * from $this->table where $this->primary_key = 1 ";
		$this->select();
		return  $this->rows[0];
	}
	public function insertData($all_project,$ongoing_roof,$ongoing_ground){
		$this->sql = "insert into $this->table (all_project,ongoing_roof,ongoing_ground,mdate,cdate) ";
		$this->sql .= " values($all_project,$ongoing_roof,$ongoing_ground,NOW(),NOW()) ";
		$this->insert();
	}
	public function updateData($all_project,$ongoing_roof,$ongoing_ground){
		$this->sql ="update $this->table set all_project=$all_project,ongoing_roof='$ongoing_roof',ongoing_ground='$ongoing_ground',mdate=NOW() where $this->primary_key = 1 ";
		$this->update();
	}
	public function getSize($status=NULL){
		$this->sql = "select $this->primary_key from $this->table ";
		return $this->select('size');
	}
	public function getAll($search,$order,$limit,$language=NULL){
		$this->sql = "select * from $this->table ";
		$this->select();
		return $this->rows;
	}
	// public function getParentCategoryAll($status=NULL,$language=NULL){
	// 	if(!empty($language)&&$language!=$this->site_language){
	// 		$categories =  $this->get_tree($status, $this->parent_table) ;
	// 		if(!empty($categories)){
	// 			foreach($categories as $key => $c){
	// 					$id = $c['id'] ;
	// 					$this->sql = "select * from $this->parent_translate_table where id=$id ";
	// 					$this->select();
	// 					$translate = $this->rows[0] ;
	// 					$categories[$key] = array_merge($categories[$key],$translate) ;
	// 			}
	// 		}
	// 	}else{
	// 		$categories =  $this->get_tree($status, $this->parent_table) ;
	// 	}
	// 	return  $categories ;
	// }
	
	// public function getParentCategory($key,$language=NULL){
	// 	if(!empty($language)&&$language!=$this->site_language){
	// 		$this->sql = " select * from $this->parent_translate_table where id = $key ";
	// 	}else{
	// 		$this->sql = " select * from $this->parent_table  where id = $key ";
	// 	}
	// 	$this->select();
	// 	return  $this->rows[0];
	// }
	
	function duplicateData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key = $id ";
		$this->select();
		$data = $this->rows[0] ;
		$this->insertData($data['category_id'],$data['name'],$data['description'],$data['slug'],$data['content'],$data['image'],$data['params'],$data['meta_key'],$data['meta_description'],$user_id,$data['status'],$data['start'],$data['end']) ;
		// insert translate 
		$new_id = $this->insert_id();
		$this->sql = "select * from $this->translate_table where $this->primary_key = $id ";
		$this->select();
		$data = $this->rows ;
		if(!empty($data)){
			foreach($data as $d){ 
				$this->saveTranslate( $d['lang'],$new_id,$d['name'],$d['description'],$d['content'],$d['image'],$d['params'],$d['meta_key'],$d['meta_description']);
			}
		}
	}
	
	function deleteData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id " ;
		$this->delete();
		if($this->is_translate){
			$this->sql = "delete from $this->translate_table where $this->translate_table.$this->primary_key=$id " ;
			$this->delete();
		}
	}
	
	function updateStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
// page translate
	function getTranslate($id,$lang){
		$this->sql = "select $this->table.id as translate_id ,  $this->table.name as translate_from, $this->table.id as page_id, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.id= $this->translate_table.id and $this->translate_table.lang='$lang' where $this->table.id=$id  ";
	//	echo $this->sql ;
		$this->select();
		return $this->rows[0] ;
	}

  function saveTranslate( $lang,$id,$name,$content,$image,$params,$meta_key,$meta_description){
		$this->sql ="select id from $this->translate_table where lang='$lang' and id=$id ";
	//	echo $this->sql ;
		$this->select(); 
		$chk = (empty($this->rows[0]['id']))?true:false ;
		if($chk){
			$this->sql ="insert into  $this->translate_table (lang, id, name, content ,image,params,meta_key,meta_description) values ('$lang',$id,'$name','$content','$image','$params','$meta_key','$meta_description')  ";
		//	echo $this->sql ;
			$this->insert();
		}else{
			$this->sql = "update  $this->translate_table set lang='$lang', name='$name', content='$content',image='$image' ,params ='$params' ,meta_key='$meta_key', meta_description='$meta_description' where id=$id  ";
		//	echo $this->sql ;
			$this->update();
			
		}
	}

function front_getInCategory($categories,$search,$orderby,$limit){
		foreach($categories as $category){
			$new_search = $search.' and category_id = $category_id ';
			$pages[$category['id']] =$this->getPagesAll($new_search,$order,$limit) ;
		}
		return $pages ;
	}

//////////////  order function  /////////////////////////////

	function reOrderDataDragDrop($ids,$sort){
		$min = min($sort);
		foreach($ids as $id){
			if($id!='start'){
				$this->sql="update $this->table set sequence=$min where $this->primary_key=$id ";
				$this->update();
				$min++;
			}
		}
	}
	
	function switchOrder($id,$sort){
		$this->sql = "select sequence from $this->table where  $this->primary_key=$id ";
		$this->select();
		$old_sequence = $this->rows[0]['sequence'] ;
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
		$this->update();
	}
	function  changeCategory($id,$category_id){
		$this->sql = "update $this->table set category_id=$category_id where $this->primary_key=$id  ";
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