<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
//$oMailer = new Mailer();
//$oPDF = new PDF();
/////// orders object ////////////////////////////////////////////
$params = array(
	'module'=>'orders',
 	'table'=>'orders',
	'parent_primary_key'=> 'id',
	'site_language'=>SITE_LANGUAGE,
	'is_translate'=>false
);
$oModule = new Orders('orders',$params );
//////////////////////////////////////////////////////////////////////
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		case 'formInit':
			if($_GET['mode']=='edit'){
				$id = $oModule->setInt($_GET['id']);
				$data = $oModule->getOne($id);
				$data['name'] = $oModule->setString($data['name']);
				$data['meta_key'] = $oModule->setString($data['meta_key']);
				$data['meta_description'] = $oModule->setString($data['meta_description']);
				echo json_encode($data);
			}
		break;
		case 'getData':
			$columns = array('id','buyer','items','grand_total','cdate','paymentStatus');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart= $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $_GET['iSortCol_0'];
			$sSortDir_0 = $_GET['sSortDir_0'];
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
				"aaData" => array()
			);
			$cnt = 1;
			if(!empty($data)){
				foreach($data as $key =>$value){
					if($value['paymentStatus']==1){	
						$iconbar_payment = '<a href="javascript:void(0)" onclick="setPaymentStatus('.$value['id'].',0)"><img src="../images/icons/color/target.png" title="เปิด" />&nbsp;'.$value['paymentMethod'].'</a>';
					}else{
						$iconbar_payment = '<a href="javascript:void(0)" onclick="setPaymentStatus('.$value['id'].',1)"><img src="../images/icons/color/stop.png" title="ปิด" />&nbsp;'.$value['paymentMethod'].'</a>';
					}
					$iconbar ='<a href="javascript:void(0)" onclick="setDeleteOrder('.$value['id'].')"><img src="../images/icons/color/cross.png" title="ลบ" /></a>';
					$row_chk = '<input name="table_select_'.$value['id'].'" id="table_select_'.$value['id'].'" class="table_checkbox" type="checkbox" value="'.$value['id'].'" />&nbsp;'.($cnt+$iDisplayStart);
					// detail table
					$member_table = '
					  <table class="da-table da-detail-view"><tbody>
						  <tr><th>Member</th><td>John Doe</td></tr>
	                      <tr><th>Category</th><td>Fashion</td></tr>
    	                   <tr><th>Email</th><td>john@doe.org</td></tr>
        	               <tr><th>Service Name</th><td>Dandelion Shop</td></tr> 
						   <tr><th>Short Description</th><td>We sell dandelions</td></tr>
                           <tr><th>Address</th><td><span class="null">N/A</span></td></tr>
        	        	</tbody></table>
					';
					$showname = '<a href="javascript:void(0)" onclick="showBuyer('.$value['id'].')"> '.$value['buyer'].' <img src="../images/icons/color/monitor.png" title="ดูรายละเอียด" /><div id="da-dialog-member-'.$value['id'].'" class="showMemberDialog" style="display:none;"></div></a>';
					$showcoupon = '<a href="javascript:void(0)" onclick="showCoupon('.$value['id'].')">'.$value['items'].' <img src="../images/icons/color/monitor.png" title="ดูรายละเอียด" /><div id="da-dialog-orderdetail-'.$value['id'].'" class="showOrderDetailDialog" style="display:none;"></div></a>';	
					$value['category'] = (empty($value['category']))?' - ':$value['category'];
					$output["aaData"][] = array(0=>$row_chk,1=>$showname,2=>$showcoupon,3=>$value['sign_left'].$value['grand_total'].$value['sign_right'],4=>$value['cdate'],5=>$iconbar_payment,6=>$iconbar,7=>$value['id'] ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
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
			$content = $oModule->setString($_POST['content']);
			$javascript = $oModule->setString($_POST['javascript']);
			$css = $oModule->setString($_POST['css']);
			$meta_key = $oModule->setString($_POST['meta_key']);
			$meta_description = $oModule->setString($_POST['meta_description']);
			$status = $oModule->setInt($_POST['status']);
			$slug = urldecode($slug);
			if(empty($id)){		
				$oModule->insertData($category_id,$name,$slug,$content,$params,$javascript,$css,$meta_key,$meta_description,$user['id'],$status);
			}else{
				$oModule->updateData($id,$category_id,$name,$slug,$content,$params,$javascript,$css,$meta_key,$meta_description,$user['id'],$status);
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
		case 'deleteOrderData':
			$id = $oModule->setInt($_GET['id']);
			$oModule->deleteOrderData($id);
		break;
		case 'loadLanguages':
			$lang = $oModule->getLanguage();
			echo json_encode($lang);
		break;
		case 'updatePaymentStatus':
			$order_id = $oModule->setInt($_GET['order_id']);
			$payment_result = 'update by admin ';
			$status = $oModule->setInt($_GET['status']);
			$oModule->confirmPayment($order_id,$payment_result,$status);
			$oModule->updateStatus($order_id,$status);
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
		case 'getMemberDetail':
			$order_id = $oModule->setInt($_GET['order_id']);
			$member = $oModule->getBuyerDetail($order_id);
			$table ='<table class="da-table da-detail-view"><tbody>
			<tr><th>ชื่อ</th><td>'.$member['member_detail']['name'].'</td></tr>
			<tr><th>อีเมล์</th><td>'.$member['member_detail']['email'].'</td></tr>
			<tr><th>มือถือ</th><td>'.$member['member_detail']['mobile'].'</td></tr>
			<tr><th>โทร</th><td>'.$member['member_detail']['tel'].'</td></tr>
			<tr><th>โทรสาร</th><td>'.$member['member_detail']['fax'].'</td></tr>
			<tr><th>ที่อยู่</th><td>'.$member['member_detail']['address'].'</td></tr>
			<tr><th>จังหวัด</th><td>'.$member['member_detail']['state_name'].'</td></tr>
			<tr><th>ประเทศ</th><td>'.$member['member_detail']['country_name'].'</td></tr>
			<tr><th>รหัสไปรษณีย์</th><td>'.$member['member_detail']['zipcode'].'</td></tr>
			</tbody></table>';
			echo $table;
		break;
		case 'getOrderDetail':
			$order_id = $oModule->setInt($_GET['order_id']);
			$orderDetail = $oModule->getOrderDetail($order_id);
			if(!empty($orderDetail)){
				foreach($orderDetail as $od_key => $od){
					$orderDetail[$od_key]['catalogVal'] = $oCatalogModule->getOne($od['catalog_id']);
				}
			}
			$table = '<div class="da-panel-content">
			<table class="da-table">
				<thead>
					<tr>
						<th>#</th>
						<th>รายการ</th>
						<th align="center">จำนวน</th>
						<th>ราคา</th>
						<th>ส่วนลด</th>
						<th>รวม</th>
						<th>ลบ</th>
					</tr>
				</thead>
				<tbody>';
			$cnt = 0;
			foreach($orderDetail as $od){
				$cnt++;
				$table .= '	
				<tr id="order_detail_row_'.$od['id'].'">
					<td>'.$cnt.'</td>
					<td>'.$od['catalogVal']['name'].'</td>
					<td align="center"><img src="../images/icons/color/minus-circle-icon.png" onclick="updateOrderDetailQuantity('.$od['id'].','.$od['order_id'].',\'minus\')" style="cursor:pointer" /><input name="quantity_'. $od['id'].'" id="quantity_'. $od['id'].'"  type="text" value="'. $od['quantity'].'" size="5" readonly="readonly" /><img src="../images/icons/color/add-icon.png" onclick="updateOrderDetailQuantity('.$od['id'].','.$od['order_id'].',\'plus\')" style="cursor:pointer" /></td>
					<td>'.$od['price'].'</td>
					<td>'.$od['discount'].'</td>
					<td><span id="orderDetailTotal_'.$od['id'].'">'. $od['total'].'</span></td>
					<td class="da-icon-column">
						<a href="javascript:void(0)" onclick="deleteOrderDetail('.$od['id'].','.$od['order_id'].')"><img src="../images/icons/color/cross.png" /></a>
					</td>
				</tr>';
			}
			$table .= ' </tbody>
			</table></div>';
			echo $table;
		break;
		case 'updateOrderDetailQuantity':
			$order_detail_id = $oModule->setInt($_GET['order_detail_id']);
			$order_id = $oModule->setInt($_GET['order_id']);
			$type = $oModule->setString($_GET['type']); 
			if($type=='plus'||$type=='minus'){
				$update_result = $oModule->updateOrderDetailQuantity($order_detail_id,$order_id,$type);
				$result['result']=true;
				$result['quantity']=$update_result['quantity'];
				$result['total']=$update_result['total'];
			}else{
				$result['result']=false;
			}
			echo json_encode($result);
		break;
		case "deleteOrderDetail":
			$order_detail_id = $oModule->setInt($_GET['order_detail_id']);
			$order_id = $oModule->setInt($_GET['order_id']);
			if($oModule->deleteOrderDetail($order_detail_id,$order_id)){
				$result['result']=true;
			}else{
				 $result['result']=false;
			}
			echo json_encode($result);
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
						foreach($listQueryData as $kk=>$val){
							$_DATA[$data_key][$val['slug']] = $val;
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
				$key = $oModule->setInt($key);
				$_DATA[$data_key] = $oModule->find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oCategories);
				if($count){
					if(!empty($key)){
						$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
					}else{
						$_DATA['COUNT'][$data_key] = $oModule->findcount($type,$key,$slug,$status,$language,$search,$filter,$oCategories);
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
		///// front end case
		case 'getTopSeller':
		 	$data['topsaller'] = $oModule->getTopSeller();
		break;
		case 'setCatalogOrder':
			if(!empty($_SESSION['memberLogin'])&&!empty($_SESSION['memberLogin']['id'])){
				$member_id = $_SESSION['memberLogin']['id'];
				$is_member = 1;
				$is_order_address = 0;
				if(!empty($_GET['countItem'])){
					$countItem = $oModule->setInt($_GET['countItem']);
					$vat_rate = 0; 
					$vat_total = 0;
					$shipping_fee = 0;
					$sub_total = 0;
					$discount = 0;
					$grand_total = 0;
					$currency_id = 1;
					$status = 0;
					$order_id = $oModule->insertCatalogOrder($vat_rate,$vat_total,$shipping_fee,$sub_total,$discount,$grand_total,$currency_id,$status);
					for($i=1;$i<=$countItem;$i++){
						$catalog_id = $oModule->setInt($_POST['couponID_item'.$i]);
						$quantity = (!empty($_POST['quantityCoupon_'.$i]))?$_POST['quantityCoupon_'.$i]:1;
						$catalogVal = $oCatalogModule->getOne($catalog_id);
						$catalogAttrVal = $oCatalogModule->getCatalogAttrValueAndDefaultValue($catalog_id);
						$price = (float)$catalogAttrVal['price'];
						$discount = 0;
						$discount_percentage = 0;
						$payment_id = $oModule->setInt($_GET['paymentMethod']);
						$payment_total = (float)$catalogAttrVal['price'];
						$payment_result = '';
						$payment_status = 0;
						// order
						$sub_total = $price*$quantity;
						$grand_total += $sub_total;
						if(!empty($order_id)){
							$order_detail_id = $oModule-> insertCatalogsOrderDetail($order_id,$catalog_id,$quantity,$price,$discount,$discount_percentage,$sub_total);
							$oModule->insertOrdersCoupon($order_id,$order_detail_id,0);
						}
						$att_catalog_name[$catalog_id] = $catalogVal['name'];  
						unset($_SESSION['orderList'][$member_id ][$catalog_id]);
					}// for
					$oModule->insertOrdersItems($order_id,$countItem);
					$oModule->insertCatalogOrderBuyer($order_id,$is_member,$member_id,$is_order_address);
					$oModule->insertCatalogOrderPayment($order_id,$payment_id,$payment_total,$payment_result,$payment_status);
					$oModule->updateCatalogOrder($order_id,$vat_rate,$vat_total,$shipping_fee,$sub_total,$discount,$grand_total,$currency_id,$status);
					if(isset($_SESSION['order_result'][$member_id])){
						unset($_SESSION['order_result'][$member_id]);
					}
					$_SESSION['order_result'][$member_id] = array(
						'result'=>'completed',
						'order_id'=>$order_id,
						'payment_id'=>$payment_id,
						'payment_status'=>$payment_status,
						'grand_total'=>$grand_total,
						'member_id'=>$member_id,
						'items'=>$att_catalog_name,
						'time'=>time()
					);
				}// if count item
				echo json_encode($_SESSION['order_result'][$member_id]);
			}else{ // if not member
				echo json_encode(array('result'=>'failed'));
			}
		break;
		case 'confirmPayment':
			if(!empty($_SESSION['memberLogin'])&&!empty($_SESSION['memberLogin']['id'])){
				$member_id = $oModule->setInt($_SESSION['memberLogin']['id']);
				$order_id = $oModule->setInt($_POST['order']);
				$payment_result['paid_time'] = $_POST['date'];
				$payment_result['paid_total'] = $_POST['total'];
				$payment_result['paid_bank_id'] = $_POST['bank'];
				$payment_result = json_encode($payment_result);
				$oModule->confirmPayment($order_id,$payment_result,1);
			}
		break;
		case 'setAddTempOrder':
			$catalog_id = $oCatalogModule->setInt($_GET['catalog_id']);
			if(!empty($catalog_id)){
				$data['catalog_id'] = $oCatalogModule->setInt($catalog_id);
				$catalogVal = $oCatalogModule->getOne($catalog_id);
				$catalogAttrVal = $oCatalogModule->getCatalogAttrValueAndDefaultValue($catalog_id);
				$data['name'] = $catalogVal['name'];
				$data['vat_rate'] = 0; 
				$data['vat_total'] = 0;
				$data['shipping_fee'] = 0;
				$data['sub_total'] = (float)$catalogAttrVal['price'];
				$data['total'] = (float)$catalogAttrVal['price'];
				$data['price'] = (float)$catalogAttrVal['price'];
				$data['discount'] = 0;
				$data['discount_percentage'] = 0;
				$data['grand_total'] = $catalogAttrVal['price'];
			  	$_SESSION['orderList'][$_SESSION['memberLogin']['id']][ $data['catalog_id']] = $data;
			  }
		break;
		case 'getMyCoupon':
			$member_id = $_SESSION['memberLogin']['id'];
			if(!empty($member_id)){
				$mycoupon = $oModule->getMycoupon($member_id); 
				$data['mycoupon'] = array();
				$cnt = 0 ;
				if(!empty($mycoupon)){
					foreach($mycoupon as $d_key=>$d_val){
						if(!empty($d_val['order_detail'])){
							foreach($d_val['order_detail'] as $od_key=>$od_val){
								$catalog = $oCatalogModule->getOne($od_val['catalog_id']); 
								$data['mycoupon'][$cnt]['name'] = $catalog['name'];
								$data['mycoupon'][$cnt]['quantity'] = $od_val['quantity'];
								$data['mycoupon'][$cnt]['total'] = $od_val['total'];
								$data['mycoupon'][$cnt]['coupon_id'] = $od_val['coupon_id'];
								$data['mycoupon'][$cnt]['coupon_used'] = $od_val['coupon_used'];
								$data['mycoupon'][$cnt]['status'] = $d_val['status'];
								$cnt++;
							}
						}
					}
				}
			}
		break;
		case 'getYourHaveCoupon':
			$member_id = $_SESSION['memberLogin']['id'];
			if(!empty($member_id)){
				$mycoupon = $oModule->getMycoupon($member_id); 
				$data['mycoupon'] = array();
				$cnt = 0;
				if(!empty($mycoupon)){
					foreach($mycoupon as $d_key=>$d_val){
						if(!empty($d_val['order_detail'])){
							foreach($d_val['order_detail'] as $od_key=>$od_val){
								$cnt++;
							}
						}
					}
				}
			}
			echo $cnt;
		break;
	}// switch
}// if isset
?>