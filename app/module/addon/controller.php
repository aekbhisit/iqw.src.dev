<?php
if(empty($_SESSION)) {
	session_start();
}
$oUsers = new Users('users');
// config module
$params = array(
	'module'=>'addon',
 	'table'=>'addon_stat_project',
 	'primary_key'=>'id',
	'parent_table'=>NULL,
	'parent_translate_table'=>NULL,
	'parent_primary_key'=>NULL,
	'translate_table'=>NULL,
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE
);
$oCategories = new Addon($params);
$params = array(
	'module'=>'addon',
 	'table'=>'addon_stat_project',
 	'primary_key'=>'id',
	'parent_table'=>NULL,
	'parent_translate_table'=>NULL,
	'parent_primary_key'=>NULL,
	'translate_table'=>NULL,
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE
);
$oModule = new Addon($params);
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
			$all_project = (int)addslashes($_POST['all_project']);
			$ongoing_roof = (int)addslashes($_POST['ongoing_roof']);
			$ongoing_ground = (int)addslashes($_POST['ongoing_ground']);	
			$oModule->updateData($all_project,$ongoing_roof,$ongoing_ground);
		break;
// 		case 'setStatus':
// 			$id = addslashes($_GET['id']);
// 			$status = addslashes($_GET['status']);
// 			$oModule->updateStatus($id,$status);
// 		break;
// 		case 'setMove':
// 			$id = addslashes($_GET['id']);
// 			$type = addslashes($_GET['type']);
// 			$oModule->moveRow($id,$type);
// 		break;
// 		case 'setDelete':
// 			$id = addslashes($_GET['id']);
// 			$oModule->deleteData($id);
// 		break;
// 		case 'loadLanguages':
// 			$lang = $oModule->getLanguage();
// 			echo json_encode($lang);
// 		break;
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
		case 'getDataInFinds':
			$columns = array('id','name','category_id','mdate','sequence');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart= $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0= $_GET['iSortCol_0'];
			$sSortDir_0= $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by $oModule->table.".$columns[$iSortCol_0].' '.$sSortDir_0;
			}else{
				$orderby = " order by ".$columns[4].' '.$sSortDir_0;
			}
			$sSearch= $_GET['sSearch']; 
			if(!empty($sSearch)){
				$search = " WHERE ( $oModule->table.name like '%$sSearch%' or  $oModule->table.slug like '%$sSearch%') ";
			}
			$data = $oModule->getAll($search,$orderby,$limit);
			$iTotal = $oModule->getSize();
			$iFilteredTotal = count($data);
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
					$showname = $value['name'].'<input name="showName_'.$value['id'].'" id="showName_'.$value['id'].'" type="hidden" value="'.$value['name'].'" />'.'<input name="showSlug_'.$value['id'].'" id="showSlug_'.$value['id'].'" type="hidden" value="'.$value['slug'].'" />';
					$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['category'],3=>$iconbar,4=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
// 		    case "loadFindOneInit":
// 						$id = addslashes($_GET['id']);
// 						$data = $oModule->getOne($id);
// 						$data['name']=htmlspecialchars_decode($data['name'],ENT_QUOTES);
// 						$data['meta_key']=htmlspecialchars_decode($data['meta_key'],ENT_QUOTES);
// 						$data['meta_description']=htmlspecialchars_decode($data['meta_description'],ENT_QUOTES);
// 						echo json_encode($data,true);
// 				break;	
// 				case 'loadFindCategoryInit':
// 						$id = addslashes($_GET['id']);
// 						$data = $oCategories->getCategory($id);
// 						$data['name']=htmlspecialchars_decode($data['name'],ENT_QUOTES);
// 						$data['description']=htmlspecialchars_decode($data['description'],ENT_QUOTES);
// 						echo json_encode($data,true);
// 				break;
// ////////////////  reorder function /////////////
// 				case 'loadCategoriesFilter':
// 					$categories = $oCategories->getCategoriesTreeAll();
// 					$options = '<option value="0">--หมวดหมู่ทั้งหมด--</option>';
// 					$cnt =1 ;
// 					if(!empty($categories)){
// 						foreach($categories as $c){
// 							$indent = '';
// 							if($c['level']>0){
// 								if($c['level']>1){
// 									$indent =  str_pad($indent,$c['level']-1,'-');
// 								}
// 								$options .= '<option value="'.$c['id'].'" >'.$indent.$c['name'].'</option>';
// 							}
// 							}
// 					 }
// 					echo $options ;
// 				break;
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