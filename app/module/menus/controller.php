<?php
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');

// config module
$params_category = array(
	'module'=>'menus_categories',
	'table'=>'menus_categories',
	'primary_key'=>'category_id',
	'translate_table'=>null,
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE
);
$oCategories = new Menus($params_category);
$params = array(
	'module'=>'menus',
	'table'=>'menus',
	'primary_key'=>'menu_id',
	'parent_table'=> 'menus_categories',
	'parent_translate_table'=>null,
	'parent_primary_key'=> 'category_id',
	'translate_table'=>'menus_translate',
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE
);
$oModule = new Menus($params);
if(isset($_GET['task'])){
	
	$task = $_GET['task'];
	switch($task){
		// categories task   
		case 'getCategoriesData':
			$columns = array('','name','level','mdate','lft');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart= $_GET['iDisplayStart'];
			$limit  = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $_GET['iSortCol_0'];
			$sSortDir_0 = $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by ".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch = $_GET['sSearch']; 
			if(!empty($sSearch)){
				$search =  " and (name like '%$sSearch%' or description like '%$sSearch%') ";
			}
			$categories = $oCategories->getCategoriesAll($search,$orderby,$limit);
			$iTotal = $oCategories->getCategoriesSize();
			$iFilteredTotal = count($categories);
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
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
						}else{
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
						}
						$iconbar .=	'<a href="javascript:void(0)" onclick="setCategoryDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>  ';
						if(SITE_TRANSLATE){
							$iconbar .= '<a href="javascript:void(0)" onclick="setCategoryTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title=แปลภาษา /></a>';
						}
			            $iconbar .=  '<a href="javascript:void(0)" onclick="setCategoryDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';
						$order = '<a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'left\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'right\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';
						$indent = '';	
						for($i=1;$i<$value['level'];$i++){
							$indent .= '-';
						}
						$showname = '<a href="javascript:void(0)" onclick="setCategoryEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';					  
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						$output["aaData"][] = array(0=>$row_chk,1=>$showname ,2=>$value['level'],3=>$value['mdate'],4=>$order,5=>$iconbar,6=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
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
			$cnt =1 ;
			if(!empty($categories)){
				foreach($categories as $c){
					if(!isset($c['parent'])) {
						$c['parent'] = 0;
					}
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
						$data['name']=htmlspecialchars_decode($data['name'],ENT_QUOTES);
						$data['description']=htmlspecialchars_decode($data['description'],ENT_QUOTES);
						echo json_encode($data);
					}
				break;
			case 'saveCategory':
					$user = $oUsers->getAdminLoginUser();
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
					$user = $oUsers->getAdminLoginUser();
					$id = $_GET["id"] ; 
					$oCategories->duplicate_categories($id,$user['id']);
				break;
			case 'setCategoryStatus':
					$id = addslashes($_GET['id']);
					$status =addslashes($_GET['status']);
					$oCategories->update_category_status($id,$status);
				break;
			case 'setCategoryDelete':
				//echo  '';
				$id = addslashes($_GET['id']);
				$child = $oCategories->get_onlychild_node($id);
				if(empty($child)){
			 		$oCategories->delete_node($id);
					$oCategories->deleteCategoryTranslate($id);
				}else{
					echo 'haschild';
				}
				break;
		case 'setCategoryMove':
			$id = $_GET['id'];
			$position = $_GET['position'];
			$oCategories->move($id,$position);
		break;
		// translate
		case 'categoryFormTranslateInit':
			if($_GET['mode']=='translate'){
				$id = addslashes($_GET['id']);
				$lang = addslashes($_GET["language"]);
				$data = $oCategories->getTranslateCategory($id,$lang);
				echo json_encode($data);
			}
		break;
		case 'saveCategoryTranslate':
			$categories_id = $_POST["categories_id"]; 
			$categories_lang = $_POST["categories_language"]; 
			$categories_name = $_POST["categories_name"];
			$categories_description = $_POST["categories_description"];
			$categories_images = $_POST["categories_server_images"];
			$oCategories->saveCategoriesTranslate($categories_lang,$categories_id,$categories_name,$categories_description,$categories_images,'');
		break;
		// pages-form.html
		case 'formInit':
			if($_GET['mode']=='edit'){
				$id = addslashes($_GET['id']);
				$data = $oCategories->getOne($id);
				$data['cate_id']=$id;
				$data['title']=$oCategories->getString($data['title']);
				echo json_encode($data);
			}
		break;
		case 'getData':
			$columns = array('category_id','title','mdate','category_id','category_id');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart= $_GET['iDisplayStart'];
			$limit  = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0= $_GET['iSortCol_0'];
			$sSortDir_0= $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oCategories->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch= $_GET['sSearch']; 
			if($sSearch=='undefined'){
				$sSearch = '';
			}
			if(!empty($sSearch)){
				$search =  " WHERE ( $oCategories->table.title like '%$sSearch%') " ;
			}
			// }else{
			// 	$category_id  = $_GET['filterCategoryID']; 
			// 	if($category_id>0){
			// 		$search =  " WHERE $oModule->table.category_id = $category_id ";
			// 	}
			// }
			$data = $oCategories->getCategoryAll($search,$orderby,$limit);
			$iTotal = $oCategories->getCategorySize() ;
			$iFilteredTotal = count($data);
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iTotal,
				"aaData" => array()
			);
			$cnt = 1;
			if(!empty($data)){
				foreach($data as $key =>$value){
					if($acc['menus']['edit'] == 1){
						if($value['status']==1){	
							$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['category_id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
						}else{
							$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['category_id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
						}
						// $iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';
						if(SITE_TRANSLATE){				
							$iconbar .=	'<a href="javascript:void(0)" onclick="setTranslate('.$value['category_id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
						}
					}
					if($acc['menus']['delete'] == 1){
						$iconbar .= '<a href="javascript:void(0)" onclick="setDelete('.$value['category_id'].',\''.$value['title'].'\')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';
					}
					if($acc['menus']['edit'] == 1){
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['category_id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setMove('.$value['category_id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';
					$order = '<input name="sequence_'.$value['category_id'].'" id="sequence_'.$value['category_id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['category_id'].')" />';
					}else{
						$order = $value['sequence'];
					}
					$row_chk = '<input name="table_select_'.$value['category_id'].'" id="table_select_'.$value['category_id'].'" class="table_checkbox" type="checkbox" value="'.$value['category_id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					if($acc['menus']['edit'] == 1){
						$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['category_id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['title'].'</a>';
					}else{
						$showname = $value['title'];
					}
					$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['mdate'],3=>$iconbar,4=>$value['category_id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['category_id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'duplicate':
			$user = $oUsers->getAdminLoginUser();
			$id = $_GET['id'];
			$oModule->duplicateData($id,$user['id']);
		break;
		case 'saveData':
			$user = $oUsers->getAdminLoginUser();
			$id = $_POST['id'];
			$category_id = $_POST['categories'];
			$name = htmlspecialchars($_POST['name'],ENT_QUOTES);
			if(empty($_POST['slug'])){
				$slug = $oModule->createSlug($_POST['name']);
			}else{
				$slug = $_POST['slug'];
			}
			$description = htmlspecialchars($_POST['description'],ENT_QUOTES);
			$content= htmlspecialchars($_POST['content'],ENT_QUOTES);
			$meta_key= htmlspecialchars($_POST['meta_key'],ENT_QUOTES);
			$meta_description= htmlspecialchars($_POST['meta_description'],ENT_QUOTES);
			$image =  (!empty($_POST['image']))?$_POST['image']:'';
			$start = (!empty($_POST['start']))?$oModule->datePickerToTime($_POST['start']):'';
			$end = (!empty($_POST['end']))?$oModule->datePickerToTime($_POST['end']):'';
			$status= $_POST['status'];
			$slug = urldecode($slug);
			if(isset($_POST['params'])) {
				$params = htmlspecialchars($_POST['params'],ENT_QUOTES);
			} else {
				$params = '';
			}
			if(empty($id)){		
				$oModule->insertData($category_id,$name,$description,$slug,$content,$image,$params,$meta_key,$meta_description,$user['id'],$status,$start,$end);
			}else{
				$oModule->updateData($id,$category_id,$name,$description,$slug,$content,$image,$params,$meta_key,$meta_description,$user['id'],$status,$start,$end);
			}
		break;
		case 'setStatus':
			$id = addslashes($_GET['id']);
			$status = addslashes($_GET['status']);
			$oCategories->updateStatus($id,$status);
		break;
		case 'setMove':
			$id = addslashes($_GET['id']);
			$type = addslashes($_GET['type']);
			$oModule->moveRow($id,$type);
		break;
		case 'setDelete':
			$id = addslashes($_GET['id']);
			$oModule->deleteMenuGroupData($id);
			echo 0;
		break;
		case 'loadLanguages':
			$lang = $oModule->getLanguage();
			echo json_encode($lang);
		break;
		// translate page
		case 'formTranslateInit':
			$id = addslashes($_GET['id']);
			$lang = addslashes($_GET['language']);
			$data = $oModule->getTranslate($id,$lang);
			echo json_encode($data);
		break;
		case 'saveTranslate':
			$id = $_POST['id'];
			$lang = $_POST['translate_language'];
			$name = $_POST['name'];
			$description = $_POST['description'];
			$content = $_POST['content'];
			$image = $_POST['image'];
			$meta_key = $_POST['meta_key'];
			$meta_description = $_POST['meta_description'];
			$oModule->saveTranslate($lang,$id,$name,$description,$content,$image,'',$meta_key,$meta_description);
		break;
		// for find module	
		case 'getCategoriesDataInFinds':
			$columns = array('','name','level','mdate','lft');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart= $_GET['iDisplayStart'];
			$limit  = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0= $_GET['iSortCol_0'];
			$sSortDir_0= $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by ".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch= $_GET['sSearch']; 
			if(!empty($sSearch)){
				$search =  " and (name like '%$sSearch%' or description like '%$sSearch%') ";
			}
			$categories = $oCategories->getCategoriesAll($search,$orderby,$limit);
			$iTotal = $oCategories->getCategoriesSize();
			$iFilteredTotal = count($categories);
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
		case 'getCategoriesDataInChangeCategory':
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
					 
					$row_chk = '<input name="table_select_group" id="table_select_'.$value['id'].'" class="table_checkbox" type="radio" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					$output["aaData"][] = array(0=>$row_chk,1=>$indent.$showname,2=>$value['level'],3=>$iconbar,4=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++ ;
					}
				}
				}
				echo json_encode($output) ;
			break;
			case 'getDataInFinds':

				$columns = array('category_id','title','mdate','category_id','category_id');
					$limit = '';
					$orderby = '';
					$search = '';
					$iDisplayLength = $_GET['iDisplayLength'];
					$iDisplayStart= $_GET['iDisplayStart'];
					$limit  = ' limit '.$iDisplayStart.','.$iDisplayLength;
					$iSortCol_0= $_GET['iSortCol_0'];
					$sSortDir_0= $_GET['sSortDir_0'];
					if(!empty($columns[$iSortCol_0])){
						$orderby = " order by $oCategories->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
					}else{
						$orderby = " order by ".$columns[4].' '.$sSortDir_0;
					}
					$sSearch= $_GET['sSearch']; 
					if($sSearch=='undefined'){
						$sSearch = '';
					}
					if(!empty($sSearch)){
						$search =  " WHERE ( $oCategories->table.title like '%$sSearch%') " ;
					}
					// }else{
					// 	$category_id  = $_GET['filterCategoryID']; 
					// 	if($category_id>0){
					// 		$search =  " WHERE $oModule->table.category_id = $category_id ";
					// 	}
					// }
					$data = $oCategories->getCategoryAll($search,$orderby,$limit);
					$iTotal = $oCategories->getCategorySize() ;
					$iFilteredTotal = count($data);
					$output = array(
						"sEcho" => intval($_GET['sEcho']),
						"iTotalRecords" => $iTotal,
						"iTotalDisplayRecords" => $iTotal,
						"aaData" => array()
					);
					$cnt = 1;
					if(!empty($data)){
						foreach($data as $key =>$value){
							if($value['status']==1){	
								$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['category_id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
							}else{
								$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['category_id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
							}
							// $iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';
							if(SITE_TRANSLATE){				
								$iconbar .=	'<a href="javascript:void(0)" onclick="setTranslate('.$value['category_id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
							}
							$iconbar .= '<a href="javascript:void(0)" onclick="setDelete('.$value['category_id'].',\''.$value['title'].'\')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';
							$order = '<a href="javascript:void(0)" onclick="setMove('.$value['category_id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setMove('.$value['category_id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';
							$order = '<input name="sequence_'.$value['category_id'].'" id="sequence_'.$value['category_id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['category_id'].')" />';
							$row_chk = '<input name="table_select_'.$value['category_id'].'" id="table_select_'.$value['category_id'].'" class="table_checkbox" type="checkbox" value="'.$value['category_id'].'" />&nbsp;'.($cnt+$iDisplayStart);
							$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['category_id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['title'].'</a> <input name="showName_'.$value['category_id'].'" id="showName_'.$value['category_id'].'" type="hidden" value="'.$value['title'].'" />'.'<input name="showSlug_'.$value['category_id'].'" id="showSlug_'.$value['category_id'].'" type="hidden" value="'.$value['slug'].'" />';
							$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
							$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['mdate'],3=>$iconbar,4=>$value['category_id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['category_id']);
							$cnt++;
						}
					}
					echo json_encode($output);

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
////////////////  reorder function /////////////
				case 'loadCategoriesFilter':
					$categories = $oCategories->getCategoriesTreeAll();
					$options = '<option value="0">--หมวดหมู่ทั้งหมด--</option>';
					$cnt =1 ;
					if(!empty($categories)){
						foreach($categories as $c){
							$indent = '';
							if($c['level']>0){
								if($c['level']>1){
									$indent =  str_pad($indent,$c['level']-1,'-');
								}
								$options .= '<option value="'.$c['id'].'" >'.$indent.$c['name'].'</option>';
							}
							}
					 }
					echo $options ;
				break;
			case 'reorderData':
						$id = explode('-',$_GET['id']) ;
						$sort = explode('-',$_GET['sort']) ;
						$oModule->reOrderDataDragDrop($id,$sort);
			break ;		
			case 'switchOrder';
				$id=$_GET['id'];
				$sort = $_GET['sort'] ;
				$oModule->switchOrder($id,$sort);
			break ;	
			case 'setReorderAll' :
				$columns = array('id','name','category_id','mdate','sequence','id','id');
				$column = $columns[(int)$_GET['column']] ;
				$direction =strtoupper(addslashes($_GET['direction']));
				$oModule->setReorderAll($column,$direction);
			break; 
			case 'changeCategory':
				$id=$_GET['id'];
				$category_id = $_GET['category_id'] ;
				$oModule->changeCategory($id,$category_id);
			break;	
////////////////task for frontend  ///////////
				case "find" :
					$language =   LANG ;// ($_SESSION['site_language'])?$_SESSION['site_language']:SITE_LANGUAGE;
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
					
					$list = $oModule->findOneMenuGroup($key[0],$language);
					$_DATA[$data_key] = $list;


					// if(is_array($key)&&!empty($key)){
					// 	$keys = $key ;
					// 	foreach($keys as $key){
					// 		$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
					// 	 if($separate){
					// 				$listQueryData =$_DATA[$data_key] ;
					// 				$_DATA[$data_key] = NULL ;
					// 				foreach( $listQueryData as $kk=>$val){
					// 					$_DATA[$data_key][$val['slug']] = $val ;
					// 				}
					// 		}
					// 		if($count){
					// 				if(!empty($key)){
					// 					$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
					// 					$_DATA['TOTALPAGE'][$data_key]  = ceil((int)$_DATA['COUNT'][$data_key]/$length);
					// 					$_DATA['PAGE'][$data_key]  =$page ;
					// 				}else{
					// 					$_DATA['COUNT'][$data_key]= $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
					// 					$_DATA['TOTALPAGE'][$data_key]  = ceil((int)$_DATA['COUNT'][$data_key]/$length);
					// 					$_DATA['PAGE'][$data_key]  =$page ;
					// 				}
					// 		}
					// 	}
					// }else{
					// 	$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
					// 	if($count){
					// 		if(!empty($key)){
					// 			$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
					// 			$_DATA['TOTALPAGE'][$data_key]  = ceil((int)$_DATA['COUNT'][$data_key]/$length);
					// 			$_DATA['PAGE'][$data_key]  =$page ;
					// 		}else{
					// 			$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
					// 			$_DATA['TOTALPAGE'][$data_key]  = ceil((int)$_DATA['COUNT'][$data_key]/$length);
					// 			$_DATA['PAGE'][$data_key]  =$page ;
					// 		}
					// 	}
					// 	if($separate){
					// 				$listQueryData = $_DATA[$data_key] ;
					// 				 $_DATA[$data_key] = NULL ;
					// 				 if(!empty($listQueryData)){
					// 					foreach( $listQueryData as $kk=>$val){
					// 						$_DATA[$data_key][$val['slug']] = $val ;
					// 					}
					// 				}
					// 		}
					// }
				break ;

		case 'setSaveMenuData' :

			$title = $oModule->setString($_POST['menuAddName']);
			$target = $oModule->setInt($_POST['menuAddTarget']);
			$type = $oModule->setInt($_POST['menuAddType']);

			$module_name = 'pages';
			$module_content = $oModule->setString($_POST['menuPagesList']);	


			$link = $oModule->setString($_POST['menuExLink']);
			$status = $oModule->setInt($_POST['menuAddStatus']);

			$menu_id = $oModule->setInt($_POST['menu_id']);
			$category_id = $oModule->setInt($_POST['cate_id']);
			$slug = $oModule->createSlug($_POST['txtmenus']);

			// $type = $oModule->setInt($_POST['txtoptionlink']);
			// $module_category = $oModule->setInt((isset($_POST['txtcategory'])?$_POST['txtcategory']:''));
			// $module_content = $oModule->setInt($_POST['txttopic']);
			// $title = $oModule->setString($_POST['menuAddName']);
			// $link = $oModule->setString($_POST['txtlink']);
			//$slug = $oModule->createSlug($_POST['txtmenus']);
			
			if((int)$_POST['hID']==0) {
			 	$parent_id = $root_node = $oModule->treeCheckRootNodeMenu($category_id);
			} else {
				$parent_id = $oModule->setInt($_POST['hID']);
			}
			//===== set data array
			$data = array(
				"parent_id"=>$parent_id,
				"category_id"=>$category_id,
				"title"=>"'$title'",
				"slug"=>"'$slug'",
				"type"=>$type,
				"link"=>"'$link'",
				"target"=>$target,
				"module_name"=>"'$module_name'",
				"module_type"=>"'$module_type'",
				"module_id"=>(int)$module_id,
				"module_category_id"=>(int)$module_category,
				"module_content_id"=>(int)$module_content,
				"mdate"=>"NOW()",
				"cdate"=>"NOW()",
				"status" => $status
			);
			// ===== set cookie default value setSaveData
			$admin_menus = array(
			);
			// @setcookie("admin_menus",json_encode($admin_menus),time()+(60*60*24*30),"/",$_DOMAIN_NAME);	
			if(empty($menu_id)){
				$oModule->setInsertData($data);
				echo 0;
			}else{
				$data[$oModule->primary_key] = $menu_id;
				//$oModule->setUpdateData($data);
				$oModule->setUpdateAll("menus",$data,"menu_id",$menu_id);
				echo 1; // response for update complete
			}
		break;

		case 'saveCategoryMenu' :
			$response = array();
			$category_id = $oModule->setInt($_POST['hprimaryid']);
			$title = $oModule->setString(($_POST['categories_name']));
			$slug = $oModule->createSlug($_POST['categories_name']);
			$data = array(
				"title"=>"'$title'",
				"slug"=>"'$slug'",
				"mdate"=>"NOW()",
				"cdate"=>"NOW()"
			);
			if(empty($category_id)){
				$id = $oModule->setInsertCategory($data);
				$response['response'] = '0';
				$response['id'] = $id;
			}else{
				$data['category_id'] = $category_id;
				$oModule->setUpdateCategory($data);
				$response['response'] = '1';
				$response['id'] = "";
			}
			echo json_encode($response);
		break;

		case "getAllCategoryNestList":
			function generateList($data,&$list,$parent,$_ACCESS_CONTROL_LAYER) {
				$params = array(
					'module'=>'menus',
					'table'=>'menus',
					'primary_key'=>'menu_id',
					'parent_table'=> 'menus_categories',
					'parent_translate_table'=>null,
					'parent_primary_key'=> 'category_id',
					'translate_table'=>'menus_translate',
					'site_language'=>SITE_LANGUAGE,
					'is_translate'=>SITE_TRANSLATE
				);
				$oModule = new Menus($params);
				if(!empty($data[$parent])){
					$list .= '<ol class="dd-list">';
					$state_showbefore = "";
					$_tutor_5 = '_tutor_5';
				 	$_tutor_6 = '_tutor_6';
				 	$_tutor_7 = '_tutor_7';
				 	$_tutor_8 = '_tutor_8';
				 	$_tutor_9 = '_tutor_9';
				 	$_tutor_10 = '_tutor_10';
				 	$i = 1;
				 	foreach ($data[$parent] as $key => $value){
				 		if($i!=1){
				 			$_tutor_5 = '';
				 			$_tutor_6 = '';
				 			$_tutor_7 = '';
				 			$_tutor_8 = '';
				 			$_tutor_9 = '';
				 			$_tutor_10 = '';
				 		}
						$i++;
				 		if($value['type']==0){
				 			$nameModule = "";
				 			$typeModule = "";
				 			if($value['module_name']=='contacts') {
				 				$nameModule = _t('menu_contacts');
				 				if($value['module_type']=='contacts_detail') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='articles') {
				 				$nameModule = _t('menu_articles');
				 				if($value['module_type']=='articles') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='articles_category') { $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='article') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='clips') {
				 				$nameModule = _t('menu_clips');
				 				if($value['module_type']=='clips') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='clips_category') { $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='clip') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='downloads') {
				 				$nameModule = _t('menu_downloads');
				 				if($value['module_type']=='downloads') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='downloads_category') { $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='download') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='events') {
				 				$nameModule = _t('menu_events');
				 				if($value['module_type']=='events') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='events_category') { $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='event') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='galleries') {
				 				$nameModule = _t('menu_galleries');
				 				if($value['module_type']=='galleries'){ $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='galleries_category'){ $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='gallerie'){ $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='members') {
				 				$nameModule = _t('menu_members');
				 				if($value['module_type']=='members_login') { $typeModule = _t('menu_members_login'); }
				 				if($value['module_type']=='members_logout') { $typeModule = _t('menu_members_logout'); }
				 				if($value['module_type']=='members_register') { $typeModule = _t('menu_members_register'); }
				 			} else if ($value['module_name']=='jobs') {
				 				$nameModule = _t('menu_jobs');
				 				if($value['module_type']=='jobs') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='jobs_category') { $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='job') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='pages') {
				 				$nameModule = 'ระบบเพจ';
				 				$contentModule = $oModule->getOneField("pages","name","id",$value['module_content_id']);
				 				// if($value['module_type']=='pages') { $typeModule = _t('menu_show_all_category'); }
				 				// if($value['module_type']=='pages_category') { $typeModule = _t('menu_show_detail_all_category'); }
				 				// if($value['module_type']=='page') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='products') {
				 				$nameModule = _t('menu_products');
				 				if($value['module_type']=='products') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='products_category') { $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='product') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='payments') {
				 				$nameModule = _t('menu_payments');
				 				if($value['module_type']=='payments_confirm') { $typeModule = _t('menu_show_payments_confirm'); }
				 				if($value['module_type']=='payments_detail') { $typeModule = _t('menu_show_payments_detail'); }
				 			} else if ($value['module_name']=='sales') {
				 				$nameModule = _t('menu_sales');
				 				if($value['module_type']=='sales_shoppingcart') { $typeModule = _t('menu_sales_shoppingcart'); }
				 			} else if ($value['module_name']=='shipping') {
				 				$nameModule = _t('menu_shipping');
				 				if($value['module_type']=='shipping_report') { $typeModule = _t('menu_shipping_report'); }
				 			} else if ($value['module_name']=='webboards') {
				 				$nameModule = _t('menu_webboards');
				 				if($value['module_type']=='webboards') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='webboards_category'){ $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='webboards_topic'){ $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='webboards_addtopic') {
				 				$nameModule = _t('menu_webboards');
				 				if($value['module_type']=='webboards_addtopic') { $typeModule = _t('menu_show_webboards_addtopic'); }
				 			} else if ($value['module_name']=='bookinghotel') {
				 				$nameModule = _t('menu_bookinghotel');
				 				if($value['module_type']=='bookinghotelproperty') { $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='bookinghotelrooms') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='bookinghotelroom') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='catalogs') {
				 				$nameModule = _t('menu_catalogs');
				 				if($value['module_type']=='catalogs') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='catalogs_category') { $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='catalog_orders') { $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='bookinghotel_property') {
				 				$nameModule = _t('menu_bookinghotel');
				 				if($value['module_type']=='bookinghotelproperty') { $typeModule = _t('menu_show_content_category'); }
				 			} else if ($value['module_name']=='bookings') {
				 				$nameModule = _t('menu_bookings');
				 				if($value['module_type']=='bookings_items') { $typeModule = _t('menu_show_all_category'); }
				 				if($value['module_type']=='bookings_category') { $typeModule = _t('menu_show_detail_all_category'); }
				 				if($value['module_type']=='bookings_item'){ $typeModule = _t('menu_show_detail_category'); }
				 			} else if ($value['module_name']=='home') {
				 				$nameModule = _t('menu_home');
				 				if($value['module_type']=='home') { $typeModule = _t('menu_show_home'); }
				 			}
				 			// $state_showbefore = '[ '.$nameModule.' - '.$typeModule.' ]';
				 			$state_showbefore = '[ '.$nameModule.' - '.$contentModule.' ]';
				 			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		                    //$link_arr = setMenuLink($value);
		                    ///$link_show = $protocol.$_SERVER['HTTP_HOST'].'/'.$link_arr['link'];
				 		}else{
				 			$state_showbefore = '[ '.$value['link'].' ]';
				 			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
				 			//$link_show = $protocol.$value['link'];
				 		}

						if($value['parent_id']==$parent) {

							$list .= '<li class="dd-item" data-id="'.$value['menu_id'].'" data-parent="'.$value['parent_id'].'" >';
							$list .= '<div class="dd-handle">';
							$list .= '<span id="menu_title_'.$value['menu_id'].'">'.htmlspecialchars_decode($value['title']).'</span> '.$state_showbefore;
							$list .= '</div>';
							$list .= '<div class="nestlist-toolbar">';
							if($value['status'] == 1){
								$list .= '<a id="btn_status_on_'.$value['menu_id'].'" href="javascript:void(0);" onclick="setUpdateMenusStatus('.$value['menu_id'].',0,this); return false;" style="color:green" title="เปิดการใช้งาน"><i class="fa fa-check" aria-hidden="true" ></i></a>';
								$list .= '<a id="btn_status_off_'.$value['menu_id'].'" href="javascript:void(0);" onclick="setUpdateMenusStatus('.$value['menu_id'].',1,this); return false;" style="color:red; display:none;" title="ปิดการใช้งาน"><i class="fa fa-times" aria-hidden="true"></i></a>';
							}else{
								$list .= '<a id="btn_status_off_'.$value['menu_id'].'" href="javascript:void(0);" onclick="setUpdateMenusStatus('.$value['menu_id'].',1,this); return false;" style="color:red" title="ปิดการใช้งาน"><i class="fa fa-times" aria-hidden="true"></i></a>';
								$list .= '<a id="btn_status_on_'.$value['menu_id'].'" href="javascript:void(0);" onclick="setUpdateMenusStatus('.$value['menu_id'].',0,this); return false;" style="color:green; display:none;" title="เปิดการใช้งาน"><i class="fa fa-check" aria-hidden="true" ></i></a>';
							}
							$list .= '&nbsp;&nbsp;<a href="javascript:void(0)" onclick="setEditMenu('.$value['menu_id'].')" style="color:orange;" title="แก้ไข"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
							$list .= '&nbsp;&nbsp;<a href="javascript:void(0)" onclick="setDuplicateMenu('.$value['menu_id'].',this)" style="color:blue;" title="คัดลอก"><i class="fa fa-files-o" aria-hidden="true"></i></a>';
							$list .= '&nbsp;&nbsp;<a href="javascript:void(0)" onclick="setTranslateMenu('.$value['menu_id'].',this)" style="color:purple;" title="แปลภาษา"><i class="fa fa-flag" aria-hidden="true"></i></a>';
							$list .= '&nbsp;&nbsp;<a href="javascript:void(0)" onclick="setDeleteMenu('.$value['menu_id'].',this)" style="color:red;" title="ลบ"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
							$list .= '</div>';
							generateList($data,$list,$value['menu_id'],$_ACCESS_CONTROL_LAYER);
							$list .= '</li>' ;
						}
					}
					$list .= '</ol>' ;
				} else {
					// foreach ($data as $key => $value) {
					// 	if($value['parent_id']==$parent) {
					// 		$list .= '<li class="dd-item" data-id="'.$value['menu_id'].'" data-parent="'.$value['parent_id'].'"><div class="dd-handle">'.$value['menu_id'].' : '.$value['title'].'</div><div style="float:right;margin-top:-34px;"><div class="btn btn-sm btn-danger"><i class="icon-trash"></i></div></div></li>' ;
					// 	}
					// }
				}
				return $list;
			}
			$category_id = $oModule->setInt($_GET['category_id']);
			$root_node = $oModule->treeCheckRootNodeMenu($category_id);
			$data  = $oModule->getAllCategoriesNestList($category_id);
			

			$tree = array();
			foreach($data as $k => $v){
				$tree[$v['parent_id']][$v['menu_id']]  = $v;
			}
			$list = '';
			generateList($tree,$list,$root_node,$_ACCESS_CONTROL_LAYER);
			echo $list;
			//generateList($tree,$list,$root_node,$_ACCESS_CONTROL_LAYER);
		break;

		case 'setUpdateMenuSort':
			$tree = urldecode($_POST['tree']); // get json tree list
			$category_id = $oModule->setInt($_GET['category_id']);
			$arr_tree = json_decode($tree,1);
		    $root_node = $oModule->treeCheckRootNodeMenu($category_id);
			$oModule->treeResetRootNodeMenu($root_node,$category_id);

			foreach($arr_tree as $k_level1 => $v_level1){
				// echo $root_node.' '.$v_level1['id'];
				// exit;
				$oModule->treeUpdateNodeMenus($root_node, $v_level1['id'],1,$category_id);
				
				if(!empty($v_level1['children'])){
					foreach($v_level1['children'] as $k_level2 => $v_level2){
						$oModule->treeUpdateNodeMenus($v_level1['id'], $v_level2['id'],2,$category_id);
						
						if(!empty($v_level2['children'])){
							foreach($v_level2['children'] as $k_level3 => $v_level3){
								//level 3
								$oModule->treeUpdateNodeMenus($v_level2['id'], $v_level3['id'],3,$category_id);
								if(!empty($v_level3['children'])){
									foreach($v_level3['children'] as $k_level4 => $v_level4){
										//level 4 
										$oModule->treeUpdateNodeMenus($v_level3['id'], $v_level4['id'],4,$category_id);
									}
								}
							}
						}
					}
				}
			}
			echo 0;
		break;
		case 'setUpdateMenusStatus':
			$menu_id = $oModule->setInt($_GET['id']);
			$status =  $oModule->setInt($_GET['status']);
			$oModule->setUpdateOne("menus","status",$status,"menu_id",$menu_id);
			echo 0;
		break;
		case 'getAllPagesList' :
			$pages_id = $oModule->setInt($_GET['pages_id']);
			$table = 'pages';
			$where = ' where status = 1 ';
			$order = ' order by sequence asc ';
			$list = $oModule->getAllField($table,$where,$order,$limit);
			$data = '<option value="">กรุณาเลือกเว็บเพจ</option>';
			if(!empty($list)){
				foreach($list as $key => $v){
					$select = !empty($pages_id) && $v['id'] == $pages_id ? 'selected="selected"' : '';
					$data .= '<option value="'.$v['id'].'" '.$select.'>'.$v['name'].'</option>';
				}
			}
			echo $data;
		break;

		case 'initMenu' :
			$id = addslashes($_GET['id']);
			$data = $oModule->getOne($id);
			$data['title']=$oModule->getString($data['title']);
			$data['link']=$oModule->getString($data['link']);
			echo json_encode($data);
		break;

		case 'setDeleteMenus' :
			$id = $oModule->setInt($_GET['id']);
			$chk_submenu = $oModule->checkHaveChildren($id);
			if(!empty($chk_submenu)){
				echo 1 ;
			}else{
				$oModule->setDeleteMenus($id);
				echo 0;
			}
		break;

		case 'setDuplicateMenu':
			$category_id = $oModule->setInt($_GET['category_id']);
			$total = $oModule->getAllDataSizeMenu($category_id);
			$menu_id = $oModule->setInt($_GET['id']);
			$oModule->setDuplicateMenu($menu_id);
			echo  0;
		break;

		case 'getlanguage':
			$default_lang = DEFAULT_LOCALE;
			$table = 'languages';
			$where = ' where status = 1 and code != "'.$default_lang.'"';
			$order = ' order by language asc ';
			$list = $oModule->getAllField($table,$where,$order,$limit);
			$data = '<option value="">กรุณาเลือกภาษา</option>';
			if(!empty($list)){
				foreach($list as $key => $v){
					$data .= '<option value="'.$v['code'].'" '.$select.'>'.$v['language'].'</option>';
				}
			}
			$menu_id = addslashes($_GET['id']);
			$menu_data = $oModule->getOne($menu_id);
			echo json_encode(array('result'=>$data,'main_title'=>$oModule->getString($menu_data['title'])));
		break;
		case 'setSaveTranslateMenuData':
			$primary_id = $oModule->setInt($_POST['translate_menu_id']);
			$lang =  $oModule->setString($_POST['menuTranslateLang']);
			$title = $oModule->setString($_POST['menuTranslateTitle']);
			$slug = $oModule->createSlug($_POST['menuTranslateTitle']);
			//GET DATA :
			$data = array(
				"menu_id"=>$primary_id,
				"lang"=>"'$lang'",
				"title"=>"'$title'",
				"slug"=>"'$slug'"
			);
			$oModule->setSaveTranslate($data);
		break;
		case 'setChangeLangTranslate' :
			$menu_id = $oModule->setInt($_GET['id']);
			$lang =  $oModule->setString($_GET['lang']);
			$titleTranslate = $oModule->getOneFieldTwoWhere("menus_translate","title","menu_id",$menu_id,"lang",$lang);
			echo json_encode(array('translate_title'=>$oModule->getString($titleTranslate)));
			
		break;
	}// switch
}// if isset
?>