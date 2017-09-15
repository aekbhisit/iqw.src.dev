<?php
class Contacts extends Database {
	var $module;
	public function __construct($module,$table=NULL){
		$this->module = (empty($table))?$module:$table;
		parent::__construct((empty($table))?$module:$table );
		$this->parent_table  = 'contacts_categories';
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
		$this->select();
		return $this->rows;
	}
	public function getCategoriesTreeAll($where=NULL){
		return $this->get_tree($where);
	}
	public function getCategoriesSize(){
		$this->sql = "select $this->primary_key from $this->table where level >0 ";
		return $this->select('size');
	}
	
	public function getCategory($id){
		$this->sql = "select id, parent_id, name,email, description, params, image, status from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0];
	}
	public function insert_categories($parent_id,$name,$slug,$email,$description,$image,$params,$user_id,$status){
		if((int)$parent_id==0){
			$parent_id = $this->check_root_node();
		}
		$this->sql = "insert into $this->table (parent_id,name,slug,email,description,image,params,user_id,status,mdate,cdate) " ;
		$this->sql .="values ($parent_id,'$name','$slug','$email','$description','$image','$params',$user_id,$status,NOW(),NOW())";
		$this->insert();
		$id = $this->insert_id();
		$this->insert_node($parent_id,$id);
	}
	
	public function update_categories($id,$parent_id,$name,$slug,$email,$description,$image,$params, $user_id,$status){
		if($parent_id==0){
			$parent_id = $this->check_root_node();
		}else{
			if ($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id,name='$name',slug='$slug',email='$email',description='$description',image='$image',params='$params',user_id=$user_id,status=$status,mdate=NOW() where $this->primary_key=$id ";
		$this->update();
	}
	public function duplicate_categories($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$data = $this->rows[0];
		$this->insert_categories($data['parent_id'],$data['name'],$data['slug'],$data['email'],$data['description'],$data['image'],$data['params'],$user_id,$data['status']);
	}
	public function update_category_status($id,$status){
		$this->sql ="update $this->table set status=$status where id=$id";
		$this->update();
	}
	public function getCategoryByEmail($email){
		$this->sql = "select * from $this->table where email='$email'";
		$this->select();
		return $this->rows[0];
	}
	// function for news  /////////////////////////////////////////////////
	// Develop by iQuickweb.com 28/06/2012
	///////////////////////////////////////////////////////////////////////////////////
	public function getContactsSize(){
		$this->sql = "select $this->primary_key from $this->table ";
		return  $this->select('size');
	}
	public function getContactsAll($search,$order,$limit){
		$this->sql = "SELECT $this->table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key $search $order $limit";
		$this->select();
		return $this->rows;
	}
	public function getContacts($id){
		$this->sql = "update $this->table set $this->table.read=1 where $this->table.$this->primary_key=$id  " ;
		$this->update();
		$this->sql = "select $this->table.*,$this->parent_table.name as category,$this->parent_table.email as category_email,contacts_reply.subject as reply_subject,contacts_reply.messages as reply_messages,contacts_reply.date as reply_date from $this->table left join $this->parent_table on $this->parent_table.id=$this->table.category_id left join contacts_reply on contacts_reply.contact_id = $this->table.id   where $this->table.$this->primary_key=$id ";
		$this->select();
		return  $this->rows[0];
	}
	public function insertContacts($category_id,$from_name,$from_email, $to_email,$subject,$content,$user_id,$status){
		$this->sql ="insert into $this->table (category_id,from_name,from_email,to_email,subject,content,user_id,status,mdate,cdate) ";
		$this->sql .=" values($category_id,'$from_name','$from_email', '$to_email','$subject','$content', $user_id,$status,NOW(),NOW()) ";
		$this->insert();
	}
	public function updateContacts($id,$category_id,$from_name,$from_email, $to_email,$content,$user_id,$status){
		$this->sql ="update $this->table set category_id=$category_id, from_name='$from_name', from_email='$from_email', to_email='$to_email', content='$content' , user_id=$user_id, status=$status, mdate=NOW() where $this->primary_key = $id ";
		$this->update();
	}
	public function deleteContacts($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
	}
	public function updateContactsStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	public function getContactsUnread(){
		$this->sql = "select * from $this->table  where $this->table.read=0 ";
		$this->select();
		return $this->rows;
	}
	public function front_getContactList(){
		$categories=  $this-> getCategoriesTreeAll();
		$contact_list  = array();
		foreach($categories as $c){
			if($c['status']==1){
				$contact_list[] = $c;
			}
		}
		return $contact_list;
	}
	public function insert_contact_reply($id,$subject,$messages){
		$this->sql = "insert into contacts_reply (contact_id,subject,messages,date) value($id,'$subject','$messages',NOW()) ;" ;
		$this->insert();
	}
	// function for pages frontend /////////////////////////////////////////////////
	// Develop by iQuickweb.com 13/08/2012
	///////////////////////////////////////////////////////////////////////////////////
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