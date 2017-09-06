<?php
class Catalogs extends Database {
	var $module   ;
	public function __construct($params=NULL){
		$this->module = $params['module']  ;
		 parent::__construct((empty($params['table']))?$module:$params['table'] );
		 
		 if(isset($params['primary_key'])){
			 $this->primary_key  =$params['primary_key'] ;
		 }
		 if(isset($params['parent_table'])){
			 $this->parent_table  =$params['parent_table'] ;
		 }
		  if(isset($params['parent_translate_table'])){
			 $this->parent_translate_table  =$params['parent_translate_table'] ;
		 }
		  if(isset($params['parent_primary_key'])){
			 $this->parent_primary_key  =$params['parent_primary_key'] ;
		 }
		 if(isset($params['site_language'])){
			 $this->site_language  =$params['site_language'] ;
		 }
		 if(isset($params['is_translate'])){
			 $this->is_translate  =$params['is_translate'] ;
		 }
		 if(isset($params['translate_table'])){
			 $this->translate_table  = $params['translate_table'] ;
		 }
	}
	
// function for categories /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
///////////////////////////////////////////////////////////////////////////////////
	public function getCategoriesAll($search,$order,$limit){
		$this->sql = "select * from $this->table where level >0  $search $order $limit";
		$this->select() ;
		return $this->rows ;
	}
	
	public function getCategoriesTreeAll($where=NULL){
		return  $this->get_tree($where) ;
	}
	
	public function getCategoriesSize(){
		$this->sql = "select $this->primary_key from $this->table where level >0 ";
		return  $this->select('size') ;
	}
	
	public function getCategory($id){
		$this->sql = "select id, parent_id, name,slug ,description, image, status from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0] ;
	}
	
	public function  insert_categories($parent_id,$name,$slug,$description,$image,$params,$user_id,$status){
			if((int)$parent_id==0){
				$parent_id = $this->check_root_node() ;
			}
			$this->sql = "insert into $this->table (parent_id,name,slug,description,image,params,user_id,status,mdate,cdate) " ;
			$this->sql .="values ($parent_id,'$name','$slug','$description','$image','$params',$user_id,$status,NOW(),NOW())";
		//	echo $this->sql ;
			$this->insert() ;
			$id = $this->insert_id();
			$this->insert_node($parent_id,$id) ;
	}
	
	public function  update_categories($id,$parent_id,$name,$slug,$description,$image,$params, $user_id,$status){
		if($parent_id==0){
			$parent_id = $this->check_root_node() ;
		}else{
			if ($parent_id != $this->get_parent_id($id)){
				$this->move_parent($id,$parent_id);
			}
		}
		$this->sql = "update $this->table set parent_id=$parent_id, name='$name', slug='$slug' ,description='$description', image='$image',params='$params', user_id=$user_id,status=$status, mdate=NOW() where $this->primary_key=$id " ;
		$this->update() ;
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
		if($slug){
			if(!empty($language)&&$language!=$this->site_language){
					$this->sql = "select $this->table.*, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.primary_key = $this->translate_table.id and $this->translate_table.lang=$language  where $this->table.slug=$key " ;
			}else{
					$this->sql = "select * from $this->table where $this->table.slug=$key ";
			}
		}else{
			if(!empty($language)&&$language!=$this->site_language){
				$this->sql = "select $this->table.*, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.primary_key = $this->translate_table.id and $this->translate_table.lang=$language  where  $this->table.$this->primary_key=$key " ;
			}else{
				$this->sql = "select * from $this->table where $this->primary_key=$key ";
			}
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
			$this->sql = " select * from $this->parent_translate_table  where id =$key ";
		}else{
			$this->sql = " select * from $this->parent_table  where id =$key ";
		}
		$this->select();
		return  $this->rows[0] ;
	}
	
	function insertData($category_id,$name,$slug,$params,$meta_key,$meta_description,$user_id,$status){
		$this->sql ="insert into $this->table (category_id,name,slug,params,javascript,css,meta_key,meta_description,user_id,status,mdate,cdate,sequence) ";
		$this->sql .=" values($category_id,'$name','$slug','$params','$javascript','$css','$meta_key','$meta_description',$user_id,$status,NOW(),NOW(),0) " ;
		$this->insert();
	}
	
	function updateData($id,$category_id,$name,$slug,$params,$meta_key,$meta_description,$user_id,$status){
		$this->sql ="update $this->table set category_id=$category_id, name='$name', slug='$slug', params='$params', javascript='$javascirpt', css='$css', meta_key='$meta_key', meta_description='$meta_description', user_id=$user_id, status=$status, mdate=NOW() where $this->primary_key = $id ";
		//echo $this->sql ;
		$this->update() ;
	}
	
	function updateTranslateData($id,$lang,$name,$params,$meta_key,$meta_description){
		$this->sql = "select uid from $this->translate_table where $this->primary_key = $id and lang='$lang' ";
		//echo $this->sql ;
		$this->select();
		$rows = $this->rows ;
		if(empty($rows)){
			$this->sql = " insert into $this->translate_table (id,lang,name,params,meta_key,meta_description) values($id,'$lang','$name','$params','$meta_key','$meta_description') ";
			//echo $this->sql ;
			$this->insert();
		}else{
			$this->sql ="update $this->translate_table set name='$name', params='$params', meta_key='$meta_key', meta_description='$meta_description' where $this->primary_key = $id and lang='$lang' ";
			//echo $this->sql ;
			$this->update() ;
		}
		
	}
	
	function checkExistsAttrField($id,$lang,$default_lang){
		$this->sql = "select uid from $this->translate_table where $this->primary_key = $id and lang='$default_lang' ";
		$this->select();
		$rows = $this->rows ;
		if(empty($rows)){
			$this->sql = "select * from catalogs_attrs_val where $this->primary_key = $id and lang='$default_lang' ";
			$this->select();
			$rows = $this->rows ;
			if(!empty($rows)){
				foreach($rows as $row){
					$catalog_id = $row['catalog_id'] ;
					$attr_id = $row['attr_id'] ;
					$default_val = $row['default_val'] ;
					$value = $row['value'] ;
					$this->sql = " insert into catalogs_attrs_val (catalog_id,attr_id,lang,default_val,value) values($catalog_id,$attr_id,'$lang',$default_val,'$value') ";
					$this->insert();
				}
			}
		}
	}

	function duplicateData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key = $id ";
		$this->select();
		$data = $this->rows[0] ;
		$this->insertData($data['category_id'],$data['name'],$data['slug'],$data['params'],$data['meta_key'],$data['meta_description'],$user_id,$data['status']) ;
		
		// insert translate 
		$new_id = $this->insert_id();
		$this->sql = "select * from $this->translate_table where $this->primary_key = $id ";
		$this->select();
		$data = $this->rows ;
		if(!empty($data)){
			foreach($data as $d){ 
				$this->saveTranslate( $d['lang'],$new_id,$d['name'],$d['params'],$d['meta_key'],$d['meta_description']);
			}
		}
		// duplicate value
		$this->duplicateAttrValue($id, $new_id);
	}
	
	function duplicateAttrValue($catalog_id,$new_id){
		$this->sql = "select * from catalogs_attrs_val where catalog_id = $catalog_id ";
		//echo $this->sql ;
		$this->select();
		$data = $this->rows ;

		foreach($data as $dav){
			$this->sql = "insert into catalogs_attrs_val (catalog_id,attr_id,lang,default_val,value) ";
			$this->sql .= "value ($new_id,".$dav['attr_id'].",'".$dav['lang']."','".$dav['default_val']."','".$dav['value']."') ";
			//echo $this->sql ;
			$this->insert();
		}



	}
	
	function deleteData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id " ;
		$this->delete();
		$this->deleteAttrValue($id);
		if($this->is_translate){
			$this->sql = "delete from $this->translate_table where $this->translate_table.$this->primary_key=$id " ;
			$this->delete();
		}
	}
	
	function updateStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
// page translate
	function getTranslate($id,$lang){
		$this->sql = "select $this->table.id as translate_id,  $this->table.name as translate_from, $this->table.id as cat_id, $this->table.category_id as category_id, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.id= $this->translate_table.id and $this->translate_table.lang='$lang' where $this->table.id=$id  ";
		//echo $this->sql ;
		$this->select();
		return $this->rows[0] ;
	}

  function saveTranslate( $lang,$id,$name,$params,$meta_key,$meta_description){
		$this->sql ="select id from $this->translate_table where lang='$lang' and id=$id ";
		$this->select(); 
		$chk = (empty($this->rows[0]['id']))?true:false ;
		if($chk){
			$this->sql ="insert into  $this->translate_table (lang, id, name,params,meta_key,meta_description) values ('$lang',$id,'$name','$params','$meta_key','$meta_description')  ";
			$this->insert();
		}else{
			$this->sql = "update  $this->translate_table set lang='$lang', name='$name',params ='$params' ,meta_key='$meta_key', meta_description='$meta_description' where id=$id  ";
			$this->update();
		}
	}

function front_getInCategory($categories,$search,$orderby,$limit){
		foreach($categories as $category){
			$new_search = $search.' and category_id = $category_id ';
			$pages[$category['id']] =$this->getPagesAll($new_search,$order,$limit) ;
		}
		return $pages ;
	}
	
// function for attr data ////////////////////////////////////////////////////////////////////////////
	function insertAttrData($category_id,$attr_name,$attr_id,$attr_label,$attr_type,$attr_value,$attr_class,$attr_style,$attr_placeholder,$attr,$note,$require,$status,$searchable,$sequence,$group,$set,$collapse){
		$this->sql ="insert into $this->table (category_id,attr_name,attr_id,attr_label,attr_type,attr_value,attr_class,attr_style,attr_placeholder,attr,note,attr_require,status,searchable,sequence,group_type,group_set,collapse) ";
		$this->sql .=" values($category_id,'$attr_name','$attr_id','$attr_label','$attr_type','$attr_value','$attr_class','$attr_style','$attr_placeholder','$attr','$note',$require,$status,$searchable,$sequence,'$group',$set,$collapse) " ;

		//echo $this->sql ;
		$this->insert();
	}
	
	function updateAttrData($id,$category_id,$attr_name,$attr_id,$attr_label,$attr_type,$attr_value,$attr_class,$attr_style,$attr_placeholder,$attr,$note,$require,$status){
		$this->sql ="update $this->table set category_id=$category_id, attr_name='$attr_name',attr_id='$attr_id',attr_label='$attr_label',attr_type='$attr_type',attr_value='$attr_value',attr_class='$attr_class',attr_style='$attr_style',attr_placeholder='$attr_placeholder',attr='$attr',note='$note',attr_require=$require ,status=$status where $this->primary_key = $id ";
		$this->update() ;
	}
	
	function duplicateAttrData($id,$user_id){
		$this->sql = "select * from $this->table where $this->primary_key = $id ";
		$this->select();
		$data = $this->rows[0] ;
		// print_r($data) ;
		$this->insertAttrData($data['category_id'],$data['attr_name'],$data['attr_id'],$data['attr_label'],$data['attr_type'],$data['attr_value'],$data['attr_class'],$data['attr_style'],$data['attr_placeholder'],$data['attr'],$data['note'],$data['attr_require'],$data['status'],(int)$data['searchable'],(int)$data['sequence'],$data['group_type'],(int)$data['group_set'],(int)$data['collapse']) ;
	}
	
	function deleteAttrData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id " ;
		$this->delete();
		$this->sql = "delete from catalogs_attrs_default where attr_id =$id " ;
		$this->delete();
		if($this->is_translate){
			$this->sql = "delete from $this->translate_table where $this->translate_table.$this->primary_key=$id " ;
			$this->delete();
		}
	}
	
	function updateAttrStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	
	function  insertAttrValue($catalog_id, $attr_id,$lang,$value,$isDefaultVal){
		if($isDefaultVal){
			$this->sql  = " insert into catalogs_attrs_val  (catalog_id,attr_id,lang,default_val) values($catalog_id, $attr_id,'$lang',$value); ";
		}else{
			$this->sql  = " insert into catalogs_attrs_val  (catalog_id, attr_id,lang,value) values($catalog_id,$attr_id,'$lang','$value') ";
		}
		//echo $this->sql ;
		$this->insert();
		//echo $this->insert_id();
	}
	
	function deleteAttrDefaultValue( $catalog_id , $attr_id,$lang='th'){
		if($isDefaultVal){
			$this->sql = "delete from catalogs_attrs_val  where catalog_id=$catalog_id and attr_id=$attr_id  and lang='$lang'";
			$this->delete();
		}
	}
	
	function updateAttrValue( $catalog_id , $attr_id,$lang,$value,$isDefaultVal,$chk_multible=1){
			// check not exist attr
			$this->sql = "select id from catalogs_attrs_val where catalog_id=$catalog_id and attr_id=$attr_id and lang='$lang' ";
			$this->select();
			if(empty($this->rows)){
				$this->insertAttrValue($catalog_id, $attr_id,$lang,$value,$isDefaultVal) ;
			}else{
				// check exist attr
				if($chk_multible==1){
						if($isDefaultVal){
							$this->sql = "update catalogs_attrs_val set lang='$lang', default_val=$value where catalog_id=$catalog_id and attr_id=$attr_id and lang='$lang' ";
						}else{
							$this->sql = "update catalogs_attrs_val set lang='$lang', value='$value' where catalog_id=$catalog_id and attr_id=$attr_id and lang='$lang' ";
						} 
						$this->update();
				}else{
					if($chk_multible>1){
						$this->insertAttrValue($catalog_id, $attr_id,$lang,$value,$isDefaultVal) ;
					}else{
						$this->deleteAttrDefaultValue( $catalog_id , $attr_id,$lang);
					}
				}
			}
	}
	
	function emptyAttrValue($catalog_id,$lang){
		 $this->sql = "update catalogs_attrs_val set default_val=NULL, value='' where catalog_id=$catalog_id and lang='$lang'";
		 	$this->update();
	}
	
	function deleteAttrValue($id){
		$this->sql = "delete from catalogs_attrs_val where catalog_id=$id ";
		echo $this->sql ;
		$this->delete();
	}
	
	function insertAttrDefaultValue($attr_id,$lang,$value,$label,$selected=0,$sequence){
		$this->sql = "insert into catalogs_attrs_default (attr_id,lang,value,label,selected,sequence) ";
		$this->sql .= " values($attr_id,'$lang','$value','$label',$selected,$sequence) ";
		//echo $this->sql ;
		$this->insert();
	}
	
	function updateAttrDefaultValue($id, $attr_id,$lang,$value,$label,$selected=0,$sequence){
		$this->sql = "update catalogs_attrs_default set attr_id=$attr_id,lang='$lang',value='$value',label='$label',selected=$selected, sequence=$sequence where id=$id  ";
		$this->update() ;
	}
	
	function getAttrDefaultValue($id){
		$this->sql  = "select * from catalogs_attrs_default where attr_id =$id order by catalogs_attrs_default.sequence asc, catalogs_attrs_default.id asc ";
		//echo '371'.$this->sql ;
		$this->select();
		return $this->rows;
	}
	
	function getCatalogAttrValue($catalog_id,$lang='th'){
		if($lang==SITE_LANGUAGE){
			$this->sql  = "select * from catalogs_attrs_val where catalog_id =$catalog_id and lang='$lang'";
		}else{
			$this->sql  = "select * from catalogs_attrs_val where catalog_id =$catalog_id and lang='$lang' ";
		}
		// echo $this->sql ;
		$this->select();
		if(empty($this->rows)){
			$this->sql  = "select * from catalogs_attrs_val where catalog_id =$catalog_id and lang='".SITE_LANGUAGE."'";
			$this->select();
		}
		return $this->rows;
	}
	
	function getCatalogAttrValueAndDefaultValue($catalog_id,$lang='th'){
		// $this->sql  = "select catalogs_attrs.attr_name , catalogs_attrs_val.value, catalogs_attrs_default.value as default_val  from catalogs_attrs_val left join catalogs_attrs on catalogs_attrs.id=catalogs_attrs_val.attr_id left join catalogs_attrs_default on catalogs_attrs_default.id=catalogs_attrs_val.default_val and  catalogs_attrs_val.lang=catalogs_attrs_default.lang  where catalog_id =$catalog_id and catalogs_attrs_val.lang='$lang' order by catalogs_attrs_default.sequence asc , catalogs_attrs.id asc   ";

		$this->sql  = "select catalogs_attrs.attr_name , catalogs_attrs_val.value, catalogs_attrs_default.value as default_val  from catalogs_attrs_val 
			left join catalogs_attrs on catalogs_attrs.id=catalogs_attrs_val.attr_id 
			left join catalogs_attrs_default on catalogs_attrs_default.id=catalogs_attrs_val.default_val  
			where catalog_id =$catalog_id and catalogs_attrs_val.lang='$lang' 
			order by catalogs_attrs_default.sequence asc , catalogs_attrs.id asc   ";

		// echo $this->sql ;
		// echo '<hr>' ;

		$this->select();
		$rows = $this->rows ;
		$attr_value = array();
		if(!empty($rows)){
		foreach( $rows as $key=>$value){
			if(!empty($value['default_val'])){
				$attr_value[$value['attr_name']] = $value['default_val'] ;
			}else{
				$attr_value[$value['attr_name']] = $value['value'] ;
			}
		}
		}
		// print_r($attr_value);
		return $attr_value ;
	}

	
// page translate
	function getAttrTranslate($id,$lang){
		$this->sql = "select $this->table.id as translate_id ,  $this->table.name as translate_from, $this->table.id as page_id, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.id= $this->translate_table.id and $this->translate_table.lang='$lang' where $this->table.id=$id  ";
	//	echo $this->sql ;
		$this->select();
		return $this->rows[0] ;
	}

  function saveAttrTranslate( $lang,$id,$name,$label,$type,$value,$style,$attr,$note){
		$this->sql ="select id from $this->translate_table where lang='$lang' and id=$id ";
	//	echo $this->sql ;
		$this->select(); 
		$chk = (empty($this->rows[0]['id']))?true:false ;
		if($chk){
			$this->sql ="insert into  $this->translate_table (lang, id, name, label, type, value, style, attr, note) values ('$lang',$id,'$name','$label','$type','$value','$style','$attr','$note')  ";
			$this->insert();
		}else{
			$this->sql = "update  $this->translate_table set lang='$lang', name='$name', label='$label',type='$type' ,value ='$value' ,style='$style', attr='$attr', note='$note' where id=$id  ";
			$this->update();
		}
	}
	
	function getAttrAll($category_id,$lang='th'){
		if($lang==SITE_LANGUAGE){
			$this->sql = "select * from $this->table where $this->table.status=1 and ($this->table.category_id=0 or $this->table.category_id=$category_id) order by sequence asc";
		}else{
			$this->sql = "select * from $this->table where $this->table.status=1 and ($this->table.category_id=0 or $this->table.category_id=$category_id) order by sequence asc";
		}

		// echo $this->sql ;
		$this->select();

		return $this->rows ;
	}
	
	function saveAttrRemoveDefaultValue($id){
		$this->sql = "delete from catalogs_attrs_default where id=$id";
		$this->delete();
	}

// functon find for catalogs ////////////////////////////////////////////////////////
///// function Query Data frontend
	public function _find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oParent){
			/* type
					1. one = fine one item by id
					3. all  =  fine all item and category
					4. category_all = fine  category_id
					6. category_one = find item in category 
					7. in_category = fine all and relation incateogry
			*/
		 	// echo '_fine'. $type;
			switch($type){
				case 'one':
					// echo 'one';
						if($slug){
							if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
									$this->sql = "select * from $this->table where $this->table.slug = '$key' ";
							}else{
									$this->sql = "select $this->table.*, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.$this->primary_key=$this->translate_table.$this->primary_key  where $this->table.slug = '$key'  and $this->translate_table.lang='$language' ";
							}
						}else{
							if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
									$this->sql = "select * from $this->table where $this->table.$this->primary_key = '$key' ";
							}else{
									$this->sql = "select $this->table.*, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.$this->primary_key=$this->translate_table.$this->primary_key  where $this->table.$this->primary_key = '$key'  and $this->translate_table.lang='$language' ";
							}
						}
						// echo $this->sql ;
						$this->select();
						$rows = $this->rows[0];
						if(!empty($rows)){
							$rows['attrValue'] = $this->getCatalogAttrValueAndDefaultValue($rows['id'],$language);
						}
						return $rows ;
				break ;
				case 'all':
						if(!empty($filter)){
								if($filter=='FILTER_FUNCTON'){
									$FILTER .=  $this->FILTER_FUNCTON();
								}else{
									$FILTER .= " $filter " ;
								}
							}
				
						if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
							$this->sql = "select $this->table.*, $this->parent_table.name as category from $this->table  left join $this->parent_table  on  $this->table.category_id=$this->parent_table.$this->parent_primary_key where $this->table.status=$status ";
							if(!empty($search)){
								$this->sql .= " and ($this->table.name like '%$search%') " ;
							}
							if(!empty($filter)){
								if($filter=='FILTER_FUNCTON'){
									$this->sql .=  $FILTER ;
								}else{
									$this->sql .= $FILTER ;
								}
							}
							if(!empty($order)){
								$this->sql .= " order by $order  ";
							}
							if($pagenate){
								$page = (!empty($page))?$page:1;
								if($page<1){
									$page =  1 ;
								}
								$start_rec = ($page-1) *$length ;
								$this->sql .= " limit  $start_rec,$length ";
							}
						}else{
							$this->sql = "select $this->table.*, $this->translate_table.*, $this->parent_table.name as category from $this->table  left join $this->parent_table  on  $this->table.category_id=$this->parent_table.$this->parent_primary_key left join $this->translate_table on $this->translate_table.$this->primary_key=$this->table.$this->primary_key where $this->table.status=$status and $this->translate_table.lang='$language' ";
							
							if(!empty($search)){
								$this->sql .= " and ($this->translate_table.name like '%$search%' ) " ;
							}
							if(!empty($order)){
								$this->sql .= " order by $order ";
							}
							if($pagenate){
								$page = (!empty($page))?$page:1;
								if($page<1){
									$page =  1 ;
								}
								$start_rec = ($page-1) *$length ;
								$this->sql .= " limit  $start_rec,$length ";
							}
						}
						// echo $this->sql  ;     
						$this->select() ;
						$rows =  $this->rows ;
						if(!empty($rows)){
							foreach($rows as $rows_key =>$rows_val){
								$rows[$rows_key]['attrValue'] = $this->getCatalogAttrValueAndDefaultValue($rows_val['id'],$language);
							}
						}
						return $rows ;
				break ;
				case 'category_all':
						if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
							if($status){
								$categories =$oParent->get_tree('enable') ; //$oParent->get_tree(" AND c.status=1 $filter ") ;
							}else{
								$categories =  $oParent->get_tree("disable") ;
							}
						}else{
							if($status){
								$categories =  $oParent->get_tree(" AND c.status=1 $filter ") ;
								foreach($categories as $key=>$c){
									$this->sql  = " select * from $this->parent_translate_table where $this->parent_translate_table.lang='$language' and $this->parent_translate_table.$this->parent_primary_key = ".$c[$this->primary_key];
									$this->select();
									$categories[$key]['name'] =  $this->rows[0]['name'];
									$categories[$key]['description'] =  $this->rows[0]['description'];
									$categories[$key]['image'] =  $this->rows[0]['image'];
								}
							}else{
								$categories =  $oParent->get_tree(" AND c.status=0 $filter ") ;
								foreach($categories as $key=>$c){
									$this->sql  = " select * from $this->parent_translate_table where $this->parent_translate_table.lang='$language' and $this->parent_translate_table.$this->parent_primary_key = ".$c[$this->primary_key];
									$this->select();
									$categories[$key]['name'] =  $this->rows[0]['name'];
									$categories[$key]['description'] =  $this->rows[0]['description'];
									$categories[$key]['image'] =  $this->rows[0]['image'];
								}
							}
						}
						return $categories ;
				break ;
				case 'category_one':
						 	$this->sql = "select id, parent_id, name, description, image, status from $this->parent_table where $this->parent_primary_key=$key ";
							$this->select();
							return  $this->rows[0] ;
				break ;
				case 'in_category':
						$categories = $oParent->get_child_node($key,'enable',$slug) ;
							foreach($categories as $category){
									if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
										$this->sql = "SELECT $this->table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key WHERE $this->table.category_id= ".$category['id']." AND  $this->table.status=$status ";
										if(!empty($search)){
											$this->sql .= " ($this->table.name LIKE '%$search%') " ;
										}
										if(!empty($filter)){
											$this->sql .= " $filter " ;
										}
										if(!empty($order)){
											$this->sql .= " ORDER BY $order ";
										}
										if($pagenate){
											$page = (!empty($page))?$page:1;
											if($page<1){
												$page =  1 ;
											}
											$start_rec = ($page-1)*$length ;
											$this->sql .= " LIMIT  $start_rec,$length ";
										}
									}else{
										$this->sql = "SELECT $this->table.*, $this->translate_table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key LEFT JOIN $this->translate_table ON $this->translate_table.$this->primary_key=$this->table.$this->primary_key WHERE  $this->table.category_id= ".$category['id']." AND $this->table.status=$status ";
										if(!empty($search)){
											$this->sql .= " ($this->translate_table.name LIKE '%$search%' ) " ;
										}
										if(!empty($filter)){
											$this->sql .= " $filter " ;
										}
										if(!empty($order)){
											$this->sql .= " ORDER BY $order ";
										}
										if($pagenate){
											$page = (!empty($page))?$page:1;
											if($page<1){
												$page =  1 ;
											}
											$start_rec = ($page-1)*$length ;
											$this->sql .= " LIMIT  $start_rec,$length ";
										}
									} // if site language
									//echo $this->sql ;
									$this->select();
									$rows =  $this->rows ;
								
									if(!empty($rows)){
										foreach($rows as $rows_key =>$rows_val){
											$rows[$rows_key]['attrValue'] = $this->getCatalogAttrValueAndDefaultValue($rows_val['id'],$language);
										}
									}
										//print_r($rows);
									$data[$category['id']] =  $rows ;
									//print_r($data);
							}// foreach
							// separate data in category 
							if(!empty($data)&&!$separate){
								$new_data = $data ;
								$sp_data = array();
								$cnt = 0 ;
								foreach($new_data as $dd_key => $dd){
									if(!empty($dd)&&is_array($dd)){
										foreach($dd as  $ld_key=>$ld){
											$sp_data[$cnt] =  $ld ;
											$cnt++;
										}
									}
								}
								$data = $sp_data ;
							}
					//		print_r($data);
							return $data ;
				break ;
			}
	}
	
	function _findcount($type,$key,$slug,$status,$language,$search,$filter,$oParent){
			switch($type){
				case 'all':
						if(!empty($filter)){
								if($filter=='FILTER_FUNCTON'){
									$FILTER .=  $this->FILTER_FUNCTON();
								}else{
									$FILTER .= " $filter " ;
								}
							}
						if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
							$this->sql = "select $this->table.*, $this->parent_table.name as category from $this->table  left join $this->parent_table  on  $this->table.category_id=$this->parent_table.$this->parent_primary_key where $this->table.status=$status ";
							if(!empty($search)){
								$this->sql .= " and ($this->table.name like '%$search%' ) " ;
							}
							if(!empty($filter)){
								if($filter=='FILTER_FUNCTON'){
									$this->sql .=  $FILTER ;
								}else{
									$this->sql .= " $FILTER " ;
								}
							}
						}else{
							$this->sql = "select $this->table.*, $this->translate_table.*, $this->parent_table.name as category from $this->table  left join $this->parent_table  on  $this->table.category_id=$this->parent_table.$this->parent_primary_key left join $this->translate_table on $this->translate_table.$this->primary_key=$this->table.$this->primary_key where $this->table.status=$status ";
							if(!empty($search)){
								$this->sql .= " and ($this->translate_table.name like '%$search%' ) " ;
							}
							if(!empty($filter)){
										$this->sql .= " $filter " ;
							}
						}
						return  $this->select('size') ;
				break ;
				case 'category_all':
						if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
							if($status){
								$categories =  $oParent->get_tree(" where status=1 $filter ") ;
							}else{
								$categories =  $oParent->get_tree(" where status=0 $filter ") ;
							}
						}
						return count($categories) ;
				break ;
				case 'in_category':
				    	$categories = $oParent->get_child_node($key,'enable',$slug) ;
						$cnt = 0  ;
							foreach($categories as $category){
									if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
										$this->sql = "select $this->table.*, $this->parent_table.name as category from $this->table  left join  $this->parent_table  on  $this->table.category_id=$this->parent_table.$this->parent_primary_key where $this->table.category_id= ".$category['id']." and  $this->table.status=$status ";
										if(!empty($search)){
											$this->sql .= " AND ($this->table.name LIKE '%$search%' ) " ;
										}
										if(!empty($filter)){
											$this->sql .= " $filter " ;
										}
									}else{
										$this->sql = "select $this->table.*, $this->translate_table.*, $this->parent_table.name as category from $this->table  left join $this->parent_table  on  $this->table.category_id=$this->parent_table.$this->parent_primary_key left join $this->translate_table on $this->translate_table.$this->primary_key=$this->table.$this->primary_key where  $this->table.category_id= ".$category['id']." and $this->table.status=$status ";
										if(!empty($search)){
											$this->sql .= " and ($this->translate_table.name LIKE '%$search%' ) " ;
										}
										if(!empty($filter)){
											$this->sql .= " $filter " ;
										}
									} // if site language
									$cnt+= $this->select('size');
							}// foreach
							return $cnt ;
				break ;
			}
	}
	///// end Query frontend data 

	function findOneTranslate($id,$lang){
		$this->sql = "select $this->table.*, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.$this->primary_key=$this->translate_table.$this->primary_key  where $this->table.$this->primary_key = $id ";
	
		$this->select();
		$rows = $this->rows[0];

		if(!empty($rows)){
			$rows['attrValue'] = $this->getCatalogAttrValueAndDefaultValue($id,$lang);
		}
		return $rows ;
	}
/////////////////////////////////////////////////////////////////////////////////////////////

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
	
	function reOrderAttrDataDragDrop($ids,$sort){
		$min = min($sort);
		foreach($ids as $id){
			if($id!='start'){
				$this->sql="update catalogs_attrs set sequence=$min where id=$id ";
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
	
	function switchAttrOrder($id,$sort){
		$this->sql = "select sequence from catalogs_attrs where  id=$id ";
		$this->select();
		$old_sequence = $this->rows[0]['sequence'] ;
		if($sort>$old_sequence){ // move up
			$this->sql="update catalogs_attrs set sequence=sequence-1 where sequence <= $sort and sequence > $old_sequence ";
			$this->update();
			$this->sql="update catalogs_attrs set sequence=$sort where id=$id ";
			$this->update();
		}else{ // move down
			$this->sql="update catalogs_attrs set sequence=sequence+1 where sequence >= $sort and sequence < $old_sequence ";
			$this->update();
			$this->sql="update catalogs_attrs set sequence=$sort where id=$id ";
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
	
	function setReorderAttr($column,$direction){
			$this->sql = " UPDATE   catalogs_attrs
											JOIN     (SELECT    p.id,
																@curRank := @curRank + 1 AS rank
													  FROM   catalogs_attrs p
													  JOIN      (SELECT @curRank := 0) r 
													  ORDER BY  p.$column $direction
													 ) ranks ON (ranks.id = catalogs_attrs.id)
											SET    catalogs_attrs.sequence = ranks.rank " ;
				$this->update();
	}
	
	function  changeCategory($id,$category_id){
		$this->sql = "update $this->table set category_id=$category_id where $this->primary_key=$id  ";
		$this->update();
	}

// function for catalog frontend /////////////////////////////////////////////////
// Develop by iQuickweb.com 13/08/2012
///////////////////////////////////////////////////////////////////////////////////
function find($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$order=NULL,$separate=false,$pagenate=false,$page=NULL,$length=10,$oParent=NULL){
			/* type
					1. one = fine one item by id
					3. all  =  fine all item and category
					4. category_all = fine  category_id
					6. category_one = find item in category 
					7. in_category = fine all and relation incateogry
			*/
			return $this->_find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oParent) ; 
	}
	
	function findcount($type='one',$key=NULL,$slug=false,$status=1,$language='th',$search=NULL,$filter='',$oParent=NULL){
		 return  $this->_findcount($type,$key,$slug,$status,$language,$search,$filter,$oParent); 
	}

function getSearchableFieldByName(){
	$this->sql = " select catalogs_attrs.id, catalogs_attrs.attr_name  from catalogs_attrs  where catalogs_attrs.searchable=1 and catalogs_attrs.status=1 ";
	$this->select();
	 $fields = array();
	 if(!empty($this->rows)){
	 	foreach($this->rows as $row){
			 $fields[$row['attr_name']] = $row['id'];
	 	}
	 }
	 return $fields ;
}

function createSQLFiler($attr_id,$search_value, $operator=" = " ,$glue= " and "){
	if($operator==" like "){
		 $value  = 	"%$search_value%";
		 $value_default  = 	"$search_value";
	}else{
		$value  = 	"$search_value";
		$value_default  = 	"$search_value";
	}
	
		$sql = $glue." (catalogs.id in (select catalogs_attrs_val.catalog_id from catalogs_attrs_val left join catalogs_attrs_default on catalogs_attrs_default.id=catalogs_attrs_val.default_val and catalogs_attrs_val.attr_id=$attr_id  where ((catalogs_attrs_val.value $operator '$value') or (catalogs_attrs_default.value $operator '$value_default'))))";
	return $sql ;
	
}

function setFilterOpeartor($str){
	switch($str){
		case 'is':
			return " = ";
		break;
		case 'not':
			return " != ";
		break;
		case 'like':
			return " like ";
		break;
		case 'grater':
			return " > ";
		break;
		case 'isgrater':
			return " >= ";
		break;
		case "lower":
			return  " < ";
		break;
		case "islower":
			return  " <= ";
		break;
	}
}
	
 function FILTER_FUNCTON(){
	 	$sql  = '';
	 	$search_key = $_POST['search_key'] ;
		$category_id = $_POST['category_id'] ;
		$search_min_price = $_POST['search_min_price'] ;
		$search_max_price = $_POST['search_max_price'] ;
	 	$sql = " and $this->table.name like '%{$search_key}%' " ;
		if(!empty($category_id)&&$category_id!=0&&$category_id!='all'){
				$sql .= " and $this->table.category_id=$category_id "  ;
		}
		// create search 
		if(!empty($_POST['attr'])){
			$fields = $this->getSearchableFieldByName() ;
			foreach($_POST['attr'] as $pattr_name=>$pattr_arroperator){
				foreach($pattr_arroperator as $pattr_operator=>$pattr_value){
					if(!empty($pattr_value)){
						$sql .= $this->createSQLFiler($fields[$pattr_name],$pattr_value,$this->setFilterOpeartor($pattr_operator),' or ') ;
					//	echo  '<p/><p/>'.$sql.'<p/><p/>';
					}
				}
			}
		}
		
	//	echo $sql ;
		return $sql ;
 }
 
 // custom function 
 	function getCountReviewByLocation(){
		$attr_id = 99 ;
		$this->sql = "select * from catalogs_attrs_default where attr_id=$attr_id order by sequence asc ";
		$this->select();
		$attrs = $this->rows ;
		$list_count = array();
		$cnt = 0;
		foreach($attrs  as  $att){
			$field_id = $att['id'] ; 
			$this->sql = " select count(DISTINCT catalog_id) as total_review from catalogs_attrs_val where attr_id=$attr_id and default_val=$field_id ";
			$this->select();
			$list_count[$cnt] = array(
				'count'=>$this->rows[0]['total_review'],
				'name'=>$att['value'],
				'slug'=>urldecode($this->createSlug($att['value'])) ,
				'id'=>$att['id']  
			);
			$cnt++;
		}
		return $list_count ;
	}
	
	function getAttrValueList($attr_id){
		$this->sql = "select * from catalogs_attrs_default where attr_id=$attr_id order by sequence asc ";
		$this->select();
		$attrs = $this->rows ;
		$cnt= 0 ;
		foreach($attrs  as  $att){
			$list_count[$cnt] = array(
				'name'=>$att['value'],
				'slug'=>urldecode($this->createSlug($att['value'])) ,
				'id'=>$att['id']  
			);
			$cnt++;
		}
		return $list_count ;
	}
 //end custom function
 
 function checkInitSearch($key,$value){
	if(isset($value)&&!empty($value)){
		switch($key){
			case 'ค้นหาที่อยู่อาศัยตามทำเล':
				$location_list=$company = $this->getAttrValueList(99) ;
				foreach($location_list as $l){
					if($l['slug']==$value){
						$_POST['attr']['zone']['is'] = $l['name'] ;
					}
				}
			break;
			case 'ค้นหาที่อยู่อาศัยตามบริษัท':
				$company = $this->getAttrValueList(84) ;
				foreach($company as $l){
					if($l['slug']==$value){
						$_POST['attr']['company']['is'] = $l['name'] ;
					}
				}
			break;
			case 'ค้นหาที่อยู่อาศัยตามทำเลBTS':
				$bts = $this->getAttrValueList(100) ;
				foreach($bts as $l){
					if($l['slug']==$value){
						$_POST['attr']['bts']['is'] = $l['name'] ;
					}
				}
			break;
			case 'ค้นหาที่อยู่อาศัยตามทำเลMRT':
				$mrt = $this->getAttrValueList(101) ;
				foreach($mrt as $l){
					if($l['slug']==$value){
						$_POST['attr']['mrt']['is'] = $l['name'] ;
					}
				}
			break;
			case 'ค้นหาที่อยู่อาศัยตามทำเลรฟท':
				$train = $this->getAttrValueList(119) ;
				foreach($train as $l){
					if($l['slug']==$value){
						$_POST['attr']['train']['is'] = $l['name'] ;
					}
				}
			break;
			case 'รีวิวแท็ก':
						$_POST['attr']['tags']['like'] = urldecode($value);
			break;
			case 'ผลการค้นหา':
					 $_POST['attr']['tags']['like'] = urldecode($value);
			break;
		}
	}
 }
 
}// class
?>