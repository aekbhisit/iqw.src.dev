<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
$oCategories = new Comments('comments_categories');
$oComments = new Comments('comments');
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		//  categories task   
		case 'getCategoriesData':
			$columns = array('','name','level','mdate','lft');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart= $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $_GET['iSortCol_0'];
			$sSortDir_0 = $_GET['sSortDir_0'];
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
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" /></a>&nbsp;';
						}else{
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" /></a>&nbsp;';
						}
						$iconbar .=	'<a href="javascript:void(0)" onclick="setCategoryEdit('.$value['id'].')"><img src="../images/icons/color/pencil.png" /></a><a href="javascript:void(0)" onclick="setCategoryDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" /></a>';
						$order = '<a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'left\')"><img src="../images/icons/black/16/arrow_up_small.png" /></a><a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'right\')" ><img src="../images/icons/black/16/arrow_down_small.png" /></a>';
						$indent = '';	
						for($i=1;$i<$value['level'];$i++){
							$indent .= '-';
						}
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						$output["aaData"][] = array(0=>$row_chk,1=>$indent.$value['name'],2=>$value['level'],3=>$value['mdate'],4=>$order,5=>$iconbar ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++ ;
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
			}
		break;
		case 'saveCategory':
			$user = $oUsers->getAdminLoginUser();
			$categories_id = $oCategories->setInt($_POST["categories_id"]);
			$categories_parent = $oCategories->setInt($_POST["categories_parent"]);
			$categories_name = $oCategories->setString($_POST["categories_name"]);
			$categories_description = $oCategories->setString($_POST["categories_description"]);
			$categories_images = $oCategories->setString($_POST["categories_server_images"]);
			$categories_status = $oCategories->setInt($_POST["categories_status"]);
			if(!empty($categories_id)){
				$oCategories->update_categories($categories_id,$categories_parent,$categories_name,$categories_description,$categories_images,'',$user['id'],$categories_status);
			}else{
				$oCategories->insert_categories($categories_parent,$categories_name,$categories_description,$categories_images,'',$user['id'],$categories_status);
			}
		break;
		case 'setCategoryStatus':
			$id = $oCategories->setInt($_GET['id']);
			$status =$oCategories->setInt($_GET['status']);
			$oCategories->update_category_status($id,$status);
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
		case 'commentsFormInit':
			if($_GET['mode']=='edit'){
				$id = $oComments->setInt($_GET['id']);
				$data = $oComments->getComments($id);
				echo json_encode($data);
			}
		break;
		case 'getCommentsData':
			$columns = array('id','content','category_id','mdate','sequence');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart = $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $_GET['iSortCol_0'];
			$sSortDir_0 = $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oComments->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch = $oComments->setString($_GET['sSearch']); 
			if(!empty($sSearch)){
				$search = " WHERE ($oComments->table.name like '%$sSearch%' or $oComments->table.slug like '%$sSearch%') ";
			}
			$comments = $oComments->getCommentsAll($search,$orderby,$limit);
			$iTotal = $oComments->getCommentsSize();
			$iFilteredTotal = count($comments);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=>$iTotal,
				"iTotalDisplayRecords"=>$iTotal, // $iFilteredTotal,
				"aaData"=>array()
			);
			$cnt = 1;
			if(!empty($comments)){
				foreach($comments as $key =>$value){
					if($value['status']==1){	
						$iconbar = '<a href="javascript:void(0)" onclick="setCommentsStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" /></a>&nbsp;';
					}else{
						if($value['status']==2){
							$iconbar = '<a href="javascript:void(0)" onclick="setCommentsStatus('.$value['id'].',0)" ><img src="../images/icons/color/alarm.png" /></a>&nbsp;';
						}else{
							$iconbar = '<a href="javascript:void(0)" onclick="setCommentsStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" /></a>&nbsp;';
						}
					}
					$iconbar .=	'<a href="javascript:void(0)" onclick="setCommentsEdit('.$value['id'].')"><img src="../images/icons/color/pencil.png" /></a><a href="javascript:void(0)" onclick="setCommentsDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" /></a>';
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					// if empty category
					$content = iconv_substr($value['content'],0,100);
					$value['category'] = (empty($value['category']))?' - ':$value['category'];
					$output["aaData"][] = array(0=>$row_chk,1=>$content,2=>$value['category'],3=>$value['mdate'],4=>$iconbar ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'saveComments':
			$user = $oUsers->getAdminLoginUser();
			$id = $oComments->setInt($_POST['comments_id']);
			$system_id = $oComments->setInt($_POST['comments_system_id']);
			$category_id = $oComments->setInt($_POST['categories']);
			$content = $oComments->setString($_POST['comments_content']);
			$status = $oComments->setInt($_POST['comments_status']);
			if(empty($id)){		
				$oComments->insertComments($category_id,$parent_id,$system_id,$content,$user['id'],$status);
			}else{
				$oComments->updateComments($id,$category_id,$parent_id,$system_id,$content,$user['id'],$status);
			}
		break;
		case 'setCommentsStatus':
			$id = $oComments->setInt($_GET['id']);
			$status = $oComments->setInt($_GET['status']);
			$oComments->updateCommentsStatus($id,$status);
		break;
		case 'setCommentsMove':
			$id = $oComments->setInt($_GET['id']);
			$type = $oComments->setString($_GET['type']);
			$oComments->moveRow($id,$type);
		break;
		case 'setCommentsDelete':
			$id = $oComments->setInt($_GET['id']);
			$oComments->deleteComments($id);
		break;
		case 'getCommentsTopRight':
			$rows = $oComments->getCommentsUnread();
			if(!empty($rows)){
				foreach($rows as $key => $value){
					$content = iconv_substr($value['content'],0,30);
					$comments[$key] = array(
						'title'=>$content,
						'date'=>$value['cdate'],
						'read'=>'read'
					);
				}
				echo json_encode($comments);
			}
		break;	
		// for find module	
		case 'getCategoriesDataInFinds':
			$columns = array('','name','level','mdate','lft');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart = $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $_GET['iSortCol_0'];
			$sSortDir_0 = $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by ".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch = $oCategories->setString($_GET['sSearch']); 
			if(!empty($sSearch)){
				$search =  " and (name like '%$sSearch%' or description like '%$sSearch%') ";
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
						$showname = $value['name'].'<input name="showName_'.$value['id'].'" id="showName_'.$value['id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['id'].'" id="showSlug_'.$value['id'].'" type="hidden" value="'.$value['slug'].'" />' ;;			 
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['level'],3=>$iconbar,4=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++ ;
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
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart = $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $_GET['iSortCol_0'];
			$sSortDir_0 = $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oModule->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch = $oModule->setString($_GET['sSearch']); 
			if(!empty($sSearch)){
				$search =  " WHERE ($oModule->table.name like '%$sSearch%' or $oModule->table.slug like '%$sSearch%') ";
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
					$showname = $value['name'].'<input name="showName_'.$value['id'].'" id="showName_'.$value['id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['id'].'" id="showSlug_'.$value['id'].'" type="hidden" value="'.$value['slug'].'" />';
					$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
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