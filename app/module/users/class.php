<?php
class Users extends Database {
	var $module   ;
	var $security_code ;
	public function __construct($module,$table=NULL){
		$this->module = (empty($table))?$module:$table  ;
		 parent::__construct((empty($table))?$module:$table );
		 $this->parent_table  = 'users_categories';
		 $this->parent_primary_key = 'id';
		 $this->security_code = SECURITY_CODE ;
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
		$this->sql = "select id, parent_id, name, slug,description, image, status from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0] ;
	}
	
	public function  insert_categories($parent_id,$name,$slug,$description,$image,$params,$user_id,$status,$admin=0){
			if((int)$parent_id==0){
				$parent_id = $this->check_root_node() ;
			}
			$this->sql = "insert into $this->table (parent_id,name,slug,description,image,params,user_id,allow_backoffice,status,mdate,cdate,admin) " ;
			$this->sql .="values ($parent_id,'$name','$slug','$description','$image','$params',$user_id,$admin,$status,NOW(),NOW(),$admin)";
			$this->insert() ;
			$id = $this->insert_id();
			$this->insert_node($parent_id,$id) ;
	}
	
	public function  update_categories($id,$parent_id,$name,$slug,$description,$image,$params, $user_id,$status){
		if($parent_id==0){
			$parent_id = $this->check_root_node() ;
		}else{
			if ($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id, name='$name', slug='$slug', description='$description', image='$image',params='$params', user_id=$user_id,status=$status, mdate=NOW() where $this->primary_key=$id " ;
		$this->update() ;
	}
	
	public function duplicate_categories($id,$user_id){
		$this->sql = "select admin from $this->table where $this->table.$this->primary_key = $id ";
		$this->select();
		$chk_admin = $this->rows[0]['admin'] ;
		if(!$chk_admin){
			$this->sql = "select * from $this->table where $this->primary_key=$id " ;
			$this->select();
			$data = $this->rows[0];
			$this->insert_categories($data["parent_id"],$data["name"],$data["slug"],$data["description"],$data["image"],$data["params"],$data["user_id"],$data["status"],$data["admin"]) ;
		}else{
			echo 'isAdmin';
		}
	}
	
	public function update_category_status($id,$status){
			$this->sql ="update $this->table set status=$status where id=$id";
			$this->update();
	}
	
	public function delete_category($id){
		$this->sql = "select admin from $this->table where $this->table.$this->primary_key = $id ";
		$this->select();
		$chk_admin = $this->rows[0]['admin'] ;
		if(!$chk_admin){
			$this->delete_node($id);
		}else{
			echo 'isAdmin';
		}
	}
	
// function for news  /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
//////////////////////////////////////////////////////////////////////////////
	public function password_encode($password){
		//return md5($password.$this->security_code) ;
			$key =$this->security_code ;
			return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $password, MCRYPT_MODE_CBC, md5(md5($key))));
	}
	public function password_decode($password){
			$key =$this->security_code ;
			return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	}
	
	public function password_match($password,$match){
			$password =  $this->password_encode($password) ;
			if($match===$hash){
				return true ;
			}else{
				return false ;
			}
	} 
	
	public function setAdminLogin($username,$password){
		$user=array() ;
		$user_info = array();
		$password_encode = $this->password_encode($password) ;
		$this->sql = "select * from $this->table where ($this->table.username='$username' or $this->table.email='$username' ) and $this->table.status=1 and $this->table.allow_backoffice=1 and $this->table.password='$password_encode' limit 0,1 ";
		$this->select();
		if(!empty($this->rows)){
			$user['loginResult'] = 'login_match' ;
			$user_info['id'] =  $this->rows[0]['id'] ;
			$user_info['username'] =  $this->rows[0]['username'] ;
			$user_info['password'] =  $this->rows[0]['password'] ;
			$_SESSION['userLogin'] =  $user_info ;
			$user['userLogin'] =   $user_info ;
		}else{
			$this->sql = "select * from $this->table where $this->table.username='$username' or $this->table.email='$username'  limit 0,1 ";
			$this->select();
			if(!empty($this->rows)){
				$user['loginResult'] = 'password_notmatch' ;
			}else{
				$user['loginResult'] = 'username_notmatch' ;
			}
		}
		echo json_encode($user) ;
	}
	
	public function setLogin($username,$password,$facebook=false){
		$user=array() ;
		$user_info = array();
		$password_encode = $this->password_encode($password) ;
		if($facebook){
			$this->sql = "select * from $this->table where ($this->table.username='$username' or $this->table.email='$username' ) and $this->table.status=1  and $this->table.password='$password_encode' limit 0,1 ";
		}else{
			$this->sql = "select * from $this->table where ($this->table.username='$username' or $this->table.email='$username' ) and $this->table.status=1  and  limit 0,1 ";
		}
		$this->select();
		if(!empty($this->rows)){
			$user['loginResult'] = 'login_match' ;
			$user_info['id'] =  $this->rows[0]['id'] ;
			$user_info['username'] =  $this->rows[0]['username'] ;
			$user_info['password'] =  $this->rows[0]['password'] ;
			$_SESSION['userLogin'] =  $user_info ;
			$user['userLogin'] =   $user_info ;
		}else{
			$this->sql = "select * from $this->table where $this->table.username='$username' or $this->table.email='$username'  limit 0,1 ";
			$this->select();
			if(!empty($this->rows)){
				$user['loginResult'] = 'password_notmatch' ;
			}else{
				$user['loginResult'] = 'username_notmatch' ;
			}
		}
		if($facebook){
			echo json_encode($user) ;
		}
	}
	
	public function getAdminLoginUser($user_login_id=NULL){
		if($_SESSION['userLogin']&&empty($user_login_id)){
			$sUserLogin  = $_SESSION['userLogin'] ;
			$user_login_id = $sUserLogin['id'] ;
		}
		$this->sql ="select  $this->table.*, $this->parent_table.name as role from  $this->table ,  $this->parent_table  where $this->table.
		$this->primary_key=$user_login_id and $this->table.category_id=$this->parent_table.$this->parent_primary_key  ";
		//echo 	$this->sql  ;
		$this->select();
		$user_row = $this->rows[0] ;
		$users = array(
			'id'=>$user_row[$this->primary_key],
			'username'=>$user_row['username'],
			'group'=>$user_row['role'],
			'name'=>$user_row['name'],
			'displayname'=>$user_row['display_name'],
			'avatar'=>$user_row['avatar'],
			'email'=>$user_row['email'],
			'info'=> '  '.$user_row['name'].' <span class="da-user-title">'.$user_row['role'].'</span>'
		);
		return $users ;
	}
	
	public function getLoginUser($user_login_id=NULL){
		if($_SESSION['userLogin']&&empty($user_login_id)){
			$sUserLogin  = $_SESSION['userLogin'] ;
			$user_login_id = $sUserLogin['id'] ;
		}
		$this->sql ="select  $this->table.*, $this->parent_table.name as role from  $this->table ,  $this->parent_table  where $this->table.
		$this->primary_key=$user_login_id and $this->table.category_id=$this->parent_table.$this->parent_primary_key  ";
		//echo 	$this->sql  ;
		$this->select();
		$user_row = $this->rows[0] ;
		$users = array(
			'id'=>$user_row[$this->primary_key],
			'username'=>$user_row['username'],
			'group'=>$user_row['role'],
			'name'=>$user_row['name'],
			'displayname'=>$user_row['display_name'],
			'avatar'=>$user_row['avatar'],
			'email'=>$user_row['email'],
			'info'=> '  '.$user_row['name'].' <span class="da-user-title">'.$user_row['role'].'</span>'
		);
		return $users ;
	}

	public function getUsersSize(){
		$this->sql = "select $this->primary_key from $this->table ";
		return  $this->select('size') ;
	}
	
	public function getUsersAll($search,$order,$limit){
		$this->sql = "SELECT $this->table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key  $search $order $limit";
		$this->select() ;
		return $this->rows ;
	}
	
	public function getUsers($id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$this->rows[0]['password'] = $this->password_decode($this->rows[0]['password']);
		$this->rows[0]['password_hide'] = $this->password_decode($this->rows[0]['password_hide']);
		return  $this->rows[0] ;
	}
	
	public function insertUsers($category_id,$email,$username,$password,$name,$display_name,$avatar,$status,$admin=0){
		$password_encode = $this->password_encode($password);
		$this->sql = "select allow_backoffice from $this->parent_table where $this->parent_primary_key = $category_id ";
		$this->select();
		$allow_backoffice = $this->rows[0]['allow_backoffice'] ;
		$allow_backoffice  = (empty($allow_backoffice ))?0:	$allow_backoffice ;
		$this->sql ="insert into $this->table (category_id,email,username,password,password_hide,name,display_name,avatar,allow_backoffice,status,mdate,cdate,admin) ";
		$this->sql .=" values($category_id,'$email','$username','$password_encode','$password_encode','$name','$display_name','$avatar', $allow_backoffice,$status, NOW(),NOW(),$admin) " ;
		$this->insert();
	}
	
	public function updateUsers($id,$category_id,$email,$username,$password,$name,$display_name,$avatar,$status){
		$password_encode = $this->password_encode($password);
		$this->sql = "select allow_backoffice from $this->parent_table where $this->parent_primary_key = $category_id ";
		$this->select();
		$allow_backoffice = $this->rows[0]['allow_backoffice'] ;
		$this->sql ="update $this->table set category_id=$category_id, email='$email', username='$username',password='$password_encode' ,password_hide='$password_encode', name='$name', display_name='$display_name', avatar='$avatar', allow_backoffice=$allow_backoffice ,status=$status, mdate=NOW() where $this->primary_key = $id ";
		$this->update() ;
	}
	
	function duplicateData($id,$user_id){
		$this->sql = "select admin from $this->table where $this->primary_key = $id ";
		 $this->select();
		 $chk_admin = $this->rows[0]['admin'] ;
		 if(!$chk_admin){
			 $this->sql = "select * from $this->table where $this->primary_key = $id ";
			$this->select();
			$data = $this->rows[0] ;
			$data['password'] = $this->password_decode($data['password']);
			$this->insertUsers($data['category_id'],$data['email'],$data['username'],$data['password'],$data['name'],$data['display_name'],$data['avatar'],$data['status'],$data['admin']) ;
		 }else{
			echo 'isAdmin'; 
		 }
	}
	
	 public function deleteUsers($id){
		 $this->sql = "select admin from $this->table where $this->primary_key = $id ";
		 $this->select();
		 $chk_admin = $this->rows[0]['admin'] ;
		 if(!$chk_admin){
			$this->sql = "delete from $this->table where $this->primary_key=$id " ;
			$this->delete();
		 }else{
			echo 'isAdmin'; 
		 }
		}
	
	public function updateUsersStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	
	// function for user address
	public function getUsersAddress($user_id){
		$this->sql =" select * from users_address where user_id=$user_id ";
		$this->select();
		return $this->rows ;
	}
	
	public function insertUsersAddress($user_id,$address,$city,$state,$country,$zipcode,$mobile,$tel,$fax){
		$this->sql  =" insert into users_address (user_id,address,city,state,country,zipcode,mobile,tel,fax) ";
		$this->sql  .=" values ($user_id,'$address','$city','$state','$country','$zipcode','$mobile','$tel','$fax') ";
		//echo $this->sql  ;
		$this->insert();
	}
	
	public function updateUsersAddress($id,$user_id,$address,$city,$state,$country,$zipcode,$mobile,$tel,$fax){
		$this->sql =" update users_address set user_id=$user_id,address='$address',city='$city',state='$state',country='$country',zipcode='$zipcode',mobile='$mobile',tel='$tel',fax='$fax' where id=$id ";
		//echo $this->sql  ;
		$this->update();
	}
	
	public function deleteUsersAddress($id){
		$this->sql  =" delete from users_address where id=$id ";
		$this->delete();
	}
	
	public function create_default_admin_category(){
		$this->insert_categories(0,'administartor','website administartor','','',1,1,1) ;
		$this->insert_categories(0,'member','website member','','',1,1,0) ;
	}
	
	public function create_default_admin($email=NULL,$user=NULL,$password=NULL){
		$this ->sql = "select id from $this->parent_table where status=1 and admin=1";
		$this->select();
		$category_id =  $this->rows[0]['id']  ;
		$this->insertUsers($category_id,$email,$user,$password,'Administrator','Administrator','',1,1) ;
	}
	
// function for frontend 
	public function checkExistedUserByEmail($email){
		$this->sql  ="select id from $this->table where $this->table.email like '$email'";
		$this->select() ;
		if(empty($this->rows)){
			return true ;
		}else{
			return false ;
		}
	}
	
	public function checkExistedUserByUsername($username){
		$this->sql  ="select id from $this->table where $this->table.username like '$username'";
		$this->select() ;
		if(empty($this->rows)){
			return true ;
		}else{
			return false ;
		}
	}
	
	
}
?>