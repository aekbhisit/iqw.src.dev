<?php
class Database {
	var $conn;
	var $table;
	var $sql;
	var $result;
	var $size;
	var $rows;
	var $insert_id;
	/// var for tree structure
	var $primary_key;
	var $parent_table;
	var $child_table;
	var $parent_primary_key;
	var $child_primary_key;
	var $error; 
	// var for tranlslate
	var $site_language;
	var $is_translate = false;
	var $parent_translate_table;
	var $translate_table;  
	public function __construct($table,$primary_key='id'){
		$this->connect();
		$this->table = $table;
		$this->primary_key = $primary_key;
		$this-> select_db();
	}
	function domainCheck(){
		$allowed_hosts = array('example.com');
		if (!in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
			exit;
		}
 	}
	public function connect(){
		$this->conn = @mysql_connect(DB_SERVER,DB_USER,DB_PASS);
	}
	public function select_db(){
			@mysql_select_db(DB_DATABASE,$this->conn);
			@mysql_query("SET NAMES UTF8"); 
			@mysql_query("SET CHARACTER SET 'uft8'");  
	}
	public function disconnect() {
		return @mysql_close();
	}
	public function query(){
		$this->result = NULL;
		//echo $this->sql;
		$this->result = @mysql_query($this->sql) or die( mysql_error() );
	}
	public function num_rows() {
		return @mysql_num_rows($this->result);
	}
	
	public function free_result() {
		return @mysql_free_result($this->result);
	}
	
	public function num_fields() {
		return @mysql_num_fields($this->result);
	}
	
	public function fields_name($offset) {
		return mysql_field_name($this->result);
	}
	
	public function fetch_array() {
		return @mysql_fetch_array($this->result,MYSQL_ASSOC);
	}
	
	public function fetch_assoc() 
	{
		return @mysql_fetch_assoc($this->result);
	}
	
	public function NumRows() 
	{
		return @mysql_num_rows($this->result);
	}

	public function fetch_rows() {
		return @mysql_fetch_row($this->result);
	}
	
	public function fetch_object() {
		return @mysql_fetch_object($result);
	}
	
	public function fetch_field() {
		return @mysql_fetch_field($result);
	}
	
	public function insert_id() {
		return @mysql_insert_id();
	}
	public function setString($string){ 
		return trim(htmlentities($string,ENT_QUOTES,'UTF-8'));
		//$convmap = array(0x0, 0x1FFFFF, 0, 0x1FFFFF);
		//return mb_encode_numericentity ($string, array (0x0, 0xffff, 0, 0xffff), 'UTF-8');
		//return mb_encode_numericentity(htmlentities($string,ENT_QUOTES,'UTF-8'),$convmap,'utf-8') ;
	}
	
	public function getString($string){
		return html_entity_decode($string,ENT_QUOTES,'UTF-8');
	}
	
	public function setInt($int){
		return (int)addslashes($int);
	}
	
	public function setFloat($float){
		return (float)addslashes($float);
	}
	//// custom common function
	function select($type=NULL){
		$this->query();
		if(empty($type)){
			$this->rows =NULL;
			$cnt=0 ;
			while($row=$this->fetch_assoc()){
				$this->rows [$cnt] = $row  ; 
				$cnt++ ;
			}
			$this-> free_result() ;
			return $this->rows ;
		}
		if($type=='size'){
			 $this->size = $this->NumRows() ;
			 $this-> free_result() ;
			 return $this->size ;
		}
	}
	
	public function insert(){
		$result  = $this->query();
		$this->insert_id  =  $this->insert_id() ;
		return $result ;
	}
	
	public function update(){
		 return $this->query();
	}
	
	public function delete(){
		 return $this->query();
	}
	
	public function createSlug($str){ 
			$str = $this->sanitize($str);
			return $str;
}

	public function reorder($field='sequence'){
		$this->sql ="select $this->primary_key from  $this->table order by $field asc";
		$this->select();
		$data = $this->rows ;
		foreach($data as $key =>$id){
			$this->sql ="update $this->table set $field=$key+1 where $this->primary_key=".$id['id']." ";
			$this->update();
		}
	}
	
	function moveRow($id,$type,$field='sequence'){
		$this->reorder() ;
		if($type=='up'){
			$this->sql="select $field  from $this->table where $this->primary_key=$id ";
			$this->select();
			$noworder = $this->rows[0][$field] ;
			$this->sql="update $this->table set $field=$noworder where $field=$noworder-1 ";
			$this->update();
			$this->sql="update $this->table set $field=$noworder-1 where $this->primary_key=$id ";
			$this->update();
		}else{
			$this->sql="select $field  from $this->table where $this->primary_key=$id ";
			$this->select();
			$noworder = $this->rows[0][$field] ;
			$this->sql="update $this->table set $field=$noworder where $field=$noworder+1 ";
			$this->update();
			$this->sql="update $this->table set $field=$noworder+1 where $this->primary_key=$id ";
			$this->update();
		}
	}
// //////////// function  tree database ////////////////////
	function check_root_node(){
		$this->sql = "select $this->primary_key from $this->table where parent_id=0 and level=0 ";
		$this->select();
		if(empty($this->rows)){
			$this->sql =  "insert into $this->table (parent_id,lft,rght,level,name)values(0,1,2,0,'root')" ;
			$this->insert() ;
			return  $this->insert_id();
		}else{
			return $this->rows[0][$this->primary_key] ;
		}
	}
	

	function create_root_node($id, $where=NULL){
		$this->sql =  "insert into $this->table (id,parent_id,lft,rght,level)values(0,0,1,4,0)" ;
		$this->insert() ;
		$this->sql =  "update $this->table set lft=2,rght=3,level=1 where $this->primary_key=$id " ;
		$this->update() ;
	}
	
	function insert_node($parent_id,$id){
		$parent = $this->get_node($parent_id) ;
		if($parent!==false&&!empty($parent['lft'])&&!empty($parent['rght'])){
				$lft = $parent['lft'];
				$rght = $parent['rght'];
				$this->sql = "update $this->table set rght = rght + 2  where rght >=$rght ";
				$this->update();
				$this->sql = "update $this->table set lft = lft + 2 where lft >$rght ";
				$this->update();
				$new_lft = $rght ; 
				$new_rght = $rght+1 ;
				$new_level =$parent['level']+1 ;
				$this->sql = "update $this->table set lft=$new_lft, rght=$new_rght,level=$new_level where $this->primary_key=$id " ;
				$this->update() ;
		}else{
			 //	$this->create_root_node($id);
		}
	}
	
	function get_parent_info($id) {
        $this->sql = " SELECT  p.*  FROM  $this->table as p, $this->table as c  WHERE  c.$this->primary_key = $id and p.$this->primary_key= c.parent_id" ;
      	$this->select();
		return $this->rows[0] ;
    }
	
	function get_parent_id($id){
		$this->sql = "select parent_id from $this->table where $this->primary_key = $id";
		$this->select();
		return $this->rows[0]['parent_id'];
	}
	
	function get_node($id,$where=NULL){
		$this->sql =  "select parent_id , lft, rght,level from $this->table where $this->primary_key=$id ";
		if(!empty($where)){ 
			if($where=='enable'){
				$this->sql .= " and status = 1" ;
			}else{
				$this->sql .=$where ;	
			}
		}
		$this->select();
			if(!empty($this->rows)){
				return array('lft'=>$this->rows[0]['lft'],'rght'=>$this->rows[0]['rght'],'level'=>$this->rows[0]['level'],'parent_id'=>$this->rows[0]['parent_id']);
			}else{
				return false ;	
			}
	}
	 
 	function get_tree($where=NULL,$table=NULL){
		if(empty($table)){
			$this->sql = "select c.*, COUNT(*)-1 as clevel from $this->table as c, $this->table as p where c.lft between  p.lft and p.rght";
		}else{
			$this->sql = "select c.*, COUNT(*)-1 as clevel from $table as c, $table as p where c.lft between p.lft and p.rght ";
		}
		if(!empty($where)){
			if($where=='enable'){
				$this->sql .= " and c.status=1 and p.status=1 group by c.lft order by c.lft  " ;
			}else{
				if($where=='disable'){
					$this->sql .= " and c.status=0 and p.status=0 group by c.lft order by c.lft  " ;
				}else{
					$this->sql .=$where ;	
				}
			}
		}else{
			$this->sql  .= " group by c.lft order by c.lft ";
		}
		// echo $this->sql ;
		$this->select();   
		return $this->rows ;
	}
	 
	function get_tree_from_child($where=NULL,$table=NULL){
		if(empty($table)){
			$this->sql = "select c.*, COUNT(*)-1 as clevel from $this->parent_table as c, $this->parent_table as p where   c.lft between  p.lft and p.rght ";
		}else{
			$this->sql = "select c.*, COUNT(*)-1 as clevel from  $this->parent_table as c,  $this->parent_table as p where   c.lft between  p.lft and p.rght ";
		}
		if(!empty($where)){
			if($where=='enable'){
				$this->sql .= " and c.status=1 and p.status=1 " ;
			}else{
				if($where=='disable'){
					$this->sql .= " and c.status=0 and p.status=0 " ;
				}else{
					$this->sql .=$where ;	
				}
			}
		}else{
			$this->sql  .= " group by c.lft order by c.lft ";
		}
		// echo $this->sql  ;
		$this->select();   
		return $this->rows ;
	}
	
	function get_onlychild_node($id,$enabale=NULL){
		$node = $this->get_node($id) ;
		$left = $node['lft'] ;
		$right = $node['rght'] ;
		$this->sql = "select c.id,c.lft,c.rght, COUNT(*)-1 as clevel from $this->table as c, $this->table as p where c.lft between p.lft and p.rght and p.lft between $left+1 and $right-1";
		if($enabale=='enable'){
				$this->sql .= " and c.status=1 and p.status=1" ;
		}
		$this->sql  .= " group by c.lft order by c.lft ";
		$this->select();   
		return $this->rows ;
	}
	
	function get_child_node($id,$enable=NULL,$slug=false){
		// echo "id=$id";
		if($slug==true&&!empty($id)){
			if($slug==true){
					$this->sql  = "select id from $this->table where slug like '$id' " ;
			}else{
				$this->sql  = "select id from $this->table where id like '$id' " ;
			}
			//echo $this->sql ;
			$this->select();
			$id = $this->rows[0]['id'];
		}
		$node = $this->get_node($id) ;
		$left = $node['lft'] ;
		$right = $node['rght'] ;
		$this->sql = "select c.id, c.lft, c.rght, COUNT(*)-1 as clevel from $this->table as c, $this->table as p where (c.lft between p.lft and p.rght) and (p.lft between $left and $right) ";
		if($enable=='enable'){
				$this->sql .= " and c.status=1 and p.status=1" ;
		}
		$this->sql  .= " group by c.lft order by c.lft asc ";
		// echo '<p>'. $this->sql ;
		$this->select();   
		return $this->rows ;
	}
	
	function get_lastchild_node($id,$enable=NULL){
		$node = $this->get_node($id) ;
		$left = $node['lft'] ;
		$right = $node['rght'] ;
		$this->sql = "select c.id,c.lft,c.rght,c.parent_id,c.level, COUNT(*)-1 as clevel from $this->table as c, $this->table as p where c.lft between  p.lft and p.rght and p.lft between $left and $right and c.rght=c.lft+1";
		if($enabale=='enable'){
				$this->sql .= " and c.status=1 and p.status=1" ;
		}
		$this->sql  .= " group by c.lft order by c.lft ";
		$this->select();   
		return $this->rows ;
	}
	
	function delete_node($id,$where=NULL){
		$node =  $this->get_node($id) ;
		if($node){
			$this->sql ="delete from $this->table where lft BETWEEN " . $node["lft"] . " and " . $node["rght"] ."";
			$this->delete();
			$this->sql = "update $this->table set lft = lft - ROUND((" . $node["rght"] . " - " . $node["lft"] . " + 1)) where lft > " . $node["rght"] . ";";
			$this->update();
			$this->sql = "update $this->table set rght = rght - ROUND((" . $node["rght"] . " - " . $node["lft"] . " + 1)) where rght > " . $node["rght"] . ";";
			$this->update();
		}
	}
	
	function update_parent_rght($id,$rght){
		$this->sql =  "update $this->table set rght=$rght where $this->primary_key=$id ";
		$this->update();
	}
	
	function update_position($id,$lft,$rght){
	//	$rght = $lft+1 ;
	//	$this->update_parent_rght($this->get_parent_id($id),$rght+1) ;
		$this->sql =  "update $this->table set lft=$lft , rght=$rght where $this->primary_key = $id";
	//	echo '<p>'.$this->sql ;
		$this->update();
	}
	
	function get_left_node ($id){
		$this->sql ="select l.$this->primary_key as id, l.lft as lft, l.rght as rght from $this->table as c, $this->table as l  where l.rght=(c.lft -1) and c.$this->primary_key = $id  ";
		$this->select();
		//print_r($this->rows) ;
		return array('id'=>$this->rows[0]['id'],'lft'=>$this->rows[0]['lft'],'rght'=>$this->rows[0]['rght']);
	}
	
	function get_right_node ($id){
		$this->sql ="select r.$this->primary_key as id, r.lft as lft, r.rght as rght from $this->table as c, $this->table as r  where r.lft=c.rght+1 and c.parent_id= r.parent_id and c.$this->primary_key = $id  ";
		$this->select();
		return array('id'=>$this->rows[0][$this->primary_key],'lft'=>$this->rows[0]['lft'],'rght'=>$this->rows[0]['rght']);
	}
	
	function move($id,$position){
		$node = 	$this->get_node($id) ;
		$child = $this->get_child_node($id) ;
		if($position=='left'||$position=='up'){
			$left_node = $this->get_left_node ($id) ;
			$left_child = $this->get_child_node($left_node['id']) ;
			$lft_cal = $left_node['lft']-$node['lft'] ;
			$rght_cal = $left_node['rght']-$node['rght'] ;
			if(!empty($child )){
				foreach($child as $c ){
					$new_lft_cal = $c['lft']+$lft_cal ;
					$new_rght_cal = $new_lft_cal+($c['rght'] -$c['lft']) ; 
					$this-> update_position($c['id'],$new_lft_cal,$new_rght_cal) ;
				}
			}
			if(!empty($left_child )){
				foreach(	$left_child as $c ){
					$new_lft_cal = $c['lft']-$rght_cal ;
					$new_rght_cal = $new_lft_cal +($c['rght'] -$c['lft']) ; 
					$this-> update_position($c['id'],$new_lft_cal,$new_rght_cal) ;
				}
			}
		}
		
		if($position=='right'||$position=='down'){
			$right_node = $this->get_right_node ($id) ;
			$right_child = $this->get_child_node($right_node['id']) ;
			$lft_cal = $node['lft']-$right_node['lft'] ;
			$rght_cal = $node['rght']-$right_node['rght'] ;
			if(!empty($right_child )){
				foreach(	$right_child as $c ){
					$new_lft_cal = $c['lft']+$lft_cal ;
					$new_rght_cal = $new_lft_cal+($c['rght'] -$c['lft']) ; 
					$this-> update_position($c['id'],$new_lft_cal,$new_rght_cal) ;
				} 
			}
			if(!empty($child )){
				foreach($child as $c ){
					$new_lft_cal = $c['lft']-$rght_cal  ;
					$new_rght_cal = $new_lft_cal+($c['rght'] -$c['lft']) ; 
					$this-> update_position($c['id'],$new_lft_cal,$new_rght_cal) ;
				}
			}
		}
	}
	
	function move_parent($id,$parent_id){
		if($parent_id==0){
			$parent_id = $this->check_root_node();
		}
		$old_node = $this->get_node($id);
		$o_lft =  $old_node['lft'] ;
		$o_rght =  $old_node['rght'] ;
		$o_level =  $old_node['level'] ;
		
		$new_parent_node = $this->get_node($parent_id);
		$n_lft = $new_parent_node['lft'] ;
		$n_rght = $new_parent_node['rght'] ;
		$n_level = $new_parent_node['level'] ;
		
		$this->sql = "update $this->table set parent_id=$parent_id where $this->primary_key = $id ";	
		//echo $this->sql  ;
		$this->update();
		
		if($o_rght<$n_rght){
				$cal_change = ($o_rght-$o_lft)+1  ;
				$lft = $n_lft ;
				$rght = $n_rght ;
				$this->sql = "update $this->table set rght = rght + $cal_change  where rght >=$rght ";
				//echo $this->sql  ;
				$this->update();
				$this->sql = "update $this->table set lft = lft + $cal_change where lft >$rght ";
				//echo $this->sql  ;
				$this->update();
				$new_lft = $n_rght ; 
				$this->sql = "update $this->table set lft=$new_lft+(lft-$o_lft), rght=$new_lft+(rght+$o_lft),level=$n_level+1+(level-$o_level) where lft>=$o_lft and rght <=$o_rght  " ;
		//		echo $this->sql  ;
				$this->update() ;
				
				$this->sql = "update $this->table set lft = lft - ROUND((" . $o_rght . " - " . $o_lft . " + 1)) where lft > " . $o_rght . ";";
				//echo $this->sql  ;
				$this->update();
				$this->sql = "update $this->table set rght = rght - ROUND((" .  $o_rght. " - " . $o_lft. " + 1)) where rght > " .  $o_rght . ";";
				//echo $this->sql  ;
				$this->update();
		}else{
				$cal_change = $o_rght-$o_lft+1  ;
				$lft = $n_lft ;
				$rght = $n_rght ;
				$this->sql = "update $this->table set rght = rght + $cal_change  where rght >=$rght ";
				//echo $this->sql  ;
				$this->update();
				$this->sql = "update $this->table set lft = lft + $cal_change where lft >$rght ";
				//echo $this->sql  ;
				$this->update();
				$new_lft = $n_rght ; 
				$old_affter_lft = $o_lft+$cal_change ;
				$old_affter_rght  = $o_rght + $cal_change ;
				$this->sql = "update $this->table set lft=$new_lft+(lft-$old_affter_lft), rght=$new_lft+(rght-$old_affter_lft),level=$n_level+1+(level-$o_level) where lft>=$old_affter_lft and rght <=$old_affter_rght" ;
				//echo $this->sql.'==='  ;
				$this->update() ;
				$new_move_rght = $o_rght+$cal_change ;
				$this->sql = "update $this->table set lft = lft - ROUND((" . $o_rght . " - " . $o_lft . " + 1)) where lft > " .$old_affter_rght   . "";
				//echo $this->sql  ;
				$this->update();
				$this->sql = "update $this->table set rght = rght - ROUND((" .  $o_rght. " - " . $o_lft. " + 1)) where rght > " .  $old_affter_rght . "";
				//echo $this->sql  ;
				$this->update();
		}
		
	}
	
	
	//taken from wordpress
function utf8_uri_encode( $utf8_string, $length = 0 ) {
    $unicode = '';
    $values = array();
    $num_octets = 1;
    $unicode_length = 0;

    $string_length = strlen( $utf8_string );
    for ($i = 0; $i < $string_length; $i++ ) {

        $value = ord( $utf8_string[ $i ] );

        if ( $value < 128 ) {
            if ( $length && ( $unicode_length >= $length ) )
                break;
            $unicode .= chr($value);
            $unicode_length++;
        } else {
            if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

            $values[] = $value;

            if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
                break;
            if ( count( $values ) == $num_octets ) {
                if ($num_octets == 3) {
                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
                    $unicode_length += 9;
                } else {
                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
                    $unicode_length += 6;
                }

                $values = array();
                $num_octets = 1;
            }
        }
    }

    return $unicode;
}

//taken from wordpress
function seems_utf8($str) {
    $length = strlen($str);
    for ($i=0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80) $n = 0; # 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
        elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
        elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
        elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
        elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
        else return false; # Does not match any model
        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                return false;
        }
    }
    return true;
}

//function sanitize_title_with_dashes taken from wordpress
function sanitize($title) {
    $title = strip_tags($title);
    // Preserve escaped octets.
    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    // Remove percent signs that are not part of an octet.
    $title = str_replace('%', '', $title);
    // Restore octets.
    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

    if ($this->seems_utf8($title)) {
        if (function_exists('mb_strtolower')) {
            $title = mb_strtolower($title, 'UTF-8');
        }
        $title = $this->utf8_uri_encode($title, 2048);
    }

    $title = strtolower($title);
    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    $title = str_replace('.', '-', $title);
    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
    $title = preg_replace('/\s+/', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = trim($title, '-');

    return $title;
}
//// function fore tranlate_language
	public function getLanguage($status=1){
		$default_lang= SITE_LANGUAGE ;
		$this->sql  = " select * from  languages where status = $status and code != '$default_lang' ";
		$this->select();
		return $this->rows ;
	}
	
	public function getLanguageAll(){
		$default_lang= SITE_LANGUAGE ;
		$this->sql  = " select * from  languages ";
		$this->select();
		return $this->rows ;
	}
	
	public function getLanguageUsage(){
		$default_lang= SITE_LANGUAGE ;
		$this->sql  = " select * from  languages where status = 1 ";
		$this->select();
		return $this->rows ;
	}
	
	public function updateSiteLanguage ($languages){
		$this->sql  = " update languages set status = 0   " ;
		$this->update();
	
			foreach($languages as $lang){
				$this->sql  = " update languages set status = 1 where code='$lang' ";
				$this->update();
			}
	}
	
	///// function Query Data frontend
	public function _find($type,$key,$slug,$status,$language,$search,$filter,$order,$separate,$pagenate,$page,$length,$oParent){
		/* type
			1. one = fine one item by id
			3. all  =  fine all item and category
			4. category_all = fine  category_id
			6. category_one = find item in category 
			7. in_category = fine all and relation incateogry
		*/
		// injection protect
		$slug = $this->_valid($slug,'bool'); 
		if($slug){
			$key = $this->_valid(urldecode($key));
		}else{
			$key = $this->_valid($key,'int');
		}
		$status = $this->_valid($status,'int');
				$language =  $this->_valid($language);
		$search = $this->_valid($search);
		$filter =  $this->_valid($filter);
		$order =  $this->_valid($order);
		$separate = $this->_valid($separate,'bool');
		$pagenate = $this->_valid($pagenate,'bool');
		$page =  $this->_valid($page,'int');
		$length =  $this->_valid($length,'int');
		// injection protect
		switch($type){
			case 'one':
				if($slug){
					if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
						$this->sql = "select * from $this->table where $this->table.slug = '$key' ";
					}else{
						$this->sql  = "select $this->table.*, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.$this->primary_key=$this->translate_table.$this->primary_key  where $this->table.slug = '$key' and $this->translate_table.lang='$language' ";
					}
				}else{
					if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
						$key = (int)$key;
						$this->sql = "select * from $this->table where $this->table.$this->primary_key = $key";
					}else{
						$this->sql  = "select $this->table.*, $this->translate_table.* from $this->table left join $this->translate_table on $this->table.$this->primary_key=$this->translate_table.$this->primary_key  where $this->table.$this->primary_key = $key and $this->translate_table.lang='$language' ";
					}
				}
				$this->select();
				return $this->rows[0];
			break;
			case 'all':
				if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
					$this->sql = "SELECT $this->table.* , $this->parent_table.name as category , $this->parent_table.id as category_id from $this->table LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key WHERE $this->table.status=$status ";
					if(!empty($search)){
						$this->sql .= " and ($this->table.name LIKE '%$search%'  OR  $this->table.content LIKE '%$search%') ";
					}
					if(!empty($filter)){
						$this->sql .= " and ( $filter ) ";
					}
					if(!empty($order)){
						$this->sql .= " ORDER BY $order ";
					}
					if($pagenate){
						$page = (!empty($page))?$page:1;
						if($page<1){
							$page = 1;
						}
						$start_rec = ($page-1) * $length;
						$this->sql .= " LIMIT  $start_rec,$length "; 
					}
				}else{
					//$this->sql = "select $this->table.* from $this->table WHERE $this->table.status=$status ";
					$this->sql = "SELECT $this->table.* , $this->translate_table.* , $this->parent_table.name as category , $this->parent_table.id as category_id FROM $this->table LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key LEFT JOIN $this->translate_table ON $this->translate_table.$this->primary_key=$this->table.$this->primary_key WHERE $this->table.status=$status ";
					if(!empty($search)){
						$this->sql .= " ($this->translate_table.name LIKE '%$search%' OR $this->translate_table.content LIKE '%$search%') ";
					}
					if(!empty($order)){
						$this->sql .= " ORDER BY $order ";
					}
					if($pagenate){
						$page = (!empty($page))?$page:1;
						if($page<1){
							$page =  1 ;
						}
						$start_rec = ($page-1) * $length;
						$this->sql .= " LIMIT  $start_rec,$length ";
					}
				}
				$this->select();
				return $this->rows;
			break;
			case 'category_all':
				//echo 'category_all';
				if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
					if($status){
						$categories =  $oParent->get_tree(" WHERE status=1 $filter ");
					}else{
						$categories =  $oParent->get_tree(" WHERE status=0 $filter ");
					}
				}else{
					if($status){
						$categories =  $oParent->get_tree(" WHERE status=1 $filter ");
						foreach($categories as $key=>$c){
							$this->sql  = " select * from $this->parent_translate_table where $this->parent_translate_table.lang='$language' and $this->parent_translate_table.$this->parent_primary_key = ".$c[$this->primary_key];
							$this->select();
							$categories[$key]['name'] =  $this->rows[0]['name'];
							$categories[$key]['description'] =  $this->rows[0]['description'];
							$categories[$key]['image'] =  $this->rows[0]['image'];
						}
					}else{
						$categories =  $oParent->get_tree(" WHERE status=0 $filter ") ;
						foreach($categories as $key=>$c){
							$this->sql  = " select * from $this->parent_translate_table where $this->parent_translate_table.lang='$language' and $this->parent_translate_table.$this->parent_primary_key = ".$c[$this->primary_key];
							$this->select();
							$categories[$key]['name'] =  $this->rows[0]['name'];
							$categories[$key]['description'] =  $this->rows[0]['description'];
							$categories[$key]['image'] =  $this->rows[0]['image'];
						}
					}
				}
				return $categories;
			break;
			case 'category_one':
				if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
				 	$this->sql = "select * from $this->parent_table where $this->parent_primary_key=$key ";
				}else{
					$this->sql  = "select $this->parent_table.*, $this->parent_translate_table.* from $this->parent_table left join $this->parent_translate_table on $this->parent_table.$this->primary_key=$this->parent_translate_table.$this->primary_key  where $this->parent_table.$this->primary_key = $key and $this->parent_translate_table.lang = '$language' ";
				}
				$this->select();
				return $this->rows[0];
			break;
			case 'in_category':
				//	echo 'find in cat' ;
				$categories = $oParent->get_child_node($key,'enable',$slug);
				//	echo 'categories parent';
				foreach($categories as $category){
					if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
						$this->sql = "SELECT $this->table.*, $this->parent_table.name as category, $this->parent_table.id as category_id FROM $this->table LEFT JOIN $this->parent_table ON $this->table.category_id=$this->parent_table.$this->parent_primary_key WHERE $this->table.category_id= ".$category['id']." AND $this->table.status=$status ";
						if(!empty($search)){
							$this->sql .= " AND ($this->table.name LIKE '%$search%' OR $this->table.content LIKE '%$search%') ";
						}
						if(!empty($filter)){
							$this->sql .= " AND ($filter) " ;
						}
						if(!empty($order)){
							$this->sql .= " ORDER BY $order ";
						}
						if($pagenate){
							$page = (!empty($page))?$page:1;
							if($page<1){
								$page = 1;
							}
							$start_rec = ($page-1)*$length ;
							$this->sql .= " LIMIT  $start_rec,$length ";
						}
					}else{
						$this->sql = "SELECT $this->table.*, $this->translate_table.*, $this->parent_table.name as category, $this->parent_table.id as category_id FROM $this->table  LEFT JOIN $this->parent_table ON $this->table.category_id=$this->parent_table.$this->parent_primary_key LEFT JOIN $this->translate_table ON $this->translate_table.$this->primary_key=$this->table.$this->primary_key WHERE $this->table.category_id= ".$category['id']." AND $this->table.status=$status and $this->translate_table.lang='$language' ";
						if(!empty($search)){
							$this->sql .= " AND ($this->translate_table.name LIKE '%$search%' OR $this->translate_table.content LIKE '%$search%') ";
						}
						if(!empty($filter)){
							$this->sql .= " AND ($filter) ";
						}
						if(!empty($order)){
							$this->sql .= " ORDER BY $order ";
						}
						if($pagenate){
							$page = (!empty($page))?$page:1;
							if($page<1){
								$page = 1;
							}
							$start_rec = ($page-1)*$length;
							$this->sql .= " LIMIT  $start_rec,$length ";
						}
						//echo $this->sql;
					} // if site language
					$this->select();
					$data[$category['id']] = $this->rows;
				} // foreach
				// separate data in category 
				if(!empty($data)&&!$separate){
					$new_data = $data;
					$sp_data = array();
					$cnt = 0;
					foreach($new_data as $dd_key => $dd){
						if(!empty($dd)&&is_array($dd)){
							foreach($dd as  $ld_key=>$ld){
								$sp_data[$cnt] =  $ld;
								$cnt++;
							}
						}
					}
					$data = $sp_data;
				}
				return $data;
			break;
		}
	}
	
	function _findcount($type,$key,$slug,$status,$language,$search,$filter,$oParent){
			// injection protect
				if($slug){
					$key = $this->_valid($key) ;
				}else{
					$key = $this->_valid($key,'int') ;
				}
				$status =  $this->_valid($status,'int') ;
				$language =  $this->_valid($language) ;
				$search = $this->_valid($search) ;
				$filter =  $this->_valid($filter) ;
				// injection protect
			switch($type){
				case 'all':
						if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
							$this->sql = "SELECT $this->table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key WHERE $this->table.status=$status ";
							if(!empty($search)){
								$this->sql .= " AND ($this->table.name LIKE '%$search%'  OR  $this->table.content LIKE '%$search%') " ;
							}
							if(!empty($filter)){
										$this->sql .= " AND ( $filter) " ;
									}
						}else{
							$this->sql = "SELECT $this->table.*, $this->translate_table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key LEFT JOIN $this->translate_table ON $this->translate_table.$this->primary_key=$this->table.$this->primary_key WHERE $this->table.status=$status ";
							if(!empty($search)){
								$this->sql .= " AND ($this->translate_table.name LIKE '%$search%'  OR  $this->translate_table.content LIKE '%$search%') " ;
							}
							if(!empty($filter)){
										$this->sql .= " AND ( $filter ) " ;
							}
						}
						return  $this->select('size') ;
				break ;
				case 'category_all':
						if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
							if($status){
								$categories =  $oParent->get_tree(" WHERE status=1 $filter ") ;
							}else{
								$categories =  $oParent->get_tree(" WHERE status=0 $filter ") ;
							}
						}
						return count($categories) ;
				break ;
				case 'in_category':
				    	$categories = $oParent->get_child_node($key,'enable',$slug) ;
						$cnt = 0  ;
							foreach($categories as $category){
									if($language==SITE_LANGUAGE||SITE_TRANSLATE==false){
										$this->sql = "SELECT $this->table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key WHERE $this->table.category_id= ".$category['id']." AND  $this->table.status=$status ";
										if(!empty($search)){
											$this->sql .= " AND ($this->table.name LIKE '%$search%'  OR  $this->table.content LIKE '%$search%') " ;
										}
										if(!empty($filter)){
											$this->sql .= " AND ($filter) " ;
										}
									}else{
										$this->sql = "SELECT $this->table.*, $this->translate_table.*, $this->parent_table.name as category FROM $this->table  LEFT JOIN $this->parent_table  ON  $this->table.category_id=$this->parent_table.$this->parent_primary_key LEFT JOIN $this->translate_table ON $this->translate_table.$this->primary_key=$this->table.$this->primary_key WHERE  $this->table.category_id= ".$category['id']." AND $this->table.status=$status ";
										if(!empty($search)){
											$this->sql .= " AND ($this->translate_table.name LIKE '%$search%'  OR  $this->translate_table.content LIKE '%$search%') " ;
										}
										if(!empty($filter)){
											$this->sql .= " AND ($filter) " ;
										}
									} // if site language
									$cnt+= $this->select('size');
							}// foreach
							return $cnt ;
				break ;
			}
	}
	///// end Query frontend data 
	
	///////  sql injectin protect 
	function _valid($inputVar, $type='text') {      
      switch ($type) {
        case "email":
            return filter_var($inputVar, FILTER_VALIDATE_EMAIL);
            break;   
        case "ip":
            return filter_var($inputVar, FILTER_VALIDATE_IP);
            break;  
        case "float":
            return filter_var($inputVar, FILTER_VALIDATE_FLOAT);
            break; 
        case "int":
            return filter_var($inputVar, FILTER_VALIDATE_INT);
            break;  
        case "url":
            return filter_var($inputVar, FILTER_VALIDATE_URL);
            break;  
        case "boolean":
            return filter_var($inputVar, FILTER_VALIDATE_BOOLEAN);
            break; 
        default :
		//	echo '908'.$inputVar;
            return mysql_real_escape_string(addslashes(htmlentities($inputVar, ENT_QUOTES))); 
        } 
	}
	
	}// class  db tree 
	




?>