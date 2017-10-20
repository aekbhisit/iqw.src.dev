<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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
		case 'formInit':
			$id = $oModule->setInt($_GET['id']);
			$data = $oModule->getOneHTML($id);
			$data['name'] = $oModule->getString($data['name']);
			$data['description'] = $oModule->getString($data['description']);
			echo json_encode($data);
		break;
		case 'saveData':
			$user = $oUsers->getAdminLoginUser();
			$id = $oModule->setInt($_POST['id']);
			$name = $oModule->setString($_POST['name']);
			$description = $oModule->setString($_POST['description']);
			$status = $oModule->setInt($_POST['status']);
			$sequence = $oModule->getSize();
			if(empty($id)){		
				$oModule->insertData($name,$description,$sequence,$status);
			}else{
				$oModule->updateData($id,$name,$description,$status);
			}
		break;
		case 'getData':
			$columns = array('zone_id','name','mdate','sequence','zone_id','zone_id');
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
			if($sSearch=='undefined'){
				$sSearch = '';
			}
			$search = '';
			if(!empty($sSearch)){
				$search = " WHERE $oModule->table.name like '%$sSearch%' ";
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
						$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['zone_id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>';
					}else{
						$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['zone_id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>';
					}
					$iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['zone_id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';
					$iconbar .= '<a href="javascript:void(0)" onclick="setDelete('.$value['zone_id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['zone_id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setMove('.$value['zone_id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';
					$order = '<input name="sequence_'.$value['zone_id'].'" id="sequence_'.$value['zone_id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['zone_id'].')" />';
					$row_chk = '<input name="table_select_'.$value['zone_id'].'" id="table_select_'.$value['zone_id'].'" class="table_checkbox" type="checkbox" value="'.$value['zone_id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					// if empty category
					$value['category'] = (empty($value['category']))?'  - ':$value['category'];
					$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['zone_id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['name'].'</a>';
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['mdate'],3=>$order,4=>$iconbar,5=>$value['zone_id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['zone_id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'getDataContent':
			$columns = array('zone_id','name','mdate','sequence','zone_id','zone_id');
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
			if($sSearch=='undefined'){
				$sSearch = '';
			}
			$search = '';
			if(!empty($sSearch)){
				$search = " WHERE $oModule->table.name like '%$sSearch%' ";
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
					$iconbar = '';
					if(SITE_TRANSLATE){
						$iconbar .=	'<a href="javascript:void(0)" onclick="setTranslate('.$value['zone_id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
					}
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['zone_id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setMove('.$value['zone_id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';
					$order = '<input name="sequence_'.$value['zone_id'].'" id="sequence_'.$value['zone_id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['zone_id'].')" />';
					$row_chk = '<input name="table_select_'.$value['zone_id'].'" id="table_select_'.$value['zone_id'].'" class="table_checkbox" type="checkbox" value="'.$value['zone_id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					// if empty category
					$value['category'] = (empty($value['category']))?' - ':$value['category'];
					$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['zone_id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['name'].'</a>';
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['mdate'],3=>$order,4=>$iconbar,5=>$value['zone_id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['zone_id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'saveData':
			$user = $oUsers->getAdminLoginUser();
			$id = $oModule->setInt($_POST['id']);
			$category_id = $oModule->setInt($_POST['categories']);
			$name = $oModule->setString($_POST['name']);
			if(empty($_POST['slug'])){
				$slug = $oModule->createSlug($name);
			}else{
				$slug = $oModule->setString($_POST['slug']);
			}
			$link = $oModule->setString($_POST['linkurl']);
			$content = $oModule->setString($_POST['content']);
			$meta_key = $oModule->setString($_POST['meta_key']);
			$meta_description = $oModule->setString($_POST['meta_description']);
			$image = (!empty($_POST['image']))?$_POST['image']:'';
			$image = $oModule->setString($image);
			$start = date('Y-m-d H:i:s'); //(!empty($_POST['start']))?$oModule->datePickerToTime($_POST['start']):'';
			$end = date('Y-m-d H:i:s'); //(!empty($_POST['end']))?$oModule->datePickerToTime($_POST['end']):'';
			$status = $oModule->setInt($_POST['status']);
			$params = '';
			if(empty($id)){
				$oModule->insertData($category_id,$name,$slug,$content,$image,$params,$link,$meta_key,$meta_description,$user['id'],$status,$start,$end);
			}else{
				$oModule->updateData($id,$category_id,$name,$slug,$content,$image,$params,$link,$meta_key,$meta_description,$user['id'],$status,$start,$end);
			}
		break;
		case 'duplicate':
			$user = $oUsers->getAdminLoginUser();
			$id = $oModule->setInt($_GET['id']);
			$oModule->duplicateData($id,$user['id']);
		break;
		case 'setStatus':
			$id = $oModule->setInt($_GET['id']);
			$status = $oModule->setInt($_GET['status']);
			$oModule->updateStatus($id,$status);
		break;
		case 'setMove':
			$id = $oModule->setInt($_GET['id']);
			$type = $oModule->setString($_GET['type']);
			$oModule->moveRow($id,$type);
		break;
		case 'setDelete':
			$id = $oModule->setInt($_GET['id']);
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
		case 'formInitHtmlZone':
			if($_GET['mode']=='edit') {
				$id = $oModuleHtmlZone->setInt($_GET['id']);
				$data = $oModuleHtmlZone->getOneHtmlZone($id);
				$data['name'] = $oModuleHtmlZone->setString($data['name']);
				$data['description'] = $oModuleHtmlZone->setString($data['description']);
				echo json_encode($data);
			}
		break;
		case 'getDataHTMLZone':
			$columns = array('zone_input_id','name','zone_id','mdate','sequence','zone_input_id','zone_input_id');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $oModuleHtmlZone->setInt($_GET['iDisplayLength']);
			$iDisplayStart = $oModuleHtmlZone->setInt($_GET['iDisplayStart']);
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $oModuleHtmlZone->setInt($_GET['iSortCol_0']);
			$sSortDir_0 = $oModuleHtmlZone->setString($_GET['sSortDir_0']);
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oModuleHtmlZone->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch = $oModuleHtmlZone->setString($_GET['sSearch']);
			if($sSearch=='undefined'){
				$sSearch = '';
			}
			$search = '';
			if(!empty($sSearch)){
				$search = " WHERE $oModuleHtmlZone->table.name like '%$sSearch%' ";
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
					$value['category'] = (empty($value['category']))?' - ':$value['category'];
					$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['zone_input_id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['name'].'</a>';
					$type_text = '';
					switch ($value['type']) {
						case 1:
							$type_text = 'Text';
						break;
						case 2:
							$type_text = 'TextArea';
						break;
						case 3:
							$type_text = 'Image';
						break;
					}
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$type_text,3=>$value['zone_name'],4=>$value['mdate'],5=>$order,6=>$iconbar,7=>$value['zone_input_id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['zone_input_id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'duplicateHtmlZone':
			$user = $oModuleHtmlZone->getAdminLoginUser();
			$id = $oModuleHtmlZone->setInt($_GET['id']);
			$oModuleHtmlZone->duplicateHtmlZone($id,$user['id']);
		break;
		case 'setStatusHtmlZone':
			$id = $oModuleHtmlZone->setInt($_GET['id']);
			$status = $oModuleHtmlZone->setInt($_GET['status']);
			$oModuleHtmlZone->setStatusHtmlZone($id,$status);
		break;
		case 'setDeleteHtmlZone':
			$id = $oModuleHtmlZone->setInt($_GET['id']);
			$oModuleHtmlZone->deleteDataHtmlZone($id);
		break;
		case 'saveDataInput':
			$user = $oUsers->getAdminLoginUser();
			$id = $oModuleHtmlZone->setInt($_POST['id']);
			$zone_id = $oModuleHtmlZone->setInt($_POST['zone_id']);
			$name = $oModuleHtmlZone->setString($_POST['name']);
			$description = $oModuleHtmlZone->setString($_POST['description']);
			$type = $oModuleHtmlZone->setInt($_POST['type']);
			$status = $oModuleHtmlZone->setInt($_POST['status']);
			if(empty($id)){	
				$oModuleHtmlZone->insertDataInput($zone_id,$name,$description,$type,$status);
			}else{
				$oModuleHtmlZone->updateDataInput($id,$zone_id,$name,$description,$type,$status);
			}
		break;
		case 'reorderDataHtmlZone':
			$id = explode('-',$_GET['id']);
			$sort = explode('-',$_GET['sort']);
			$oModuleHtmlZone->reOrderDataDragDrop($id,$sort);
		break;
		case 'switchOrderHtmlZone';
			$id = $oModuleHtmlZone->setInt($_GET['id']);
			$sort = $oModuleHtmlZone->setString($_GET['sort']);
			$oModuleHtmlZone->switchOrder($id,$sort);
		break;
		case 'setReorderAllHtmlZone':
			$columns = array('zone_id','name','mdate','sequence','zone_id','zone_id');
			$column = $columns[$oModuleHtmlZone->setInt($_GET['column'])];
			$direction = strtoupper($oModuleHtmlZone->setString($_GET['direction']));
			$oModuleHtmlZone->setReorderAll($column,$direction);
		break;
		case 'getAllInputInBlock':
			$zone_id = $oModuleHtmlZone->setInt($_GET['id']);
			$data = $oModuleHtmlZone->getAllInputInBlock($zone_id);
			if(!empty($data)) {
				foreach ($data as $key => $value) {
					$data[$key]['params'] = $oModuleHtmlZone->getString($value['params']);
				}
			}
			echo json_encode($data);
		break;
		case 'getAllInputInBlock_lang':
			$zone_id = $oModuleHtmlZone->setInt($_GET['id']);
			$language = $oModuleHtmlZone->setString($_GET['language']);
			$data = $oModuleHtmlZone->getAllInputInBlock_lang($zone_id,$language);
			if(!empty($data)) {
				foreach ($data as $key => $value) {
					$data[$key]['params'] = $oModuleHtmlZone->getString($value['params']);
				}
			}
			echo json_encode($data);
		break;
		case 'saveDataHTML':
			$zone_id = $oModuleDATAHtml->setInt($_POST['id']);
			foreach ($_POST['block'] as $key_zone_input_id => $value) {
				$data = array(
					"zone_data_id"=>$oModuleDATAHtml->setInt($value['zone_data_id']),
					"zone_id"=>$oModuleDATAHtml->setInt($zone_id),
					"zone_input_id"=>$oModuleDATAHtml->setInt($key_zone_input_id),
					"params"=>"'".$oModuleDATAHtml->setString($value['text'])."'",
					"mdate"=>"NOW()",
					"cdate"=>"NOW()"
				);
				$oModuleDATAHtml->setManagementData($data);
			}
		break;
		case 'saveTranslateContent':
			$zone_id = $oModuleDATAHtml->setInt($_POST['id']);
			$lang = $oModuleDATAHtml->setString($_POST['translate_language']);
			foreach ($_POST['block'] as $key_zone_input_id => $value) {
				$data = array(
					"zone_data_id"=>$oModuleDATAHtml->setInt($value['zone_data_id']),
					"zone_id"=>$oModuleDATAHtml->setInt($zone_id),
					"zone_input_id"=>$oModuleDATAHtml->setInt($key_zone_input_id),
					"lang"=>"'$lang'",
					"params"=>"'".$oModuleDATAHtml->setString($value['text'])."'",
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
				$search = " WHERE ($oModule->table.name like '%$sSearch%') ";
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
				foreach($data as $key => $value){
					if($value['status']==1){
						$iconbar = '<a href="javascript:void(0)"><img src="../images/icons/color/target.png" title="เปิด" /></a>';
					}else{
						$iconbar = '<a href="javascript:void(0)"><img src="../images/icons/color/stop.png" title="ปิด" /></a>';
					}
					$row_chk = '<input name="table_select_'.$value['zone_id'].'" id="table_select_'.$value['zone_id'].'" class="table_checkbox" type="checkbox" value="'.$value['zone_id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					$showname = $value['name'].'<input name="showName_'.$value['zone_id'].'" id="showName_'.$value['zone_id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['zone_id'].'" id="showSlug_'.$value['zone_id'].'" type="hidden" value="'.$value['name'].'" />';
					$value['category'] = (empty($value['category']))?' - ':$value['category'];
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['category'],3=>$iconbar,4=>$value['zone_id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['zone_id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case "loadFindOneInit":
			$id = $oModule->setInt($_GET['id']);
			$data = $oModule->getOneHTML($id);
			$data['name'] = $oModule->getString($data['name']);
			$data['slug'] = $oModule->getString($data['name']);
			echo json_encode($data,true);
		break;	
		// reorder function /////////////
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
		case 'reorderData':
			$id = explode('-',$_GET['id']);
			$sort = explode('-',$_GET['sort']);
			$oModule->reOrderDataDragDrop($id,$sort);
		break;
		case 'switchOrder';
			$id = $oModule->setInt($_GET['id']);
			$sort = $oModule->setString($_GET['sort']);
			$oModule->switchOrder($id,$sort);
		break;
		case 'setReorderAll':
			$columns = array('zone_id','name','mdate','sequence','zone_id','zone_id');
			$column = $columns[$oModule->setInt($_GET['column'])];
			$direction =strtoupper($oModule->setString($_GET['direction']));
			$oModule->setReorderAll($column,$direction);
		break;
		////////////////task for frontend  ///////////
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
			$pagenate = (!empty($_GET['paginate']))?$_GET['paginate']:0;
			$page = (!empty($_GET['page']))?$_GET['page']:1;
			$length = (!empty($_GET['length']))?$_GET['length']:10;
			$count = (!empty($_GET['count']))?$_GET['count']:0;
			$data_key = (!empty($_GET['data_key']))?$_GET['data_key']:$module;
			if(is_array($key)&&!empty($key)){
				$keys = $key;
				foreach($keys as $key){
					$key = $oModule->setInt($key);
					$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oModuleHtmlZone);
					if($separate){
						$listQueryData =$_DATA[$data_key];
						$_DATA[$data_key] = NULL;
						foreach( $listQueryData as $kk=>$val){
							$_DATA[$data_key][$val['slug']] = $val;
						}
					}
					if($count){
						if(!empty($key)){
							$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oModuleHtmlZone);
							$_DATA['TOTALPAGE'][$data_key] = ceil((int)$_DATA['COUNT'][$data_key]/$length);
							$_DATA['PAGE'][$data_key] = $page;
						}else{
							$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oModuleHtmlZone);
							$_DATA['TOTALPAGE'][$data_key] = ceil((int)$_DATA['COUNT'][$data_key]/$length);
							$_DATA['PAGE'][$data_key] = $page;
						}
					}
				}
			}else{
				$key = $oModule->setInt($key);
				$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oModuleHtmlZone);
				if($count){
					if(!empty($key)){
						$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oModuleHtmlZone);
						$_DATA['TOTALPAGE'][$data_key] = ceil((int)$_DATA['COUNT'][$data_key]/$length);
						$_DATA['PAGE'][$data_key] = $page;
					}else{
						$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oModuleHtmlZone);
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