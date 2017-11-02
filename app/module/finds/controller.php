<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
// config module
$params_category = array(
 	'table'=>'finds_categories',
	'translate_table'=>'finds_categories_translate',
	'is_translate'=>SITE_TRANSLATE
);
$oCategories = new Finds('finds_categories',$params_category);
$params = array(
 	'table'=>'finds',
	'parent_table'=>'finds_categories',
	'parent_primary_key'=>'id',
	'translate_table'=>'finds_translate',
	'is_translate'=>SITE_TRANSLATE 
);
$oModule = new Finds('finds',$params);
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		//  categories task   
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
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>';
						}else{
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png"  title="ปิด" /></a>';
						}
						$iconbar .=	'<a href="javascript:void(0)" onclick="setCategoryDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';
						if(SITE_TRANSLATE){
							$iconbar .='<a href="javascript:void(0)" onclick="setCategoryTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title=แปลภาษา /></a>';
						}
			            $iconbar .=	'<a href="javascript:void(0)" onclick="setCategoryDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';
						if($value['status_index']){	
							$iconbar .= '<a href="javascript:void(0)" onclick="setCategoryStatusIndex('.$value['id'].',0)" ><img src="../images/icons/color/house.png" title="หน้าแรม" /></a>';
						}else{
							$iconbar.= '<a href="javascript:void(0)" onclick="setCategoryStatusIndex('.$value['id'].',1)" ><img src="../images/icons/color/blog.png" title="หน้าปกติ" /></a>';
						}
						$order = '<a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'left\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'right\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';
						$indent = '';
						for($i=1;$i<$value['level'];$i++){
							$indent .= '-';
						}
						$showname = '<a href="javascript:void(0)" onclick="setCategoryEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';			 
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['layout'],3=>$value['level'],4=>$order,5=>$iconbar,6=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
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
					if(!isset($c['parent'])) {
						$c['parent'] = 0;
					}
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
				$data['name'] = $oCategories->getString($data['name']);
				$data['layout'] = $oCategories->getString($data['layout']);
				echo json_encode($data);
			}
		break;
		case 'saveCategory':
			$user = $oUsers->getAdminLoginUser();
			$categories_id = $oCategories->setInt($_POST["categories_id"]);
			$categories_parent = $oCategories->setInt($_POST["categories_parent"]);
			$categories_name = $oCategories->setString($_POST["categories_name"]);
			if(empty($_POST['categories_slug'])){
				$categories_slug = $oCategories->createSlug($categories_name);
			}else{
				$categories_slug = $oCategories->setString($_POST['categories_slug']);
			}
			$categories_layout = $oCategories->setString($_POST["categories_layout"]);
			$categories_status = $oCategories->setInt($_POST["categories_status"]);
			$categories_slug = urldecode($categories_slug);
			$query = $_POST["findQuerySelectedID"];
			if(!empty($categories_id)){
				$oCategories->update_categories($categories_id,$categories_parent,$categories_name,$categories_slug,$categories_layout,$categories_status,$query);
			}else{
				$oCategories->insert_categories($categories_parent,$categories_name,$categories_slug,$categories_layout,$categories_status,$query);
			}
		break;
		case 'duplicateCategory':
			$user = $oUsers->getAdminLoginUser();
			$id = $oCategories->setInt($_GET["id"]); 
			$oCategories->duplicate_categories($id,$user['id']);
		break;
		case 'setCategoryStatus':
			$id = $oCategories->setInt($_GET['id']);
			$status = $oCategories->setInt($_GET['status']);
			$oCategories->update_category_status($id,$status);
		break;
		case 'setCategoryStatusIndex':
			$id = $oCategories->setInt($_GET['id']);
			$status = $oCategories->setInt($_GET['status']);
			$oCategories->update_category_status_index($id,$status);
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
		// translate
		case 'categoryFormTranslateInit':
			if($_GET['mode']=='translate'){
				$id = $oCategories->setInt($_GET['id']);
				$lang = $oCategories->setString($_GET["language"]);
				$data = $oCategories->getTranslateCategory($id,$lang);
				echo json_encode($data);
			}
		break;
		case 'saveCategoryTranslate':
			$categories_id = $oCategories->setInt($_POST["categories_id"]);
			$categories_lang = $oCategories->setString($_POST["categories_language"]); 
			$categories_name = $oCategories->setString($_POST["categories_name"]);
			$categories_description = $oCategories->setString($_POST["categories_layout"]);
			$oCategories->saveCategoriesTranslate($categories_lang,$categories_id,$categories_name,$categories_description,$categories_images,'');
		break;
		case 'formInit':
			if($_GET['mode']=='edit'){
				$id = $oModule->setInt($_GET['id']);
				$data = $oModule->getOne($id);
				$data['find_key'] = json_decode($data['find_key']);
				echo json_encode($data);
			}
		break;
		case 'getData':
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
				foreach($data as $key => $value){
					if($value['status']==1){	
						$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>';
					}else{
						$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>';
					}
					$iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';
					if(SITE_TRANSLATE){			
						$iconbar .=	'<a href="javascript:void(0)" onclick="setTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
					}
					$iconbar .=	'<a href="javascript:void(0)" onclick="setDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['find_name'].'</a>';
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['find_module'],3=>$value['find_task'],4=>$value['find_type'],5=>$value['find_key'],6=>$iconbar,7=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'saveData':
			$user = $oUsers->getAdminLoginUser();
			$id = $oModule->setInt($_POST['id']);
			$moduleList = $oModule->setString($_POST['moduleList_id']);
			$name = $_POST['name'];
			$findTaskType = $_POST['findTaskType'];
			$task = $_POST['task'];
			$findTask = $_POST['findTask'];
			$findType = $_POST['findType'];
			$findParameter = $_POST['findParameter'];
			$findOneSelectedID = $_POST['findOneSelectedID'];
			$findCategorySelectedID = $_POST['findCategorySelectedID'];
			$isSlug = $_POST['isSlug'];
			$findStatus = $_POST['findStatus'];
			$searchParameter = $_POST['searchParameter'];
			$search_key = $_POST['search_key'];
			$filter_key = $_POST['filter_key'];
			$find_order = $_POST['find_order'];
			$pageParameter = $_POST['pageParameter'];
			$paginateLength = $_POST['paginateLength'];
			$findSeparate = $_POST['findSeparate'];
			$findCount = $_POST['findCount'];
			$status = $_POST['status'];
			// list var for database
			$data = array(
				'module'=>'', // string module
				'task'=>'', // controller task
				'type'=>'',  //one, all,  category_all , category_one, in_category
				'key'=>array(), // id or slug string
				'slug'=>1, // is slug true,  false
				'status'=>1, // field status value
				'search'=>'', // search key
				'filter'=>'', // filter key
				'order'=>'', // order field
				'paginate'=>0, // use pagenate true false
				'page'=>1,// use page int
				'length'=>0,// use length int
				'count'=>0, // trur false
				'separate'=>0 // is separate data by cateogry
			);
			$data['module'] = $moduleList;
			switch($findTaskType){ // case task find or cusmton
				case "find":
					$data['task'] = 'find';
					switch($findType){
						case "one":
							$data['type'] = 'one';
							$key = array();
							if($findParameter=='custom'){
								$key = array();
								if(!empty($findOneSelectedID)){
									foreach($findOneSelectedID as $fcs_id){
										$key[] = $fcs_id;
									}
								}
								$data['key'] = $key;
							}else{
								$data['key'] = array($findParameter);
							}
							$data['slug'] = 0;
						break;
						case "in_category":
							$data['type'] = 'in_category';
							if($findParameter=='custom'){
								$key=array();
								if(!empty($findCategorySelectedID)){
									foreach($findCategorySelectedID as $fcs_id){
										$key[] = $fcs_id;
									}
								}
								$data['key'] = $key;
								$data['slug'] = 0;
							}else{
								$data['key'] = array($findParameter);
								$data['slug'] = (int)$isSlug;
							}
							$data['status'] = $findStatus;
							switch($searchParameter){
								case "0":
									$data['search'] = NULL;
								break;
								case "custom":
									$data['search'] = $search_key;
								break;
								default:
									$data['search'] = $searchParameter;
								break;
							}
							$data['filter'] = $filter_key;
							$data['order'] = $find_order;
							if($pageParameter=="0"){
								$data['paginate'] = 0;
								$data['page'] = 1;
								$data['length'] = 1;
							}else{
								$data['paginate'] = 1;
								$data['page'] = $pageParameter;
								$data['length'] = $paginateLength;
							}
							$data['count'] = $findCount;
							$data['separate'] = $findSeparate;
						break;
						case "all":
							$data['type'] = 'all';
							$data['key'] = 0;
							$data['slug'] = (int)$isSlug;
							$data['status'] = $findStatus;
							switch($searchParameter){
								case "0":
									$data['search'] = NULL;
								break;
								case "custom":
									$data['search'] = $search_key;
								break;
								default:
									$data['search'] = $searchParameter;
								break;
							}
							$data['filter'] = $filter_key;
							$data['order'] = $find_order;
							if($pageParameter=="0"){
								$data['paginate'] = 0;
								$data['page'] = 1;
								$data['length'] = 1;
							}else{
								$data['paginate'] = 1;
								$data['page'] = $pageParameter;
								$data['length'] = $paginateLength;
							}
							$data['count'] = $findCount;
							$data['separate'] = $findSeparate;
						break;
						case "category_one":
							$data['type'] = 'category_one';
							if($findParameter=='custom'){
								$key=array();
								if(!empty($findCategorySelectedID)){
									foreach($findCategorySelectedID as $fcs_id){
										$key[] = $fcs_id;
									}
								}
								$data['key'] = $key;
							}else{
								$data['key'] = $searchParameter;
							}
							$data['filter'] = $filter_key;
						break;
						case "category_all":
							$data['type'] = 'category_all';
							$data['filter'] = $filter_key;
						break;
					}
				break;
				case "custom":
					$data['task'] = $task;
				break;
			}
			if(empty($id)){
				$oModule->insertData($data['module'],$name,$data['task'],$data['type'],json_encode($data['key']),$data['slug'],$data['status'],$data['search'],$data['filter'],$data['order'],$data['paginate'],$data['page'],$data['length'],$data['count'],$data['separate'],$status);
			}else{
				$oModule->updateData($id,$data['module'],$name,$data['task'],$data['type'],json_encode($data['key']),$data['slug'],$data['status'],$data['search'],$data['filter'],$data['order'],$data['paginate'],$data['page'],$data['length'],$data['count'],$data['separate'],$status);
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
		case 'loadLanguages':
			$lang = $oModule->getLanguage();
			echo json_encode($lang);
		break;
		// translate page
		case 'formTranslateInit':
			$id = $oModule->setInt($_GET['id']);
			$lang = $oModule->setString($_GET['language']);
			$data = $oModule->getTranslate($id,$lang);
			echo json_encode($data);
		break;
		case 'saveTranslate':
			$id = $oModule->setInt($_POST['id']);
			$lang = $oModule->setString($_POST['translate_language']);
			$name = $oModule->setString($_POST['name']);
			$content = $oModule->setString($_POST['content']);
			$meta_key = $oModule->setString($_POST['meta_key']);
			$meta_description = $oModule->setString($_POST['meta_description']);
			$oModule->saveTranslate($lang,$id,$name,$content,'',$meta_key,$meta_description);
		break;
		case 'getModuleList':
			$modules = $oModule->getModulesList();
			echo json_encode($modules);
		break;
		// for find module				
		case 'getFindQueryInRoute':
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
				$search = " WHERE ( $oModule->table.name like '%$sSearch%' or $oModule->table.slug like '%$sSearch%') ";
			}
			$data = $oModule->getAll($search,$orderby,$limit);
			$iTotal = $oModule->getSize();
			$iFilteredTotal = count($data);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=> $iTotal,
				"iTotalDisplayRecords"=>$iTotal, // $iFilteredTotal,
				"aaData"=>array()
			);
			$cnt = 1;
			if(!empty($data)){
				foreach($data as $key =>$value){
					if($value['status']==1){
						$iconbar = '<img src="../images/icons/color/target.png" title="เปิด" /> ';
					}else{
						$iconbar = '<img src="../images/icons/color/stop.png" title="ปิด" />';
					}  
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					$showname = '<input name="showName_'.$value['id'].'" id="showName_'.$value['id'].'" type="hidden" value="'.$value['find_name'].'" /><input name="showModule_'.$value['id'].'" id="showModule_'.$value['id'].'" type="hidden" value="'.$value['find_module'].'" />'.$value['find_name'];
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['find_module'],3=>$value['find_task'],4=>$value['find_type'],5=>$value['find_key'],6=>$value['id'].':'. $iconbar,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case "generateQueryFile":
			// recusive creat tree
			function createTree(&$list, $parent){
				$tree = array();
				foreach ($parent as $k=>$l){
					if(isset($list[$l['id']])){
						$l['children'] = createTree($list,$list[$l['id']]);
					}
					$tree[] = $l;
				} 
				return $tree;
			}
			function createSwtich($list,$switch,$oModule){
				foreach ($list as $k=>$l){
					if(!empty($l['children'])&&is_array($l['children'])){
						$exp_route_slug = explode(',',$l['slug']);
						if(count($exp_route_slug)>1){
							foreach($exp_route_slug as $ers){
								$switch .= "\n".'case "'.$ers.'" : ';
							}
						}else{
							$switch .= "\n".'case "'.$exp_route_slug[0].'" : ';
						}
						$param = '$_PARAM['.($l['clevel']).']';
						$switch .= "\n".'switch (urldecode('.$param.')){  ';
						$switch .= createSwtich($l['children'], '',$oModule);
						$switch .= "\n".'default :';
						$switch .= "\n".' '.$oModule->createRequestRoute($l['id']).' ';
						$switch .= "\n".' '.$oModule->createLoopQuery($l['layout']).' ';
						$switch .="\n".'break; ';
						$switch .= "\n".'}';
						$switch .= "\n".' break; ';
					}else{
						$exp_route_slug = explode(',',$l['slug']);
						if(count($exp_route_slug)>1){
							foreach($exp_route_slug as $ers){
								$switch .= "\n".'case "'.$ers.'" : ';
							}
						}else{
							$switch .= "\n".'case "'.$exp_route_slug[0].'" : ';
						}
						$switch .= "\n".' '.$oModule->createRequestRoute($l['id']).' ';
						$switch .= "\n".' '.$oModule->createLoopQuery($l['layout']).' ';
						$switch .= "\n".' break;';
					}
				} 
				return $switch;
			}
			// recusive
			$route = $oCategories->getCategoriesTreeAll();	
			$new = array();
			foreach ($route as $rt){
				$new[$rt['parent_id']][] = $rt;
			}
			$tree = createTree($new, array($route[0]));
			$switch = "<?PHP".
			// load theme split rule
			$switch .= "\n".'$Q = (isset($_GET["q"]))?$_GET["q"]:""; ';
			$switch .= "\n".'$split_url = explode("/",$Q);';
			$switch .= "\n".'switch(count($split_url)){' ;
			$switch .= "\n".'	case 2:';
			$switch .= "\n".'		$_SESSION["site_language"] = $split_url[0]; ';
			$switch .= "\n".'		$Q =(isset($_GET["q"]))?$split_url[1]:""; ';
			$switch .= "\n".'		define("LANG", $_SESSION["site_language"]);';
			$switch .= "\n".'	break;';
			$switch .= "\n".'	case 3:';
			$switch .= "\n".'		$_SESSION["site_language"] = $split_url[1];';
			$switch .= "\n".'		$_SESSION["_country"] = $split_url[0];';
			$switch .= "\n".'		$Q = (isset($_GET["q"]))?$split_url[2]:""; ';
			$switch .= "\n".'		define("LANG", $_SESSION["site_language"]);';
			$switch .= "\n".'	break;';
			$switch .= "\n".'	default:';
			$switch .= "\n".'		$Q = (isset($_GET["q"]))?$_GET["q"]:"";';
			$switch .= "\n".'		define("LANG","th"); $_SESSION["site_language"] = "th";';
			$switch .= "\n".'	break;';
			$switch .= "\n".'}';
			$switch .= "\n".'$_PARAM = explode("-",$Q);';
			$switch .= "\n".'$_PARAM_PLUS[1] = explode("-",$Q,1);';
			$switch .= "\n".'if(count($_PARAM)>=2){list($bf1,$_PARAM_PLUS[2]) = explode("-",$Q,2);}';
			$switch .= "\n".'if(count($_PARAM)>=3){list($bf1,$bf2,$_PARAM_PLUS[3]) = explode("-",$Q,3);}';
			$switch .= "\n".'if(count($_PARAM)>=4){list($bf1,$bf2,$bf3,$_PARAM_PLUS[4]) = explode("-",$Q,4);}';
			$switch .= "\n".'if(count($_PARAM)>=5){list($bf1,$bf2,$bf3,$bf4,$_PARAM_PLUS[5]) = explode("-",$Q,5);}';
			$switch .= "\n".'if(count($_PARAM)>=6){list($bf1,$bf2,$bf3,$b4,$bf5,$_PARAM_PLUS[6]) = explode("-",$Q,6);}';
			$switch .= "\n".'if(count($_PARAM)>=7){list($bf1,$bf2,$bf3,$b4,$bf5,$bf6,$_PARAM_PLUS[7]) = explode("-",$Q,7);}';
			$switch .= "\n".'if(count($_PARAM)>=8){list($bf1,$bf2,$bf3,$b4,$bf5,$bf6,$bf7,$_PARAM_PLUS[8]) = explode("-",$Q,8);}';
			$switch .= "\n".'if(count($_PARAM)>=9){list($bf1,$bf2,$bf3,$b4,$bf5,$bf6,$bf7,$bf8,$_PARAM_PLUS[9]) = explode("-",$Q,9);}';
			$switch .= "\n".'if(count($_PARAM)>=10){list($bf1,$bf2,$bf3,$b4,$bf5,$bf6,$bf7,$bf8,$bf9,$_PARAM_PLUS[10]) = explode("-",$Q,10);}';
			// run stat log
			$switch .= "\n".'$request_route = array(0=>array("module" => "statistics","task"=>"insertStatsLogs","type"=>NULL,"key"=>NULL, "slug"=>NULL,"status"=>NULL,"search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>true,"slugkey"=>true ));';
			// loadn theme split rule
			$switch .= "\n".'switch(urldecode($_PARAM[0])){ ';
			$switch .= createSwtich($tree[0]['children'],'',$oModule);
			$switch .= "\n".'default: ';
			$switch .= "\n".' '.$oModule->createRequestHomeRoute().' ';
			$switch .= "\n".' '.$oModule->createLoopQuery('index.php').' ';
			$switch .= "\n".'break; ';
			$switch .="\n".'} ';
			$switch .="\n".' ?> ';
			$objFile = fopen('route.php','w');
			fwrite($objFile,$switch);
			fclose($objFile);
			chmod('route.php',0777);
		break;
		////////////////task for frontend  ///////////
		case "find" :
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
			$data_key =  (!empty($_GET['data_key']))?$_GET['data_key']:$module;
			if(is_array($key)&&!empty($key)){
				$keys = $key;
				foreach($keys as $key){
					$key = $oModule->setInt($key);
					$data[$module][$type][$key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$sort,$separate,$pagenate,$page,$length,$oCategories);
				 	if($slugkey){
						$listQueryData = $data[$module][$type][$key];
						$data[$module][$type][$key] = NULL ;
						foreach($listQueryData as $kk=>$val){
							$data[$module][$type][$key][$val['slug']] = $val;
						}
					}
					if($count){
						$data[$module][$type]['count'][$key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
					}
				}
			}else{
				$key = $oModule->setInt($key);
				$data[$module][$type][$key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$sort,$separate,$pagenate,$page,$length,$oCategories);
				if($count){
					$data[$module][$type]['count'][$key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
				}
				if($slugkey){
					$listQueryData = $data[$module][$type][$key];
					$data[$module][$type][$key] = NULL;
					foreach( $listQueryData as $kk=>$val){
						$data[$module][$type][$key][$val['slug']] = $val;
					}
				}
			}
		break;
	}// switch
}// if isset
?>