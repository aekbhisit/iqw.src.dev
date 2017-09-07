<?php
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
// config module
$params_category = array(
		'module'=>'catalogs_categories',
 		'table'=>'catalogs_categories',
		'translate_table'=>'catalogs_categories_translate',
		'site_language'=>SITE_LANGUAGE ,
		'is_translate'=>SITE_TRANSLATE ,
);
$oCategories = new Catalogs($params_category);
$params = array(
		'module'=>'catalogs',
 		'table'=>'catalogs',
		'parent_table'=> 'catalogs_categories',
		'parent_table_translate'=> 'catalogs_categories_translate',
		'parent_primary_key'=> 'id',
		'translate_table'=>'catalogs_translate',
		'site_language'=>SITE_LANGUAGE ,
		'is_translate'=>SITE_TRANSLATE 
);
$oModule = new Catalogs($params );
$params = array(
		'module'=>'catalogs',
 		'table'=>'catalogs_attrs',
		'parent_table'=> 'catalogs_categories',
		'parent_table_translate'=> 'catalogs_categories_translate',
		'parent_primary_key'=> 'id',
		'translate_table'=>'catalogs_attrs_translate',
		'site_language'=>SITE_LANGUAGE ,
		'is_translate'=>SITE_TRANSLATE 
);
$oAttr = new Catalogs($params );

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
				$iconbar = '	<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>';
			}else{
				$iconbar = '	<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png"  title="ปิด" /></a>';
			}
									
				$iconbar .=	'  <a href="javascript:void(0)" onclick="setCategoryDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>  ';
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
				$showname = '<a href="javascript:void(0)" onclick="setCategoryEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';		
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
			case 'loadAttrCategories': 
				$categories = $oCategories->getCategoriesTreeAll();
				$data = array(
					0=>array('level'=>0,'id'=>0,'parent'=>0,'name'=>'--- ทั้งหมด ---'),
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
						 $oCategories->update_categories($categories_id,$categories_parent,$categories_name, $categories_slug,$categories_description,$categories_images,'',$user['id'],$categories_status);
					 }else{
						 $oCategories->insert_categories($categories_parent,$categories_name, $categories_slug,$categories_description,$categories_images,'',$user['id'],$categories_status);
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
						$data['name']=htmlspecialchars_decode($data['name'],ENT_QUOTES);
						$data['meta_key']=htmlspecialchars_decode($data['meta_key'],ENT_QUOTES);
						$data['meta_description']=htmlspecialchars_decode($data['meta_description'],ENT_QUOTES); 
						//echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
						echo json_encode($data);
					}
				break ;
			case 'getData':
					$columns = array('id','name','category_id','mdate','sequence','id','id');
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
					if($sSearch=='undefined'){
						$sSearch = '';
					}
					if(!empty($sSearch)){
						$search =  " WHERE ( $oModule->table.name like '%$sSearch%' or  $oModule->table.slug like '%$sSearch%') " ;
						$category_id  = $_GET['filterCategoryID']; 
						if($category_id>0){
							$search .=  " AND $oModule->table.category_id = $category_id ";
						}
					}else{
						$category_id  = $_GET['filterCategoryID']; 
						if($category_id>0){
							$search =  " WHERE  $oModule->table.category_id = $category_id ";
						}
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
						$iconbar =  '';
					if($value['status']==1){	
						$iconbar .= '	<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
					}else{
					//	if($value['status']==0){
							$iconbar .= '	<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
				//		}
					}
											
					$iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>  ';		
					if(SITE_TRANSLATE){				
							$iconbar .=	'  <a href="javascript:void(0)" onclick="setTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
					}
					$iconbar .='  <a href="javascript:void(0)" onclick="setDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>	';
											
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a>
										 <a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>
									  ';
					$order = '<input name="sequence_'.$value['id'].'" id="sequence_'.$value['id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['id'].')" />';
							
									  
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						
						$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
						$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';
						$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['category'],3=>$value['mdate'],4=>$order,5=>$iconbar,6=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++ ;
						}
					}
						echo json_encode($output) ;
				break;
			case 'saveData':
				$user = $oUsers->getAdminLoginUser();
				$id = $_POST['id'];
				$category_id = $_POST['categories'];
				$name = htmlspecialchars($_POST['name'],ENT_QUOTES);	
				if(empty($_POST['slug'])){
					$slug= $oModule->createSlug($name) ;
				}else{
					$slug=$_POST['slug'] ;
				}
				$meta_key= htmlspecialchars($_POST['meta_key'],ENT_QUOTES);
				$meta_description= htmlspecialchars($_POST['meta_description'],ENT_QUOTES);
				$status= $_POST['status'];
				$params ='';
				$slug = urldecode($slug);
				
				if(empty($id)){		
					$oModule-> insertData($category_id,$name,$slug,$params,$meta_key,$meta_description,$user['id'],$status);
					$id = $oModule->insert_id();
					// attrValue 
					$attrValue = $_POST['attrValue'] ;
					
					//print_r($attrValue) ;
					
					if(!empty($attrValue)){
						foreach($attrValue  as $av_key=>$av_val){
							if(is_array($av_val)){
								$av_val = json_encode($av_val);
								$av_val = addslashes($av_val);
							}else{
								$av_val = htmlspecialchars($av_val);
							}
							$oModule->insertAttrValue($id, $av_key,SITE_LANGUAGE,$av_val,0);
						}
					}
					// attrDefaultValue
					$attrDefaultValue = $_POST['attrDefaultValue'] ;
					if(!empty($attrDefaultValue)){
						foreach($attrDefaultValue  as $key=>$advs){
							if(is_array($advs)){
								foreach($advs as $adv_key => $adv_val ){
									$oModule->insertAttrValue($id, $key,SITE_LANGUAGE,$adv_val,1);
								}
							}
						}
					}
					
				}else{
					$oModule->updateData($id,$category_id,$name,$slug,$params,$meta_key,$meta_description,$user['id'],$status);
				   // attrValue 
					
				  $oModule->emptyAttrValue($id,SITE_LANGUAGE);
				  	// exit;
					$attrValue = $_POST['attrValue'] ;
					if(!empty($attrValue)){
						foreach($attrValue  as $av_key=>$av_val){
							if(is_array($av_val)){
								$av_val = json_encode($av_val);
								$av_val = addslashes($av_val);
							}else{
								$av_val = htmlspecialchars($av_val);
							}
							$oModule->updateAttrValue($id, $av_key,SITE_LANGUAGE,$av_val,0);
						}
					}
					// exit;
					$attrDefaultValue = $_POST['attrDefaultValue'] ;
					if(!empty($attrDefaultValue)){
						foreach($attrDefaultValue  as $key=>$advs){
							if(is_array($advs)){
								if(count($attrDefaultValue[$key])>1){
									$oModule->deleteAttrDefaultValue($id,$key,SITE_LANGUAGE) ;
								}
							}
						}
						
						foreach($attrDefaultValue  as $key=>$advs){
							if(is_array($advs)){
							//	$oModule->deleteAttrDefaultValue($id , $key,$SITE_LANGUAGE,$adv_val,$isDefaultVal) ;
								foreach($advs as $adv_key => $adv_val ){
									$oModule->updateAttrValue($id, $key,SITE_LANGUAGE,$adv_val,1,count($attrDefaultValue[$key]));
								}
							}
						}
					}
				}
				break;
			case 'saveTranslateData':

				$user = $oUsers->getAdminLoginUser();

				$lang =  $_POST['translate_language']; 

				$id = $_POST['id'];
				$category_id = $_POST['categories'];
				$name = htmlspecialchars($_POST['name'],ENT_QUOTES);	
				if(empty($_POST['slug'])){
					$slug= $oModule->createSlug($name) ;
				}else{
					$slug=$_POST['slug'] ;
				}
				$meta_key= htmlspecialchars($_POST['meta_key'],ENT_QUOTES);
				$meta_description= htmlspecialchars($_POST['meta_description'],ENT_QUOTES);
				$status= $_POST['status'];
				$params ='';
				$slug = urldecode($slug);
				
				if(empty($id)){		
					$oModule-> insertData($category_id,$name,$slug,$params,$meta_key,$meta_description,$user['id'],$status);
					$id = $oModule->insert_id();
					// attrValue 
					$attrValue = $_POST['attrValue'] ;
					
					//print_r($attrValue) ;
					
					if(!empty($attrValue)){
						foreach($attrValue  as $av_key=>$av_val){
							if(is_array($av_val)){
								$av_val = json_encode($av_val);
								$av_val = addslashes($av_val);
							}else{
								$av_val = htmlspecialchars($av_val);
							}
							$oModule->insertAttrValue($id, $av_key,$lang,$av_val,0);
						}
					}
					// attrDefaultValue
					$attrDefaultValue = $_POST['attrDefaultValue'] ;
					if(!empty($attrDefaultValue)){
						foreach($attrDefaultValue  as $key=>$advs){
							if(is_array($advs)){
								foreach($advs as $adv_key => $adv_val ){
									$oModule->insertAttrValue($id, $key,$lang,$adv_val,1);
								}
							}
						}
					}
					
				}else{
					$oModule->updateTranslateData($id,$lang,$name,$params,$meta_key,$meta_description);
				   // attrValue 
				  $oModule->emptyAttrValue($id,$lang);
					$attrValue = $_POST['attrValue'] ;
					if(!empty($attrValue)){
						foreach($attrValue  as $av_key=>$av_val){
							if(is_array($av_val)){
								$av_val = json_encode($av_val);
								$av_val = addslashes($av_val);
							}else{
								$av_val = htmlspecialchars($av_val);
							}
							$oModule->updateAttrValue($id, $av_key,$lang,$av_val,0);
						}
					}
					$attrDefaultValue = $_POST['attrDefaultValue'] ;
					if(!empty($attrDefaultValue)){
						foreach($attrDefaultValue  as $key=>$advs){
							if(is_array($advs)){
								if(count($attrDefaultValue[$key])>1){
									$oModule->deleteAttrDefaultValue($id,$key,$lang) ;
								}
							}
						}
						
						foreach($attrDefaultValue  as $key=>$advs){
							if(is_array($advs)){
							//	$oModule->deleteAttrDefaultValue($id , $key,$SITE_LANGUAGE,$adv_val,$isDefaultVal) ;
								foreach($advs as $adv_key => $adv_val ){
									$oModule->updateAttrValue($id, $key,$lang,$adv_val,1,count($attrDefaultValue[$key]));
								}
							}
						}
					}
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
					if($id!=96&&$id!=97&&$id!=98){
						$oModule->deleteData($id);
					}
				break;
				case 'loadLanguages':
					$lang = $oModule->getLanguage();
					echo json_encode($lang);
				break ;
// translate page
				case 'formTranslateInit':
							$id = addslashes($_GET['id']);
							$lang = addslashes($_GET['language']);

							$oModule->checkExistsAttrField($id,$lang,'TH') ;

							$data = $oModule->getTranslate($id,$lang);
							echo json_encode($data);
					break ;
				case 'saveTranslate':
							$id = $_POST['id'];
							$lang = $_POST['language'];
							$name = $_POST['name'];	
							$meta_key= $_POST['meta_key'];
							$meta_description= $_POST['meta_description'];
							$oModule->saveTranslate( $lang,$id,$name,'',$meta_key,$meta_description) ;
				break;
/////////////// attr field task ////////////////////////////////////////////////////////////////////////////////
			case 'formAttrInit':
				if($_GET['mode']=='edit'){
						$id = addslashes($_GET['id']);
						$data = $oAttr->getOne($id);
						if($data['attr_type']=='select'||$data['attr_type']=='checkbox'||$data['attr_type']=='radio'){
							$data['default_val'] = $oAttr->getAttrDefaultValue($data['id']);
						}
						echo json_encode($data);
					}
				break ;
			case 'getAttrData':
					$columns = array('id','attr_name','attr_type','category_id','sequence');
					$limit = '';
					$orderby ='' ;
					$search = '';
					$iDisplayLength = $_GET['iDisplayLength'];
					$iDisplayStart= $_GET['iDisplayStart'];
					$limit  = ' limit '.$iDisplayStart.','.$iDisplayLength ;
					$iSortCol_0= $_GET['iSortCol_0'];
					$sSortDir_0= $_GET['sSortDir_0'];
					if(!empty($columns[$iSortCol_0])){
						$orderby = " order by  $oAttr->table.".$columns[$iSortCol_0].' '.$sSortDir_0 ;
					}else{
						$orderby = " order by  ".$columns[4].' '.$sSortDir_0 ;
					}
					$sSearch= $_GET['sSearch']; 
					if(!empty($sSearch)){
						$search =  " WHERE ( $oAttr->table.name like '%$sSearch%' or  $oAttr->table.slug like '%$sSearch%') " ;
					}
					$data = $oAttr->getAll($search,$orderby,$limit);
					//print_r($categories);
					$iTotal = $oAttr->getSize() ;
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
						$iconbar = '	<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>';
					}else{
						$iconbar = '	<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>';
					}
											
					$iconbar .=	'  <a href="javascript:void(0)" onclick="setDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>  ';
					if(SITE_TRANSLATE){				
							$iconbar .=	'  <a href="javascript:void(0)" onclick="setTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
					}
					
					$iconbar .='    <a href="javascript:void(0)" onclick="setDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>	';
											
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a>
										 <a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>
									  ';
					$order = '<input name="sequence_'.$value['id'].'" id="sequence_'.$value['id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['id'].')" />';
									  
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						
						$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$value['attr_name'].'</a>';			 			
						$value['category'] = (empty($value['category']))?' ทั้งหมด ':$value['category'] ;
						$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['attr_type'],3=>$value['category'],4=>$order,5=>$iconbar,6=>$value['id'],"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++ ;
						}
					}
						echo json_encode($output) ;
				break;
			case 'getAttrField':
					$category_id = (!empty($_GET['category_id']))?$_GET['category_id']:0;
					$catalog_id = (!empty($_GET['catalog_id']))?$_GET['catalog_id']:0;

					if(!empty($catalog_id)){
						$catalog_attr_val_arr =	$oAttr->getCatalogAttrValue($catalog_id);
						$catalog_attr_val = array();
						if(!empty($catalog_attr_val_arr)){
							foreach($catalog_attr_val_arr as $cava){
								$cava_index = count($catalog_attr_val[$cava['attr_id']]);
								$catalog_attr_val[$cava['attr_id']][$cava_index] = array('default_value'=>$cava['default_val'],'value'=>$cava['value']) ;
							}
						}
					}
					$attrs = $oAttr->getAttrAll($category_id);
					$fields = '';

					$group_list = array();
					foreach($attrs as $attr){
						if(!empty($attr['group_type'])){
						$group_list[$attr['group_type']][$attr['group_set']][$attr['id']] = count($group_list[$attr['group_type']][$attr['group_set']])+1  ;
						}
					}

					foreach($attrs as $attr){
						   $require = ($attr['attr_require'])?'<span class="required">*</span>':'';
						   switch($attr['attr_type']){
							   case 'text':
							   		if(!empty($catalog_attr_val[$attr['id']])){
										$text_value = $catalog_attr_val[$attr['id']][0]['value'] ;
									}else{
										$text_value =$attr['attr_value'] ;
									}
							   		$field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="text" value="'.$text_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'" />';
							   break;
							   case 'textarea':
							  		 if(!empty($catalog_attr_val[$attr['id']])){
										$textarea_value = $catalog_attr_val[$attr['id']][0]['value'] ;
									}else{
										$textarea_value =$attr['attr_value'] ;
									}
							  		 $field_tag ='<textarea  name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'">'.$textarea_value.'</textarea>';
							   break;
							   case 'texteditor':
							   		if(!empty($catalog_attr_val[$attr['id']])){
										$textarea_value = $catalog_attr_val[$attr['id']][0]['value'] ;
									}else{
										$textarea_value =$attr['attr_value'] ;
									}
							  		 $field_tag ='<textarea  name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" class="elrte '.$attr['attr_class'].'" style="'.$attr['attr_style'].'">'.$textarea_value.'</textarea>';
							   break;
							    case 'checkbox':
							   		  $def_val  = $oAttr->getAttrDefaultValue($attr['id']);	 
									  if(!empty($def_val)){
										 $field_tag = '';
										 $checkbox_checked_list = array();
										 if(!empty($catalog_attr_val[$attr['id']])){
											 foreach($catalog_attr_val[$attr['id']] as $cava_key =>$cava_value){
												 $checkbox_checked_list[$cava_value['default_value']] = true ; 
											 }
										 }
									 	foreach($def_val as $key=>$dv){
											 if(!empty($catalog_attr_val[$attr['id']])){
													$is_selected =($checkbox_checked_list[$dv['id']]===true)?' checked="checked" ':'';
													$field_tag .= '<label for="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" class="checkbox"><input type="checkbox" name="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" id="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" value="'.$dv['id'].'"  class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'"  '. $is_selected .' />';
													$field_tag .= $dv['label'].'</label>' ;
											}else{
													$is_selected =($dv['selected']==1)?' checked="checked" ':'';
													$field_tag .= '<label for="attrDefaultValue['.$attr['id'].']['.$dv['id'].']"  class="checkbox"><input type="checkbox" name="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" id="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" value="'.$dv['id'].'"  class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'"  '. $is_selected .' />';
													$field_tag .= $dv['label'].'</label>' ;
											}
										}
									 }
							   break;
							   case 'radio':
							   		  $def_val  = $oAttr->getAttrDefaultValue($attr['id']);
									 if(!empty($def_val)){
										  $field_tag = '';
										  if(!empty($catalog_attr_val[$attr['id']])){
											 foreach($catalog_attr_val[$attr['id']] as $cava_key =>$cava_value){
												 $radio_checked_list[$cava_value['default_value']] = true ; 
											 }
										 }
										 //print_r($radio_checked_list);
										 foreach($def_val as $key=>$dv){
											 	 if(!empty($catalog_attr_val[$attr['id']])){
											 		$is_selected =($radio_checked_list[$dv['id']]===true)?' checked="checked" ':'';
										     		$field_tag .= '<label for="attrDefaultValue['.$attr['id'].']"><input type="radio" name="attrDefaultValue['.$attr['id'].']" id="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" value="'.$dv['id'].'"  class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'"  '. $is_selected .' />';
										 	 		$field_tag .= $dv['label'].'</label>' ;
												}else{
													 $is_selected =($dv['selected']==1)?' checked="checked" ':'';
										   			 $field_tag .= '<label for="attrDefaultValue['.$attr['id'].']"><input type="radio" name="attrDefaultValue['.$attr['id'].']" id="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" value="'.$dv['id'].'"  class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'"  '. $is_selected .' />';
										 	 		$field_tag .= $dv['label'].'</label>' ;
												}
										 }
									 }
							   break;
							   case 'select':
							  		 $def_val  = $oAttr->getAttrDefaultValue($attr['id']);
									 $field_tag ='<select name="attrDefaultValue['.$attr['id'].']['.$attr['id'].']" id="attrDefaultValue['.$attr['id'].']['.$attr['id'].']" class="'.$attr['attr_class'].' " style="'.$attr['attr_style'].'" '.$attr['attr'].'>';
									 if(!empty($def_val)){
										   if(!empty($catalog_attr_val[$attr['id']])){
											 foreach($catalog_attr_val[$attr['id']] as $cava_key =>$cava_value){
												 $select_checked_list[$cava_value['default_value']] = true ; 
											 }
										 }
										 foreach($def_val as $key=>$dv){
											 	if(!empty($catalog_attr_val[$attr['id']])){
													 $is_selected =($select_checked_list[$dv['id']]===true)?' selected="selected" ':'';
											 		$field_tag .='<option data-val="'.$dv['value'].'"  value="'.$dv['id'].'" '.$is_selected.'>'.$dv['label'].'</option>';
												}else{
													 $is_selected =($dv['selected']==1)?' selected="selected" ':'';
													 $field_tag .='<option data-val="'.$dv['value'].'" value="'.$dv['id'].'" '.$is_selected.'>'.$dv['label'].'</option>';
												}
										 }
									 }
									 $field_tag .='</select>';
							   break;
							   case 'file':
										if(!empty($catalog_attr_val)){
											$file_value = $catalog_attr_val[$attr['id']][0]['value'] ;
										}else{
											$file_value =$attr['attr_value'] ;
										}
							     		$field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="text" value="'.$file_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].' elfinder-browse" style="'.$attr['attr_style'].'" />';
							   break;
							   case 'image':
							  			 if(!empty($catalog_attr_val)){
											$image_value = $catalog_attr_val[$attr['id']][0]['value'] ;
										}else{
											$image_value =$attr['attr_value'] ;
										}
							     		$field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="text" value="'.$image_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].' elfinder-browse" style="'.$attr['attr_style'].'" />';
							   break;
							   case 'gallery':
							  			 if(!empty($catalog_attr_val)){
											$gallery_value = json_decode($catalog_attr_val[$attr['id']][0]['value'],1);
										}else{
											$gallery_value =json_decode($attr['attr_value'],1) ;
										}
										$img_length = count($gallery_value);
							     		$field_tag = '<div class="displayImages">
																	<input name="countImageIndex_'.$attr['id'].'" id="countImageIndex_'.$attr['id'].'" type="hidden" value="'.$img_length.'" />
																	<input name="deletedImageList_'.$attr['id'].'" id="deletedImageList_'.$attr['id'].'" type="hidden" value="" />
																	 <ul   id="displayImagesList_'.$attr['id'].'">';
										if(!empty($gallery_value)){
											$cnt = 0 ;
											foreach($gallery_value as $gv){
												//print_r($gv);
												$field_tag .='
												<li id="'.$attr['id'].'_'.$cnt.'" style="list-style:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">
												   <tr>
												 		<td width="120" height="120" align="center" valign="middle"><a href="'.$gv['src'].'" rel="prettyPhoto"><img src="'.$gv['src'].'"  style="width:100px; height:100px; margin-top:5px; border:#ccc 1px solid; border-radius:10px;"/></a></td>
														<td>
															<input name="attrValue['.$attr['id'].']['.$cnt.'][src]"  id="attrValue['.$attr['id'].']['.$cnt.'][src]" type="hidden" value="'.$gv['src'].'" />
															<label  style="height:20px; font-size:90%; padding:0px;\">ชื่อภาพ</label><input name="attrValue['.$attr['id'].']['.$cnt.'][title]"  id="attrValue['.$attr['id'].']['.$cnt.'][title]" type="text" style="margin-bottom:5px;" value="'.$gv['title'].'" />
												   			<label  style="height:20px; font-size:90%; padding:0px\">คำอธิบายภาพ</label><input name="attrValue['.$attr['id'].']['.$cnt.'][description]"  id="attrValue['.$attr['id'].']['.$cnt.'][description]" type="text" value="'.$gv['description'].'" />
														</td>
														<td style="width:50px; padding-left:20px; padding-top:20px;" align="center" valign="middle">
												   			<a href="javascript:void(0)" onclick="deleteGalleryImage('.$attr['id'].','.$cnt.','.$cnt.');"><input type="button" value="ลบ" class="da-button red left"></a>
															<br/><input name="attrValue['.$attr['id'].']['.$cnt.'][order]"  id="attrValue['.$attr['id'].']['.$cnt.'][order]" type="text" class="showImageOrder" value="'.$cnt.'" style="width:40px; margin-top:5px;"  />
														</td>
													</tr>
												   </table>
												 </li>';
												 $cnt++;
											}
										}
																	 
										$field_tag .= '</ul>
																	<div style="clear:both"></div>
                                                    			</div>
                                                    <div><a href="javascript:void(0)" onclick="showSelectImageDialog('.$attr['id'].');"><input type="button" value="เลือกรูปภาพ" class="da-button blue left"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="sortAble('.$attr['id'].',1);" id="attr_gallery_sort_'.$attr['id'].'"><input type="button" value="จัดเรียงภาพ" class="da-button orange left" style="margin-left:10px;"></a> </div>
                                                	 <div id="finder_'.$attr['id'].'"></div>';
											//		 <input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="hidden" value="'.$gallery_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].' elfinder-browse" style="'.$attr['attr_style'].'" />';
							   break;
							    case 'maps':
							  			 if(!empty($catalog_attr_val)){
											$image_value = $catalog_attr_val[$attr['id']][0]['value'] ;
										}else{
											$image_value =$attr['attr_value'] ;
										}
							     		$field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="text" value="'.$image_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].' google-maps" style="'.$attr['attr_style'].'" onclick="show_maps_attr('.$attr['id'].',this)" />';
										$field_tag .= '<div id="maps_container_'.$attr['id'].'" style="display:none;"><div class="da-panel-content" id="map_container_panel_'.$attr['id'].'" style="position:relative; width 100%; height:100%; z-index:950;">
										<div class="maps_head" style="position:absolute; width:93%; height:30px;  z-index:951; left:7%;  bottom:0px; ">
											<label for="latitude_'.$attr['id'].'"><input name="maps_latitude_'.$attr['id'].'" id="maps_latitude_'.$attr['id'].'"  type="text" placeholder="Latitude" value="13.764896366170102"  /></label>
											<label for="latitude_'.$attr['id'].'"><input name="maps_longitude_'.$attr['id'].'" id="maps_longitude_'.$attr['id'].'"  type="text"  placeholder="Longitude" value="100.53837776184082"  /></label>
											<label for="maps_search_'.$attr['id'].'"><input name="maps_search_'.$attr['id'].'" id="maps_search_'.$attr['id'].'"  type="text" style="width:50%" /><input name="maps_search_button_'.$attr['id'].'" id="maps_search_button_'.$attr['id'].'" type="button" value="ค้นหา" /></label>
										</div>
										<div id="google_maps_container_'.$attr['id'].'" class="google_maps_container" style="position:relative; width 100%; height:100%"></div>
										</div></div>';
							   break;
							   case 'hidden':
							  			 if(!empty($catalog_attr_val)){
											$hidden_value = $catalog_attr_val[$attr['id']][0]['value'] ;
										}else{
											$hidden_value =$attr['attr_value'] ;
										}
							 		  $field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="hidden" value="'.$hidden_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'" />';
							   break;
						   }

						    // $fields .='<div class="grid_4"><div class="da-panel collapsible collapsed">';
						   if(!empty($attr['group_type'])){
						   		if($attr['group_set']>1 && (empty($attr['attr_value'])&&empty($catalog_attr_val[$attr['id']][0]['value'])) ){
						   			$f_class = 'hide' ;
						   		}else{
						   			$f_class = 'show' ;
						   		}

						   		if($group_list[$attr['group_type']][$attr['group_set']][$attr['id']]==1){
							   		$fields .='<div data-group="'.$attr['group_type'].'" data-set="'.$attr['group_set'].'" class="da-form-row attrs group '.$attr['group_type'].' '.$f_class.'">
	                                        	<label>'.$attr['attr_label'].$require.'</label> ';
	                            }
	                                $fields .='<div class="da-form-item large">
	                                        	<span class="formNote" >ใส่ข้อมูล '.$attr['attr_label'].'</span>
	                                            '.$field_tag.'';
	                            if($group_list[$attr['group_type']][$attr['group_set']][$attr['id']]==1&&$group_list[$attr['group_type']][$attr['group_set']][$attr['id']]<=count($group_list[$attr['group_type']][$attr['group_set']])){                
                                    $fields .='<button type="button" class="form-group-add da-button green" onclick="setShowFormGroupSet(\''.$attr['group_type'].'\','.$attr['group_set'].',\'show\')" > + </button>';
                                    $fields .='<button type="button" class="form-group-remove da-button red" onclick="setShowFormGroupSet(\''.$attr['group_type'].'\','.$attr['group_set'].',\'hide\')" > - </button>';
                                }
	                                $fields .='</div>';

	                            if($group_list[$attr['group_type']][$attr['group_set']][$attr['id']]==count($group_list[$attr['group_type']][$attr['group_set']])){    
	                            	$fields .='</div>'; 
	                            }else{
	                            	$fields .='<br>'; 
	                            }
                        	}else{
                    			$fields .='<div class="da-form-row attrs">
                                        <label>'.$attr['attr_label'].$require.'</label>
                                        <div class="da-form-item large">
                                        	<span class="formNote" >ใส่ข้อมูล '.$attr['attr_label'].'</span>
                                            '.$field_tag.'
                                        </div>
                                      </div>';
                        	}
					}


					echo $fields ;
				break;
			case 'getAttrFieldTranslate':
					$category_id = (!empty($_GET['category_id']))?$_GET['category_id']:0;
					$catalog_id = (!empty($_GET['id']))?$_GET['id']:0;
					$lang = addslashes($_GET['language']);

					$row = $oModule->findOneTranslate($catalog_id,$lang);

					if(!empty($catalog_id)){
						$catalog_attr_val_arr =	$oAttr->getCatalogAttrValue($catalog_id,$lang);
						$catalog_attr_val = array();

						// print_r($catalog_attr_val_arr);

						if(!empty($catalog_attr_val_arr)){
							foreach($catalog_attr_val_arr as $cava){
								$cava_index = count($catalog_attr_val[$cava['attr_id']]);
								$catalog_attr_val[$cava['attr_id']][$cava_index] = array('default_value'=>$cava['default_val'],'value'=>$cava['value']) ;
							}
						}
					}
					$attrs = $oAttr->getAttrAll($category_id,$lang);
					$fields = '';

					$group_list = array();
					foreach($attrs as $attr){
						if(!empty($attr['group_type'])){
						$group_list[$attr['group_type']][$attr['group_set']][$attr['id']] = count($group_list[$attr['group_type']][$attr['group_set']])+1  ;
						}
					}

					foreach($attrs as $attr){
						   $require = ($attr['attr_require'])?'<span class="required">*</span>':'';
						   switch($attr['attr_type']){
							   case 'text':
							   		if(!empty($catalog_attr_val[$attr['id']])){
										$text_value = $catalog_attr_val[$attr['id']][0]['value'] ;
									}else{
										$text_value =$attr['attr_value'] ;
									}
							   		$field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="text" value="'.$text_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'" />';
							   break;
							   case 'textarea':
							  		 if(!empty($catalog_attr_val[$attr['id']])){
										$textarea_value = $catalog_attr_val[$attr['id']][0]['value'] ;
									}else{
										$textarea_value =$attr['attr_value'] ;
									}
							  		 $field_tag ='<textarea  name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'">'.$textarea_value.'</textarea>';
							   break;
							   case 'texteditor':
							   		if(!empty($catalog_attr_val[$attr['id']])){
										$textarea_value = $catalog_attr_val[$attr['id']][0]['value'] ;
									}else{
										$textarea_value =$attr['attr_value'] ;
									}
							  		 $field_tag ='<textarea  name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" class="elrte '.$attr['attr_class'].'" style="'.$attr['attr_style'].'">'.$textarea_value.'</textarea>';
							   break;
							    case 'checkbox':
							   		  $def_val  = $oAttr->getAttrDefaultValue($attr['id']);	 
									  if(!empty($def_val)){
										 $field_tag = '';
										 $checkbox_checked_list = array();
										 if(!empty($catalog_attr_val[$attr['id']])){
											 foreach($catalog_attr_val[$attr['id']] as $cava_key =>$cava_value){
												 $checkbox_checked_list[$cava_value['default_value']] = true ; 
											 }
										 }
									 	foreach($def_val as $key=>$dv){
											 if(!empty($catalog_attr_val[$attr['id']])){
													$is_selected =($checkbox_checked_list[$dv['id']]===true)?' checked="checked" ':'';
													$field_tag .= '<label for="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" class="checkbox"><input type="checkbox" name="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" id="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" value="'.$dv['id'].'"  class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'"  '. $is_selected .' />';
													$field_tag .= $dv['label'].'</label>' ;
											}else{
													$is_selected =($dv['selected']==1)?' checked="checked" ':'';
													$field_tag .= '<label for="attrDefaultValue['.$attr['id'].']['.$dv['id'].']"  class="checkbox"><input type="checkbox" name="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" id="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" value="'.$dv['id'].'"  class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'"  '. $is_selected .' />';
													$field_tag .= $dv['label'].'</label>' ;
											}
										}
									 }
							   break;
							   case 'radio':
							   		  $def_val  = $oAttr->getAttrDefaultValue($attr['id']);
									 if(!empty($def_val)){
										  $field_tag = '';
										  if(!empty($catalog_attr_val[$attr['id']])){
											 foreach($catalog_attr_val[$attr['id']] as $cava_key =>$cava_value){
												 $radio_checked_list[$cava_value['default_value']] = true ; 
											 }
										 }
										 //print_r($radio_checked_list);
										 foreach($def_val as $key=>$dv){
											 	 if(!empty($catalog_attr_val[$attr['id']])){
											 		$is_selected =($radio_checked_list[$dv['id']]===true)?' checked="checked" ':'';
										     		$field_tag .= '<label for="attrDefaultValue['.$attr['id'].']"><input type="radio" name="attrDefaultValue['.$attr['id'].']" id="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" value="'.$dv['id'].'"  class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'"  '. $is_selected .' />';
										 	 		$field_tag .= $dv['label'].'</label>' ;
												}else{
													 $is_selected =($dv['selected']==1)?' checked="checked" ':'';
										   			 $field_tag .= '<label for="attrDefaultValue['.$attr['id'].']"><input type="radio" name="attrDefaultValue['.$attr['id'].']" id="attrDefaultValue['.$attr['id'].']['.$dv['id'].']" value="'.$dv['id'].'"  class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'"  '. $is_selected .' />';
										 	 		$field_tag .= $dv['label'].'</label>' ;
												}
										 }
									 }
							   break;
							   case 'select':
							  		 $def_val  = $oAttr->getAttrDefaultValue($attr['id']);
									 $field_tag ='<select name="attrDefaultValue['.$attr['id'].']['.$attr['id'].']" id="attrDefaultValue['.$attr['id'].']['.$attr['id'].']" class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'" '.$attr['attr'].'>';
									 if(!empty($def_val)){
										   if(!empty($catalog_attr_val[$attr['id']])){
											 foreach($catalog_attr_val[$attr['id']] as $cava_key =>$cava_value){
												 $select_checked_list[$cava_value['default_value']] = true ; 
											 }
										 }
										 foreach($def_val as $key=>$dv){
											 	if(!empty($catalog_attr_val[$attr['id']])){
													 $is_selected =($select_checked_list[$dv['id']]===true)?' selected="selected" ':'';
											 		$field_tag .='<option  data-val="'.$dv['value'].'"  value="'.$dv['id'].'" '.$is_selected.'>'.$dv['label'].'</option>';
												}else{
													 $is_selected =($dv['selected']==1)?' selected="selected" ':'';
													 $field_tag .='<option  data-val="'.$dv['value'].'"  value="'.$dv['id'].'" '.$is_selected.'>'.$dv['label'].'</option>';
												}
										 }
									 }
									 $field_tag .='</select>';
							   break;
							   case 'file':
										if(!empty($catalog_attr_val)){
											$file_value = $catalog_attr_val[$attr['id']][0]['value'] ;
										}else{
											$file_value =$attr['attr_value'] ;
										}
							     		$field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="text" value="'.$file_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].' elfinder-browse" style="'.$attr['attr_style'].'" />';
							   break;
							   case 'image':
							  			 if(!empty($catalog_attr_val)){
											$image_value = $catalog_attr_val[$attr['id']][0]['value'] ;
										}else{
											$image_value =$attr['attr_value'] ;
										}
							     		$field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="text" value="'.$image_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].' elfinder-browse" style="'.$attr['attr_style'].'" />';
							   break;
							   case 'gallery':
							  			 if(!empty($catalog_attr_val)){
											$gallery_value = json_decode($catalog_attr_val[$attr['id']][0]['value'],1);
										}else{
											$gallery_value =json_decode($attr['attr_value'],1) ;
										}
										$img_length = count($gallery_value);
							     		$field_tag = '<div class="displayImages">
																	<input name="countImageIndex_'.$attr['id'].'" id="countImageIndex_'.$attr['id'].'" type="hidden" value="'.$img_length.'" />
																	<input name="deletedImageList_'.$attr['id'].'" id="deletedImageList_'.$attr['id'].'" type="hidden" value="" />
																	 <ul   id="displayImagesList_'.$attr['id'].'">';
										if(!empty($gallery_value)){
											$cnt = 0 ;
											foreach($gallery_value as $gv){
												//print_r($gv);
												$field_tag .='
												<li id="'.$attr['id'].'_'.$cnt.'" style="list-style:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">
												   <tr>
												 		<td width="120" height="120" align="center" valign="middle"><a href="'.$gv['src'].'" rel="prettyPhoto"><img src="'.$gv['src'].'"  style="width:100px; height:100px; margin-top:5px; border:#ccc 1px solid; border-radius:10px;"/></a></td>
														<td>
															<input name="attrValue['.$attr['id'].']['.$cnt.'][src]"  id="attrValue['.$attr['id'].']['.$cnt.'][src]" type="hidden" value="'.$gv['src'].'" />
															<label  style="height:20px; font-size:90%; padding:0px;\">ชื่อภาพ</label><input name="attrValue['.$attr['id'].']['.$cnt.'][title]"  id="attrValue['.$attr['id'].']['.$cnt.'][title]" type="text" style="margin-bottom:5px;" value="'.$gv['title'].'" />
												   			<label  style="height:20px; font-size:90%; padding:0px\">คำอธิบายภาพ</label><input name="attrValue['.$attr['id'].']['.$cnt.'][description]"  id="attrValue['.$attr['id'].']['.$cnt.'][description]" type="text" value="'.$gv['description'].'" />
														</td>
														<td style="width:50px; padding-left:20px; padding-top:20px;" align="center" valign="middle">
												   			<a href="javascript:void(0)" onclick="deleteGalleryImage('.$attr['id'].','.$cnt.','.$cnt.');"><input type="button" value="ลบ" class="da-button red left"></a>
															<br/><input name="attrValue['.$attr['id'].']['.$cnt.'][order]"  id="attrValue['.$attr['id'].']['.$cnt.'][order]" type="text" class="showImageOrder" value="'.$cnt.'" style="width:40px; margin-top:5px;"  />
														</td>
													</tr>
												   </table>
												 </li>';
												 $cnt++;
											}
										}
																	 
										$field_tag .= '</ul>
																	<div style="clear:both"></div>
                                                    			</div>
                                                    <div><a href="javascript:void(0)" onclick="showSelectImageDialog('.$attr['id'].');"><input type="button" value="เลือกรูปภาพ" class="da-button blue left"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="sortAble('.$attr['id'].',1);" id="attr_gallery_sort_'.$attr['id'].'"><input type="button" value="จัดเรียงภาพ" class="da-button orange left" style="margin-left:10px;"></a> </div>
                                                	 <div id="finder_'.$attr['id'].'"></div>';
											//		 <input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="hidden" value="'.$gallery_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].' elfinder-browse" style="'.$attr['attr_style'].'" />';
							   break;
							    case 'maps':
							  			 if(!empty($catalog_attr_val)){
											$image_value = $catalog_attr_val[$attr['id']][0]['value'] ;
										}else{
											$image_value =$attr['attr_value'] ;
										}
							     		$field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="text" value="'.$image_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].' google-maps" style="'.$attr['attr_style'].'" onclick="show_maps_attr('.$attr['id'].',this)" />';
										$field_tag .= '<div id="maps_container_'.$attr['id'].'" style="display:none;"><div class="da-panel-content" id="map_container_panel_'.$attr['id'].'" style="position:relative; width 100%; height:100%; z-index:950;">
										<div class="maps_head" style="position:absolute; width:93%; height:30px;  z-index:951; left:7%;  bottom:0px; ">
											<label for="latitude_'.$attr['id'].'"><input name="maps_latitude_'.$attr['id'].'" id="maps_latitude_'.$attr['id'].'"  type="text" placeholder="Latitude" value="13.764896366170102"  /></label>
											<label for="latitude_'.$attr['id'].'"><input name="maps_longitude_'.$attr['id'].'" id="maps_longitude_'.$attr['id'].'"  type="text"  placeholder="Longitude" value="100.53837776184082"  /></label>
											<label for="maps_search_'.$attr['id'].'"><input name="maps_search_'.$attr['id'].'" id="maps_search_'.$attr['id'].'"  type="text" style="width:50%" /><input name="maps_search_button_'.$attr['id'].'" id="maps_search_button_'.$attr['id'].'" type="button" value="ค้นหา" /></label>
										</div>
										<div id="google_maps_container_'.$attr['id'].'" class="google_maps_container" style="position:relative; width 100%; height:100%"></div>
										</div></div>';
							   break;
							   case 'hidden':
							  			 if(!empty($catalog_attr_val)){
											$hidden_value = $catalog_attr_val[$attr['id']][0]['value'] ;
										}else{
											$hidden_value =$attr['attr_value'] ;
										}
							 		  $field_tag = '<input name="attrValue['.$attr['id'].']" id="attrValue['.$attr['id'].']" type="hidden" value="'.$hidden_value.'" placeholder="'.$attr['attr_placeholder'].'" class="'.$attr['attr_class'].'" style="'.$attr['attr_style'].'" />';
							   break;
						   }

						    // $fields .='<div class="grid_4"><div class="da-panel collapsible collapsed">';
						   if(!empty($attr['group_type'])){

						   		if($attr['group_set']>1 && (empty($attr['attr_value'])&&empty($catalog_attr_val[$attr['id']][0]['value'])) ){
						   			$f_class = 'hide' ;
						   		}else{
						   			$f_class = 'show' ;
						   		}

						   		if($group_list[$attr['group_type']][$attr['group_set']][$attr['id']]==1){
							   		$fields .='<div data-group="'.$attr['group_type'].'" data-set="'.$attr['group_set'].'" class="da-form-row attrs group '.$attr['group_type'].' '.$f_class.'">
	                                        	<label>'.$attr['attr_label'].$require.'</label> ';
	                            }
	                                $fields .='<div class="da-form-item large">
	                                        	<span class="formNote" >ใส่ข้อมูล '.$attr['attr_label'].'</span>
	                                            '.$field_tag.'';
	                            if($group_list[$attr['group_type']][$attr['group_set']][$attr['id']]==1&&$group_list[$attr['group_type']][$attr['group_set']][$attr['id']]<=count($group_list[$attr['group_type']][$attr['group_set']])){                
                                    $fields .='<button type="button" class="form-group-add da-button green" onclick="setShowFormGroupSet(\''.$attr['group_type'].'\','.$attr['group_set'].',\'show\')" > + </button>';
                                    $fields .='<button type="button" class="form-group-remove da-button red" onclick="setShowFormGroupSet(\''.$attr['group_type'].'\','.$attr['group_set'].',\'hide\')" > - </button>';
                                }
	                                $fields .='</div>';

	                            if($group_list[$attr['group_type']][$attr['group_set']][$attr['id']]==count($group_list[$attr['group_type']][$attr['group_set']])){    
	                            	$fields .='</div>'; 
	                            }else{
	                            	$fields .='<br>'; 
	                            }
                        	}else{
                    			$fields .='<div class="da-form-row attrs">
                                        <label>'.$attr['attr_label'].$require.'</label>
                                        <div class="da-form-item large">
                                        	<span class="formNote" >ใส่ข้อมูล '.$attr['attr_label'].'</span>
                                            '.$field_tag.'
                                        </div>
                                      </div>';
                        	}
					}
					echo $fields ;
				break;
			case 'saveAttrData':
				$user = $oUsers->getAdminLoginUser();
				$id = $_POST['id'];
				$category_id = $_POST['categories'];
				$attr_name = $_POST['attr_name'];	
				$attr_id = $_POST['attr_id'];	
				$attr_label = htmlspecialchars($_POST['attr_label']);	
				$attr_type= $_POST['attr_type'];
				$attr_value= htmlspecialchars($_POST['attr_value']);
				$attr_class= $_POST['attr_class'];
				$attr_style= addslashes($_POST['attr_style']);
				$attr_placeholder= htmlspecialchars($_POST['attr_placeholder']);
				$attr= addslashes($_POST['attr']);
				$note= htmlspecialchars($_POST['note']);
				$require = $_POST['require'];
				$status= $_POST['status'];
				$attr_default_sequence = $_POST['defaultFieldSequence'] ;
				$attr_default_value = $_POST['defaultFieldValue'] ;
				$attr_default_label = $_POST['defaultFieldLabel'] ;
				$attr_default_selected = $_POST['defaultFieldChecked'] ;
				$attr_default_is_insert = $_POST['defaultFieldIsInsert'] ;
				
				// print_r($attr_default_value);
				
				if(empty($id)){	
					$oAttr->insertAttrData($category_id,$attr_name,$attr_id,htmlspecialchars($attr_label),$attr_type,$attr_value,$attr_class,$attr_style,$attr_placeholder,$attr,$note,$require,$status);
					$id = $oAttr->insert_id();
					// insert attr default value
					if($attr_type=='select'||$attr_type=='checkbox'||$attr_type=='radio'){
							if(!empty($attr_default_value)){
								foreach($attr_default_value as $key=>$adv){

									$lang = SITE_LANGUAGE ;
									$value = $adv ;
									$label = htmlspecialchars($attr_default_label[$key]) ;
									$selected = (!empty($attr_default_selected[$key]))?1:0;
									$sequence =$attr_default_sequence[$key] ;
									$oAttr->insertAttrDefaultValue($id,$lang,$value,$label,$selected,$sequence) ;
								}
							}
					}
				}else{
					$oAttr->updateAttrData($id,$category_id,$attr_name,$attr_id,$attr_label,$attr_type,$attr_value,$attr_class,$attr_style,$attr_placeholder,$attr,$note,$require,$status);
					if(!empty($attr_default_value)){
								foreach($attr_default_value as $key=>$adv){
									// echo '='.$key.':'.$attr_default_is_insert[$key].'=';
									$lang = SITE_LANGUAGE ;
									$value = $adv ;
									$label = $attr_default_label[$key] ;
									$selected = (!empty($attr_default_selected[$key]))?1:0;
									$sequence =$attr_default_sequence[$key] ;
									if($attr_default_is_insert[$key]==1||$key==0){
										$oAttr->insertAttrDefaultValue($id,$lang,$value,$label,$selected,$sequence) ;
									}else{
										$oAttr->updateAttrDefaultValue($key,$id,$lang,$value,$label,$selected,$sequence);
									}
									
								}
							}
				}
				break;
				case 'duplicateAttr':
					$user = $oUsers->getAdminLoginUser();
					$id = $_GET['id'];
					$oAttr->duplicateAttrData($id,$user['id']);
				break;
				case 'setAttrStatus':
					$id = addslashes($_GET['id']);
					$status = addslashes($_GET['status']);
					$oAttr->updateAttrStatus($id,$status);
				break;
				case 'setAttrMove':
					$id = addslashes($_GET['id']);
					$type = addslashes($_GET['type']);
					$oAttr->moveRow($id,$type);
				break;
				case 'setAttrDelete':
					$id = addslashes($_GET['id']);
					$oAttr->deleteAttrData($id);
				break;
				case 'loadAttrLanguages':
					$lang = $oAttr->getAttrLanguage();
					echo json_encode($lang);
				break ;
				case 'formAttrTranslateInit':
							$id = addslashes($_GET['id']);
							$lang = addslashes($_GET['language']);
							$data = $oAttr->getAttrTranslate($id,$lang);
							echo json_encode($data);
					break ;
				case 'saveAttrTranslate':
							$id = $_POST['id'];
							$lang = $_POST['translate_language'];
							$name = $_POST['fieldname'];	
							$label = $_POST['fieldlabel'];	
							$type= $_POST['fieldtype'];
							$value= $_POST['defaultvalue'];
							$style= $_POST['fieldstyle'];
							$attr= $_POST['fieldattr'];
							$note= $_POST['note'];
							$oAttr->saveAttrTranslate( $lang,$id,$name,$label,$type,$value,$style,$attr,$note) ;
				break;
				case 'removeDefaultValue':
					$default_id = $_GET['id'] ;
					$oAttr->saveAttrRemoveDefaultValue($default_id) ;
				break;	
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
			case 'loadCategoriesAttrFilter':
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
			// attrswitchAttrOrder
			case 'setReorderAttr' :
				$columns = array('id','name','attr_type','category_id','sequence','id','id');
				$column = $columns[(int)$_GET['column']] ;
				$direction =strtoupper(addslashes($_GET['direction']));
				$oModule->setReorderAttr($column,$direction);
			break;
		   case 'reorderAttrData':
				$id = explode('-',$_GET['id']) ;
				$sort = explode('-',$_GET['sort']) ;
				$oModule->reOrderAttrDataDragDrop($id,$sort);
			break ;		
			case 'switchAttrOrder';
				$id=$_GET['id'];
				$sort = $_GET['sort'] ;
				$oModule->switchAttrOrder($id,$sort);
			break ;	
			case 'changeAttrCategory':
				$id=$_GET['id'];
				$category_id = $_GET['category_id'] ;
				$oModule->changeAttrCategory($id,$category_id);
			break;	
////////////////task for frontend  //////////////////////////////////////////////////////////////////////////
			case "find" :
					
					$language =   LANG ; // ($_SESSION['site_language'])?$_SESSION['site_language']:SITE_LANGUAGE;
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
					if($filter=='FILTER_FUNCTON'){
						$oModule->checkInitSearch($_PARAM[0],$_PARAM_PLUS[3]) ;
					}
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
				
			case "findjson" :
					$language =   LANG ; //($_SESSION['site_language'])?$_SESSION['site_language']:SITE_LANGUAGE;
					$module = $_GET['module'];
					$task = $_GET['task'];
					$type  = $_GET['type'] ;
					$key = $_GET['key'] ;
					$slug =  (!empty($_GET['slug']))?$_GET['slug']:0 ;
					$status =(!empty($_GET['status']))?$_GET['status']:1 ;
					$search = (!empty($_GET['search']))?$_GET['search']:'';
					$filter = 'FILTER_FUNCTON'; //(!empty($_GET['filter']))?$_GET['filter']:'';
					$order = (!empty($_GET['order']))?$_GET['order']:'' ;
					$separate = (!empty($_GET['separate']))?$_GET['separate']:0 ;
					$pagenate =  (!empty($_GET['paginate']))?$_GET['paginate']:0 ;
					$page =  (!empty($_GET['page']))?$_GET['page']:1 ;
					$length =  (!empty($_GET['length']))?$_GET['length']:10 ;
					$count =  (!empty($_GET['count']))?$_GET['count']:0 ;
					$data_key =  (!empty($_GET['data_key']))?$_GET['data_key']:$module;
					$_POST['search_key'] = addslashes($_GET['user_search_key']);
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
					$array_json = array(
						'DATA'=>array($data_key=>$_DATA[$data_key]),
						'COUNT'=>array($data_key=>$_DATA['COUNT'][$data_key]),
						'TOTALPAGE'=>array($data_key=>$_DATA['TOTALPAGE'][$data_key]),
						'PAGE'=>array($data_key=>$_DATA['PAGE'][$data_key])
					);
					echo json_encode($array_json);
				break ;
				// custom function
			case 'getCountReviewByLocation':
					$_DATA['getCountReviewByLocation']= $oModule->getCountReviewByLocation();
				break;
			case 'addToCompare':
					echo 'add to compare';
					if($_GET['type']=='add'){
						$_SESSION['reviewCompareID'][$_GET['compare_id']] = $_GET['compare_id'] ;
					}
					if($_GET['type']=='remove'){
						unset($_SESSION['reviewCompareID'][$_GET['compare_id']]);
					}
					
				//	print_r($_SESSION['reviewCompareID']) ;
					
				break;
			case 'getReviewCompare':
					$language =  LANG ;
					$module = 'catalogs';
					$task = 'getReviewCompare';
					$type  = 'one' ;
					$key = $_SESSION['reviewCompareID'];
					$slug =  0 ;
					$status =1 ;
					$search = '';
					$filter = '';
					$order = '' ;
					$separate = 0 ;
					$pagenate =  0 ;
					$page =  1 ;
					$length =  10 ;
					$count =  0 ;
					$data_key =  'compare' ;
					
					if(is_array($key)&&!empty($key)){
						$keys = $key ;
						foreach($keys as $key){
							$_DATA[$data_key][$key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
						 if($separate){
									$listQueryData =$_DATA[$data_key][$key] ;
									$_DATA[$data_key][$key] = NULL ;
									foreach( $listQueryData as $kk=>$val){
										$_DATA[$data_key][$key][$val['slug']] = $val ;
									}
							}
							if($count){
									if(!empty($key)){
										$_DATA['COUNT'][$data_key][$key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
										$_DATA['TOTALPAGE'][$data_key][$key]  = ceil((int)$_DATA['COUNT'][$data_key]/$length);
										$_DATA['PAGE'][$data_key][$key]  =$page ;
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
				break;
			
	}// switch
}// if isset
?>