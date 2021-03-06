<?php
session_start();
$oUsers = new Users('users');
// config module
$params_category = array(
		'module'=>'hotelrates_categories',
 		'table'=>'hotelrates_categories',
		'table_translate'=>'hotelrates_categories_translate',
		'site_language'=>SITE_LANGUAGE ,
		'is_translate'=>SITE_TRANSLATE 
);
$oCategories = new Modules($params_category);
$params = array(
		'module'=>'hotelrates',
 		'table'=>'hotelrates',
		'parent_table'=> 'hotelrates_categories',
		'parent_table_translate'=> 'hotelrates_categories_translate',
		'parent_primary_key'=> 'id',
		'table_translate'=>'modules_translate',
		'site_language'=>SITE_LANGUAGE ,
		'is_translate'=>SITE_TRANSLATE 
);
$oModule = new Modules($params );

if(isset($_GET['task'])){
	$task =$_GET['task'] ;
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
				$iconbar = '	<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
			}else{
				$iconbar = '	<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png"  title="ปิด" /></a>&nbsp;';
			}
									
			$iconbar .=	'   <a href="javascript:void(0)" onclick="setCategoryEdit('.$value['id'].')"><img src="../images/icons/color/pencil.png" title="แก้ไข" /></a>';
			if(SITE_TRANSLATE){
				$iconbar .=  '  <a href="javascript:void(0)" onclick="setCategoryTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title=แปลภาษา /></a>';
			}
			
            $iconbar .=  '  <a href="javascript:void(0)" onclick="setCategoryDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';
									
			$order = '<a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'left\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a>
							     <a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'right\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>
							  ';
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
					}
				break;
	
			case 'saveCategory':
					$user = $oUsers->getAdminLoginUser();
					 $categories_id = $_POST["categories_id"] ; 
					 $categories_parent = $_POST["categories_parent"];
					 $categories_name = $_POST["categories_name"];
					 $categories_description = $_POST["categories_description"];
					 $categories_images = $_POST["categories_server_images"] ;
					 $categories_status = $_POST["categories_status"] ;
					 if(!empty($categories_id)){
						 $oCategories->update_categories($categories_id,$categories_parent,$categories_name,$categories_description,$categories_images,'',$user['id'],$categories_status);
					 }else{
						 $oCategories->insert_categories($categories_parent,$categories_name,$categories_description,$categories_images,'',$user['id'],$categories_status);
					 }
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
					 $categories_id = $_POST["categories_id"] ; 
					 $categories_lang = $_POST["categories_language"] ; 
					 $categories_name = $_POST["categories_name"];
					 $categories_description = $_POST["categories_description"];
					 $categories_images = $_POST["categories_server_images"] ;
					 $oCategories->saveCategoriesTranslate( $categories_lang,$categories_id,$categories_name,$categories_description,$categories_images,'');
				break;

///  pages-form.html
			case 'formInit':
				if($_GET['mode']=='edit'){
						$id = addslashes($_GET['id']);
						$data = $oModule->getOne($id);
						echo json_encode($data);
					}
				break ;
			case 'getData':
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
						$iconbar = '	<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
					}else{
						$iconbar = '	<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
					}
											
					$iconbar .=	'  <a href="javascript:void(0)" onclick="setEdit('.$value['id'].')"><img src="../images/icons/color/pencil.png" title="แก้ไข" /></a>';
					if(SITE_TRANSLATE){				
							$iconbar .=	'  <a href="javascript:void(0)" onclick="setTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
					}
					$iconbar .='  <a href="javascript:void(0)" onclick="setDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>	';
											
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a>
										 <a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>
									  ';
									  
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						
						// if empty category
						$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
						$output["aaData"][] = array(0=>$row_chk,1=>$indent.$value['name'],2=>$value['category'],3=>$value['mdate'],4=>$order,5=>$iconbar ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++ ;
						}
					}
						echo json_encode($output) ;
				break;
			case 'saveData':
				$user = $oUsers->getAdminLoginUser();
				$id = $_POST['id'];
				$category_id = $_POST['categories'];
				$name = $_POST['name'];	
				if(empty($_POST['slug'])){
					$slug= $oModule->createSlug($name) ;
				}else{
					$slug=$_POST['slug'] ;
				}
				$rating = $_POST['rating'];	
				$number_of_room = $_POST['number_of_room'];	
				$address = $_POST['address'];	
				$city = $_POST['city'];	
				$postcode = $_POST['postcode'];	
				$tel = $_POST['tel'];	
				$fax = $_POST['fax'];	
				$email = $_POST['email'];	
				$website = $_POST['website'];	
				$nearest_airport = $_POST['nearest_airport'];	
				$distance_to_airport = $_POST['distance_to_airport'];	
				$hotel_location = $_POST['hotel_location'];	
				$best_way_to_airport = $_POST['best_way_to_airport'];	
				$room_detail = array() ;
				
				// room1 
				$room_detail[0] = array(
					'valid_1'=> $_POST['valid_1'],
					'room_1'=> $_POST['room_1'],
					'single_1'=> $_POST['single_1'],
					'twin_1'=> $_POST['twin_1'],
					'extra_1'=> $_POST['extra_1'],
					'rack_1'=> $_POST['rack_1'],
					'allotment_1'=> $_POST['allotment_1'],
				)  ;
				$room_detail[1] = array(
					'valid_2'=> $_POST['valid_2'],
					'room_2'=> $_POST['room_2'],
					'single_2'=> $_POST['single_2'],
					'twin_2'=> $_POST['twin_2'],
					'extra_2'=> $_POST['extra_2'],
					'rack_2'=> $_POST['rack_2'],
					'allotment_2'=> $_POST['allotment_2'],
				)  ;
				$room_detail[2] = array(
					'valid_3'=> $_POST['valid_3'],
					'room_3'=> $_POST['room_3'],
					'single_3'=> $_POST['single_3'],
					'twin_3'=> $_POST['twin_3'],
					'extra_3'=> $_POST['extra_3'],
					'rack_3'=> $_POST['rack_3'],
					'allotment_3'=> $_POST['allotment_3'],
				)  ;
				$room_detail[3] = array(
					'valid_4'=> $_POST['valid_4'],
					'room_4'=> $_POST['room_4'],
					'single_4'=> $_POST['single_4'],
					'twin_4'=> $_POST['twin_4'],
					'extra_4'=> $_POST['extra_4'],
					'rack_4'=> $_POST['rack_4'],
					'allotment_4'=> $_POST['allotment_4'],
				)  ;
				$room_detail[4] = array(
					'valid_5'=> $_POST['valid_5'],
					'room_5'=> $_POST['room_5'],
					'single_5'=> $_POST['single_5'],
					'twin_5'=> $_POST['twin_5'],
					'extra_5'=> $_POST['extra_5'],
					'rack_5'=> $_POST['rack_5'],
					'allotment_5'=> $_POST['allotment_5'],
				)  ;
				$room_detail[5] = array(
					'valid_6'=> $_POST['valid_6'],
					'room_6'=> $_POST['room_6'],
					'single_6'=> $_POST['single_6'],
					'twin_6'=> $_POST['twin_6'],
					'extra_6'=> $_POST['extra_6'],
					'rack_6'=> $_POST['rack_6'],
					'allotment_6'=> $_POST['allotment_6'],
				)  ;
				$room_detail[6] = array(
					'valid_7'=> $_POST['valid_7'],
					'room_7'=> $_POST['room_7'],
					'single_7'=> $_POST['single_7'],
					'twin_7'=> $_POST['twin_7'],
					'extra_7'=> $_POST['extra_7'],
					'rack_7'=> $_POST['rack_7'],
					'allotment_7'=> $_POST['allotment_7'],
				)  ;
			   $room_detail[7] = array(
					'valid_8'=> $_POST['valid_8'],
					'room_8'=> $_POST['room_8'],
					'single_8'=> $_POST['single_8'],
					'twin_8'=> $_POST['twin_8'],
					'extra_8'=> $_POST['extra_8'],
					'rack_8'=> $_POST['rack_8'],
					'allotment_8'=> $_POST['allotment_8'],
				)  ;
				 $room_detail[8] = array(
					'valid_9'=> $_POST['valid_9'],
					'room_9'=> $_POST['room_9'],
					'single_9'=> $_POST['single_9'],
					'twin_9'=> $_POST['twin_9'],
					'extra_9'=> $_POST['extra_9'],
					'rack_9'=> $_POST['rack_9'],
					'allotment_9'=> $_POST['allotment_9'],
				)  ;
				$room_detail[9] = array(
					'valid_10'=> $_POST['valid_10'],
					'room_10'=> $_POST['room_10'],
					'single_10'=> $_POST['single_10'],
					'twin_10'=> $_POST['twin_10'],
					'extra_10'=> $_POST['extra_10'],
					'rack_10'=> $_POST['rack_10'],
					'allotment_10'=> $_POST['allotment_10'],
				)  ;
					
				$room_detail = json_encode($room_detail);
					
				$dinner = $_POST['dinner'] ;
				if($dinner[0]=='no'){
					$dinner = 'no' ;
				}
				if($dinner[0]=='yes'){
					$dinner = 'yse' ;
				}
				if($dinner=='yes'){
						$dinner .=  ':' . $_POST['diner_date'].' - '. $_POST['dinner_date'].' (Nett Rate : '.$_POST['dinner_nett_rate'].' )' ;
				}
				$rate_include = $_POST['rate_include'];	
				$rate_include =  json_encode($rate_include) ;
				$children_policy  = $_POST['children_policy'];	
				$facilities  = $_POST['facilities'];
				$facilities =  json_encode($facilities) ;
				$amenities = $_POST['amenities'];	
				$amenities =  json_encode($amenities) ;
				$contact_information = array();
				
				$contact_information = array(
					'reserve_contact'=>array(
						'contact_name' => $_POST['contact_name'],
						'contact_tel' => $_POST['contact_tel'],
						'contact_fax' => $_POST['contact_fax'],
						'contact_email' => $_POST['contact_email']
					),
					'department_contact'=>array(
						'contact_name' => $_POST['contact_dep_name'],
						'contact_tel' => $_POST['contact_dep_tel'],
						'contact_fax' => $_POST['contact_dep_fax'],
						'contact_email' => $_POST['contact_dep_email']
					)
				);
				
				$contact_information = json_encode($contact_information) ;
				
				if(empty($id)){		
					$oModule->insertData(0,$name,$slug,$rating,$number_of_room,$address,$city,$postcode,$tel,$fax,$email,$website,$nearest_airport,$distance_to_airport,$hotel_location,$best_way_to_airport, $room_detail, $dinner, $rate_include,$children_policy,$facilities,$amenities,$contact_information, 1,1);
				}else{
					$oModule->updateData(0,$name,$slug,$rating,$number_of_room,$address,$city,$postcode,$tel,$fax,$email,$website,$nearest_airport,$distance_to_airport,$hotel_location,$best_way_to_airport, $room_detail, $dinner, $rate_include,$children_policy,$facilities,$amenities,$contact_information, 1,1);
				}
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
				case 'loadLanguages':
					$lang = $oModule->getLanguage();
					echo json_encode($lang);
				break ;
// translate page
				case 'formTranslateInit':
							$id = addslashes($_GET['id']);
							$lang = addslashes($_GET['language']);
							$data = $oModule->getTranslate($id,$lang);
							echo json_encode($data);
					break ;
				case 'saveTranslate':
							$id = $_POST['id'];
							$lang = $_POST['language'];
							$name = $_POST['name'];	
							$content= $_POST['content'];
							$meta_key= $_POST['meta_key'];
							$meta_description= $_POST['meta_description'];
							$oModule->saveTranslate( $lang,$id,$name,$content,'',$meta_key,$meta_description) ;
				break;
				
////////////////task for frontend  ///////////
				case 'front_getAll':
					$search =  " WHERE ( $oModule->table.name like '%$sSearch%' or  $oModule->table.slug like '%$sSearch%') and $oModule->table.status=1  " ;
					$orderby =" ORDER BY $oModule->table.id  asc " ;
					$limit  = ' limit 0,20'  ;
					$data[$oModule->module]['all'] = $oModule->find($search,$orderby,$limit);
				break ;
				case 'front_getInCategory':
					$category_id =  17 ; 
					$search =  " WHERE ( $oModule->table.name like '%$sSearch%' or  $oModule->table.slug like '%$sSearch%') and $oModule->table.status=1  " ;
					$orderby =" $oModule->table.id  asc " ;
					$limit  = ' limit 0,20'  ;
					$categories = $oCategories->get_child_node($category_id,'enable') ;
					$data['pages'][$category_id] =  $oModule->front_getInCategory($categories,$search,$orderby,$limit);
				break ;
				case 'front_getPagesOne':
					$id = addslashes($_GET['id']);
					$data['pages']['id'] =  $oModule->front_getOne($id);
				break ;
				case 'front_getPagesOneSlug':
					$slug = addslashes($_GET['slug']);
					$data['pages'][$slug] =  $oModule->front_getOneSlug($slug);
				break ;
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
////////////////task for frontend  ///////////
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
									}else{
										$_DATA['COUNT'][$data_key]= $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
									}
							}
						}
					}else{
						$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
						if($count){
							if(!empty($key)){
								$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
							}else{
								$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
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