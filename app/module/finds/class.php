<?php
class Finds extends Database {
	var $module;
	public function __construct($module,$params=NULL){
		$this->module = (empty($params['table']))?$module:$params['table'];
		parent::__construct((empty($params['table']))?$module:$params['table'] );
		if(isset($params['parent_table'])){
			$this->parent_table = $params['parent_table'];
		}
		if(isset($params['parent_primary_key'])){
			$this->parent_primary_key = $params['parent_primary_key'];
		}
		if(isset($params['is_translate'])){
			$this->is_translate = $params['is_translate'];
		}
		if(isset($params['translate_table'])){
			$this->translate_table = $params['translate_table'];
		}
	}
	public function getCategoriesAll($search,$order,$limit){
		$this->sql = "select * from $this->table where level>0 $search $order $limit";
		$this->select();
		return $this->rows;
	}
	public function getCategoriesTreeAll($where=NULL){
		return  $this->get_tree($where);
	}
	public function getCategoriesSize(){
		$this->sql = "select $this->primary_key from $this->table where level>0 ";
		return  $this->select('size');
	}
	public function getCategory($id){
		$this->sql = "select $this->table.id,$this->table.parent_id,$this->table.name,$this->table.slug,$this->table.layout,$this->table.status from $this->table where $this->primary_key=$id ";
		$this->select();
		$category = $this->rows[0];
		$this->sql = "select finds_relation.find_id,finds.find_module,finds.find_name from finds_relation left join finds on finds_relation.find_id=finds.id where finds_relation.category_id=$id ";
		$this->select();
		if(!empty($this->rows)){
			foreach($this->rows as $fn){
				$category['finds'][] = array('find_id'=>$fn['find_id'],'module'=>$fn['find_module'],'name'=>$fn['find_name']);
			}
		}
		return $category;
	}
	public function insertFindRelation($category_id,$find_id){
		$this->sql = "insert into finds_relation (category_id,find_id) values($category_id,$find_id) ";
		$this->insert();
	}
	public function deleteFindRelation($category_id){
		$this->sql = "delete from finds_relation where category_id=$category_id ";
		$this->delete();
	}
	public function insert_categories($parent_id,$name,$slug,$layout,$status,$query){
		if((int)$parent_id==0){
			$parent_id = $this->check_root_node();
		}
		$this->sql = "insert into $this->table (parent_id,name,slug,layout,status) ";
		$this->sql .= "values ($parent_id,'$name','$slug','$layout',$status) ";
		$this->insert();
		$id = $this->insert_id();
		$this->insert_node($parent_id,$id);
		$this->deleteFindRelation($id);
		if(!empty($query)){
			foreach($query as $q){
				$this->insertFindRelation($id,$q);
			}
		}
	}
	public function update_categories($id,$parent_id,$name,$slug,$layout,$status,$query){
		if($parent_id==0){
			$parent_id = $this->check_root_node();
		}else{
			if ($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id,name='$name',slug='$slug',layout='$layout',status=$status where $this->primary_key=$id ";
		$this->update();
		$this->deleteFindRelation($id);
		if(!empty($query)){
			foreach($query as $q){
				$this->insertFindRelation($id,$q);
			}
		}
	}
	public function duplicate_categories($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$data = $this->rows[0];
		$this->insert_categories($data['parent_id'],$data['name'],$data['slug'],$data['layout'],$data['status']);
	}
	public function update_category_status($id,$status){
		$this->sql = "update $this->table set status=$status where id=$id";
		$this->update();
	}
	public function update_category_status_index($id,$status){
		$this->sql = "update $this->table set status_index=0 where status_index=1 ";
		$this->update();
		$this->sql = "update $this->table set status_index=$status where id=$id";
		$this->update();
	}
	// translate function
	public function getTranslateCategory($id,$lang){
		$this->sql = "select $this->table.id as category_id,$this->table.name as translate_from,$this->translate_table.* from $this->table left join $this->translate_table on $this->table.id=$this->translate_table.id and $this->translate_table.lang='$lang' where $this->table.id=$id ";
		$this->select();
		return $this->rows[0];
	}
	public function saveCategoriesTranslate($categories_lang,$categories_id,$categories_name,$categories_layout){
		$this->sql = "select id from $this->category_translate_table where lang='$categories_lang' and id=$categories_id ";
		$this->select();
		$chk = (empty($this->rows[0]['id']))?true:false;
		if($chk){
			$this->sql = "insert into $this->translate_table (lang,id,name,layout) values ('$categories_lang',$categories_id,'$categories_name','$categories_layout') ";
			$this->insert();
		}else{
			$this->sql = "update $this->translate_table set lang ='$categories_lang',name='$categories_name',layout='$categories_layout' where id=$categories_id ";
			$this->update();
		}
	}
	public function getSize(){
		$this->sql = "select $this->primary_key from $this->table ";
		return $this->select('size');
	}
	public function getAll($search,$order,$limit){
		$this->sql = "SELECT $this->table.* FROM $this->table $search $order $limit";
		$this->select();
		return $this->rows;
	}
	public function getOne($id,$language='th'){
		$this->sql = "select * from $this->table where $this->table.$this->primary_key=$id ";
		$this->select();
		$finds =  $this->rows[0];
		return $finds;
	}
	public function insertData($module,$name,$task,$type,$key,$is_slug,$status,$search,$filter,$order_by,$paginate,$page,$length,$is_count,$separate,$status){
		$name = urldecode($name);
		$this->sql ="insert into $this->table (find_module,find_name,find_task,find_type,find_key,find_is_slug,find_status,find_search,find_filter,find_order_by,find_paginate,find_page,find_length,find_is_count,find_separate,status) ";
		$this->sql .=" values ('$module','$name','$task','$type','$key',$is_slug,$status,'$search','$filter','$order_by',$paginate,'$page',$length,$is_count,$separate,$status) ";
		$this->insert();
	}
	public function updateData($id,$module,$name,$task,$type,$key,$is_slug,$status,$search,$filter,$order_by,$paginate,$page,$length,$is_count,$separate,$status){
		$name = urldecode($name);
		$this->sql ="update $this->table set find_module='$module',find_name='$name',find_task='$task',find_type='$type',find_key='$key',find_is_slug=$is_slug,find_status=$status,find_search='$search',find_filter='$filter',find_order_by='$order_by',find_paginate=$paginate,find_page='$page',find_length=$length,find_is_count=$is_count,find_separate=$separate,status=$status where $this->primary_key=$id ";
		$this->update();
	}
	public function duplicateData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		$data = $this->rows[0]; //$module,$name,$task,$type,$key,$is_slug,$status,$search,$filter,$order_by,$paginate,$page,$length,$is_count,$separate
		$this->insertData($data['module'],$data['name'],$data['task'],$data['type'],$data['key'],$data['is_slug'],$data['status'],$data['search'],$data['filter'],$data['order_by'],$data['paginate'],$data['page'],$data['length'],$data['is_count'],$data['separate'],$data['status']);
	}
	public function deleteData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
	}
	public function updateStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	public function getModulesList(){
		$this->sql ="select id,name from system_modules where status=1 order by sequence,id asc ";
		$this->select();
		return $this->rows;
	}
	// function for create loadtheme
	public function createRequestRoute($route_id){
		if(!empty($route_id)){
			$this->sql = " select finds.* from finds_relation left join finds on finds.id=finds_relation.find_id where finds.status=1 and finds_relation.category_id = $route_id ";
			$this->select();
			if(!empty($this->rows)){
				$ROUTE .= '$request_route[1] = array("module"=>"configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );';
				$start_index =  1 ; 
				foreach($this->rows as $find){
					$ROUTE .= "\n";
					$start_index++;
					// find key check
					$find_key = json_decode($find['find_key'],1);
					$find_key_str = '';
					if(!empty($find_key)&&count($find_key)>1){
						$key_str = implode(',',$find_key);
						$find_key_str = 'array('.$key_str.')';
					}else{
						$chk_find = substr($find_key[0],0,5);
						if($chk_find=='param'){
							$chk_int = str_replace('param','',$find_key[0]);
							if(is_numeric($chk_int)){
								$find_key_str = '$_PARAM['.(int)($chk_int-1).']';
							}else{
								$find_key_str = '$_PARAM_PLUS['.(int)($chk_int).']';
							}
						}else{
							$find_key_str = 'array("'.$find_key[0].'")';
						}
					}
					// find search check
					$find_search = $find['find_search'];
					$find_search_str = '';
					$chk_search = substr($find_search,0,5);
					if($chk_search=='param'){
						$chk_int = str_replace('param','',$find_search);
						if(is_numeric($chk_int)){
							$find_search_str = '$_PARAM['.(int)($chk_int-1).']';
						}else{
							$find_search_str = '$_PARAM_PLUS['.(int)($chk_int).']';
						}
					}else{
						$find_search_str = '"'.$find_search.'"';
					}
					// find page check
					$find_page = $find['find_page'];
					$find_page_str = '';
					$chk_page  = substr($find_page,0,5);
					if($chk_page=='param'){
						$chk_int = str_replace('param','',$find_page);
						if(is_numeric($chk_int)){
							$find_page_str = '$_PARAM['.(int)($chk_int-1).']';
						}else{
							$find_page_str = '$_PARAM_PLUS['.(int)($chk_int).']';
						}
					}else{
						$find_page_str = (int)$find_page;
					}
					$ROUTE .= '$request_route['.$start_index.'] = array(';
					$ROUTE .= '"module" =>"'.$find['find_module'].'",';
					$ROUTE .= '"task"=>"'.$find['find_task'].'",';
					$ROUTE .= '"type"=>"'.$find['find_type'].'",';
					$ROUTE .= '"key"=>'.$find_key_str.','; 
					$ROUTE .= '"slug"=>'.$find['find_is_slug'].','; 
					$ROUTE .= '"status"=>'.$find['find_status'].',';
					$ROUTE .= '"search"=>'.$find_search_str.',';
					$ROUTE .= '"filter"=>"'.$find['find_filter'].'",';
					$ROUTE .= '"order"=>"'.$find['find_order_by'].'",'; 
					$ROUTE .= '"paginate"=>'.$find['find_paginate'].','; 
					$ROUTE .= '"page"=>'.$find_page_str.',';
					$ROUTE .= '"length"=>'.$find['find_length'].',';
					$ROUTE .= '"separate"=>'.$find['find_separate'].',';
					$ROUTE .= '"count"=>'.$find['find_is_count'].',';
					$ROUTE .= '"data_key"=>"'.$find['find_name'].'"';
					$ROUTE .= ');';
				}
			}
			return $ROUTE;
		}else{
			return ''; 
		}
 	}
	public function createRequestHomeRoute(){
		$this->sql  = "select id from finds_categories where status_index=1";
		$this->select();
		$index_id = $this->rows[0]['id'];
		if(!empty($index_id)){
			return $this->createRequestRoute($index_id);
		}else{
			return false;
		}
	}
	public function createLoopQuery($path){
		$loop ='
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"];
			if(!empty($route["type"])){ $_GET["type"]=$route["type"]; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){ $_GET["key"]=$route["key"]; }else{ $_GET["key"]=""; }
			if(!empty($route["slug"])){ $_GET["slug"]=$route["slug"]; }else{ $_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"]=$route["status"]; }else{ $_GET["status"]=1; }  
			if(!empty($route["search"])){ $_GET["search"]=$route["search"]; }else{ $_GET["search"]=""; } 
			if(!empty($route["filter"])){ $_GET["filter"]=$route["filter"]; }else{ $_GET["filter"]=""; } 
			if(!empty($route["order"])){ $_GET["order"]=$route["order"]; }else{ $_GET["order"]=""; } 
			if(!empty($route["separate"])){ $_GET["separate"]=$route["separate"]; }else{ $_GET["separate"]=0; }
			if(!empty($route["paginate"])){ $_GET["paginate"]=$route["paginate"]; }else{ $_GET["paginate"]=0; } 
			if(!empty($route["page"])){ $_GET["page"]=$route["page"]; }else{ $_GET["page"]=1; }
			if(!empty($route["length"])){ $_GET["length"]=$route["length"]; }else{ $_GET["length"]=10; }
			if(!empty($route["count"])){ $_GET["count"]=$route["count"]; }else{ $_GET["count"]=0; }
			if(!empty($route["data_key"])){ $_GET["data_key"]=$route["data_key"]; }else{ $_GET["data_key"]=""; }
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."'.$path.'");
		';
		return $loop;
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
}
?>