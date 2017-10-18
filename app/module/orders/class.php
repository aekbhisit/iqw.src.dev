<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class Orders extends Database {
	var $module;
	public function __construct($module,$params=NULL){
		$this->module = (empty($params['table']))?$module:$params['table'];
		parent::__construct((empty($params['table']))?$module:$params['table']);
		if(isset($params['primary_key'])){
			$this->primary_key = $params['primary_key'];
		}
		if(isset($params['parent_table'])){
			$this->parent_table = $params['parent_table'];
		}
		if(isset($params['parent_translate_table'])){
			$this->parent_translate_table = $params['parent_translate_table'];
		}
		if(isset($params['parent_primary_key'])){
			$this->parent_primary_key = $params['parent_primary_key'];
		}
		if(isset($params['site_language'])){
			$this->site_language = $params['site_language'];
		}
		if(isset($params['is_translate'])){
		 	$this->is_translate = $params['is_translate'];
	 	}
		if(isset($params['translate_table'])){
			$this->translate_table = $params['translate_table'];
		}
	}
	public function getSize(){
		$this->sql = "select $this->primary_key from $this->table ";
		return $this->select('size');
	}
	public function getAll($search,$order,$limit){
		$this->sql = "SELECT orders.id as id, orders.grand_total,orders.cdate,members.name as buyer,members.id as member_id,orders_payment.payment_status as paymentStatus,payments.name as paymentMethod,currency.sign_left,currency.sign_right,orders_items.items from orders ";
		$this->sql .= " left join orders_items on orders.id=orders_items.order_id "; //catalogs.name as catalog_name,
		$this->sql .= " left join orders_buyer on orders.id=orders_buyer.order_id left join members on orders_buyer.member_id=members.id ";
		$this->sql .= " left join orders_payment on orders.id=orders_payment.order_id left join payments on orders_payment.payment_id = payments.id ";
		$this->sql .= " left join currency on orders.currency_id=currency.id ";
		$this->sql .= " $search $order $limit ";
		$this->select();
		return $this->rows;
	}
	public function getOne($id){
		$this->sql = "select * from $this->table where $this->table.$this->primary_key=$id ";
		$this->select();
		return  $this->rows[0];
	}
	public function getOrderAll($isOrderDetail=true){
		$this->sql = "select * from $this->table ";
		$this->select();
		$order = $this->rows;
		if($isOrderDetail){
			foreach($order as $k_order => $v_order){
				$order[$k_order]['detail'] = $this->getOrderDetailInOrder($v_order['id']);
			}
		}
		return $order;
	}
	public function getOrder($id,$isOrderDetail=true){
		$this->sql = "select * from $this->table where $this->table.$this->primary_key=$id ";
		$this->select();
		$order = $this->rows[0];
		if($isOrderDetail){
			$order['detail'] = $this->getOrderDetailInOrder($id);
		}
		return $order;
	}
	public function getOrderDetailOne($id){
		$this->sql = "select * from orders_detail where $this->table.$this->primary_key=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function getOrderDetailInOrder($id){
		$this->sql = "select * from orders_detail where $this->table.order_id=$id ";
		$this->select();
		return $this->rows;
	}
	public function getOrderBuyer($id){
		$this->sql = "select order_buyer from order_buyer where order_id=$id";
		$this->select();
		$buyer = $this->rows[0];
		if($buyer['is_order_address']){
			$this->sql = "select * from orders_address where order_id=$id ";
			$this->select();
			$buyer['address'] = $this->rows[0];
		}else{
			$this->sql = "select * from user_address where order_id=".$buyer['member_id'];
			$this->select();
			$buyer['address'] = $this->rows[0];
		}
		return $buyer;
	}
	public function insertOrder($vat_rate,$vat_total,$ship_fee,$sub_total,$discount,$grand_total,$currency_id,$status){
		$this->sql = "insert into $this->table (vat_rate,vat_total,ship_fee,sub_total,discount,grand_total,currency_id,status,mdate,cdate) ";
		$this->sql .= " values($vat_rate,$vat_total,$ship_fee,$sub_total,$discount,$grand_total,$currency_id,$status,NOW(),NOW()) ";
		$this->insert();
	}
	public function insertOrderDetail($order_id,$catalog_id,$quantity,$pirce,$discount,$discount_percentage,$total){
		$this->sql = "insert into $this->table (order_id,catalog_id,quantity,pirce,discount,discount_percentage,total) ";
		$this->sql .= " values($order_id,$catalog_id,$quantity,$pirce,$discount,$discount_percentage,$total) ";
		$this->insert();
	}
	public function insertOrderBuyer($order_id,$is_member,$member_id,$name,$address,$address1,$city,$state_id,$country_id,$zipcode,$email,$mobile,$tel,$fax){
		$this->sql = "insert into orders_buyer (order_id,is_member,member_id) ";
		$this->sql .=" values($order_id,$is_member,$member_id) ";
		$this->insert();
		if(!empty($name)&&!empty($address)){		
			$this->sql =" insert into orders_address (order_id,name,address,address1,city,state_id,country_id,zipcode,email,mobile,tel,fax) ";
			$this->sql .=" values($order_id,'$name','$address','$address1','$city',$state_id,$country_id,'$zipcode','$email','$mobile','$tel','$fax') ";
			$this->insert();
		}
	}
	public function updateOrderBuyer($id,$order_id,$is_member,$member_id,$name,$address,$address1,$city,$state_id,$country_id,$zipcode,$email,$mobile,$tel,$fax){
		$this->sql = "update orders_buyer set order_id=$order_id,is_member=$is_member,member_id=$member_id where id=$id ";
		$this->update();
		if(!empty($name)&&!empty($address)){		
			$this->sql = " update orders_address set order_id=$order_id,name='$name',address='$address',address1='$address1',city='$city',state_id=$state_id,country_id=$country_id,zipcode='$zipcode',email='$email',mobile='$mobile',tel='$tel',fax='$fax' where order_id=$id ";
			$this->update();
		}
	}
	public function updateOrder($id, $vat_rate,$vat_total,$ship_fee,$sub_total,$discount,$grand_total,$currency_id,$status){
		$this->sql = "update $this->table set vat_rate=$vat_rate,vat_total=$vat_total,ship_fee=$ship_fee,sub_total=$sub_total,discount=$discount,grand_total=$grand_total,currency_id=$currency_id,status=$status,mdate=NOW() where $this->primary_key=$id ";
		$this->update();
	}
	public function updateOrderDetail($id, $order_id,$catalog_id,$quantity,$pirce,$discount,$discount_percentage,$total){
		$this->sql = "update $this->table set order_id=$order_id,catalog_id=$catalog_id,quantity=$quantity,pirce=$pirce,discount=$discount,discount_percentage=$discount_percentage,total=$total where $this->primary_key=$id ";
		$this->update();
	}
	public function duplicateData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$data = $this->rows[0];
		$this->insertData($data['category_id'],$data['name'],$data['slug'],$data['content'],$data['params'],$data['javascript'],$data['css'],$data['meta_key'],$data['meta_description'],$user_id,$data['status']);
	}
	public function insertOrdersItems($order_id,$items){
		$this->sql = "insert into orders_items (order_id,items) values($order_id,$items) ";
		$this->insert();
	}
	public function updateOrdersItems($id,$order_id,$items){
		$this->sql = " update orders_items set items=$items where order_id=$order_id and id=$id ";
		$this->update();
	}
	public function insertOrdersCoupon($order_id,$order_detail_id,$used){
		$this->sql = "insert into orders_coupon (order_id,order_detail_id,used) values($order_id,$order_detail_id,$used) ";
		$this->insert();
	}
	public function updateOrdersCoupon($id,$used){
		$this->sql = " update orders_coupon set used=$used where id=$id ";
		$this->update();
	}
	public function deleteData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
	}
	public function deleteOrderData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
		$this->sql = "delete from orders_address where order_id=$id ";
		$this->delete();
		$this->sql = "delete from orders_detail where order_id=$id ";
		$this->delete();
		$this->sql = "delete from orders_buyer where order_id=$id ";
		$this->delete();
		$this->sql = "delete from orders_payment where order_id=$id ";
		$this->delete();
		$this->sql = "delete from orders_items where order_id=$id ";
		$this->delete();
		$this->sql = "delete from orders_coupon where order_id=$id ";
		$this->delete();
	}
	public function updateStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	public function find($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$order=NULL,$sort=NULL,$separate=false,$pagenate=false,$page=NULL,$length=10,$oParent=NULL){
		/* type
		1. one = fine one item by id
		3. all  =  fine all item and category
		4. category_all = fine  category_id
		6. category_one = find item in category 
		7. in_category = fine all and relation incateogry
		*/
		return $this->_find($type,$key,$slug,$status,$language,$search,$filter,$order,$sort,$separate,$pagenate,$page,$length,$oParent);
	}
	public function findcount($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$oParent=NULL){
		return $this->_findcount($type,$key,$slug,$status,$language,$search,$filter,$oParent); 
	}
	// frontend function 
	public function insertCatalogOrder($vat_rate,$vat_total,$shipping_fee,$sub_total,$discount,$grand_total,$currency_id,$status){
		$this->sql  = "insert into orders (vat_rate,vat_total,shipping_fee,sub_total,discount,grand_total,currency_id,status,mdate,cdate)";
		$this->sql .= " values($vat_rate,$vat_total,$shipping_fee,$sub_total,$discount,$grand_total,$currency_id,$status,NOW(),NOW())";
		$this->insert();
		return $this->insert_id();
	}
	public function updateCatalogOrder($id,$vat_rate,$vat_total,$shipping_fee,$sub_total,$discount,$grand_total,$currency_id,$status){
		$this->sql = "update orders set vat_rate=$vat_rate,vat_total=$vat_total,shipping_fee=$shipping_fee,sub_total=$sub_total,discount=$discount,grand_total=$grand_total,currency_id=$currency_id,status=$status,mdate=NOW() where id=$id ";
		$this->update();
	}
	public function insertCatalogsOrderDetail($order_id,$catalog_id,$quantity,$price,$discount,$discount_percentage,$total){
		$this->sql = "insert into orders_detail (order_id,catalog_id,quantity,price,discount,discount_percentage,total)";
		$this->sql .= "values($order_id,$catalog_id,$quantity,$price,$discount,$discount_percentage,$total)";
		$this->insert();
		return $this->insert_id();
	}
	public function updateCatalogsOrderDetail($id,$order_id,$catalog_id,$quantity,$price,$discount,$discount_percentage,$total){
		$this->sql = "update orders_detail set order_id=$order_id,catalog_id=$catalog_id,quantity=$quantity,price=$price,discount=$discount,discount_percentage=$discount_percentage,total=$total where id=$id";
		$this->update();
	}
	public function insertCatalogOrderBuyer($order_id,$is_member,$member_id,$is_order_address){
		$this->sql = "insert into orders_buyer (order_id,is_member,member_id,is_order_address) ";
		$this->sql .=" values($order_id,$is_member,$member_id,$is_order_address)";	
		$this->insert();
	}
	public function updateCatalogOrderBuyer($order_id,$is_member,$member_id,$is_order_address){
		$this->sql = "update orders_buyer set is_member=$is_member,member_id=$member_id,is_order_address=$is_order_address where orders_id=$order_id ";
		$this->update();
	}
	public function insertCatalogOrderPayment($order_id,$payment_id,$payment_total,$payment_result,$payment_status){
		$this->sql = " insert into orders_payment (order_id,payment_id,payment_total,payment_result,payment_status,mdate,cdate) ";
		$this->sql .= " values($order_id,$payment_id,$payment_total,'$payment_result',$payment_status,NOW(),NOW()) ";
		$this->insert();
	}
	public function updateCatalogOrderPayment($order_id,$payment_id,$payment_total,$payment_result,$payment_status){
	 	$this->sql = "update orders_payment set order_id=$order_id,payment_id=$payment_id,payment_total=$payment_total,payment_result='$payment_result',payment_status=$payment_status,mdate=NOW() where order_id=$order_id";
		$this->update();
	}
	public function confirmPayment($order_id,$payment_result,$payment_status){
		$this->sql  ="update orders_payment set payment_result='$payment_result',payment_status=$payment_status where order_id=$order_id";
		$this->update();
	}
	public function getMycoupon($member_id){
		$this->sql = "SELECT orders.id as id,orders.grand_total,orders.cdate,orders.status as status,members.name as buyer,members.id as member_id,orders_payment.payment_status as paymentStatus,payments.name as paymentMethod,currency.sign_left,currency.sign_right,orders_items.items from orders ";
		$this->sql .= " left join orders_items on orders.id=orders_items.order_id ";
		$this->sql .= " left join orders_buyer on orders.id=orders_buyer.order_id left join members on orders_buyer.member_id=members.id and orders_buyer.member_id=$member_id";
		$this->sql .= " left join orders_payment on orders.id=orders_payment.order_id left join payments on orders_payment.payment_id = payments.id  and  orders_payment.payment_status=1";
		$this->sql .= " left join currency on orders.currency_id=currency.id ";
		$this->select();
		$orders = $this->rows;
		if(!empty($orders)){
			foreach($orders as $order_key => $order_val){
				$this->sql = " select orders_detail.*,orders_coupon.id as coupon_id,orders_coupon.used as coupon_used from orders_detail left join orders_coupon on orders_detail.id=orders_coupon.id where orders_detail.order_id=".$order_val['id'];
				$this->select();
				$orders[$order_key]['order_detail'] = $this->rows;
			}
		}
		return $orders;
	}
	public function getTopSeller(){
		$this->sql =" SELECT COUNT(orders_detail.id) as totalSale, orders_detail.catalog_id FROM orders_detail GROUP BY orders_detail.catalog_id  ORDER BY totalSale desc LIMIT 0,10 " ;
		$this->select();
		$rows = $this->rows;
		if(!empty($rows)){
			foreach($rows as $kk=>$vv){
				$this->sql = "select catalogs.name, catalogs.slug from catalogs where catalogs.id=".$vv['catalog_id']." ";
				$this->select();
				$rows[$kk]['name'] = $this->rows[0]['name'];
				$rows[$kk]['slug'] = $this->rows[0]['slug'];
			}
		}
		return $rows;
	}
	public function getBuyerDetail($id){
		$this->sql = "select orders_buyer.* from orders_buyer where order_id = $id ";
		$this->select();
		$orders_buyer =  $this->rows[0] ;
		$member_id = $orders_buyer['member_id'];
		if($orders_buyer['is_member']==1){
			if($orders_buyer['is_order_address']==0){
				$this->sql = "select members.*,members_address.*,states.state_name,country.name as country_name from members left join members_address on members.id=members_address.member_id left join states on members_address.state=states.state_id left join country on members_address.country=country.country_id where members.id=$member_id ";
				$this->select();
				$orders_buyer['member_detail'] = $this->rows[0];
			}else{
				$this->sql = "select members.*,orders_address,states.state_name,country.name as country_name from members left join orders_address on members.id=orders_address.member_id left join states on members_address.state=states.state_id left join country on members_address.country=country.country_id where members.member_id=$member_id ";
				$this->select();
				$orders_buyer['member_detail'] = $this->rows[0];
			}
		}
		return $orders_buyer;
	}
	public function getOrderDetail($id){
		$this->sql = "select orders_detail.* from orders_detail where orders_detail .order_id=$id ";
		$this->select();
		return $this->rows;
	}
	public function recalOrderTotal($order_id){
		$this->sql = "select total from orders_detail where order_id=$order_id";
		$this->select();
		$order_detail = $this->rows;
		$sub_total = 0 ;
		if(!empty($order_detail)){
			foreach($order_detail as $od){
				$sub_total +=$od['total'];
			}
		}
		$this->sql = "select vat_rate,shipping_fee,discount from orders where id=$order_id";
		$this->select();
		$orders = $this->rows[0] ;
		$vat_total = $sub_total*($orders['vat_rate']) ;
		$grand_total = ($sub_total+$vat_total+$orders['shipping_fee'])-$orders['discount'];
		$this->sql = "update orders set sub_total=$sub_total,grand_total=$grand_total where id=$order_id ";
		$this->update();
	}
	public function updateOrderDetailQuantity($id,$order_id,$type){
		if($type=='plus'){
			$this->sql = "update orders_detail set quantity=quantity+1,total=total+price where id=$id";
		}
		if($type=='minus'){
			$this->sql = "select quantity from orders_detail where id=$id ";
			$this->select();
			$chk_qty = $this->rows[0]['quantity'];
			if($chk_qty>=1){
				$this->sql = "update orders_detail set quantity=quantity-1,total=total-price where id=$id";
			}
		}
		$this->update();
		$this->recalOrderTotal($order_id);
		$this->sql = "select * from orders_detail where id=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function deleteOrderDetail($id,$order_id){
		$this->sql = "delete from orders_detail where id=$id ";
		$this->delete();
		$this->recalOrderTotal($order_id); 
		$this->minusOrderItems($order_id);
		return true;
	}
	public function minusOrderItems($order_id){
		$this->sql = "update orders_items set items=items-1 where order_id=$order_id ";
		$this->update();
	}
}
?>