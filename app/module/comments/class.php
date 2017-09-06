<?php
class Comments extends Database {
	var $module   ;
	public function __construct($module,$table=NULL){
		$this->module = (empty($table))?$module:$table  ;
		 parent::__construct((empty($table))?$module:$table );
		 $this->parent_table  = 'comments_categories';
		 $this->parent_primary_key = 'id';
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
		$this->sql = "select id, parent_id, name, description, image, status from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0] ;
	}
	
	public function  insert_categories($parent_id,$name,$description,$image,$params,$user_id,$status){
			if((int)$parent_id==0){
				$parent_id = $this->check_root_node() ;
			}
			$this->sql = "insert into $this->table (parent_id,name,description,image,params,user_id,status,mdate,cdate) " ;
			$this->sql .="values ($parent_id,'$name','$description','$image','$params',$user_id,$status,NOW(),NOW())";
			//echo $this->sql; 
			$this->insert() ;
			$id = $this->insert_id();
			$this->insert_node($parent_id,$id) ;
	}
	
	public function  update_categories($id,$parent_id,$name,$description,$image,$params, $user_id,$status){
		if($parent_id==0){
			$parent_id = $this->check_root_node() ;
		}else{
			if ($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id, name='$name', description='$description', image='$image',params='$params', user_id=$user_id,status=$status, mdate=NOW() where $this->primary_key=$id " ;
		$this->update() ;
	}
	
	public function update_category_status($id,$status){
			$this->sql ="update $this->table set status=$status where id=$id";
			$this->update();
	}
	
// function for comments  /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
///////////////////////////////////////////////////////////////////////////////////
	public function getCommentsSize(){
		$this->sql = "select $this->primary_key from $this->table ";
		return  $this->select('size') ;
	}
	
	public function getCommentsAll($search,$order,$limit){
		$this->sql = "SELECT $this->table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key  $search $order $limit";
		$this->select() ;
		return $this->rows ;
	}
	
	public function getComments($id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0] ;
	}
	
	function insertComments($category_id,$parent_id,$system_id,$content,$user_id,$status){
		$this->sql ="insert into $this->table (category_id,parent_id,system_id,content,user_id,status,mdate,cdate,sequence) ";
		$this->sql .=" values($category_id,$parent_id,$system_id,'$content',$user_id,$status,NOW(),NOW(),0) " ;
		$this->insert();
	}
	
	function updateComments($id,$category_id,$parent_id,$system_id,$content,$user_id,$status){
		$this->sql ="update $this->table set category_id=$category_id,parent_id=$parent_id,system_id=$system_id,content='$content' user_id=$user_id, status=$status, mdate=NOW(), where $this->primary_key = $id ";
		$this->update() ;
	}
	
	function deleteComments($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id " ;
		$this->delete();
	}
	
	function updateCommentsStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	
	function getCommentsUnread(){
		$this->sql = "select * from $this->table where $this->table.read=0 ";	
		$this->select();
		return $this->rows ;
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