<?php
class Menus extends Database {
	var $module;
	public function __construct($params=NULL){
		$this->module = $params['module']  ;
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
	function datePickerToTime($in_date){
		list($date,$time) = explode(' ',$in_date);
		list($month,$day,$year) = explode('/',$date);
		return $year.'-'.$month.'-'.$day.' '.$time.':00';
	}
	function timeToDatePicker($in_date){
		list($date,$time) = explode(' ',$in_date);
		list($year,$month,$day) = explode('-',$date);
		list($h,$m,$s)=explode(':',$time);
		return $month.'/'.$day.'/'. $year.' '.$h.':'.$m;
	}
	// function for categories /////////////////////////////////////////////////
	// Develop by iQuickweb.com 28/06/2012
	///////////////////////////////////////////////////////////////////////////////////
	public function getCategoriesAll($search,$order,$limit){
		$this->sql = "select * from $this->table where level >0  $search $order $limit";
		$this->select();
		return $this->rows;
	}
	public function getCategoriesTreeAll($where=NULL){
		return  $this->get_tree($where);
	}
	public function getCategoriesSize(){
		$this->sql = "select $this->primary_key from $this->table where level >0 ";
		return  $this->select('size');
	}
	public function getCategory($id){
		$this->sql = "select id, parent_id, name, slug, description, image, status from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0];
	}
	public function  insert_categories($parent_id,$name,$slug,$description,$image,$params,$user_id,$status){
			if((int)$parent_id==0){
				$parent_id = $this->check_root_node();
			}
			$this->sql = "insert into $this->table (parent_id,name,slug,description,image,params,user_id,status,mdate,cdate) " ;
			$this->sql .="values ($parent_id,'$name','$slug','$description','$image','$params',$user_id,$status,NOW(),NOW())";
			$this->insert() ;
			$id = $this->insert_id();
			$this->insert_node($parent_id,$id) ;
			return $id ;
	}
	
	public function  update_categories($id,$parent_id,$name,$slug,$description,$image,$params, $user_id,$status){
		if($parent_id==0){
			$parent_id = $this->check_root_node();
		}else{
			if ($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id, name='$name',slug='$slug',description='$description', image='$image',params='$params', user_id=$user_id,status=$status, mdate=NOW() where $this->primary_key=$id ";
		$this->update();
	}
	
	public function duplicate_categories($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key=$id " ;
		$this->select();
		$data = $this->rows[0];
		$new_id =$this->insert_categories($data['parent_id'],$data['name'],$data['slug'],$data['description'],$data['image'],$data['params'],$user_id,$data['status']) ;
		// translate
		$this->sql = "select * from $this->translate_table where $this->primary_key=$id " ;
		$this->select();
		$data = $this->rows;
		if(!empty($data)){
			foreach($data as $d){
				$this->saveCategoriesTranslate( $d['lang'],$new_id,$d['name'],$d['description'],$d['image'],$d['params']);
			}
		}
	}
	
	function deleteCategoryTranslate($id){
		$this->sql = "select * from $this->translate_table where $this->primary_key=$id " ;
		$this->select();
		$data = $this->rows;
		if(!empty($data)){
			$this->sql = "delete from  $this->translate_table where $this->primary_key=$id ";
			$this->delete();
		}
	}
	
	public function update_category_status($id,$status){
			$this->sql ="update $this->table set status=$status where id=$id";
			$this->update();
	}
	
	// translate function
	public function getTranslateCategory($id,$lang){
		$this->sql = "select $this->table.id as category_id, $this->table.name as translate_from ,$this->translate_table.* from $this->table  left join $this->translate_table  on $this->table.id=$this->translate_table.id and $this->translate_table.lang='$lang'  where $this->table.id=$id ";
		$this->select();
		return	$this->rows[0] ;
	}
	
	function saveCategoriesTranslate( $categories_lang,$categories_id,$categories_name,$categories_description,$categories_images,$param){
		$this->sql ="select id from $this->translate_table where lang='$categories_lang' and id=$categories_id ";
		//echo $this->sql ;
		$this->select();
		$chk = (empty($this->rows[0]['id']))?true:false ;
		if($chk){
			$this->sql ="insert into  $this->translate_table(lang, id, name, description , image,params ) values ('$categories_lang',$categories_id,'$categories_name','$categories_description','$categories_images','$param')  ";
			//echo $this->sql  ;
			$this->insert();
		}else{
			$this->sql = "update  $this->translate_table set lang ='$categories_lang' , name='$categories_name', description='$categories_description' , image='$categories_images',params='$param' where id=$categories_id  ";
			//echo $this->sql  ;
			$this->update();
		}
	}
	
// function for module  /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
///////////////////////////////////////////////////////////////////////////////////


	//---- Function for Krungthaicarrent (Start from 12/09/2017) ----//

	public function getOneField($table,$field,$where,$where_val){
		$this->sql = "select $field as data from $table
		where $where = '".$where_val."' ";

		$this->select();
		// $row = $this->getRows();
		return $this->rows[0]['data'] ; 
	}

	public function getOneFieldTwoWhere($table,$field,$where,$where_val,$where2,$where_val2){
		$this->sql = "select $field as data from $table
		where $where = '".$where_val."' 
		and $where2 = '".$where_val2."' ";

		$this->select();
		// $row = $this->getRows();
		return $this->rows[0]['data'] ; 
	}

	public function getAllField($table,$where,$order,$limit){
		$this->sql = "select * from $table 
		$where $order $limit ";
		$this->select();
		return $this->rows;
	}

	public function getOneFieldList($table,$where,$where_val){
		$this->sql = "select * from $table 
		Where $where = '".$where_val."' ";
		$this->select();
		return $this->getRows() ;
	}

	public function getOneFieldListTwoWhere($table,$where,$where_val,$where2,$where_val2){
		$this->sql = "select * from $table 
		Where $where = '".$where_val."'
		and $where2 = '".$where_val2."' ";
		$this->select();
		return $this->getRows() ;
	}

	public function setInsertSQL($table,$data){
		$this->sql = "insert into $table (".implode(',',array_keys($data)).') values('.implode(',',$data).')' ; 
		$this->insert() ;
		$domain_id = $this->insertID() ;
		return $domain_id ;
	}

	public function setUpdateOne($table,$field,$val,$where_field,$where_val){
		$this->sql = "UPDATE $table SET $field = '$val' Where $where_field = $where_val";
		$this->update();
	}

	public function setUpdateAll($table,$field_data,$where_field,$where_val){
		$this->sql = "UPDATE $table SET ";
		$c = 1;
		$count = count($field_data);
		foreach($field_data as $k => $v){
			$this->sql .= " $k = $v ";
			if($c < $count){
				$this->sql .= ',';
			}
			$c++;
		}
		$this->sql .= "Where $where_field = $where_val";
		$this->update();

		return 1;
	}

	public function setDeleteAll($table,$where,$where_val){
		$this->sql =  "delete from $table where $where=$where_val";
		$this->delete();
	}

	public function getCategoryAll($search,$order,$limit,$language=NULL){
		$this->sql = "SELECT $this->table.* FROM $this->table $search $order $limit";
		$this->select() ;
		return $this->rows ;
	}

	public function getCategorySize($search,$order,$limit,$language=NULL){
		$this->sql = "select $this->primary_key from $this->table $search $order $limit";
		return  $this->select('size') ;
	}

	public function treeCheckRootNodeMenu($category_id){
		$this->sql = "select $this->primary_key from $this->table where parent_id = 0 and level = 0 and $this->parent_primary_key = $category_id ";
		$this->select();
		// $this->setRows() ;
		if(empty($this->rows)){
			$this->sql =  "insert into $this->table (parent_id,$this->parent_primary_key,n_left,n_right,level,title)values(0,$category_id,1,2,0,'$category_id')" ;
			$this->insert() ;
			$root_id =   $this->insertID();
			return $root_id ;
		}else{
			return $this->rows[0][$this->primary_key];
		}
	}

	public function treeInsertNodeMenu($parent_id,$id,$category_id)
	{	
		$parent = $this->treeGetNodeMenu($parent_id,' and category_id = '.$category_id) ;
		if($parent!==false&&!empty($parent['n_left'])&&!empty($parent['n_right']))
		{
			$left = $parent['n_left'];
			$right = $parent['n_right'];
			//function update main
			$this->sql = "update $this->table set n_right = n_right + 2  where n_right >=$right and $this->parent_primary_key = $category_id ";
			$this->update();
			$this->sql = "update $this->table set n_left = n_left + 2 where n_left >$right and $this->parent_primary_key = $category_id ";
			$this->update();
				
			$new_lft = $right ; 
			$new_rght = $right+1 ;
			$new_level =$parent['level']+1 ;
			$this->sql = "update $this->table set n_left=$new_lft, n_right=$new_rght,level=$new_level where $this->primary_key=$id and $this->parent_primary_key = $category_id" ;
			$this->update() ;
			return true ;
		}
		else
		{
			return false ;
		}
	}

	public function treeGetNodeMenu($id,$where=NULL){
		$this->sql =  "select parent_id , n_left, n_right , level from $this->table where $this->primary_key = $id ";
		if(!empty($where)){
			$this->sql .=$where;
		}
		$this->select();
		$list = $this->rows;
		if(!empty($list)){
			return array('n_left'=>$list[0]['n_left'],'n_right'=>$list[0]['n_right'],'level'=>$list[0]['level'],'parent_id'=>$this->rows[0]['parent_id']);
			print(array('n_left'=>$list[0]['n_left'],'n_right'=>$list[0]['n_right'],'level'=>$list[0]['level'],'parent_id'=>$this->rows[0]['parent_id']));
			exit;
		}else{
			return false;
		}
	}

	public function getAllCategoriesNestList($category_id)
	{
		$casewhere = " and c.$this->parent_primary_key = ".$category_id." and p.$this->parent_primary_key = ".$category_id;
		return $this->treeGetTreeMenu($casewhere);
	}

	public function treeGetTreeMenu($where=NULL,$table=NULL)
	{
		$this->sql = "select c.*, COUNT(*)-1 as clevel from $this->table as c, $this->table as p where c.n_left between  p.n_left and p.n_right" ;
		if(!empty($where))
		{
			$this->sql .=$where ;	
		}
		$this->sql  .= " group by c.n_left order by c.n_left ";
		$this->select();   
		// $this->setRows() ;
		return $this->rows ;
	}

	public function treeResetRootNodeMenu($id,$category_id){
		$this->sql = "update $this->table set n_left=1, n_right = 2  where $this->primary_key = $id ";
		$this->update();
	}

	public function treeUpdateNodeMenus($parent_id,$id,$level,$category_id){
		$this->sql = "update $this->table set parent_id = $parent_id , level = $level , category_id = $category_id where $this->primary_key = $id ";
		$this->update();
		$parent = $this->treeGetNodeMenu($parent_id,' and category_id = '.$category_id);
		if($parent!==false&&!empty($parent['n_left'])&&!empty($parent['n_right'])){
			$lft = $parent['n_left'];
			$rght = $parent['n_right'];
			$this->sql = "update $this->table set n_right = n_right + 2  where n_right >=$rght and $this->parent_primary_key = $category_id";
			$this->update();
			$this->sql = "update $this->table set n_left = n_left + 2 where n_left >$rght and $this->parent_primary_key = $category_id";
			$this->update();

			$new_lft = $rght;
			$new_rght = $rght+1;
			$new_level =$parent['level']+1;
			$this->sql = "update $this->table set n_left=$new_lft, n_right=$new_rght,level=$new_level where $this->primary_key=$id and $this->parent_primary_key = $category_id";
			$this->update();

			// if($level == 1){
			// 	print_r($parent);
			// 	exit;
			// }
			
			return true ;
		}else{
			return false ;
		}
	}

	public function checkHaveChildren($id){
		$this->sql = "select * from $this->table where parent_id = $id";
		$this->select();   
		return $this->rows ;
	}

	public function setInsertData($data)
	{
		if($data['parent_id']==0)
		{
			$data['parent_id'] = $this->treeCheckRootNodeMenu($data['category_id']) ;
		}
		$this->sql = "insert into $this->table (".implode(',',array_keys($data)).') values('.implode(',',$data).')' ; 
		$this->insert();
		$menu_id = $this->insertID();
		$this->treeInsertNodeMenu($data['parent_id'],$menu_id,$data['category_id']) ;
		return $category_id;
	}

	public function setInsertCategory($data)
	{
		$this->sql = "insert into $this->parent_table (".implode(',',array_keys($data)).') values('.implode(',',$data).')' ; 
		$this->insert();
		return $this->insertID();
	}
	public function setUpdateCategory($data)
	{
		$this->sql = "update $this->parent_table set title =".$data['title']." , slug = ".$data['slug'].", mdate=".$data['mdate']." where category_id =".$data['category_id']; 
		$this->update() ;
	}

	public function setDeleteMenus($id)
	{
		$this->sql =  "delete from $this->table where $this->primary_key=$id";
		$this->delete();
		$this->setDeleteTranslate($id,'all');
	}

	public function getAllDataSizeMenu($categoey_id){
		$this->sql = "select count(menu_id) as total from menus where category_id = $categoey_id and level > 0 ";
		$this->select();
		return $this->rows[0]['total'];
	} 

	public function getOneData($id)
	{
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		return $this->rows[0] ;
	}

	public function setDuplicateMenu($id)
	{
		$data = $this->getOneData($id);
		unset($data['menu_id']);
		$data['category_id'] = (int)$data['category_id'];
		$data['title'] = "'".$data['title']."'";
		$data['slug'] = "'".$data['slug']."'";
		$data['type'] = (int)$data['type'];
		$data['link'] = "'".$data['link']."'";
		$data['target'] = (int)$data['traget'];
		$data['module_name'] = "'".$data['module_name']."'";
		$data['module_type'] = "'".$data['module_type']."'";
		$data['module_id'] = (int)$data['module_id'];
		$data['module_category_id'] = (int)$data['module_category_id'];
		$data['module_content_id'] = (int)$data['module_content_id'];
		$data['visible'] = (int)$data['visible'];
		$data['mdate'] = $data['mdate'];
		$data['mdate'] = "NOW()";
		$data['cdate'] = "'".$data['cdate']."'";
		$data['icon_position'] = (int)$data['icon_position'];
		$data['icon_class'] = "'".$data['icon_class']."'";

		$this->sql = "insert into $this->table (".implode(',',array_keys($data)).') values('.implode(',',$data).')' ; 
		$this->insert() ;
		$new_menu_id  = $this->insertID() ;
		
		$this->treeInsertNodeMenu($data['parent_id'],$new_menu_id,$data['category_id']) ;

		return $new_menu_id ;
	}

	public function setSaveTranslate($data)
	{
		$this->sql ="select $this->primary_key from $this->translate_table where lang=".$data['lang']." and $this->primary_key =".$data[$this->primary_key]." ";
		$this->select();
		$chk = (empty($this->rows[0][$this->primary_key]))?true:false ;
		if($chk)
		{
			$this->setInsertDataTransalate($data);
		}
		else
		{
			$this->setUpdateDataTransalate($data);
		}
	}
	public function setInsertDataTransalate($data)
	{
		$this->sql = "insert into $this->translate_table (".implode(',',array_keys($data)).') values('.implode(',',$data).')' ; 
		$this->insert();
		echo 0 ;// response for insert
	}
	public function setUpdateDataTransalate($data)
	{
		$this->sql = "update $this->translate_table set lang=".$data['lang']." , title=".$data['title'].", slug=".$data['slug']." where $this->primary_key=".$data[$this->primary_key]." and lang=".$data['lang']." " ;
		$this->update() ;
		echo 1 ;// response for update
	}
	public function setDeleteTranslate($id,$lang='all')
	{
		if($lang=='all')
		{
			$this->sql = "select * from $this->translate_table where $this->primary_key=$id  " ;
		}
		else
		{
			$this->sql = "select * from $this->translate_table where $this->primary_key=$id and lang='$lang' limit 0,1 " ;
		}
		$this->select();
		$data = $this->rows;
		if(!empty($data))
		{			
			if($lang=='all')
			{
				foreach($data as $val)
				{	
					$this->sql = "delete from $this->translate_table where $this->primary_key=$id and lang='".$val['lang']."' ";
					$this->delete();
				}
			}
			else
			{
				$this->sql = "delete from $this->translate_table where $this->primary_key=$id and lang='$lang' ";
				$this->delete();
			}
		}
	}

	function deleteMenuGroupData($category_id){
		$this->sql = "select * from menus where category_id = $category_id ";
		$this->select();
		$menuData = $this->rows;
		if(!empty($menuData)){
			foreach($menuData as $k=>$v){
				$this->setDeleteTranslate($v['menu_id'],'all');
				$this->sql = "delete from menus where menu_id = ".$v['menu_id'];
				$this->delete();
			}
		}
		$this->sql = "delete from menus_categories where category_id = $category_id ";
		$this->delete();
	}

	public function findOneMenuGroup($id,$language){
		if(!empty($language)&&$language!=$this->site_language){
			$this->sql = "select m.*, t.* from $this->table m
						  left join $this->translate_table t on (m.menu_id = t.menu_id)
						  where m.status = 1 and m.$this->parent_primary_key = $id and t.lang='$language'
						  order by m.n_left asc ";
			$this->select();
		}else{
			$this->sql = "select * from $this->table where status = 1 and $this->parent_primary_key = $id order by n_left asc ";
			$this->select();
		}
		//echo $this->sql; exit;
		$data = $this->rows;
		return $data;
	}

	//---- END : Function for Krungthaicarrent (Start from 12/09/2017) ----//

	public function getSize($status=NULL){
		if(!empty($status)&&$status==1){
			$this->sql = "select $this->primary_key from $this->table where $this->table.status=$status ";
		}else{
			$this->sql = "select $this->primary_key from $this->table ";
		}
		return  $this->select('size') ;
	}
	
	public function getAll($search,$order,$limit,$language=NULL){
		if(!empty($language)&&$language!=$this->site_language){
				$this->sql = "SELECT $this->table.*, $this->translate_table , $this->parent_translate_table.name as category FROM $this->table  LEFT JOIN $this->parent_translate_table  ON  $this->table.category_id=$this->parent_translate_table.$this->parent_primary_key LEFT JOIN $this->translate_table ON $this->table.$this->primary_key = $this->translate_table.$this->primary_key  $search $order $limit";
		}else{
			 	$this->sql = "SELECT $this->table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key  $search $order $limit";
		}
		$this->select() ;
		return $this->rows ;
	}
	
	public function getOne($key,$slug=false,$language=NULL){
		
			if(!empty($language)&&$language!=$this->site_language){
				$this->sql = "select $this->table.*, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.primary_key = $this->translate_table.id and $this->translate_table.lang=$language  where  $this->table.$this->primary_key=$key " ;
			}else{
				$this->sql = "select * from $this->table where $this->primary_key=$key ";
			}
		
		$this->select();
		return  $this->rows[0] ;
	}
	
	public function getParentCategoryAll($status=NULL,$language=NULL){
		if(!empty($language)&&$language!=$this->site_language){
			$categories =  $this->get_tree($status, $this->parent_table) ;
			if(!empty($categories)){
				foreach($categories as $key => $c){
						$id = $c['id'] ;
						$this->sql = "select * from $this->parent_translate_table where id=$id ";
						$this->select();
						$translate = $this->rows[0] ;
						$categories[$key] = array_merge($categories[$key],$translate) ;
				}
			}
		}else{
			$categories =  $this->get_tree($status, $this->parent_table) ;
		}
		return  $categories ;
	}
	
	public function getParentCategory($key,$language=NULL){
		if(!empty($language)&&$language!=$this->site_language){
			$this->sql = " select * from $this->parent_translate_table where id = $key ";
		}else{
			$this->sql = " select * from $this->parent_table  where id = $key ";
		}
		$this->select();
		return  $this->rows[0];
	}
	function insertData($category_id,$name,$description,$slug,$content,$image,$params,$meta_key,$meta_description,$user_id,$status,$start,$end){
		$this->sql ="insert into $this->table (category_id,name,slug,description,content,image,params,meta_key,meta_description,user_id,status,mdate,cdate,start,end,sequence) ";
		$this->sql .=" values($category_id,'$name','$slug','$description','$content','$image','$params','$meta_key','$meta_description',$user_id,$status,NOW(),NOW(),'$start','$end',0) " ;
		//echo $this->sql;
		$this->insert();
	}
	function updateData($id,$category_id,$name,$description,$slug,$content,$image,$params,$meta_key,$meta_description,$user_id,$status,$start,$end){
		$this->sql ="update $this->table set category_id=$category_id,name='$name',description='$description',slug='$slug',content='$content',image='$image',params='$params',meta_key='$meta_key',meta_description='$meta_description',user_id=$user_id,status=$status,mdate=NOW(),start='$start',end='$end' where $this->primary_key = $id ";
		$this->update();
	}
	function duplicateData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key = $id ";
		$this->select();
		$data = $this->rows[0] ;
		$this->insertData($data['category_id'],$data['name'],$data['description'],$data['slug'],$data['content'],$data['image'],$data['params'],$data['meta_key'],$data['meta_description'],$user_id,$data['status'],$data['start'],$data['end']) ;
		// insert translate 
		$new_id = $this->insert_id();
		$this->sql = "select * from $this->translate_table where $this->primary_key = $id ";
		$this->select();
		$data = $this->rows ;
		if(!empty($data)){
			foreach($data as $d){ 
				$this->saveTranslate($d['lang'],$new_id,$d['name'],$d['description'],$d['content'],$d['image'],$d['params'],$d['meta_key'],$d['meta_description']);
			}
		}
	}
	function deleteData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
		if($this->is_translate){
			$this->sql = "delete from $this->translate_table where $this->translate_table.$this->primary_key=$id ";
			$this->delete();
		}
	}
	function updateStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	// page translate
	function getTranslate($id,$lang){
		$this->sql = "select $this->table.id as translate_id , $this->table.name as translate_from, $this->table.id as page_id, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.id = $this->translate_table.id and $this->translate_table.lang = '$lang' where $this->table.id=$id ";
		$this->select();
		return $this->rows[0];
	}
  	public function saveTranslate($lang,$id,$name,$description,$content,$image,$params,$meta_key,$meta_description){
		$this->sql ="select id from $this->translate_table where lang='$lang' and id=$id ";
		$this->select(); 
		$chk = (empty($this->rows[0]['id']))?true:false;
		if($chk){
			$this->sql ="insert into $this->translate_table (lang,id,name,description,content,image,params,meta_key,meta_description) values ('$lang',$id,'$name','$description','$content','$image','$params','$meta_key','$meta_description') ";
			$this->insert();
		}else{
			$this->sql = "update $this->translate_table set lang='$lang',name='$name',description='$description',content='$content',image='$image' ,params ='$params',meta_key='$meta_key',meta_description='$meta_description' where lang='$lang' and id=$id ";
			$this->update();
		}
	}
	function front_getInCategory($categories,$search,$orderby,$limit){
		foreach($categories as $category){
			$new_search = $search.' and category_id = $category_id ';
			$pages[$category['id']] =$this->getPagesAll($new_search,$order,$limit);
		}
		return $pages;
	}

//////////////  order function  /////////////////////////////

	function reOrderDataDragDrop($ids,$sort){
		$min = min($sort);
		foreach($ids as $id){
			if($id!='start'){
				$this->sql="update $this->table set sequence=$min where $this->primary_key=$id ";
				$this->update();
				$min++;
			}
		}
	}
	
	function switchOrder($id,$sort){
		$this->sql = "select sequence from $this->table where  $this->primary_key=$id ";
		$this->select();
		$old_sequence = $this->rows[0]['sequence'] ;
		if($sort>$old_sequence){ // move up
			$this->sql="update $this->table set sequence=sequence-1 where sequence <= $sort and sequence > $old_sequence ";
			$this->update();
			$this->sql="update $this->table set sequence=$sort where $this->primary_key=$id ";
			$this->update();
		}else{ // move down
			$this->sql="update $this->table set sequence=sequence+1 where sequence >= $sort and sequence < $old_sequence ";
			$this->update();
			$this->sql="update $this->table set sequence=$sort where $this->primary_key=$id ";
			$this->update();
		}
	}
	
	function setReorderAll($column,$direction){
			$this->sql = " UPDATE   $this->table
											JOIN     (SELECT    p.$this->primary_key,
																@curRank := @curRank + 1 AS rank
													  FROM   $this->table p
													  JOIN      (SELECT @curRank := 0) r 
													  ORDER BY  p.$column $direction
													 ) ranks ON (ranks.$this->primary_key = $this->table.$this->primary_key)
											SET    $this->table.sequence = ranks.rank " ;
		$this->update();
	}
	function  changeCategory($id,$category_id){
		$this->sql = "update $this->table set category_id=$category_id where $this->primary_key=$id  ";
		$this->update();
	}
// function for pages frontend /////////////////////////////////////////////////
// Develop by iQuickweb.com 13/08/2012
///////////////////////////////////////////////////////////////////////////////////
function find($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$order=NULL,$sort=NULL,$separate=false,$pagenate=false,$page=NULL,$length=10,$oParent=NULL){
			/* type
					1. one = fine one item by id
					3. all  =  fine all item and category
					4. category_all = fine  category_id
					6. category_one = find item in category 
					7. in_category = fine all and relation incateogry
			*/
			return $this->_find($type,$key,$slug,$status,$language,$search,$filter,$order,$sort,$separate,$pagenate,$page,$length,$oParent) ; 
		
	}
	
	function findcount($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$oParent=NULL){
		 return  $this->_findcount($type,$key,$slug,$status,$language,$search,$filter,$oParent); 
	}

}
?>