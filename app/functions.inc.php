<?php
function pre($data){
	echo '<pre>' ;
		print_r($data);
	echo '</pre>';
}

function _html($html){
	return html_entity_decode($html) ;
}

function pageBreak($content,$break,$pagebreak='<!-- pagebreak -->'){
	$split_content  = explode($pagebreak,$content);
		return  ($split_content[0].$break) ; 
}
function getYoutubeThumbnail($url){
	$queryString = parse_url($url, PHP_URL_QUERY);
	parse_str($queryString, $params);
	$v = $params['v'];  
     if(strlen($v)>0){
          return  "http://i3.ytimg.com/vi/$v/default.jpg";
       }else{
		  return '';  
	   }
}
function getYoutubeV($url){
	$queryString = parse_url($url, PHP_URL_QUERY);
	parse_str($queryString, $params);
	$v = $params['v'];  
     if(strlen($v)>0){
          return  $v ;
       }else{
		  return '';  
	   }
}

function showDate($date,$format,$lang){
	// YYYY MM DD, BBBB MM DD
	list($FY,$FM,$D) = explode(' ',$format); 
	list($DATE,$TIME) = explode(' ',$date) ;
	list($YYYY,$MM,$DD) = explode('-',$DATE);
	list($HH,$II,$SS) = explode(':',$TIME);
	return array(
		'Y'=>convertYear($YYYY,$FY),
		'M'=>convertMonth($MM,$FM,$lang),
		'D'=>$DD,
		'H'=>$HH,
		'I'=>$II,
		'S'=>$SS,
	);
}

function convertMonth($MM,$format,$lang){
	// YY, YYYY, BB,BBBB
	// MM, MMM, MMMM
	switch($lang){
		case "en":
			switch($format){
				case "MM":
					switch($MM){
						case '01':  return 'JA' ; break ;
						case '02':  return 'FE' ; break ;
						case '03':  return 'MR' ; break ;
						case '04':  return 'AL' ; break ;
						case '05':  return 'MA' ; break ;
						case '06':  return 'JN' ; break ;
						case '07':  return 'JL' ; break ;
						case '08':  return 'AU' ; break ;
						case '09':  return 'SE' ; break ;
						case '10':  return 'OC' ; break ;
						case '11':  return 'NO' ; break ;
						case '12':  return 'DE' ; break ;
					}
				break;
				case "MMM":
					switch($MM){
						case '01':  return 'Jan' ; break ;
						case '02':  return 'Feb' ; break ;
						case '03':  return 'Mar' ; break ;
						case '04':  return 'Apr' ; break ;
						case '05':  return 'May' ; break ;
						case '06':  return 'Jun' ; break ;
						case '07':  return 'Jul' ; break ;
						case '08':  return 'Aug' ; break ;
						case '09':  return 'Sep' ; break ;
						case '10':  return 'Oct' ; break ;
						case '11':  return 'Nov' ; break ;
						case '12':  return 'Dec' ; break ;
					}
				break;
				case "MMMM":
					switch($MM){
						case '01':  return 'January' ; break ;
						case '02':  return 'February' ; break ;
						case '03':  return 'March' ; break ;
						case '04':  return 'April' ; break ;
						case '05':  return 'May' ; break ;
						case '06':  return 'June' ; break ;
						case '07':  return 'July' ; break ;
						case '08':  return 'August' ; break ;
						case '09':  return 'September' ; break ;
						case '10':  return 'October' ; break ;
						case '11':  return 'November' ; break ;
						case '12':  return 'December' ; break ;
					}
				break;
				default:
						return $MM ;
				break ;
			}
		break;
		case "th":
			case "MM":
			case "MMM":
					case '01':  return 'ม.ค.' ; break ;
					case '02':  return 'ก.พ.' ; break ;
					case '03':  return 'มี.ค.' ; break ;
					case '04':  return 'เม.ย.' ; break ;
					case '05':  return 'พ.ค.' ; break ;
					case '06':  return 'มิ.ย.' ; break ;
					case '07':  return 'ก.ค.' ; break ;
					case '08':  return 'ส.ค.' ; break ;
					case '09':  return 'ก.ย.' ; break ;
					case '10':  return 'ต.ค.' ; break ;
					case '11':  return 'พ.ค.' ; break ;
					case '12':  return 'ธ.ค.' ; break ;
				break;
				case "MMMM":
						case '01':  return 'มกราคม' ; break ;
						case '02':  return 'กุมภาพันธ์' ; break ;
						case '03':  return 'มีนาคม' ; break ;
						case '04':  return 'เมษายน' ; break ;
						case '05':  return 'พฤษภาคม' ; break ;
						case '06':  return 'มิถุนายน' ; break ;
						case '07':  return 'กรกฎาคม' ; break ;
						case '08':  return 'สิงหาคม' ; break ;
						case '09':  return 'กันยายน' ; break ;
						case '10':  return 'ตุลาคม' ; break ;
						case '11':  return 'พฤษภาคม' ; break ;
						case '12':  return 'ธันวาคม' ; break ;
				break;
				default:
						return $MM ;
				break ;
		break;
	}
}

function convertYear($YY,$format){
	switch($format){
		case 'YY':
			return substr($YY,2,2);
		break;
		case 'YYYY':
			return $YY;
		break;
		case 'BB':
			$bYear = (string)$YY+543 ;
			return substr($bYear,2,2);
		break;
		case 'BBBB':
			$bYear = (string)$YY+543 ;
			return substr($bYear,2,2);
		break;
	}
}

function _substr($string,$start,$length){
	return iconv_substr($string,$start,$length,'UTF-8');
}

function _paginate($total_page,$page,$link,$active){
	$total_page = ceil($count/$length);
	return $total_page ;
}

?>