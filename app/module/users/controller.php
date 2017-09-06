<?php
if(empty($_SESSION)) {
	session_start();
}
$oModule = new Users('users');
$oCategories = new Users('users_categories');
$oMailer = new Mailer();
if(isset($_GET['task'])){
	$task = $_GET['task'] ;
	switch($task){
//  categories task   
		case 'getCategoriesData':
			$columns = array('','name','level','mdate','lft');
			$limit = '';
			$orderby ='' ;
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart= $_GET['iDisplayStart'];
			$limit  = ' limit '.$iDisplayStart.','.$iDisplayLength ;
			$iSortCol_0= $_GET['iSortCol_0'];
			$sSortDir_0= $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by  ".$columns[$iSortCol_0].' '.$sSortDir_0 ;
			}else{
				$orderby = " order by  ".$columns[4].' '.$sSortDir_0 ;
			}
			$sSearch= $_GET['sSearch']; 
			if(!empty($sSearch)){
				$search =  " and (name like '%$sSearch%' or description like '%$sSearch%') " ;
			}
			$categories = $oCategories->getCategoriesAll($search,$orderby,$limit);
			//print_r($categories);
			$iTotal = $oCategories->getCategoriesSize() ;
			 $iFilteredTotal =  count($categories);
			$output = array(
					"sEcho" => intval($_GET['sEcho']),
					"iTotalRecords" => $iTotal,
					"iTotalDisplayRecords" => $iTotal, // $iFilteredTotal,
					"aaData" => array()
				);
			$cnt = 1;
			if(!empty($categories)){
			foreach($categories as $key =>$value){
			if($value['level']>0){
			if($value['status']){	
				$iconbar = '	<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" /></a>';
			}else{
				$iconbar = '	<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" /></a>';
			}
			$iconbar .=	'  <a href="javascript:void(0)" onclick="setCategoryDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>  ';
			$iconbar .=	' <a href="javascript:void(0)" onclick="setCategoryDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" /></a>';
									
			$order = '<a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'left\')"><img src="../images/icons/black/16/arrow_up_small.png" /></a>
							     <a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'right\')" ><img src="../images/icons/black/16/arrow_down_small.png" /></a>
							  ';
			$indent = '';	
				for($i=1;$i<$value['level'];$i++){
					$indent .= '-';
				}
				$showname = '<a href="javascript:void(0)" onclick="setCategoryEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';			 	  
				$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
				$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['level'],3=>$value['mdate'],4=>$order,5=>$iconbar,6=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
				$cnt++ ;
				}
			}
			}
				echo json_encode($output) ;
				break;
			case 'loadCategories':
				$categories = $oCategories->getCategoriesTreeAll();
				$data = array(
					0=>array('level'=>0,'id'=>0,'parent'=>0,'name'=>'--- ไม่เลือก ---'),
				);
				$cnt =1 ;
			    if(!empty($categories)){
					foreach($categories as $c){
						$data[$cnt] = array('level'=>$c['level'],'id'=>$c['id'],'parent'=>$c['parent'],'name'=>$c['name']) ;
						$cnt++;
					}
				 }
				echo json_encode($data);
				break;
			case 'categoryFormInit':
					if($_GET['mode']=='edit'){
						$id = addslashes($_GET['id']);
						$data = $oCategories->getCategory($id);
						echo json_encode($data);
					}else{
							echo json_encode(array());
					}
				break;
			case 'saveCategory':
					$user = $oModule->getAdminLoginUser();
					 $categories_id = $_POST["categories_id"] ; 
					 $categories_parent = $_POST["categories_parent"];
					 $categories_name = htmlspecialchars($_POST["categories_name"],ENT_QUOTES);
					  if(empty($_POST['categories_slug'])){
						$categories_slug= $oModule->createSlug($categories_name) ;
					}else{
						$categories_slug=$_POST['categories_slug'] ;
					}
					$categories_description = htmlspecialchars($_POST["categories_description"],ENT_QUOTES);
					 $categories_images = $_POST["categories_server_images"] ;
					 $categories_status = $_POST["categories_status"] ;
					 $categories_slug = urldecode($categories_slug);
					 if(!empty($categories_id)){
						 $oCategories->update_categories($categories_id,$categories_parent,$categories_name,$categories_slug,$categories_description,$categories_images,'',$user['id'],$categories_status);
					 }else{
						 $oCategories->insert_categories($categories_parent,$categories_name,$categories_slug,$categories_description,$categories_images,'',$user['id'],$categories_status);
					 }
				break;
			case 'duplicateCategory':
					$user = $oModule->getAdminLoginUser();
					$id = $_GET["id"] ; 
					$oCategories->duplicate_categories($id,$user['id']);
				break;
			case 'setCategoryStatus':
					$id = addslashes($_GET['id']);
					$status =addslashes($_GET['status']);
					$oCategories->update_category_status($id,$status);
				break;
			case 'setCategoryDelete':
				$id = addslashes($_GET['id']);
				$child = $oCategories->get_onlychild_node($id);
				if(empty($child)){
			 		$oCategories->delete_category($id);
				}else{
					echo 'haschild';
				}
				break;
			case 'setCategoryMove':
				$id= $_GET['id'] ;
				$position =  $_GET['position'];
				$oCategories->move($id,$position) ;
			break;
///  users-form.html
		case 'setAdminLogin':
			$password = addslashes($_POST['password']);
			$username = addslashes($_POST['username']);
			$oModule->setAdminLogin($username,$password);
			break ;
		case 'getAdminLoginUser':
			if(isset($_SESSION['userLogin'])){
				$user = $_SESSION['userLogin'] ;
				echo json_encode($oModule->getAdminLoginUser($user['id'])) ;
			}else{
				$user = array('id'=>false) ;
				echo json_encode($user) ;
			}
			break;	
		case 'setAdminLogout':
			if(isset($_SESSION['userLogin'])||isset($_COOKIE['userLogin'])){
				unset($_SESSION['userLogin']);
				unset($_COOKIE['userLogin']);
			}
			break ;
		case 'usersFormInit':
				if($_GET['mode']=='edit'){
						if($_GET['id']=='login_user'){
								$user = $oModule->getAdminLoginUser();
								$id =  $user['id'] ;
						}else{
								$id = addslashes($_GET['id']);
						}
						$data = $oModule->getUsers($id);
						echo json_encode($data);
					}else{
							echo json_encode(array());
					}
				break ;
			case 'getUsersData':
					$columns = array('id','name','category_id','mdate','sequence');
					$limit = '';
					$orderby ='' ;
					$search = '';
					$iDisplayLength = $_GET['iDisplayLength'];
					$iDisplayStart= $_GET['iDisplayStart'];
					$limit  = ' limit '.$iDisplayStart.','.$iDisplayLength ;
					$iSortCol_0= $_GET['iSortCol_0'];
					$sSortDir_0= $_GET['sSortDir_0'];
					if(!empty($columns[$iSortCol_0])){
						$orderby = " order by  $oModule->table.".$columns[$iSortCol_0].' '.$sSortDir_0 ;
					}else{
						$orderby = " order by  ".$columns[4].' '.$sSortDir_0 ;
					}
					$sSearch= $_GET['sSearch']; 
					if(!empty($sSearch)){
						$search =  " WHERE ( $oModule->table.name like '%$sSearch%' or  $oModule->table.username like '%$sSearch%'  or  $oModule->table.display_name like '%$sSearch%'  or  $oModule->table.email like '%$sSearch%') " ;
					}
					$users = $oModule->getUsersAll($search,$orderby,$limit);
					//print_r($categories);
					$iTotal = $oModule->getUsersSize() ;
					 $iFilteredTotal =  count($users);
					$output = array(
							"sEcho" => intval($_GET['sEcho']),
							"iTotalRecords" => $iTotal,
							"iTotalDisplayRecords" => $iTotal, // $iFilteredTotal,
							"aaData" => array()
						);
					$cnt = 1;
					if(!empty($users)){
					foreach($users as $key =>$value){
					if($value['status']==1){	
						$iconbar = '	<a href="javascript:void(0)" onclick="setUsersStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" /></a>&nbsp;';
					}else{
						if($value['status']==2){
							$iconbar = '	<a href="javascript:void(0)" onclick="setUsersStatus('.$value['id'].',0)" ><img src="../images/icons/color/alarm.png" /></a>&nbsp;';
						}else{
							$iconbar = '	<a href="javascript:void(0)" onclick="setUsersStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" /></a>&nbsp;';
						}
					}
					$address = ' <a href="javascript:void(0)" onclick="setUsersAddress('.$value['id'].')"><img src="../images/icons/black/32/address_book_1.png" /></a>';		
	
					$iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a> 
											<a href="javascript:void(0)" onclick="setUsersDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" /></a>';
											
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
						$showname = '<a href="javascript:void(0)" onclick="setUsersEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';
						$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['category'],3=>$value['mdate'],4=>$address,5=>$iconbar ,6=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++ ;
						}
					}
						echo json_encode($output) ;
				break;
			case 'saveUsers':
				$id = $_POST['users_id'];
				$category_id = $_POST['categories'];
				$name = $_POST['users_name'];	
				$display_name= $_POST['users_display_name'];
				$email= $_POST['users_email'];
				$username= $_POST['users_username'];
				$password= $_POST['users_password'];
				$avatar =  (!empty($_POST['users_avatar']))?$_POST['users_avatar']:'';
				$status= $_POST['users_status'];
				if(empty($id)){		
					$oModule->insertUsers($category_id,$email,$username,$password,$name,$display_name,$avatar,$status);
				}else{
					$oModule->updateUsers($id,$category_id,$email,$username,$password,$name,$display_name,$avatar,$status);
				}
				break;
			case 'setUsersStatus':
				$id = addslashes($_GET['id']);
				$status = addslashes($_GET['status']);
				$oModule->updateUsersStatus($id,$status);
				break;
				case 'duplicate':
					$user = $oModule->getAdminLoginUser();
					$id = $_GET['id'];
					$oModule->duplicateData($id,$user['id']);
				break;
			case 'setUsersDelete':
				$id = addslashes($_GET['id']);
				$oModule->deleteUsers($id);
				break;
			case 'loadUsersAddress':
				$user_id = addslashes($_GET['user_id']);
				$address = $oModule->getUsersAddress($user_id);
				echo json_encode($address);
				break ;
			case 'saveUsersAddress':
				$user_id = addslashes($_GET['user_id']);
				if(!empty($user_id)){
					$address_id = $_POST['address_type']; 
					$address = $_POST['users_address'];
					$city = $_POST['users_city'];
					$state = $_POST['users_state'];
					$country = $_POST['users_country'];
					$zipcode = $_POST['users_zipcode'];
					$mobile = $_POST['users_mobile'];
					$tel  = $_POST['users_tel'];
					$fax  = $_POST['users_fax'];
					if($address_id=='add'){
						 $oModule->insertUsersAddress($user_id,$address,$city,$state,$country,$zipcode,$mobile,$tel,$fax) ;
					}else{
						$oModule->updateUsersAddress($address_id,$user_id,$address,$city,$state,$country,$zipcode,$mobile,$tel,$fax) ;
					}
				}
				break ;		
			case 'setUsersAddressDelete':
				$id = addslashes($_GET["id"]);
				 $oModule->deleteUsersAddress($id) ;
			break ;
			case 'create_admin':
				$oCategories->create_default_admin_category();
				$oModule->create_default_admin($_POST['admin_email'],$_POST['admin_user'],$_POST['admin_password']);
			break;
			
	}// switch
}// if isset
?>