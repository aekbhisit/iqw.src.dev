<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
$oCategories = new Contacts('contacts_categories');
$oModule = new Contacts('contacts');
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		// categories task
		case 'getCategoriesData':
			$columns = array('','name','level','mdate','lft');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $oCategories->setInt($_GET['iDisplayLength']);
			$iDisplayStart = $oCategories->setInt($_GET['iDisplayStart']);
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $oCategories->setInt($_GET['iSortCol_0']);
			$sSortDir_0 = $oCategories->setString($_GET['sSortDir_0']);
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by ".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch = $oCategories->setString($_GET['sSearch']);
			if(!empty($sSearch)){
				$search = " and (name like '%$sSearch%' or description like '%$sSearch%') ";
			}
			$categories = $oCategories->getCategoriesAll($search,$orderby,$limit);
			$iTotal = $oCategories->getCategoriesSize();
			$iFilteredTotal = count($categories);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=>$iTotal,
				"iTotalDisplayRecords"=>$iTotal, // $iFilteredTotal,
				"aaData"=>array()
			);
			$cnt = 1;
			if(!empty($categories)){
				foreach($categories as $key => $value){
					if($value['level']>0){
						if($value['status']){	
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',0)"><img src="../images/icons/color/target.png" /></a>';
						}else{
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',1)"><img src="../images/icons/color/stop.png" /></a>';
						}		
						$iconbar .=	'<a href="javascript:void(0)" onclick="setCategoryDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';	
						$iconbar .=	'<a href="javascript:void(0)" onclick="setCategoryDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" /></a>';
						$order = '<a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'left\')"><img src="../images/icons/black/16/arrow_up_small.png" /></a><a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'right\')" ><img src="../images/icons/black/16/arrow_down_small.png" /></a>';
						$indent = '';	
						for($i=1;$i<$value['level'];$i++){
							$indent .= '-';
						}
						$showname = '<a href="javascript:void(0)" onclick="setCategoryEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';			 				  
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['level'],3=>$value['mdate'],4=>$order,5=>$iconbar,6=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++;
					}
				}
			}
			echo json_encode($output);
		break;
		case 'loadCategories':
			$categories = $oCategories->getCategoriesTreeAll();
			$data = array(
				0=>array('level'=>0,'id'=>0,'parent'=>0,'name'=>'--- ไม่เลือก ---'),
			);
			$cnt = 1;
			   if(!empty($categories)){
				foreach($categories as $c){
					$data[$cnt] = array('level'=>$c['level'],'id'=>$c['id'],'parent'=>$c['parent'],'name'=>$c['name']);
					$cnt++;
				}
			}
			echo json_encode($data);
		break;
		case 'categoryFormInit':
			if($_GET['mode']=='edit'){
				$id = $oCategories->setInt($_GET['id']);
				$data = $oCategories->getCategory($id);
				echo json_encode($data);
			}else{
				echo json_encode(array());
			}
		break;
		case 'saveCategory':
			$user = $oUsers->getAdminLoginUser();
			$categories_id = $oCategories->setInt($_POST["categories_id"]);
			$categories_parent = $oCategories->setInt($_POST["categories_parent"]);
			$categories_name = $oCategories->setString($_POST["categories_name"]);
			$categories_email = $oCategories->setString($_POST["categories_email"]);
			if(empty($_POST['categories_slug'])){
				$categories_slug = $oCategories->createSlug($categories_name);
			}else{
				$categories_slug = $oCategories->setString($_POST['categories_slug']);
			}
			$categories_description = $oCategories->setString($_POST["categories_description"]);
			$categories_params = $oCategories->setString($_POST["categories_params"]);
			$categories_images = $oCategories->setString($_POST["categories_server_images"]);
			$categories_status = $oCategories->setInt($_POST["categories_status"]);
			if(!empty($categories_id)){
				$oCategories->update_categories($categories_id,$categories_parent,$categories_name,$categories_slug,$categories_email ,$categories_description,$categories_images,$categories_params,$user['id'],$categories_status);
			}else{
				$oCategories->insert_categories($categories_parent,$categories_name,$categories_slug,$categories_email ,$categories_description,$categories_images,$categories_params,$user['id'],$categories_status);
			}
		break;
		case 'setCategoryStatus':
			$id = $oCategories->setInt($_GET['id']);
			$status = $oCategories->setInt($_GET['status']);
			$oCategories->update_category_status($id,$status);
		break;
		case 'duplicateCategory':
			$user = $oUsers->getAdminLoginUser();
			$id = $oCategories->setInt($_GET['id']);
			$oCategories->duplicate_categories($id,$user['id']);
		break;
		case 'setCategoryDelete':
			$id = $oCategories->setInt($_GET['id']);
			$child = $oCategories->get_onlychild_node($id);
			if(empty($child)){
				$oCategories->delete_node($id);
			}else{
				echo 'haschild';
			}
		break;
		case 'setCategoryMove':
			$id = $oCategories->setInt($_GET['id']);
			$position = $oCategories->setString($_GET['position']);
			$oCategories->move($id,$position);
		break;
		case 'contactsFormInit':
			if($_GET['mode']=='edit'){
				$id = $oModule->setInt($_GET['id']);
				$data = $oModule->getContacts($id);
				echo json_encode($data);
			}else{
				echo json_encode(array());
			}
		break;
		case 'getContactsData':
			$columns = array('id','name','category_id','mdate','sequence');
			$limit = '';
			$orderby ='' ;
			$search = '';
			$iDisplayLength = $oModule->setInt($_GET['iDisplayLength']);
			$iDisplayStart = $oModule->setInt($_GET['iDisplayStart']);
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $oModule->setInt($_GET['iSortCol_0']);
			$sSortDir_0 = $oModule->setString($_GET['sSortDir_0']);
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oModule->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch = $oModule->setString($_GET['sSearch']); 
			if(!empty($sSearch)){
				$search = " WHERE ($oModule->table.name like '%$sSearch%' or $oModule->table.slug like '%$sSearch%') ";
			}
			$contacts = $oModule->getContactsAll($search,$orderby,$limit);
			$iTotal = $oModule->getContactsSize();
			$iFilteredTotal = count($contacts);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=>$iTotal,
				"iTotalDisplayRecords"=>$iTotal, // $iFilteredTotal,
				"aaData"=>array()
			);
			$cnt = 1;
			if(!empty($contacts)){
				foreach($contacts as $key =>$value){
					$iconbar =	'<a href="javascript:void(0)" onclick="setContactsDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" /></a>';
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					// if empty category
					$value['category'] = (empty($value['category']))?' - ':$value['category'];
					$output["aaData"][] = array(0=>$row_chk,1=>$value['subject'].'<br><div class="show_contact_msg">'.$value['content'].'</div>',2=>$value['category'],3=>$value['cdate'],4=>$iconbar,5=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'saveContacts':
			$user = $oUsers->getAdminLoginUser();
			$id = $oModule->setInt($_POST['contacts_id']);
			$category_id = $oModule->setInt($_POST['categories']);
			$from_name = $oModule->setString($_POST['contacts_from_name']);
			$from_email = $oModule->setString($_POST['contacts_from_email']);
			$to_email = $oModule->setString($_POST['contacts_to_email']);	
			$subject = $oModule->setString($_POST['contacts_subject']);	
			$content = $oModule->setString($_POST['contacts_content']);
			$status = $oModule->setInt($_POST['contacts_status']);
			if(empty($id)){		
				$oModule->insertContacts($category_id,$from_name,$from_email,$to_email,$content,$user['id'],$status);
			}else{
				$oModule->updateContacts($id,$category_id,$from_name,$from_email,$to_email,$content,$user['id'],$status);
			}
		break;
		case 'setContactsDelete':
			$id = $oModule->setInt($_GET['id']);
			$oModule->deleteContacts($id);
		break;
		case 'getMessagesTopRight':
			$rows = $oModule->getContactsUnread();
			if(!empty($rows)){
				foreach($rows as $key => $value){
					$messages[$key] = array(
						'title'=>$value['from_name'],
						'date'=>$value['cdate'],
						'read'=>'read'
					);
				}
				echo json_encode($messages);
			}
		break;
		// for frontend
		case 'front_getContactList':
			$data['contacts']['contact_list'] = $oCategories->front_getContactList();
		break;
		case 'sentContact':
			if(!isset($_SESSION['sendMailMessage'])) {
				$mailTo = addslashes('sathapornsukrammi@gmail.com');
				$toName = addslashes('Contact footer');
				$ccTo = NULL;
				$bccTo = NULL;
				$subject = 'Contact footer';
				$mailFrom = addslashes($_POST['inputEmail']);
				$fromName= addslashes($_POST['inputName']);
				$msg = "<br>ชื่อ: ".$_POST['inputName'];
				$msg .= "<br>อีเมล์: ".$_POST['inputEmail'];
				$msg .= "<br>ข้อความ: ".$_POST['inputMessage'];
				$attachments = NULL ;
				$sendmail = $oMailer->sendMail($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$msg,$attachments);
				$oModule->insertContacts(0,$fromName,$mailFrom,$mailTo,$subject,$msg,0,0);
				if($sendmail){
					$_SESSION['sendMailMessage'] = 'Message sent!';
					echo 1;
				}else{
					$_SESSION['sendMailMessage'] = 'Error';
					echo 99;
				}
			} else {
				echo 400;
			}
		break;
		// for find module	
		case 'getCategoriesDataInFinds':
			$columns = array('','name','level','mdate','lft');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $oCategories->setInt($_GET['iDisplayLength']);
			$iDisplayStart = $oCategories->setInt($_GET['iDisplayStart']);
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $oCategories->setInt($_GET['iSortCol_0']);
			$sSortDir_0 = $oCategories->setString($_GET['sSortDir_0']);
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by ".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch = $oCategories->setString($_GET['sSearch']);
			if(!empty($sSearch)){
				$search = " and (name like '%$sSearch%' or description like '%$sSearch%') ";
			}
			$categories = $oCategories->getCategoriesAll($search,$orderby,$limit);
			$iTotal = $oCategories->getCategoriesSize();
			$iFilteredTotal = count($categories);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=>$iTotal,
				"iTotalDisplayRecords"=>$iTotal, // $iFilteredTotal,
				"aaData"=>array()
			);
			$cnt = 1;
			if(!empty($categories)){
				foreach($categories as $key =>$value){
					if($value['level']>0){
						if($value['status']){
							$iconbar = '<a href="javascript:void(0)"><img src="../images/icons/color/target.png" title="เปิด" /></a>';
						}else{
							$iconbar = '<a href="javascript:void(0)"><img src="../images/icons/color/stop.png" title="ปิด" /></a>';
						}
						$indent = '';
						for($i=1;$i<$value['level'];$i++){
							$indent .= '-';
						}
						$showname = $value['name'].'<input name="showName_'.$value['id'].'" id="showName_'.$value['id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['id'].'" id="showSlug_'.$value['id'].'" type="hidden" value="'.$value['slug'].'" />';
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['level'],3=>$iconbar,4=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++;
					}
				}
			}
			echo json_encode($output);
		break;
		case 'getDataInFinds':
			$columns = array('id','name','category_id','mdate','sequence');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $oModule->setInt($_GET['iDisplayLength']);
			$iDisplayStart = $oModule->setInt($_GET['iDisplayStart']);
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $oModule->setInt($_GET['iSortCol_0']);
			$sSortDir_0 = $oModule->setString($_GET['sSortDir_0']);
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oModule->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch = $oModule->setString($_GET['sSearch']);
			if(!empty($sSearch)){
				$search = " WHERE ($oModule->table.name like '%$sSearch%' or $oModule->table.slug like '%$sSearch%') ";
			}
			$data = $oModule->getAll($search,$orderby,$limit);
			$iTotal = $oModule->getSize();
			$iFilteredTotal = count($data);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=>$iTotal,
				"iTotalDisplayRecords"=>$iTotal, // $iFilteredTotal,
				"aaData"=>array()
			);
			$cnt = 1;
			if(!empty($data)){
				foreach($data as $key =>$value){
					if($value['status']==1){	
						$iconbar = '<a href="javascript:void(0)"><img src="../images/icons/color/target.png" title="เปิด" /></a>';
					}else{
						$iconbar = '<a href="javascript:void(0)"><img src="../images/icons/color/stop.png" title="ปิด" /></a>';
					}
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					$showname = $value['name'].'<input name="showName_'.$value['id'].'" id="showName_'.$value['id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['id'].'" id="showSlug_'.$value['id'].'" type="hidden" value="'.$value['slug'].'" />' ;
					$value['category'] = (empty($value['category']))?' - ':$value['category'];
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['category'],3=>$iconbar,4=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case "loadFindOneInit":
			$id = $oModule->setInt($_GET['id']);
			$data = $oModule->getOne($id);
			$data['name'] = $oModule->getString($data['name']);
			$data['meta_key'] = $oModule->getString($data['meta_key']);
			$data['meta_description'] = $oModule->getString($data['meta_description']);
			echo json_encode($data,true);
		break;	
		case 'loadFindCategoryInit':
			$id = $oCategories->setInt($_GET['id']);
			$data = $oCategories->getCategory($id);
			$data['name'] = $oCategories->getString($data['name']);
			$data['description'] = $oCategories->getString($data['description']);
			echo json_encode($data,true);
		break;
		////////////////task for frontend  //////////////////////////////////////////////////////////////////////////
		case "find":
			$language = LANG;
			$module = $_GET['module'];
			$task = $_GET['task'];
			$type = $_GET['type'];
			$key = $_GET['key'];
			$slug = (!empty($_GET['slug']))?$_GET['slug']:0;
			$status =(!empty($_GET['status']))?$_GET['status']:1;
			$search = (!empty($_GET['search']))?$_GET['search']:'';
			$filter = (!empty($_GET['filter']))?$_GET['filter']:'';
			$order = (!empty($_GET['order']))?$_GET['order']:'';
			$separate = (!empty($_GET['separate']))?$_GET['separate']:0;
			$pagenate =  (!empty($_GET['paginate']))?$_GET['paginate']:0;
			$page = (!empty($_GET['page']))?$_GET['page']:1;
			$length = (!empty($_GET['length']))?$_GET['length']:10;
			$count = (!empty($_GET['count']))?$_GET['count']:0;
			$data_key =  (!empty($_GET['data_key']))?$_GET['data_key']:$module;
			if(is_array($key)&&!empty($key)){
				$keys = $key;
				foreach($keys as $key){
					$key = $oModule->setInt($key);
					$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
				 	if($separate){
						$listQueryData = $_DATA[$data_key];
						$_DATA[$data_key] = NULL;
						foreach($listQueryData as $kk=>$val){
							$_DATA[$data_key][$val['slug']] = $val;
						}
					}
					if($count){
						if(!empty($key)){
							$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
						}else{
							$_DATA['COUNT'][$data_key]= $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
						}
					}
				}
			}else{
				$key = $oModule->setInt($key);
				$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
				if($count){
					if(!empty($key)){
						$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
					}else{
						$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
					}
				}
				if($separate){
					$listQueryData = $_DATA[$data_key];
					$_DATA[$data_key] = NULL;
					if(!empty($listQueryData)){
						foreach( $listQueryData as $kk=>$val){
							$_DATA[$data_key][$val['slug']] = $val;
						}
					}
				}
			}
		break;
	}// switch
}// if isset
?>