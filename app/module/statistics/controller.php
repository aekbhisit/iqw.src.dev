<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
$oStats = new Statistics('stats');
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		case 'getStatsData':
			$columns = array('id','ip','browser','platform','date');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart= $_GET['iDisplayStart'];
			$limit = ' limit '.$iDisplayStart.','.$iDisplayLength;
			$iSortCol_0 = $_GET['iSortCol_0'];
			$sSortDir_0 = $_GET['sSortDir_0'];
			if(!empty($columns[$iSortCol_0])){
				$orderby = " order by stats_visit.date desc";
			}else{
				$orderby = " order by stats_visit.date desc";
			}
			$sSearch = $oStats->setString($_GET['sSearch']); 
			if(!empty($sSearch)){
				$search = " WHERE ( $oStats->table.browser like '%$sSearch%' or $oStats->table.version like '%$sSearch%' or $oStats->table.platform like '%$sSearch%' or $oStats->table.date like '%$sSearch%') ";
			}
			$stats = $oStats->getStatsAll($search,$orderby,$limit);
			$iTotal = $oStats->getStatsSize();
			$iFilteredTotal = count($stats);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=>$iTotal,
				"iTotalDisplayRecords"=>$iTotal,  // $iFilteredTotal,
				"aaData"=>array()
			);
			$cnt = $iDisplayStart+1;
			if(!empty($stats)){
				foreach($stats as $key =>$value){
					$iconbar ='<a href="'.$value['url'].'" target="_blank"><img src="../images/icons/color/magnifier.png" /></a>';
					$row_chk = '';
					$output["aaData"][] = array(0=>$cnt,1=>long2ip($value['ip']),2=>$value['browser'].' '.$value['version'],3=>$value['platform'],4=>$oStats->getCountry($value['ip']),5=>$value['date'],6=>$iconbar ,"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['id']);
					$cnt++;
				}
			}
			echo json_encode($output);
		break;
		case 'insertStatsLogs':
			$oStats->insertStatsLogs();
		break;
		case "getVisitorCircular":
			if(!isset($_SESSION['stats_sumary'])){
				$oStats->setSumStat();
				$stats = $oStats->getSumaryCurrentStat();
				$_SESSION['stats_sumary'] = $stats;
			}else{
				$stats = $_SESSION['stats_sumary'];
			}
			$today = $oStats->setInt($stats['today_visitor']);
			$today_pv = $oStats->setInt($stats['today_pv']);
			$week = $oStats->setInt($stats['week_visitor']);
			$week_pv = $oStats->setInt($stats['week_pv']);
			$month = $oStats->setInt($stats['month_visitor']);
			$month_pv = $oStats->setInt($stats['month_pv']);
			$all = $oStats->setInt($stats['all_visitor']);
			$all_pv = $oStats->setInt($stats['all_pv']);
			$circular_stat = "
			<li class=\"da-circular-stat {fillColor:'#a6d037',value:".$today.",maxValue:".$today_pv.",label:'Today'}\"></li>
			<li class=\"da-circular-stat {fillColor:'#ea799b',value:".$week.",maxValue:".$week_pv.",label:'This Week'}\"></li>
			<li class=\"da-circular-stat {fillColor:'#fab241',value:".$month.",maxValue:".$month_pv.",label:'This Month'}\"></li>
			<li class=\"da-circular-stat {fillColor:'#61a5e4',value:".$all.",maxValue:".$all_pv.",label:'Total'}\"></li>";
			echo $circular_stat;
		break;
		case "getVisitorGraphData":
			$year = $oStats->getStatYearGraph();
			foreach($year as $key => $val){
				$m = $val['month'];
				if(!isset($val['data'])) {
					$val['data']['visitor'] = 0;
					$val['data']['pv'] = 0;
				}
				$visitor = (int)$val['data']['visitor'];
				$pages = (int)$val['data']['pv'];
				$data[$key] = array($m,$visitor,$pages); 
			}
			echo json_encode($data);
		break;
		case "showCountAllModule":
			$modules = array('pages','blogs','news','products_mainproduct','contacts','users');
			$count = $oStats->showCountAllModule($modules);
			echo json_encode($count);
		break;
		case "setDeleteAllStatData":
			$oStats->setDeleteAllStatData();
			unset($_SESSION['stats_sumary']);
			echo json_encode(array('success'=>1));
		break;
	} // switch
} // if isset
?>