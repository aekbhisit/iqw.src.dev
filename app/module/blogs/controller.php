<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
// config module
$params_category = array(
	'module'=>'blogs_categories',
 	'table'=>'blogs_categories',
	'table_translate'=>'blogs_categories_translate',
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE
);
$oCategories = new Blogs($params_category);
$params = array(
	'module'=>'blogs',
 	'table'=>'blogs',
	'parent_table'=> 'blogs_categories',
	'parent_table_translate'=> 'blogs_categories_translate',
	'parent_primary_key'=> 'id',
	'table_translate'=>'blogs_translate',
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE 
);
$oModule = new Blogs($params);
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		//  categories task   
		case 'getCategoriesData':
			$columns = array('','name','level','mdate','lft');
			$limit = '';
			$orderby ='' ;
			$search = '';
			$iDisplayLength = $oCategories->setInt($_GET['iDisplayLength']);
			$iDisplayStart = $oCategories->setInt($_GET['iDisplayStart']);
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $oCategories->setInt($_GET['iSortCol_0']);
			$sSortDir_0 = $oCategories->setString($_GET['sSortDir_0']);
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oCategories->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
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
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
						}else{
							$iconbar = '<a href="javascript:void(0)" onclick="setCategoryStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>;';
						}
						$iconbar .=	'<a href="javascript:void(0)" onclick="setCategoryDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>';
						if(SITE_TRANSLATE){
							$iconbar .= '<a href="javascript:void(0)" onclick="setCategoryTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title=แปลภาษา /></a>';
						}
			            $iconbar .= '<a href="javascript:void(0)" onclick="setCategoryDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';
						$order = '<a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'left\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a><a href="javascript:void(0)" onclick="setCategoryMove('.$value['id'].',\'right\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>';
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
			$cnt =1 ;
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
						$data['content']=htmlspecialchars_decode($data['content'],ENT_QUOTES);
						$data['meta_key']=htmlspecialchars_decode($data['meta_key'],ENT_QUOTES);
						$data['meta_description']=htmlspecialchars_decode($data['meta_description'],ENT_QUOTES);
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
					if($value['status']==1){	
						$iconbar = '	<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
					}else{
						$iconbar = '	<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
					}
											
					$iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>  ';			
					if(SITE_TRANSLATE){				
							$iconbar .=	'  <a href="javascript:void(0)" onclick="setTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title="แปลภาษา" /></a>';
					}
					
					$iconbar .='    <a href="javascript:void(0)" onclick="setDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>	';
											
					$order = '<a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" title="ขึ้น" /></a>
										 <a href="javascript:void(0)" onclick="setMove('.$value['id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" title="ลง" /></a>
									  ';
					$order = '<input name="sequence_'.$value['id'].'" id="sequence_'.$value['id'].'" type="text"  value="'.$value['sequence'].'" title="'.$value['sequence'].'" style="width:40px;" onblur="switchDataOrder('.$value['id'].')" />';				  
					
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						
					$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';
					$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
					$output["aaData"][] = array(0=>$row_chk,1=>$showname ,2=>$value['category'],3=>$value['mdate'],4=>$order,5=>$iconbar,6=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
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
					$slug= $oModule->createSlug($_POST['name']) ;
				}else{
					$slug=$_POST['slug'] ;
				}
				$content= htmlspecialchars($_POST['content'],ENT_QUOTES);
				$meta_key= htmlspecialchars($_POST['meta_key'],ENT_QUOTES);
				$meta_description= htmlspecialchars($_POST['meta_description'],ENT_QUOTES);
				$image =  (!empty($_POST['image']))?$_POST['image']:'';
				$status= $_POST['status'];
				$slug = urldecode($slug);
				if(empty($id)){		
					$oModule->insertData($category_id,$name,$slug,$content,$image,$params,$meta_key,$meta_description,$user['id'],$status);
				}else{
					$oModule-> updateData($id,$category_id,$name,$slug,$content,$image,$params,$meta_key,$meta_description,$user['id'],$status);
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
							$lang = $_POST['translate_language'];
							$name = $_POST['name'];	
							$content= $_POST['content'];
							$image= $_POST['image'];
							$meta_key= $_POST['meta_key'];
							$meta_description= $_POST['meta_description'];
							$oModule->saveTranslate( $lang,$id,$name,$content,$image,'',$meta_key,$meta_description) ;
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
										$_DATA['PAGE'][$data_key]  = $page;
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