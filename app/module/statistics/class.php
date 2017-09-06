<?php
class Statistics extends Database {
	var $module   ;
	var $stats_visit_table = 'stats_visit' ;
	public function __construct($module,$table=NULL){
		$this->module = (empty($table))?$module:$table  ;
		 parent::__construct((empty($table))?$module:$table );
	}
	
// function for rate  /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
///////////////////////////////////////////////////////////////////////////////////
	public function getStatsSize(){
		$this->sql = "SELECT $this->table.*, $this->stats_visit_table.url  as url FROM $this->table Left Join  $this->stats_visit_table ON $this->stats_visit_table.stat_id= $this->table.id ";
		return  $this->select('size');
	}
	public function getStatsAll($search,$order,$limit){
		$this->sql = "SELECT $this->table.*, $this->stats_visit_table.url  as url FROM $this->table Left Join  $this->stats_visit_table ON $this->stats_visit_table.stat_id= $this->table.id  $search $order $limit";
		$this->select();
		return $this->rows;
	}
	public function getStats($id){
		$this->sql = "select * from $this->table where $this->primary_key=$id ";
		$this->select();
		return  $this->rows[0];
	}
	public function showCountAllModule($modules){
		$count_all = array();
		if(!empty($modules)){
			foreach($modules as $m){
				$this->sql = "select COUNT(id) as total from $m ";
				$this->select();
				$count_all[$m] = $this->rows[0]['total'];			
			}
		}
		return $count_all;
	}
	public function setSumStat(){
		$week_start = date("Y-m-d", strtotime('sunday this week'));
		$today_start = date("Y-m-d").' 00:00:00';
		$today_end = date("Y-m-d").' 23:59:59';
		$today_m = date("m");
		$today_y = date("Y");
		$current_date = date("Y-m-d H:i:s");
		$this_sunday = date("Y-m-d",strtotime('this sunday')).' 00:00:00';
		$next_sunday = date("Y-m-d",strtotime('next saturday')).' 23:59:59';
		$this->sql = "select stat_last_update from configs_site ";
		$this->select();
		$last_stat_update = $this->rows[0]['stat_last_update'];
		if(!empty($last_stat_update)){
			list($lsu_date,$lsu_time) = explode(' ',$last_stat_update);
			list($lsu_y,$lsu_m,$lsu_d) = explode('-',$lsu_date);
 		}else{
 			$lsu_m = '01' ;
 			$lsu_y = date("Y");
 			$last_stat_update = date("Y").'-01-01 00:00:00';
 		}
 		if((int)$today_y > (int)$lsu_y){
	 		if( ((int)$today_y - (int)$lsu_y)>=2){
	 			$month_diff = (12-(int)$lsu_m)+((((int)$today_y - (int)$lsu_y)-1)*12)+((int)$today_m);
	 		}else{
	 			$month_diff = (12-(int)$lsu_m)+((int)$today_m);
	 		}
 		}else{
 			$month_diff = ((int)$today_m - (int)$lsu_m);
 		}
 		$this->sql = "select count(DISTINCT $this->table.id) as visitor from $this->table where $this->table.date between '$today_start' and '$today_end' ";
		$this->select();
		$today_visitor = $this->rows[0]['visitor'];
		$this->sql = "select count($this->stats_visit_table.id) as pages from $this->stats_visit_table   where $this->stats_visit_table.date between '$today_start' and '$today_end'  ";
		$this->select();
		$today_pv = $this->rows[0]['pages'];
		$this->sql = "select count(DISTINCT $this->table.id) as visitor from $this->table where $this->table.date between '$this_sunday' and '$next_sunday'  ";
		$this->select();
		$week_visitor = $this->rows[0]['visitor'];
		$this->sql = "select count($this->stats_visit_table.id) as pages from $this->stats_visit_table   where $this->stats_visit_table.date between '$this_sunday' and '$next_sunday'  ";
		$this->select();
		$week_pv = $this->rows[0]['pages'];
		$this->sql = "update configs_site set stat_today_visit=$today_visitor,stat_today_pv=$today_pv, stat_thisweek_visit=$week_visitor ,stat_thisweek_pv=$week_pv, stat_last_update=NOW()  where id=1 " ;
		$this->update();
 		$loop_m = (int)$lsu_m;
 		$loop_y = (int)$lsu_y;
 		$chk_date_array=array();
 		for($i=0;$i<=$month_diff;$i++){
 			$month = (($loop_m + $i)%12) ;
 			if($month<10){ $str_month = "0$month"; }else{ $str_month = "$month"; }
 			$year = $loop_y+((int)(($loop_m + $i)/12));
 			$end_date = "$year-$str_month-31 23:59:59";
 			$this->sql = "select count(DISTINCT $this->table.id) as visitor from $this->table where $this->table.date between '$last_stat_update' and '$end_date' ";
			$this->select();
			$visitor = $this->rows[0]['visitor'];
			$this->sql = "select count($this->stats_visit_table.id) as pages from $this->stats_visit_table where $this->stats_visit_table.date between '$last_stat_update' and '$end_date' ";
			$this->select();
			$pv = $this->rows[0]['pages'];
			$this->sql = "select visitor,pv from stats_sum_all where month=$month and year=$year ";
			$this->select();
			if(!empty($this->rows)){
				$old_visitor = $this->rows[0]['visitor']+ $visitor;
				$old_pv = $this->rows[0]['pv']+$pv;
				$this->sql = "update stats_sum_all set visitor=$old_visitor,pv=$old_pv, mdate=NOW() where month=$month and year=$year ";
				$this->update();
			}else{
				$this->sql = "insert into stats_sum_all (month,year,visitor,pv,mdate) values($month,$year,$visitor,$pv,NOW() ) ";
				$this->insert();
			}
 		}
 		$this->sql = " delete from stats where date < '$this_sunday' ";
		$this->delete();
		$this->sql = " delete from stats_visit where date < '$this_sunday' ";
		$this->delete();
	}
	public function getSumaryCurrentStat(){
		$this->sql = " select stat_today_visit,stat_today_pv,stat_thisweek_visit,stat_thisweek_pv from configs_site where id=1 ";
		$this->select();
		$stat['today_visitor'] = $this->rows[0]['stat_today_visit'];
		$stat['today_pv'] = $this->rows[0]['stat_today_pv'];
		$stat['week_visitor'] = $this->rows[0]['stat_thisweek_visit'];
		$stat['week_pv'] = $this->rows[0]['stat_thisweek_pv'];
		$today_m = (int)date("m");
		$today_y = (int)date("Y");
		$this->sql = " select visitor,pv from stats_sum_all where month=$today_m and year=$today_y ";
		$this->select();
		$stat['month_visitor'] = $this->rows[0]['visitor'];
		$stat['month_pv'] = $this->rows[0]['pv'];
		$this->sql = " select sum(visitor) as visitor ,sum(pv) as pv from stats_sum_all ";
		$this->select();
		$stat['all_visitor'] = $this->rows[0]['visitor'];
		$stat['all_pv'] = $this->rows[0]['pv'];
		return $stat;
	}

	public function getVisitorSumary($type=NULL,$date=NULL,$month=NULL){ //type : day week month ,$date date start to find data 2012-08-17 
		switch($type){
			case 'day':
			if(empty($date)){
				$start =   date("Y-m-d").' 00:00:00';
				$end =   date("Y-m-d").' 23:59:59';  
			}else{
				$start = $date .' 00:00:00';
				$end = $date.' 23:59:59';  
			}
			break ;
			case 'week':
				if(empty($date)){
					$ts = strtotime(date("Y-m-d"));
				}else{
					$ts = strtotime($date);
				}
   				 $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
				 $end =date('Y-m-d', strtotime('next saturday', $start)).' 23:59:59';
				 $start =date("Y-m-d",$start).' 00:00:00';
			break;
			case 'month':
				if(empty($date)){
					if(empty($month)){
   						$start = date("Y-m").'-1 00:00:00';
						$end =date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y'). '00:00:00')))).' 23:59:59';
					}else{
						$start = date("Y-").$month.'-1 00:00:00';
						$end =date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime($month.'/01/'.date('Y'). '00:00:00')))).' 23:59:59';
					}
				}else{
					list($y,$m,$d) = explode('-',$date);
   					$start = date("Y-").'-'.$m.'-1 00:00:00';
					$end =date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime($m.'/01/'.date('Y'). '00:00:00')))).' 23:59:59';
				}
			break;
			case "all":
				$start =  '0';
				$end =   date("Y-m-d").' 23:59:59';  
			break ;
			default:
				$start =   date("Y-m-d").' 00:00:00';
				$end =   date("Y-m-d").' 23:59:59';  
			break;
		}
		$this->sql = "select count(DISTINCT $this->table.id) as visitor, count($this->stats_visit_table.id) as pages   from $this->table left join $this->stats_visit_table  on $this->table.id=$this->stats_visit_table.stat_id where $this->table.date between '$start' and '$end'  ";
		$this->select();
		$stats = $this->rows[0] ;
		return $stats ;
	}
	
	public function getStatYearGraph(){
		$today_y = (int)date("Y");
		$this->sql = " select visitor,pv,month from stats_sum_all where year=$today_y ";
		$this->select();
		$stats = $this->rows ;
		$stats_array = array();
		if(!empty($stats)){
			foreach($stats as $stat){
				$stats_array[$stat['month']] = $stat;
			}
		}
		$year = array(
			1=>array('month'=>'Jan'),
			2=>array('month'=>'Feb'),
			3=>array('month'=>'Mar'),
			4=>array('month'=>'Apr'),
			5=>array('month'=>'May'),
			6=>array('month'=>'Jun'),
			7=>array('month'=>'Jul'),
			8=>array('month'=>'Aug'),
			9=>array('month'=>'Sep'),
			10=>array('month'=>'Oct'),
			11=>array('month'=>'Nov'),
			12=>array('month'=>'Dec')
		);
		$this_m = (int)date('m') ;
		for($i=1;$i<=$this_m;$i++){
			$year[$i]['data'] = $stats_array[$i];
		}
		return $year;
	}
	function setDeleteAllStatData(){
		$this->sql = "update configs_site set stat_today_visit=0,stat_today_pv=0, stat_thisweek_visit=0 ,stat_thisweek_pv=0, stat_last_update=NOW() where id=1 " ;
		$this->update();

		$this->sql = " delete from stats ";
		$this->delete();
		$this->sql = " delete from stats_visit ";
		$this->delete();
		$this->sql = " delete from stats_sum_all ";
		$this->delete();
	}

	function insertStatsLogs($url=NULL){
		if(!isset($_SESSION['stat_logs']['id'])){
			$ip = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
			$useragent =  $_SERVER['HTTP_USER_AGENT'];
			$UA =$this->getBrowser();
			$browser = $UA['browser'];
			$version = $UA['version'];
			$platform = $UA['platform'];
			$refer_url = $_SERVER['HTTP_REFERER'];
			$this->sql ="insert into $this->table (ip,browser,version,platform,refer_url,date) ";
			$this->sql .=" values($ip,'$browser','$version','$platform','$refer_url',NOW()) ";
			$this->insert();
			$stat_id = $this->insert_id();
			$_SESSION['stat_logs']['id']  =  $stat_id;
			$this->insertStateVisitLogs($stat_id,$url);
		}else{
			$this->insertStateVisitLogs($_SESSION['stat_logs']['id'] ,$url);
		}
	}
	
	function insertStateVisitLogs($stat_id,$url=NULL){
		if(empty($url)){
			$url = $_SERVER['REQUEST_URI']  ;
		}
		if(empty($_SESSION['stat_logs']['visit'][$url])){
			$this->sql  = "insert into $this->stats_visit_table (stat_id,url,date) values ($stat_id,'$url',NOW()) ";
			$this->insert();
			$_SESSION['stat_logs']['visit'][$url] = time();
		}
	}
	
	function getCountry($ip){
		$this->sql  = "select country, co1 FROM stats_country WHERE ip1 <= {$ip} AND ip2 >= {$ip};" ;
		$this->select();
		if(empty($this->rows)){
			return '--no data--' ;
		}else{
			 return	$this->rows[0]['country'];
		}
	}
	
	function getBrowser() 
	{ 
			$u_agent = $_SERVER['HTTP_USER_AGENT']; 
			$bname = 'Unknown';
			$platform = 'Unknown';
			$version= "";

			//First get the platform?
			if (preg_match('/linux/i', $u_agent)) {
				$platform = 'linux';
			}
			elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
				$platform = 'mac';
			}
			elseif (preg_match('/windows|win32/i', $u_agent)) {
				$platform = 'windows';
			}
			
			// Next get the name of the useragent yes seperately and for good reason
			if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
			{ 
				$bname = 'Internet Explorer'; 
				$ub = "MSIE"; 
			} 
			elseif(preg_match('/Firefox/i',$u_agent)) 
			{ 
				$bname = 'Mozilla Firefox'; 
				$ub = "Firefox"; 
			} 
			elseif(preg_match('/Chrome/i',$u_agent)) 
			{ 
				$bname = 'Google Chrome'; 
				$ub = "Chrome"; 
			} 
			elseif(preg_match('/Safari/i',$u_agent)) 
			{ 
				$bname = 'Apple Safari'; 
				$ub = "Safari"; 
			} 
			elseif(preg_match('/Opera/i',$u_agent)) 
			{ 
				$bname = 'Opera'; 
				$ub = "Opera"; 
			} 
			elseif(preg_match('/Netscape/i',$u_agent)) 
			{ 
				$bname = 'Netscape'; 
				$ub = "Netscape"; 
			} 
			
			// finally get the correct version number
			$known = array('Version', $ub, 'other');
			$pattern = '#(?<browser>' . join('|', $known) .
			')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			if (!preg_match_all($pattern, $u_agent, $matches)) {
				// we have no matching number just continue
			}
			
			// see how many we have
			$i = count($matches['browser']);
			if ($i != 1) {
				//we will have two since we are not using 'other' argument yet
				//see if version is before or after the name
				if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
					$version= $matches['version'][0];
				}
				else {
					$version= $matches['version'][1];
				}
			}
			else {
				$version= $matches['version'][0];
			}
			
			// check if we have a number
			if ($version==null || $version=="") {$version="?";}
			
			return array(
				'userAgent' => $u_agent,
				'browser'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
			);
} 


}
?>