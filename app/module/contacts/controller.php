<?php
session_start();
$oUsers = new Users('users');
$oCategories = new Contacts('contacts_categories');
$oModule = new Contacts('contacts');
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
			// if(SITE_TRANSLATE){
			// 	$iconbar .=  '  <a href="javascript:void(0)" onclick="setCategoryTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title=แปลภาษา /></a>';
			// }		
			$iconbar .=	'<a href="javascript:void(0)" onclick="setCategoryDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" /></a>';
									
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
					$user = $oUsers->getAdminLoginUser();
					 $categories_id = $_POST["categories_id"] ; 
					 $categories_parent = $_POST["categories_parent"];
					 $categories_name = htmlspecialchars($_POST["categories_name"],ENT_QUOTES);
					 $categories_email = $_POST["categories_email"];
					  if(empty($_POST['categories_slug'])){
						$categories_slug= $oModule->createSlug($categories_name) ;
					}else{
						$categories_slug=$_POST['categories_slug'] ;
					}
					 $categories_description = htmlspecialchars($_POST["categories_description"],ENT_QUOTES);
					 $categories_params = htmlspecialchars($_POST["categories_params"],ENT_QUOTES);
					 $categories_images = $_POST["categories_server_images"] ;
					 $categories_status = $_POST["categories_status"] ;
					 if(!empty($categories_id)){
						 $oCategories->update_categories($categories_id,$categories_parent,$categories_name,$categories_slug,$categories_email ,$categories_description,$categories_images,$categories_params,$user['id'],$categories_status);
					 }else{
						 $oCategories->insert_categories($categories_parent,$categories_name,$categories_slug,$categories_email ,$categories_description,$categories_images,$categories_params,$user['id'],$categories_status);
					 }
				break;
			case 'setCategoryStatus':
					$id = addslashes($_GET['id']);
					$status =addslashes($_GET['status']);
					$oCategories->update_category_status($id,$status);
				break;
			case 'duplicateCategory':
					$user = $oUsers->getAdminLoginUser();
					$id = $_GET["id"] ; 
					$oCategories->duplicate_categories($id,$user['id']);
				break;
			case 'setCategoryDelete':
				$id = addslashes($_GET['id']);
				$child = $oCategories->get_onlychild_node($id);
				if(empty($child)){
			 		$oCategories->delete_node($id);
				}else{
					echo 'haschild';
				}
				break;
			case 'setCategoryMove':
				$id= $_GET['id'] ;
				$position =  $_GET['position'];
				$oCategories->move($id,$position) ;
			break;
///  oModule-form.html
			case 'contactsFormInit':
				if($_GET['mode']=='edit'){
						$id = addslashes($_GET['id']);
						$data = $oModule->getContacts($id);
						echo json_encode($data);
					}else{
						echo json_encode(array());
					}
				break ;
			case 'getContactsData':
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
						$search =  " WHERE ( $oModule->table.name like '%$sSearch%' or  $oModule->table.slug like '%$sSearch%') " ;
					}
					$contacts = $oModule->getContactsAll($search,$orderby,$limit);
					//print_r($categories);
					$iTotal = $oModule->getContactsSize() ;
					 $iFilteredTotal =  count($contacts);
					$output = array(
							"sEcho" => intval($_GET['sEcho']),
							"iTotalRecords" => $iTotal,
							"iTotalDisplayRecords" => $iTotal, // $iFilteredTotal,
							"aaData" => array()
						);
					$cnt = 1;
					if(!empty($contacts)){
					foreach($contacts as $key =>$value){
											
					$iconbar =	'
											<a href="javascript:void(0)" onclick="setContactsDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" /></a>';
											
		
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						
						// if empty category
						$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
						$output["aaData"][] = array(0=>$row_chk,1=>$value['subject'].'<br><div class="show_contact_msg">'.$value['content'].'</div>',2=>$value['category'],3=>$value['cdate'],4=>$iconbar,5=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++ ;
						}
					}
						echo json_encode($output) ;
				break;
			case 'saveContacts':
				$user = $oUsers->getAdminLoginUser();
				$id = $_POST['contacts_id'];
				$category_id = $_POST['categories'];
				$from_name = $_POST['contacts_from_name'];	
				$from_email = $_POST['contacts_from_email'];	
				$to_email = $_POST['contacts_to_email'];	
				$subject = $_POST['contacts_subject'];	
				$content= addslashes(htmlentities($_POST['contacts_content']));
				// $embed= addslashes(htmlentities($_POST['contacts_embed']));
				$status= $_POST['contacts_status'];
				if(empty($id)){		
					$oModule->insertContacts($category_id,$from_name,$from_email, $to_email,$content,$user['id'],$status);
				}else{
					$oModule->updateContacts($id,$category_id,$from_name,$from_email, $to_email,$content,$user['id'],$status);
				}
				break;
				case 'setContactsDelete':
					$id = addslashes($_GET['id']);
					$oModule->deleteContacts($id);
				break;
			case 'getMessagesTopRight':
				$rows = $oModule->getContactsUnread();
				if(!empty($rows)){
					foreach($rows as $key => $value){
						$messages[$key] = array(
									'title'=>$value['from_name'],
									'date'=>$value['cdate'] ,
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
		// case 'sentContact':
		// 		// print_r($_POST);
		// 		// print_r($_FILES);
		// 		$mailTo = addslashes($_POST['contact_to']);
		// 		$toName = addslashes($_POST['contact_to']);
		// 		$ccTo = NULL ;
		// 		$bccTo = NULL ; 
		// 		$subject = "Inquiry from website jstech.com";
		// 		$mailFrom = $_POST['email'] ;
		// 		$fromName= $_POST['name'];
		// 		$msg = "Name: ".$_POST['name']."\nemail: ".$_POST['email']."\nTel: ".$_POST['tel']."\ncompanyname: ".$_POST['companyname']."\n Messages:".$_POST['from_message'] ;
		// 		$attachments = NULL ;
		// 		// $oMailer-> sendMail($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$msg,$attachments) ;
		// 		// $contact = $oCategories->getCategoryByEmail($mailTo) ;
		// 		// $oModule->insertContacts($contact['id'],$fromName,$mailFrom, $mailTo,$subject,$msg,0,0);
		// 		// $oMailer->testGmail();
		// break ;
		case 'sentContact':
				$mailTo = addslashes('tong@z.com');
				$toName = addslashes('Inquiry from website jstech.com');
				$ccTo = NULL ;
				$bccTo = NULL ; 
				$subject = ' : Inquiry from website '.ROOT;
				$mailFrom = addslashes($_POST['email']) ;
				$fromName= addslashes($_POST['name']);
				$msg = "<br> ผู้ติดต่อ: ".$_POST['name'] ; 
				$msg .= "<br>  บริษัท: ".$_POST['companyname'] ; 
				$msg .= "<br>  เบอร์โทรศัพท์: ".$_POST['tel'] ; 
				$msg .= "<br>  อีเมล์: ".$_POST['email'] ; 
				$msg .= "<br>  รายละเอียด: ".$_POST['message'] ;
		
				$sendmail = $oMailer->sendMail($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$msg,$attachments) ;
				$contact = $oCategories->getCategoryByEmail('tong@z.com') ;
				$oModule->insertContacts($contact['id'],$fromName,$mailFrom, $mailTo,$subject,addslashes(htmlentities($msg)),0,0);
				// $oMailer->testGmail();
				if($sendmail){
					$_SESSION['sendMailMessage'] = 'Message sent!';
					echo $sendmail ;
				}else{
					$_SESSION['sendMailMessage'] = 'Error';
					echo $sendmail ;
				}

				// header('Location: /th/services') ;

		break ;

		case 'sentReqruitment':

				$mailTo = addslashes('phetcharatgroupp@gmail.com');
				$toName = addslashes('สมัครงาน');
				$ccTo = NULL ;
				$bccTo = NULL ; 
				$subject = 'สมัครงาน';
				$mailFrom = addslashes('phetcharatgroupp@gmail.com') ;
				$fromName= addslashes($_POST['name'].' '.$_POST['lastname']);
				$msg = "<br> ชื่อ: ".$_POST['name'] ; 
				$msg .= "<br>  นามสกุล: ".$_POST['lastname'] ; 
				$msg .= "<br>  อีเมล์: ".$_POST['email'] ; 
				$msg .= "<br>  เบอร์โทรศัพท์: ".$_POST['tel'] ; 
				$msg .= "<br>  ตำแหน่งงาน: ".$_POST['position'] ; 
				$msg .= "<br>  สถานที่ทำงาน: ".$_POST['place'] ; 
				
				$attachments = NULL ;
				$sendmail =  $oMailer-> sendMail($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$msg,$attachments) ;
				$contact = $oCategories->getCategoryByEmail('phetcharatgroupp@gmail.com') ;
				$oModule->insertContacts($contact['id'],$fromName,$mailFrom, $mailTo,$subject,$msg,0,0);
				// $oMailer->testGmail();
				if($sendmail){
					$_SESSION['sendMailMessage'] = 'Message sent!';
				}else{
					$_SESSION['sendMailMessage'] = 'Error';
				}

				echo $_SESSION['sendMailMessage'] ;
				
			break ;
		case 'sentRegister':

				$mailTo = addslashes('phetcharatgroupp@gmail.com');
				$toName = addslashes('สมัครงาน');
				$ccTo = NULL ;
				$bccTo = NULL ; 
				$subject = 'ลงทะเบียนเพื่อรับสิทธิพิเศษจากโครงการ';
				$mailFrom = addslashes('phetcharatgroupp@gmail.com') ;
				$fromName= addslashes($_POST['name'].' '.$_POST['lastname']);
				$msg = "<br> ชื่อ: ".$_POST['name'] ; 
				$msg .= "<br>  นามสกุล: ".$_POST['lastname'] ; 
				$msg .= "<br>  อีเมล์: ".$_POST['email'] ; 
				$msg .= "<br>  เบอร์โทรศัพท์: ".$_POST['tel'] ; 
				$msg .= "<br>  ประเทศที่อยู่อาศัย: ".$_POST['country'] ; 
				$msg .= "<br>  โครงการ: ".$_POST['project'] ; 
				
				$attachments = NULL ;
				$sendmail =  $oMailer-> sendMail($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$msg,$attachments) ;
				$contact = $oCategories->getCategoryByEmail('phetcharatgroupp@gmail.com') ;
				$oModule->insertContacts($contact['id'],$fromName,$mailFrom, $mailTo,$subject,$msg,0,0);
				// $oMailer->testGmail();
				if($sendmail){
					$_SESSION['sendMailMessage'] = 'Message sent!';
				}else{
					$_SESSION['sendMailMessage'] = 'Error';
				}

				echo $_SESSION['sendMailMessage'] ;
				
			break ;
		case 'sentReplyContact':
				$id =  $_POST['contacts_id']; 
				$mailTo = $_POST['replyto_email']; 
				$toName = $_POST['replyto_email'] ;
				$ccTo = NULL ;
				$bccTo = NULL ; 
				$subject = 'Reply : '.SITE_NAME.' : '.$_POST['reply_subject'];
				$mailFrom = $_POST['from_email'] ;
				$fromName= $_POST['from_email'] ;
				$msg =$_POST['contacts_messages'] ;
				$attachments = NULL ;
				if($oMailer-> sendMail($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$msg,$attachments)){
					$oModule->insert_contact_reply($id,$subject,$msg);
				}
				
				//$contact = $oCategories->getCategoryByEmail($mailTo) ;
				//$oModule->insertContacts($contact['id'],$fromName,$mailFrom, $mailTo,$subject,$msg,0,0);
				//$oMailer->testGmail();
		break ;
			case 'sentBooking':
				$mailTo = $_POST['contact_to'];
				$toName = $_POST['contact_to'] ;
				$ccTo = NULL ;
				$bccTo = NULL ; 
				$subject = 'From costavillage.com Booking : '.$_POST['from_name'];
				$mailFrom = $_POST['from_email'] ;
				$fromName= $_POST['from_name'] ;
				$msg = ' รายละเอียดการจองห้องพัก
				<br>
				<table width="500" border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td width="99" align="right">ชื่อ : </td>
									<td width="401"> '.$_POST['from_title'].'  '.$_POST['from_name'].'</td>
								  </tr>
								  <tr>
									<td align="right">อีเมลหลัก :</td>
									<td> '.$_POST['from_email'].' </td>
								  </tr>
								  <tr>
									<td align="right">อีเมลสำรอง :</td>
									<td> '.$_POST['from_email_1'].'</td>
								  </tr>
								  <tr>
									<td align="right">โทร :</td>
									<td> '.$_POST['from_tel'].'</td>
								  </tr>
								  <tr>
									<td align="right">ประเภทห้อง :</td>
									<td>  '.$_POST['room_type'].'</td>
								  </tr>
								  <tr>
									<td align="right">จำนวนผู้เข้าพัก :</td>
									<td> ผู้ใหญ่: '.$_POST['adults'].' เด็ก: '.$_POST['kids'].'</td>
								  </tr>
								  <tr>
									<td align="right">วันที่เข้าพัก :</td>
									<td>เข้าพัก  '.$_POST['start-date'].' ถึง '.$_POST['end-date'].'</td>
								  </tr>
								  <tr>
									<td align="right">การชำระเงิน :</td>
									<td>การชำระเงิน '.$_POST['payment'].'</td>
								  </tr>
								  <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								  </tr>
								</table>' ; 
				$attachments = NULL ;
				$oMailer-> sendMail($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$msg,$attachments) ;
				
				$contact = $oCategories->getCategoryByEmail($mailTo) ;
				$oModule->insertContacts($contact['id'],$fromName,$mailFrom, $mailTo,$subject,$msg,0,0);
				//$oMailer->testGmail();
		break ;	
		// for find module	
			case 'getCategoriesDataInFinds':
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
					$iconbar = '<a href="javascript:void(0)"  ><img src="../images/icons/color/target.png" title="เปิด" /></a>  ';
				}else{
					$iconbar = '<a href="javascript:void(0)"  ><img src="../images/icons/color/stop.png"  title="ปิด" /></a>  ';
				}
				//application_double.png
					
					$indent = '';	
					for($i=1;$i<$value['level'];$i++){
						$indent .= '-';
					}
				
					$showname = $value['name'].'<input name="showName_'.$value['id'].'" id="showName_'.$value['id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['id'].'" id="showSlug_'.$value['id'].'" type="hidden" value="'.$value['slug'].'" />' ;;			 
					 
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['level'],3=>$iconbar,4=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++ ;
					}
				}
				}
				echo json_encode($output) ;
			break;
			case 'getDataInFinds':
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
						$search =  " WHERE ( $oModule->table.name like '%$sSearch%' or  $oModule->table.slug like '%$sSearch%') " ;
					}
					$data = $oModule->getAll($search,$orderby,$limit);
					//print_r($categories);
					$iTotal = $oModule->getSize() ;
					 $iFilteredTotal =  count($data);
					$output = array(
							"sEcho" => intval($_GET['sEcho']),
							"iTotalRecords" => $iTotal,
							"iTotalDisplayRecords" => $iTotal, // $iFilteredTotal,
							"aaData" => array()
						);
					$cnt = 1;
					if(!empty($data)){
					foreach($data as $key =>$value){
					if($value['status']==1){	
						$iconbar = '	<a href="javascript:void(0)"  ><img src="../images/icons/color/target.png" title="เปิด" /></a>  ';
					}else{
						$iconbar = '	<a href="javascript:void(0)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>  ';
					}
				
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					
					$showname = $value['name'].'<input name="showName_'.$value['id'].'" id="showName_'.$value['id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['id'].'" id="showSlug_'.$value['id'].'" type="hidden" value="'.$value['slug'].'" />' ;
						
						$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
						$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['category'],3=>$iconbar,4=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++ ;
						}
					}
						echo json_encode($output) ;
			break;
		    case "loadFindOneInit":
						$id = addslashes($_GET['id']);
						$data = $oModule->getOne($id);
						$data['name']=htmlspecialchars_decode($data['name'],ENT_QUOTES);
						$data['meta_key']=htmlspecialchars_decode($data['meta_key'],ENT_QUOTES);
						$data['meta_description']=htmlspecialchars_decode($data['meta_description'],ENT_QUOTES);
						echo json_encode($data,true);
				break;	
				case 'loadFindCategoryInit':
						$id = addslashes($_GET['id']);
						$data = $oCategories->getCategory($id);
						$data['name']=htmlspecialchars_decode($data['name'],ENT_QUOTES);
						$data['description']=htmlspecialchars_decode($data['description'],ENT_QUOTES);
						echo json_encode($data,true);
				break;

	////////////////task for frontend  //////////////////////////////////////////////////////////////////////////
					case "find" :
					$language =  ($_SESSION['site_language'])?$_SESSION['site_language']:SITE_LANGUAGE;
					$module = $_GET['module'];
					$task = $_GET['task'];
					$type  = $_GET['type'] ;
					$key = $_GET['key'] ;
					$slug =  (!empty($_GET['slug']))?$_GET['slug']:0 ;
					$status =(!empty($_GET['status']))?$_GET['status']:1 ;
					$search = (!empty($_GET['search']))?$_GET['search']:'';
					$filter = (!empty($_GET['filter']))?$_GET['filter']:'';
					$order = (!empty($_GET['order']))?$_GET['order']:'' ;
					$separate = (!empty($_GET['separate']))?$_GET['separate']:0 ;
					$pagenate =  (!empty($_GET['paginate']))?$_GET['paginate']:0 ;
					$page =  (!empty($_GET['page']))?$_GET['page']:1 ;
					$length =  (!empty($_GET['length']))?$_GET['length']:10 ;
					$count =  (!empty($_GET['count']))?$_GET['count']:0 ;
					$data_key =  (!empty($_GET['data_key']))?$_GET['data_key']:$module;
					
					if(is_array($key)&&!empty($key)){
						$keys = $key ;
						foreach($keys as $key){
							$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
						 if($separate){
									$listQueryData =$_DATA[$data_key] ;
									$_DATA[$data_key] = NULL ;
									foreach( $listQueryData as $kk=>$val){
										$_DATA[$data_key][$val['slug']] = $val ;
									}
							}
							if($count){
									if(!empty($key)){
										$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
										$_DATA['TOTALPAGE'][$data_key]  = ceil((int)$_DATA['COUNT'][$data_key]/$length);
										$_DATA['PAGE'][$data_key]  =$page ;
									}else{
										$_DATA['COUNT'][$data_key]= $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
										$_DATA['TOTALPAGE'][$data_key]  = ceil((int)$_DATA['COUNT'][$data_key]/$length);
										$_DATA['PAGE'][$data_key]  =$page ;
									}
							}
						}
					}else{
						$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
						if($count){
							if(!empty($key)){
								$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
								$_DATA['TOTALPAGE'][$data_key]  = ceil((int)$_DATA['COUNT'][$data_key]/$length);
								$_DATA['PAGE'][$data_key]  =$page ;
							}else{
								$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
								$_DATA['TOTALPAGE'][$data_key]  = ceil((int)$_DATA['COUNT'][$data_key]/$length);
								$_DATA['PAGE'][$data_key]  =$page ;
							}
						}
						if($separate){
									$listQueryData = $_DATA[$data_key] ;
									 $_DATA[$data_key] = NULL ;
									 if(!empty($listQueryData)){
										foreach( $listQueryData as $kk=>$val){
											$_DATA[$data_key][$val['slug']] = $val ;
										}
									}
							}
					}
				break ;
	}// switch
}// if isset
?>