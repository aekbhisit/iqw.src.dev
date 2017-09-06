<?php 
header ("Content-type: text/html; charset=tis-620");
session_start();
require('../lib/db.php') ;
require('../lib/table.php') ;
require('class.php');
include("../class/resize-class.php");

if($_COOKIE['BELang']=='Thai'){
	include('language_th.php');
}else{
	include('language_en.php');
} 

	//echo 'create';
	$oOrder =  new Orders() ;
	$website_address =   $oOrder->getWebsiteAddress($_SESSION['sDomainID']);
	$paper =  $_GET['paperSize'] ;
	$onlyMember = $_GET['onlyMember'] ;
	$startItem = $_GET['startItem'] ;
	$endItem = $_GET['endItem'] ;
	$both_address = ((int)$onlyMember)?0:1 ;
	$filter['buyer_type'] = (isset($_GET['buyerType']))?$_GET['buyerType']:0 ;
	$filter['isPaid'] =(isset($_GET['isPaid']))?$_GET['isPaid']:0 ;
	$filter['isShipped'] = (isset($_GET['isShipped']))?$_GET['isShipped']:0 ;
	$filter['paymentMethod'] = (isset($_GET['paymentMethod']))?$_GET['paymentMethod']:0 ;
	$page  = (isset($_GET['page']))?$_GET['page']:1 ;
	$length  = (isset($_GET['length']))?$_GET['length']:20 ;
	$start_rec = ($page-1)*$length ;
	$limit = ' '.$start_rec.','.$length.' ' ;
	$search =  (isset($_GET['search']))?$_GET['search']:NULL ;
	$startDate =  (isset($_GET['startDate']))?$_GET['startDate']:NULL ;
	$endDate =  (isset($_GET['endDate']))?$_GET['endDate']:NULL ;
	$size = $oOrder->getOrdersSize(addslashes($_SESSION['sDomainID']));
	$orders  =  $oOrder->getOrdersData(addslashes($_SESSION['sDomainID']),$limit,$startDate,$endDate,$filter,$search) ;		
	if($website_address['countryEN']=='Thailand'){
		$province = $website_address['provinceTH'] ;
		$country = '';
	}else{
		$province = $website_address['provinceEN'] ;
		$country =  $website_address['countryEN'] ;
	}
//	print_r($orders)
?>
<?php
define('FPDF_FONTPATH','fonts/');
require('label/fpdf.php');
require('label/PDF_Label.php');
// Standard format

if($paper=='A16'){
	$pdf = new PDF_Label('A16');
	if($both_address==1){
		$length = ceil($endItem-$startItem+1)/2;
	}else{
		$length = ceil($endItem-$startItem+1) ;
	}
}else{
	$pdf = new PDF_Label('A4');
	if($both_address==1){
		$length = ceil($endItem-$startItem+1)/2;
	}else{
		$length = ceil($endItem-$startItem+1) ;
	}
}
$pdf->AddPage();

$cnt = 1 ;
 foreach($orders as $key => $order){
	if($cnt<=$length){
	$cnt++ ;
	$address1 = iconv('tis-620','cp874'," "._printlabel_to." ".$order['buyer_name']) ;
	$address2 =  iconv('tis-620','cp874',$order['buyer_address']);
	$address3 =  iconv('tis-620','cp874', " "._printlabel_ordernumber.": #".$order['order_id']);
	$text = sprintf("%s\n%s\n%s", $address1, $address2, $address3);
	$pdf->Add_Label($text);
	
	if($both_address==1){
		$address1 = iconv('tis-620','cp874'," "._printlabel_from." ".$website_address['fristname'].'  '.$website_address['lastname']) ;
		$address2 =  iconv('tis-620','cp874',$website_address['address'].' '.$province.' '.$country.' '.$website_address['postcode']);
		$address3 =   iconv('tis-620','cp874', $website_address['domain_name']);
		$text = sprintf("%s\n%s\n%s", $address1, $address2, $address3 );
		$pdf->Add_Label($text);
	}// if both
	}// if length
}
$file = date('ymdhis');
$pdf -> Output("$file.pdf","D");
?>
