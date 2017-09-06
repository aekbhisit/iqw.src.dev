<?php  
session_start();
$oUsers = new Users('users');
$oModule = new Galleries('galleries');
if(isset($_GET['task'])){
	$task = $_GET['task'] ;
	switch($task){
///  galleries-form.html
			case 'galleriesFormInit':
				if($_GET['mode']=='edit'){
						$id = addslashes($_GET['id']);
						$data = $oModule->getGalleries($id);
						echo json_encode($data);
					}
				break ;
			case 'galleriesFormTranslateInit':
						$id = addslashes($_GET['id']);
						$lang = addslashes($_GET['lang']);
						$data = $oModule->getGalleriesTranslate($id,$lang);
						echo json_encode($data);
				break ;
			case 'getGalleriesData':
					$columns = array('id','name','mdate','sequence');
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
						$search =  " WHERE ( $oModule->table.name like '%$sSearch%' or  $oModule->table.slug like '%$sSearch%' or  $oModule->table.images like '%$sSearch%') " ;
					}
					$galleries = $oModule->getGalleriesAll($search,$orderby,$limit);
					//print_r($categories);
					$iTotal = $oModule->getGalleriesSize() ;
					 $iFilteredTotal =  count($galleries);
					$output = array(
							"sEcho" => intval($_GET['sEcho']),
							"iTotalRecords" => $iTotal,
							"iTotalDisplayRecords" => $iTotal, // $iFilteredTotal,
							"aaData" => array()
						);
					$cnt = 1;
					if(!empty($galleries)){
					foreach($galleries as $key =>$value){
					if($value['status']==1){	
						$iconbar = '	<a href="javascript:void(0)" onclick="setGalleriesStatus('.$value['id'].',0)" ><img src="../images/icons/color/target.png" /></a>&nbsp;';
					}else{
						$iconbar = '	<a href="javascript:void(0)" onclick="setGalleriesStatus('.$value['id'].',1)" ><img src="../images/icons/color/stop.png" /></a>&nbsp;';
					}
											
						$iconbar .=	'<a href="javascript:void(0)" onclick="setDuplicate('.$value['id'].')"><img src="../images/icons/color/application_double.png" title="คัดลอก" /></a>  ';			
					if(SITE_TRANSLATE){
						$iconbar .=  '  <a href="javascript:void(0)" onclick="setTranslate('.$value['id'].')"><img src="../images/icons/color/style.png" title=แปลภาษา /></a>';
					}
						$iconbar .=	'  <a href="javascript:void(0)" onclick="setGalleriesDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" /></a>';
											
					$order = '<a href="javascript:void(0)" onclick="setGalleriesMove('.$value['id'].',\'up\')"><img src="../images/icons/black/16/arrow_up_small.png" /></a>
										 <a href="javascript:void(0)" onclick="setGalleriesMove('.$value['id'].',\'down\')" ><img src="../images/icons/black/16/arrow_down_small.png" /></a>
									  ';
									  
						$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
						
						$showname = '<a href="javascript:void(0)" onclick="setGalleriesEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';
						$value['category'] = (empty($value['category']))?'  - ':$value['category'] ;
						$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$value['mdate'],3=>$order,4=>$iconbar,5=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
						$cnt++ ;
						}
					}
						echo json_encode($output) ;
				break;
			case 'saveGalleries':
				$user = $oUsers->getAdminLoginUser();
				//print_r($_POST);
				$id = $_POST['galleries_id'];
				$name = addslashes($_POST['galleries_name']);	
				$content = addslashes($_POST['content']);	
				$sort =  $_POST['galleries_sort'];	
				$all_images_detail =  explode('||',$_POST['galleries_all_detail']);	
				$slug= $oModule->createSlug($name) ;
				$meta_key= $_POST['galleries_meta_key'];
				$meta_description= $_POST['galleries_meta_description'];
				$status= $_POST['galleries_status'];
				$images =  (!empty($_POST['galleries_images']))?$_POST['galleries_images']:'';
				//echo $images ;
				$all_images_data = array();
				$sort_arr =  explode(',',$sort);
				$new_sort_list = array();
				foreach ($sort_arr as $sk=>$sv){
						$new_sort_list[(int)$sv] = $sk ;
				}
				
				$detail_arr=array();
				foreach($all_images_detail as $dk=>$dv){
					$arr = explode('::',$dv);
					$detail_arr[$arr[0]]=  array('src'=>$arr[1],'title'=>$arr[2],'description'=>$arr[3]) ;
				}
				
				$img_arr = explode(',',$images);
				$img_cnt =  0 ;
				$new_img_arr =array();
				foreach($img_arr  as $img_src){
					if(!empty($img_src)){
						$new_img_arr[$img_cnt] =  $img_src ;
						$img_cnt++;
					}
				}
				$sorted_arr =array();
				foreach(	$new_sort_list as $nk=>$nv){
					$title = (isset($detail_arr[$nv]['title']))?$detail_arr[$nv]['title']:'';
					$description = (isset($detail_arr[$nv]['description']))?$detail_arr[$nv]['description']:'';
					$sorted_arr[(int)$nk]=array('id'=>$nk,'src'=>$new_img_arr[(int)$nv],'title'=>$title,'description'=>$description);
				}
				ksort($sorted_arr);
				$json_img = json_encode($sorted_arr);
				echo $json_img ;
				if(empty($id)){		
					$oModule->insertGalleries($name,$content,$slug,$json_img,$params,$meta_key,$meta_description,$user['id'],$status) ;
				}else{
					$oModule->updateGalleries($id,$name,$content,$slug,$json_img,$params,$meta_key,$meta_description,$user['id'],$status) ;
				}
				break;
				case "saveTranslate":
					$id = $_POST['galleries_id'];
					$lang =  $_POST['translate_language'];
					$name = addslashes($_POST['galleries_name']);	
					$content = addslashes($_POST['content']);	
					$sort =  $_POST['galleries_sort'];	
					$all_images_detail =  explode('||',$_POST['galleries_all_detail']);	
				//	$slug= $oModule->createSlug($name) ;
					$meta_key= $_POST['galleries_meta_key'];
					$meta_description= $_POST['galleries_meta_description'];
					$images =  (!empty($_POST['galleries_images']))?$_POST['galleries_images']:'';
					$all_images_data = array();
					$sort_arr =  explode(',',$sort);
					$new_sort_list = array();
					foreach ($sort_arr as $sk=>$sv){
						if((int)$sv>0){
							$new_sort_list[(int)$sv] = $sk ;
						}
					}
					
					$detail_arr=array();
					foreach($all_images_detail as $dk=>$dv){
						$arr = explode('::',$dv);
						$detail_arr[$arr[0]]=  array('src'=>$arr[1],'title'=>$arr[2],'description'=>$arr[3]) ;
					}
					
					$img_arr = explode(',',$images);
					$sorted_arr =array();
					foreach(	$new_sort_list as $nk=>$nv){
						$title = (isset($detail_arr[$nv]['title']))?$detail_arr[$nv]['title']:'';
						$description = (isset($detail_arr[$nv]['description']))?$detail_arr[$nv]['description']:'';
						$sorted_arr[(int)$nk]=array('id'=>$nk,'src'=>$img_arr[(int)$nv-1],'title'=>$title,'description'=>$description);
					}
					ksort($sorted_arr);
					$json_img = json_encode($sorted_arr);
					 $oModule->saveTranslate($lang, $id ,$name,$content,$json_img,$params,$meta_key,$meta_description);
				break ;
				case 'duplicate':
					$user = $oUsers->getAdminLoginUser();
					$id = $_GET['id'];
					$oModule->duplicateData($id,$user['id']);
				break;
				case 'setGalleriesStatus':
					$id = addslashes($_GET['id']);
					$status = addslashes($_GET['status']);
					$oModule->updateGalleriesStatus($id,$status);
				break;
				case 'setGalleriesMove':
					$id = addslashes($_GET['id']);
					$type = addslashes($_GET['type']);
					$oModule->moveRow($id,$type);
				break;
				case 'setGalleriesDelete':
					$id = addslashes($_GET['id']);
					$oModule->deleteGalleries($id);
				break;
				case 'loadLanguages':
					$lang = $oModule->getLanguage();
					echo json_encode($lang);
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