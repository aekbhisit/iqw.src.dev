<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
$oSearchs = new Search('rates');
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		case 'getSearchData':
			$columns = array('id','id','id','id','id');
			$limit = '';
			$orderby = '';
			$search = '';
			$iDisplayLength = $_GET['iDisplayLength'];
			$iDisplayStart = $_GET['iDisplayStart'];
			$iSortCol_0 = $_GET['iSortCol_0'];
			$sSortDir_0 = $_GET['sSortDir_0'];
			$sSearch = (empty($_GET['sSearch']))?$_GET['find']:$_GET['sSearch'];
			$sSearch = $oSearchs->setSring($sSearch);
			$results = $oSearchs->getSearchAll($search_modules,$sSearch);
			$iTotal = $oSearchs->getSearchSize($search_modules,$sSearch);
			$iFilteredTotal = count($results);
			$output = array(
				"sEcho"=>intval($_GET['sEcho']),
				"iTotalRecords"=>$iTotal,
				"iTotalDisplayRecords"=>$iFilteredTotal, // $iFilteredTotal,
				"aaData"=>array()
			);
			$cnt = 1;
			if(!empty($results)){
				foreach($results as $key => $value){
					if($key>=$iDisplayStart && $key<($iDisplayStart+$iDisplayLength)){
						$output["aaData"][] = array(0=>$key+1,1=>$value['module'],2=>'<a href="../'.$value['link'].$value['data']['id'].'">'.$value['data']['title'].'</a><br>'.iconv_substr($value['data']['description'],0,200),"DT_RowClass"=>'row-'.$cnt,"DT_RowId"=>$value['data']['id']);
						$cnt++;
					}// if 
				}
			}
			echo json_encode($output);
		break;
		case 'frontSearchData':
			if(isset($_REQUEST['search'])){
				$search_term = trim($_REQUEST['search']);
				if(strlen($search_term)<=0){
					$oWSD->error($oWSD->label("Please enter a search query."),true);
				}else if(strlen($search_term)<$GLOBALS['_SEARCH_MIN_CHARS']){
					$oWSD->error($oWSD->label("Sorry, you must enter at least %d characters in your search query",$GLOBALS['_SEARCH_MIN_CHARS']),true);
				}
				$oWSD -> search($search_term);
				$_DATA['search'] = $oWSD->resultToArray();
			}
		break;
		case 'frontSearchJson':
			echo 'front search';
			if(isset($_REQUEST['search'])){
				$search_term = trim($_REQUEST['search']);
				if(strlen($search_term)<=0){
					$oWSD->error($oWSD->label("Please enter a search query."),true);
				}else if(strlen($search_term) < $GLOBALS['_SEARCH_MIN_CHARS']){
					$oWSD->error($oWSD->label("Sorry, you must enter at least %d characters in your search query",$GLOBALS['_SEARCH_MIN_CHARS']),true);
				}
				$oWSD->search($search_term);
				echo $oWSD->resultToJSON();
			}
		break;
	}// switch
}// if isset
?>