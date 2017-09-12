<?PHP
error_reporting(E_ALL);
ini_set('display_errors', 0);
$split_url = explode('/',$_GET["q"]) ;
switch(count($split_url)){
	case 2:
		$_SESSION['_language'] = $split_url[0] ;
		$Q =(isset($_GET["q"]))?$split_url[1]:""; 
		//if(!defined('PRE_ROOT')){
			define('LANG', $_SESSION['_language'] );
		//}
	break;
	case 3:
		$_SESSION['_language'] = $split_url[1] ;
		$_SESSION['_country'] = $split_url[0] ;
		$Q =(isset($_GET["q"]))?$split_url[2]:""; 
		//if(!defined('PRE_ROOT')){
			define('LANG', $_SESSION['_language'] );
		//}
	break;
	default:
		$Q =(isset($_GET["q"]))?$_GET["q"]:"";
		define('LANG', '' );
	break;
}


$_PARAM = explode("-",$Q);
$_PARAM_PLUS[1] = explode("-",$Q,1);
if(count($_PARAM)>=2){list($bf1,$_PARAM_PLUS[2]) = explode("-",$Q,2);}
if(count($_PARAM)>=3){list($bf1,$bf2,$_PARAM_PLUS[3]) = explode("-",$Q,3);}
if(count($_PARAM)>=4){list($bf1,$bf2,$bf3,$_PARAM_PLUS[4]) = explode("-",$Q,4);}
if(count($_PARAM)>=5){list($bf1,$bf2,$bf3,$bf4,$_PARAM_PLUS[5]) = explode("-",$Q,5);}
if(count($_PARAM)>=6){list($bf1,$bf2,$bf3,$b4,$bf5,$_PARAM_PLUS[6]) = explode("-",$Q,6);}
if(count($_PARAM)>=7){list($bf1,$bf2,$bf3,$b4,$bf5,$bf6,$_PARAM_PLUS[7]) = explode("-",$Q,7);}
if(count($_PARAM)>=8){list($bf1,$bf2,$bf3,$b4,$bf5,$bf6,$bf7,$_PARAM_PLUS[8]) = explode("-",$Q,8);}
if(count($_PARAM)>=9){list($bf1,$bf2,$bf3,$b4,$bf5,$bf6,$bf7,$bf8,$_PARAM_PLUS[9]) = explode("-",$Q,9);}
if(count($_PARAM)>=10){list($bf1,$bf2,$bf3,$b4,$bf5,$bf6,$bf7,$bf8,$bf9,$_PARAM_PLUS[10]) = explode("-",$Q,10);}
$request_route = array(0=>array("module" => "statistics","task"=>"insertStatsLogs","type"=>NULL,"key"=>NULL, "slug"=>NULL,"status"=>NULL,"search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>true,"slugkey"=>true ));
switch(urldecode($_PARAM[0])){ 
case "home" : 
case "หน้าหลัก" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"news","task"=>"find","type"=>"in_category","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"news.cdate desc limit 0,8","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"newshome");
$request_route[3] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("9"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_detail");
$request_route[4] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("10"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_product_brand");
$request_route[5] = array("module" =>"clips","task"=>"find","type"=>"one","key"=>array("1"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_clip");
$request_route[6] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."index.php");
		 ;
 break;
case "aboutus" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("11"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"aboutus");
$request_route[3] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("10"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_product_brand");
$request_route[4] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."aboutus.php");
		 ;
 break;
case "products" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"catalogs","task"=>"find","type"=>"category_all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"productcatlist");
$request_route[3] = array("module" =>"catalogs","task"=>"find","type"=>"all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"catalogs.sequence asc","paginate"=>1,"page"=>$_PARAM[1],"length"=>12,"separate"=>0,"count"=>1,"data_key"=>"products");
$request_route[4] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."product.php");
		 ;
 break;
case "productdetail" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"catalogs","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"productdetail");
$request_route[3] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."product-view.php");
		 ;
 break;
case "news" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"news","task"=>"find","type"=>"all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"news.sequence asc, news.cdate desc","paginate"=>1,"page"=>$_PARAM[1],"length"=>12,"separate"=>0,"count"=>1,"data_key"=>"news");
$request_route[3] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."news.php");
		 ;
 break;
case "newsdetail" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"news","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"newsdetail");
$request_route[3] = array("module" =>"news","task"=>"find","type"=>"in_category","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"news.cdate desc limit 0,8","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"newshome");
$request_route[4] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."news-view.php");
		 ;
 break;
case "technicaldetail" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.sequence asc","paginate"=>1,"page"=>$_PARAM[1],"length"=>12,"separate"=>0,"count"=>1,"data_key"=>"tech");
$request_route[3] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."technical-detail.php");
		 ;
 break;
case "viewtechnicaldetail" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.sequence asc limit 0,8","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"othertech");
$request_route[3] = array("module" =>"blogs","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"techdetail");
$request_route[4] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."technical-detail-view.php");
		 ;
 break;
case "projectreferences" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"galleries","task"=>"find","type"=>"in_category","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"galleries.sequence asc","paginate"=>1,"page"=>$_PARAM[1],"length"=>12,"separate"=>0,"count"=>1,"data_key"=>"project");
$request_route[3] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."project-references.php");
		 ;
 break;
case "projectreferencesdetail" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"galleries","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"projectdetail");
$request_route[3] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."project-references-view.php");
		 ;
 break;
case "contactus" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."contact.php");
		 ;
 break;
case "brand" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"banners","task"=>"find","type"=>"in_category","key"=>array("34"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"banners.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"slide");
$request_route[3] = array("module" =>"catalogs","task"=>"find","type"=>"in_category","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>1,"page"=>$_PARAM[2],"length"=>24,"separate"=>0,"count"=>1,"data_key"=>"productscat");
$request_route[4] = array("module" =>"catalogs","task"=>"find","type"=>"category_all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"productcatlist");;
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}
			include(THEME_ROOT."productcat.php");
		 ;
 break;
default: 

 	$request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
 
			foreach($request_route as $key=>$route){
			ob_start();
			$_GET["module"] =$route["module"];
			$_GET["task"] =  $route["task"] ;
			if(!empty($route["type"])){$_GET["type"] =  $route["type"] ; }else{ $_GET["type"]=""; }
			if(!empty($route["key"])){$_GET["key"] =  $route["key"] ; }else{$_GET["key"] ="";}
			if(!empty($route["slug"])){ $_GET["slug"] =  $route["slug"] ;}else{$_GET["slug"] = 0 ;} 
			if(!empty($route["status"])){ $_GET["status"] =  $route["status"] ;}else{ $_GET["status"] = 1 ;}  
			if(!empty($route["search"])){ $_GET["search"] =  $route["search"] ;}else{$_GET["search"] =  "" ;} 
			if(!empty($route["filter"])){ $_GET["filter"] =  $route["filter"] ;}else{$_GET["filter"] =  "" ;} 
			if(!empty($route["order"])){ $_GET["order"] =  $route["order"] ;} else{ $_GET["order"] =  "" ;} 
			if(!empty($route["separate"])){ $_GET["separate"] =  $route["separate"] ;}else{$_GET["separate"]=0 ;}
			if(!empty($route["paginate"])){ $_GET["paginate"] =  $route["paginate"] ;}else{$_GET["paginate"] = 0 ;} 
			if(!empty($route["page"])){ $_GET["page"] =  $route["page"] ;} else{$_GET["page"] =  1;}
			if(!empty($route["length"])){ $_GET["length"] =  $route["length"] ;} else{$_GET["length"] =  10 ;}
			if(!empty($route["count"])){ $_GET["count"] =  $route["count"] ;} else{$_GET["count"] =0 ;}
			if(!empty($route["data_key"])){ $_GET["data_key"] =  $route["data_key"] ;} else{$_GET["data_key"] = "" ;}
			include("app/index.php");
			ob_end_flush(); 
			}

			include(THEME_ROOT."index.php");
		 ;
break; 
} 
 ?> 