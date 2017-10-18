<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class Newsletters extends Database {
	var $module;
	public function __construct($params=NULL){
		$this->module = $params['module'];
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
	public function datePickerToTime($in_date){
		list($date,$time) = explode(' ',$in_date);
		list($month,$day,$year) = explode('/',$date);
		return $year.'-'.$month.'-'.$day.' '.$time.':00';
	}
	public function timeToDatePicker($in_date){
		list($date,$time) = explode(' ',$in_date);
		list($year,$month,$day) = explode('-',$date);
		list($h,$m,$s)=explode(':',$time);
		return $month.'/'.$day.'/'. $year.' '.$h.':'.$m;
	}
	public function getSize($status=NULL){
		if(!empty($status)&&$status==1){
			$this->sql = "select $this->primary_key from $this->table where $this->table.status=$status ";
		}else{
			$this->sql = "select $this->primary_key from $this->table ";
		}
		return $this->select('size');
	}
	public function getAll($search,$order,$limit,$language=NULL){
		$this->sql = "SELECT $this->table.* FROM $this->table $search $order $limit";
		$this->select();
		return $this->rows;
	}
	public function getExportAll(){
		$this->sql = "SELECT $this->table.email FROM $this->table where status=1 ";
		$this->select();
		$email = $this->rows;
		$data = array();
		foreach($email as $e){
			$data[] = $e['email'];
		}
		return $data;
	}
	public function getOne($key,$slug=false,$language=NULL){
		if($slug){
			if(!empty($language)&&$language!=$this->site_language){
				$this->sql = "select $this->table.*,$this->table_translate.* from $this->table left join $this->table_translate on $this->table.primary_key=$this->table_translate.id and $this->table_translate.lang=$language where $this->table.slug=$key ";
			}else{
				$this->sql = "select * from $this->table where $this->table.slug=$key ";
			}
		}else{
			if(!empty($language)&&$language!=$this->site_language){
				$this->sql = "select $this->table.*,$this->table_translate.* from $this->table left join $this->table_translate on $this->table.primary_key=$this->table_translate.id and $this->table_translate.lang=$language where $this->table.$this->primary_key=$key ";
			}else{
				$this->sql = "select * from $this->table where $this->primary_key=$key ";
			}
		}
		$this->select();
		return $this->rows[0];
	}
	public function insertData($email){
		$this->sql = "insert into $this->table (email,cdate,status) ";
		$this->sql .= " values('$email',NOW(),1) ";
		$this->insert();
	}
	public function updateData($email,$status){
		$this->sql = "update $this->table set status=$status where email='$mail' ";
		$this->update();
	}
	public function deleteData($id){
		$this->sql = "delete from $this->table where $this->primary_key=$id ";
		$this->delete();
	}
	public function updateStatus($id,$status){
		$this->sql="update $this->table set status=$status where $this->primary_key=$id ";
		$this->update();
	}
	public function updateStatusByEmail($email,$status){
		$this->sql="update $this->table set status=$status where email='$email' ";
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
}
?>