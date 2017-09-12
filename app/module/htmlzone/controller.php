<?php
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
// config module
$params = array(
	'module'=>'html_zone_data',
 	'table'=>'html_zone_data',
 	'translate_table'=>'html_zone_data_translate',
 	'primary_key'=>'zone_data_id',
	'parent_table'=>'html_zone',
	'parent_translate_table'=>'html_zone_translate',
	'parent_primary_key'=>'zone_id',
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE
);
$oModuleDATAHtml = new Zonehtml($params);
$params = array(
	'module'=>'html_zone_input',
 	'table'=>'html_zone_input',
 	'translate_table'=>'html_zone_input_translate',
 	'primary_key'=>'zone_input_id',
	'parent_table'=>'html_zone',
	'parent_translate_table'=>'html_zone_translate',
	'parent_primary_key'=>'zone_id',
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE
);
$oModuleHtmlZone = new Zonehtml($params);
$params = array(
	'module'=>'html_zone',
 	'table'=>'html_zone',
 	'translate_table'=>'html_zone_translate',
 	'primary_key'=>'zone_id',
	'parent_table'=>'html_zone_input',
	'parent_translate_table'=>'html_zone_input_translate',
	'parent_primary_key'=>'zone_input_id',
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE
);
$oModule = new Zonehtml($params);
if(isset($_GET['task'])){
	$task =$_GET['task'];
	switch($task){
		// pages-form.html
		case 'formInit':
			$data = $oModule->getOne(1);
			$data['all_project'] = (int)$data['all_project'];
			$data['ongoing_roof'] = (int)$data['ongoing_roof'];
			$data['ongoing_ground'] = (int)$data['ongoing_ground'];
			echo json_encode($data);
		break;
		case 'saveData':
			$user = $oUsers->getAdminLoginUser();
			$id = $_POST['id'];
			$name = addslashes($_POST['name']);
			$description = addslashes($_POST['description']);
			$status = (int)addslashes($_POST['status']);
			$sequence = $oModule->getSize();
			if(empty($id)){		
				$oModule->insertData($name,$description,$sequence,$status);
			}else{
				$oModule->updateData($id,$name,$description,$status);
			}
		break;
		case 'getData':
			$columns = array('zone_id','name','zone','mdate','sequence','zone_id','zone_id');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart = $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0= $_GET['iSortCol_0'];
			$sSortDir_0= $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oModule->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch= $_GET['sSearch']; 
			if($sSearch=='undefined'){
				$sSearch = '';
			}
			$search = '';
			if(!empty($sSearch)){
				$search =  " WHERE $oModule->table.name like '%$sSearch%' ";
			}
			$data = $oModule->getAll($search,$orderby,$limit);
			$iTotal = $oModule->getSize();
			$iFilteredTotal = count($data);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=>$iTotal,
				"iTotalDisplayRecords"=>$iTotal, // $iFilteredTotal,
				"aaData"=> array()
			);
			$cnt = 1;
			if(!empty($data)){
				foreach($data as $key =>$value){
					if($value['status']==1){	
						$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['zone_id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
					}else{
						$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['zone_id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
					}
					$iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['zone_id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';
					if(SITE_TRANSLATE){				
						$iconbar .=	'<a href="javascript:void(0)" onclick="setTranslate('.$value['zone_id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
					}
					$iconbar .= '<a href="javascript:void(0)" onclick="setDelete('.$value['zone_id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>	';
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['zone_id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setMove('.$value['zone_id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';					
					$order = '<input name="sequence_'.$value['zone_id'].'" id="sequence_'.$value['zone_id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['zone_id'].')" />';				  
					$row_chk = '<input name="table_select_'.$value['zone_id'].'" id="table_select_'.$value['zone_id'].'" class="table_checkbox" type="checkbox" value="'.$value['zone_id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					// if empty category
					$value['category'] = (empty($value['category']))?'  - ':$value['category'];
					$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['zone_id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['name'].'</a>';
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['category'],3=>$value['mdate'],4=>$order,5=>$iconbar,6=>$value['zone_id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['zone_id']);
					$cnt++ ;
				}
			}
			echo json_encode($output);
		break;
		case 'getDataContent':
			$columns = array('zone_id','name','zone','mdate','sequence','zone_id','zone_id');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart = $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0= $_GET['iSortCol_0'];
			$sSortDir_0= $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oModule->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch= $_GET['sSearch']; 
			if($sSearch=='undefined'){
				$sSearch = '';
			}
			$search = '';
			if(!empty($sSearch)){
				$search =  " WHERE $oModule->table.name like '%$sSearch%' ";
			}
			$data = $oModule->getAll($search,$orderby,$limit);
			$iTotal = $oModule->getSize();
			$iFilteredTotal = count($data);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=>$iTotal,
				"iTotalDisplayRecords"=>$iTotal, // $iFilteredTotal,
				"aaData"=> array()
			);
			$cnt = 1;
			if(!empty($data)){
				foreach($data as $key =>$value){
					$iconbar = '';
					// if($value['status']==1){	
					// 	$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['zone_id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
					// }else{
					// 	$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['zone_id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
					// }
					// $iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['zone_id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';
					if(SITE_TRANSLATE){				
						$iconbar .=	'<a href="javascript:void(0)" onclick="setTranslate('.$value['zone_id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
					}
					// $iconbar .= '<a href="javascript:void(0)" onclick="setDelete('.$value['zone_id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>	';
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['zone_id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setMove('.$value['zone_id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';					
					$order = '<input name="sequence_'.$value['zone_id'].'" id="sequence_'.$value['zone_id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['zone_id'].')" />';				  
					$row_chk = '<input name="table_select_'.$value['zone_id'].'" id="table_select_'.$value['zone_id'].'" class="table_checkbox" type="checkbox" value="'.$value['zone_id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					// if empty category
					$value['category'] = (empty($value['category']))?'  - ':$value['category'];
					$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['zone_id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['name'].'</a>';
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['category'],3=>$value['mdate'],4=>$order,5=>$iconbar,6=>$value['zone_id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['zone_id']);
					$cnt++ ;
				}
			}
			echo json_encode($output);
		break;
		case 'saveData':
			$user = $oUsers->getAdminLoginUser();
			$id = $_POST['id'];
			$category_id = $_POST['categories'];
			$name = $_POST['name'];	
			if(empty($_POST['slug'])){
				$slug = $oModule->createSlug($name);
			}else{
				$slug = $_POST['slug'];
			}
			$link = $_POST['linkurl'];
			$content = $_POST['content'];
			$meta_key = $_POST['meta_key'];
			$meta_description = $_POST['meta_description'];
			$image = (!empty($_POST['image']))?$_POST['image']:'';
			$start = date('Y-m-d H:i:s'); //(!empty($_POST['start']))?$oModule->datePickerToTime($_POST['start']):'';
			$end = date('Y-m-d H:i:s'); //(!empty($_POST['end']))?$oModule->datePickerToTime($_POST['end']):'';
			$status= $_POST['status'];
			$params = '';
			if(empty($id)){		
				$oModule->insertData($category_id,$name,$slug,$content,$image,$params,$link,$meta_key,$meta_description,$user['id'],$status,$start,$end);
			}else{
				$oModule->updateData($id,$category_id,$name,$slug,$content,$image,$params,$link,$meta_key,$meta_description,$user['id'],$status,$start,$end);
			}
		break;
		case 'duplicate':
			$user = $oUsers->getAdminLoginUser();
			$id = $_GET['id'];
			$oModule->duplicateData($id,$user['id']);
		break;
		case 'setStatus':
			$id = addslashes($_GET['id']);
			$status = addslashes($_GET['status']);
			$oModule->updateStatus($id,$status);
		break;
		case 'setMove':
			$id = addslashes($_GET['id']);
			$type = addslashes($_GET['type']);
			$oModule->moveRow($id,$type);
		break;
		case 'setDelete':
			$id = addslashes($_GET['id']);
			$oModule->deleteData($id);
		break;
		case 'loadBlock':
			$block = $oModule->getBlockAll();
			$data = array(
				0=>array('zone_id'=>0,'name'=>'--- ไม่เลือก ---'),
			);
			$cnt = 1;
			if(!empty($block)){
				foreach($block as $b){
					$data[$cnt] = array('zone_id'=>$b['zone_id'],'name'=>$b['name']);
					$cnt++;
				}
			}
			echo json_encode($data);
		break;
		case 'loadLanguages':
			$lang = $oModule->getLanguage();
			echo json_encode($lang);
		break;
// 		// for find module	
// 		case 'getCategoriesDataInFinds':
// 				$columns = array('','name','level','mdate','lft');
// 				$limit = '';
// 				$orderby = '';
// 				$search = '';
// 				$iDisplayLength = $_GET['iDisplayLength'];
// 				$iDisplayStart= $_GET['iDisplayStart'];
// 				$limit  = ' limit '.$iDisplayStart.','.$iDisplayLength ;
// 				$iSortCol_0= $_GET['iSortCol_0'];
// 				$sSortDir_0= $_GET['sSortDir_0'];
// 				if(!empty($columns[$iSortCol_0])){
// 					$orderby = " order by  ".$columns[$iSortCol_0].' '.$sSortDir_0 ;
// 				}else{
// 					$orderby = " order by  ".$columns[4].' '.$sSortDir_0 ;
// 				}
// 				$sSearch= $_GET['sSearch']; 
// 				if(!empty($sSearch)){
// 					$search =  " and (name like '%$sSearch%' or description like '%$sSearch%') " ;
// 				}
// 				$categories = $oCategories->getCategoriesAll($search,$orderby,$limit);
// 				//print_r($categories);
// 				$iTotal = $oCategories->getCategoriesSize() ;
// 				 $iFilteredTotal =  count($categories);
// 				$output = array(
// 						"sEcho" => intval($_GET['sEcho']),
// 						"iTotalRecords" => $iTotal,
// 						"iTotalDisplayRecords" => $iTotal, // $iFilteredTotal,
// 						"aaData" => array()
// 					);
// 				$cnt = 1;
// 				if(!empty($categories)){
// 				foreach($categories as $key =>$value){
// 				if($value['level']>0){
// 				if($value['status']){	
// 					$iconbar = '<a href="javascript:void(0)"><img src="../images/icons/color/target.png" title="เปิด" /></a>';
// 				}else{
// 					$iconbar = '<a href="javascript:void(0)"><img src="../images/icons/color/stop.png"  title="ปิด" /></a>';
// 				}
// 				//application_double.png
					
// 					$indent = '';	
// 					for($i=1;$i<$value['level'];$i++){
// 						$indent .= '-';
// 					}
				
// 					$showname = $value['name'].'<input name="showName_'.$value['id'].'" id="showName_'.$value['id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['id'].'" id="showSlug_'.$value['id'].'" type="hidden" value="'.$value['slug'].'" />' ;;			 
					 
// 					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
// 					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['level'],3=>$iconbar,4=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
// 					$cnt++ ;
// 					}
// 				}
// 			}
// 			echo json_encode($output) ;
// 		break;
		case 'formInitHtmlZone':
			$id = (int)addslashes($_GET['id']);
			$data = $oModuleHtmlZone->getOneHtmlZone($id);
			$data['all_project'] = (int)$data['all_project'];
			$data['ongoing_roof'] = (int)$data['ongoing_roof'];
			$data['ongoing_ground'] = (int)$data['ongoing_ground'];
			echo json_encode($data);
		break;
		case 'getDataHTMLZone':
			$columns = array('zone_input_id','name','zone_id','mdate','sequence','zone_input_id','zone_input_id');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart = $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $_GET['iSortCol_0'];
			$sSortDir_0 = $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oModuleHtmlZone->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch= $_GET['sSearch']; 
			if($sSearch=='undefined'){
				$sSearch = '';
			}
			$search = '';
			if(!empty($sSearch)){
				$search =  " WHERE $oModuleHtmlZone->table.name like '%$sSearch%' ";
			}
			$data = $oModuleHtmlZone->getAllHtmlZone($search,$orderby,$limit);
			$iTotal = $oModuleHtmlZone->getSizeHtmlZone();
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
						$iconbar = '<a href="javascript:void(0)" onclick="setStatusHtmlZone('.$value['zone_input_id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
					}else{
						$iconbar = '<a href="javascript:void(0)" onclick="setStatusHtmlZone('.$value['zone_input_id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
					}
					$iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicateHtmlZone('.$value['zone_input_id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';
					if(SITE_TRANSLATE){
						$iconbar .=	'<a href="javascript:void(0)" onclick="setTranslateHtmlZone('.$value['zone_input_id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
					}
					$iconbar .= '<a href="javascript:void(0)" onclick="setDeleteHtmlZone('.$value['zone_input_id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>	';
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['zone_input_id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setMove('.$value['zone_input_id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';					
					$order = '<input name="sequence_'.$value['zone_input_id'].'" id="sequence_'.$value['zone_input_id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['zone_input_id'].')" />';				  
					$row_chk = '<input name="table_select_'.$value['zone_input_id'].'" id="table_select_'.$value['zone_input_id'].'" class="table_checkbox" type="checkbox" value="'.$value['zone_input_id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					// if empty category
					$value['category'] = (empty($value['category']))?'  - ':$value['category'];
					$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['zone_input_id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['name'].'</a>';
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['zone_id'],3=>$value['mdate'],4=>$order,5=>$iconbar,6=>$value['zone_input_id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['zone_input_id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'duplicateHtmlZone':
			$user = $oUsers->getAdminLoginUser();
			$id = $_GET['id'];
			$oModuleHtmlZone->duplicateHtmlZone($id,$user['id']);
		break;
		case 'setStatusHtmlZone':
			$id = addslashes($_GET['id']);
			$status = addslashes($_GET['status']);
			$oModuleHtmlZone->setStatusHtmlZone($id,$status);
		break;
		case 'setDeleteHtmlZone':
			$id = addslashes($_GET['id']);
			$oModuleHtmlZone->deleteDataHtmlZone($id);
		break;
		case 'saveDataInput':
			$user = $oUsers->getAdminLoginUser();
			$id = (int)addslashes($_POST['id']);
			$zone_id = (int)addslashes($_POST['zone_id']);
			$name = addslashes($_POST['name']);	
			$description = addslashes($_POST['description']);
			$type = (int)addslashes($_POST['type']);
			$status = (int)addslashes($_POST['status']);
			if(empty($id)){		
				$oModuleHtmlZone->insertDataInput($zone_id,$name,$description,$type,$status);
			}else{
				$oModuleHtmlZone->updateDataInput($id,$zone_id,$name,$description,$type,$status);
			}
		break;
		case 'getAllInputInBlock':
			$zone_id = (int)addslashes($_GET['id']);
			$data = $oModuleHtmlZone->getAllInputInBlock($zone_id);
			echo json_encode($data);
		break;
		case 'getAllInputInBlock_lang':
			$zone_id = (int)addslashes($_GET['id']);
			$language = addslashes($_GET['language']);
			$data = $oModuleHtmlZone->getAllInputInBlock_lang($zone_id,$language);
			echo json_encode($data);
		break;
		case 'saveDataHTML':
			$zone_id = (int)$_POST['id'];
			foreach ($_POST['block'] as $key_zone_input_id => $value) {
				$data = array(
					"zone_data_id"=>$value['zone_data_id'],
					"zone_id"=>$zone_id,
					"zone_input_id"=>$key_zone_input_id,
					"params"=>"'".$value['text']."'",
					"mdate"=>"NOW()",
					"cdate"=>"NOW()"
				);
				$oModuleDATAHtml->setManagementData($data);
			}
		break;
		case 'saveTranslateContent':
			$zone_id = (int)$_POST['id'];
			$lang = $_POST['translate_language'];
			foreach ($_POST['block'] as $key_zone_input_id => $value) {
				$data = array(
					"zone_data_id"=>$value['zone_data_id'],
					"zone_id"=>$zone_id,
					"zone_input_id"=>$key_zone_input_id,
					"lang"=>"'$lang'",
					"params"=>"'".$value['text']."'",
					"mdate"=>"NOW()",
					"cdate"=>"NOW()"
				);
				$oModuleDATAHtml->setManagementDataTranslate($data);
			}
		break;

		case 'getDataInFinds':
			$columns = array('zone_id','name','zone_id','mdate','sequence');
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
			$sSearch= $_GET['sSearch']; 
			if(!empty($sSearch)){
				$search = " WHERE ( $oModule->table.name like '%$sSearch%') ";
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
					$row_chk = '<input name="table_select_'.$value['zone_id'].'" id="table_select_'.$value['zone_id'].'" class="table_checkbox" type="checkbox" value="'.$value['zone_id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					$showname = $value['name'].'<input name="showName_'.$value['zone_id'].'" id="showName_'.$value['zone_id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['zone_id'].'" id="showSlug_'.$value['zone_id'].'" type="hidden" value="'.$value['slug'].'" />';
					$value['category'] = (empty($value['category']))?' - ':$value['category'] ;
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['category'],3=>$iconbar,4=>$value['zone_id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['zone_id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case "loadFindOneInit":
			$id = (int)$_GET['id'];
			$data = $oModule->getOneHTML($id);
			$data['name'] = htmlspecialchars_decode($data['name'],ENT_QUOTES);
			$data['slug'] = htmlspecialchars_decode($data['name'],ENT_QUOTES);
			echo json_encode($data,true);
		break;	
// 				case 'loadFindCategoryInit':
// 						$id = addslashes($_GET['id']);
// 						$data = $oCategories->getCategory($id);
// 						$data['name']=htmlspecialchars_decode($data['name'],ENT_QUOTES);
// 						$data['description']=htmlspecialchars_decode($data['description'],ENT_QUOTES);
// 						echo json_encode($data,true);
// 				break;
// ////////////////  reorder function /////////////
		case 'loadCategoriesFilter':
			$block = $oModule->getBlockAll();
			$options = '<option value="0">--All Block--</option>';
			$cnt = 1;
			if(!empty($block)){
				foreach($block as $c){
					$options .= '<option value="'.$c['id'].'" >'.$c['name'].'</option>';
				}
			}
			echo $options;
		break;
// 			case 'reorderData':
// 						$id = explode('-',$_GET['id']) ;
// 						$sort = explode('-',$_GET['sort']) ;
// 						$oModule->reOrderDataDragDrop($id,$sort);
// 			break ;		
// 			case 'switchOrder';
// 				$id=$_GET['id'];
// 				$sort = $_GET['sort'] ;
// 				$oModule->switchOrder($id,$sort);
// 			break ;	
// 			case 'setReorderAll' :
// 				$columns = array('id','name','category_id','mdate','sequence','id','id');
// 				$column = $columns[(int)$_GET['column']] ;
// 				$direction =strtoupper(addslashes($_GET['direction']));
// 				$oModule->setReorderAll($column,$direction);
// 			break; 
// 			case 'changeCategory':
// 				$id=$_GET['id'];
// 				$category_id = $_GET['category_id'] ;
// 				$oModule->changeCategory($id,$category_id);
// 			break;	
////////////////task for frontend  ///////////
		case "find" :
			$language = LANG;// ($_SESSION['site_language'])?$_SESSION['site_language']:SITE_LANGUAGE;
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
			$pagenate = (!empty($_GET['paginate']))?$_GET['paginate']:0;
			$page = (!empty($_GET['page']))?$_GET['page']:1;
			$length = (!empty($_GET['length']))?$_GET['length']:10;
			$count = (!empty($_GET['count']))?$_GET['count']:0;
			$data_key = (!empty($_GET['data_key']))?$_GET['data_key']:$module;
			if(is_array($key)&&!empty($key)){
				$keys = $key;
				foreach($keys as $key){
					$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
					if($separate){
						$listQueryData =$_DATA[$data_key];
						$_DATA[$data_key] = NULL;
						foreach( $listQueryData as $kk=>$val){
							$_DATA[$data_key][$val['slug']] = $val;
						}
					}
					if($count){
						if(!empty($key)){
							$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
							$_DATA['TOTALPAGE'][$data_key] = ceil((int)$_DATA['COUNT'][$data_key]/$length);
							$_DATA['PAGE'][$data_key] = $page;
						}else{
							$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
							$_DATA['TOTALPAGE'][$data_key] = ceil((int)$_DATA['COUNT'][$data_key]/$length);
							$_DATA['PAGE'][$data_key] = $page;
						}
					}
				}
			}else{
				$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
				if($count){
					if(!empty($key)){
						$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
						$_DATA['TOTALPAGE'][$data_key] = ceil((int)$_DATA['COUNT'][$data_key]/$length);
						$_DATA['PAGE'][$data_key] = $page;
					}else{
						$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
						$_DATA['TOTALPAGE'][$data_key] = ceil((int)$_DATA['COUNT'][$data_key]/$length);
						$_DATA['PAGE'][$data_key] = $page;
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