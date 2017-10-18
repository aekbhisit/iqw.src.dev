<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
// config module
$oUsers = new Users('users');
$params = array(
	'module'=>'newsletters',
 	'table'=>'newsletters',
	'parent_table'=>'newsletters_categories',
	'parent_table_translate'=> 'newsletters_categories_translate',
	'parent_primary_key'=>'id',
	'table_translate'=>'newsletters_translate',
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>SITE_TRANSLATE
);
$oModule = new Newsletters($params);
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		case 'getData':
			$columns = array('id','email','cdate','id','id');
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
			$sSearch = $oModule->setString($_GET['sSearch']); 
			if($sSearch=='undefined'){
				$sSearch = '';
			}
			if(!empty($sSearch)){
				$search = " WHERE ( $oModule->table.email like '%$sSearch%' ) ";
				$category_id = $oModule->setInt($_GET['filterCategoryID']);
				if($category_id>0){
					$search .= " AND $oModule->table.category_id = $category_id ";
				}
			}else{
				$category_id = $oModule->setInt($_GET['filterCategoryID']);
				if($category_id>0){
					$search = " WHERE $oModule->table.category_id = $category_id ";
				}
			}
			$data = $oModule->getAll($search,$orderby,$limit);
			$iTotal = $oModule->getSize() ;
			$iFilteredTotal =  count($data);
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
						$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',0)"><img src="../images/icons/color/target.png" title="เปิด" /></a>&nbsp;';
					}else{
						$iconbar = '<a href="javascript:void(0)" onclick="setStatus('.$value['id'].',1)"><img src="../images/icons/color/stop.png" title="ปิด" /></a>&nbsp;';
					}
					$iconbar .= '<a href="javascript:void(0)" onclick="setDelete('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';		  
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					// if empty category
					$value['category'] = (empty($value['category']))?' - ':$value['category'];
					$showname = '<a href="javascript:void(0)" onclick="setEdit('.$value['id'].')" ><img src="../images/icons/color/application_edit.png" title="แก้ไข" /> '.$indent.$value['name'].'</a>';
					$output["aaData"][] = array(0=>$row_chk,1=>$value['email'],2=>$value['cdate'],3=>$iconbar,4=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'saveData':
			$email = $oModule->setString($_GET['email']);
			$oModule->insertData($email);
		break;
		case 'setStatus':
			$id = $oModule->setInt($_GET['id']);
			$status = $oModule->setInt($_GET['status']);
			$oModule->updateStatus($id,$status);
		break;
		case 'setStatusByEmail':
			$email = $oModule->setString($_GET['email']);
			$status = $oModule->setInt($_GET['status']);
			$oModule->updateStatus($email,$status);
		break;
		case 'setDelete':
			$id = $oModule->setInt($_GET['id']);
			$oModule->deleteData($id);
		break;
		case 'exportCSV':
			$data = $oModule->getExportAll();
			$fp = fopen('../media/Files/newsletters.csv','w');
			fputcsv($fp,$data);
			fclose($fp);
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header("Content-Disposition: attachment; filename=\"../media/Files/newsletters.csv\"");
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Content-Length: ' . filesize('../media/Files/newsletters.csv'));
			ob_clean();
			flush();
			$file = @fopen('../media/Files/newsletters.csv',"rb");
			if ($file) {
				while(!feof($file)) {
					print(fread($file, 1024*8));
					flush();
				}
			}
			@fclose($file);
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
							$_DATA['COUNT'][$data_key]= $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
							$_DATA['TOTALPAGE'][$data_key] = ceil((int)$_DATA['COUNT'][$data_key]/$length);
							$_DATA['PAGE'][$data_key] = $page;
						}
					}
				}
			}else{
				$key = $oModule->setInt($key);
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