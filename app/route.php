<?PHP
$Q =(isset($_GET["q"]))?$_GET["q"]:""; 
$split_url = explode("/",$Q ) ;
switch(count($split_url)){
	case 2:
		$_SESSION["site_language"] = $split_url[0] ; 
		$Q =(isset($_GET["q"]))?$split_url[1]:""; 
		define("LANG", $_SESSION["site_language"] );
	break;
	case 3:
		$_SESSION["site_language"] = $split_url[1] ;
		$_SESSION["_country"] = $split_url[0] ;
		$Q =(isset($_GET["q"]))?$split_url[2]:""; 
		define("LANG", $_SESSION["site_language"] );
	break;
	default:
		$Q =(isset($_GET["q"]))?$_GET["q"]:"";
		define("LANG", "th" ); $_SESSION["site_language"] = "th";
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
$request_route[2] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_followus");
$request_route[3] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("4"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_appointed");
$request_route[4] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_quickmenu");
$request_route[5] = array("module" =>"galleries","task"=>"find","type"=>"one","key"=>array("1"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_gallery");
$request_route[6] = array("module" =>"news","task"=>"find","type"=>"in_category","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"news.cdate desc limit 0,12","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_events");
$request_route[7] = array("module" =>"news","task"=>"find","type"=>"in_category","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"news.cdate desc limit 0,12","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_news");
$request_route[8] = array("module" =>"products","task"=>"find","type"=>"all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"products.sequence asc limit 0,20","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_all_product");
$request_route[9] = array("module" =>"products","task"=>"find","type"=>"in_category","key"=>array("6"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"products.sequence asc limit 0,1","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_show_product");
$request_route[10] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("1"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_know_about");
$request_route[11] = array("module" =>"banners","task"=>"find","type"=>"one","key"=>array("1"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_banner");;
 
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
case "about" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"pages","task"=>"find","type"=>"in_category","key"=>array("5"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"pages.sequence asc ","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"about_history");
$request_route[3] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("5"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"about_know_about");
$request_route[4] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_followus");
$request_route[5] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("4"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_appointed");
$request_route[6] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_quickmenu");
$request_route[7] = array("module" =>"banners","task"=>"find","type"=>"one","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"about_banner");;
 
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
			include(THEME_ROOT."about.php");
		 ;
 break;
case "products" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_followus");
$request_route[3] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("4"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_appointed");
$request_route[4] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_quickmenu");
$request_route[5] = array("module" =>"pages","task"=>"find","type"=>"in_category","key"=>array("6"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"pages.sequence asc ","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"products_wheretoget_all");
$request_route[6] = array("module" =>"pages","task"=>"find","type"=>"category_one","key"=>array("6"),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_wheretoget");
$request_route[7] = array("module" =>"products","task"=>"find","type"=>"in_category","key"=>array("5"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"products.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"products_gel");
$request_route[8] = array("module" =>"products","task"=>"find","type"=>"in_category","key"=>array("4"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"products.sequence asc ","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"products_condom");
$request_route[9] = array("module" =>"products","task"=>"find","type"=>"category_one","key"=>array("5"),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"products_cateogry_gel");
$request_route[10] = array("module" =>"products","task"=>"find","type"=>"category_one","key"=>array("4"),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_cat_condom");
$request_route[11] = array("module" =>"banners","task"=>"find","type"=>"one","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_banner");;
 
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
			include(THEME_ROOT."products.php");
		 ;
 break;
case "product" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"products","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_detail");
$request_route[3] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_quickmenu");
$request_route[4] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("4"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_appointed");
$request_route[5] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_followus");
$request_route[6] = array("module" =>"banners","task"=>"find","type"=>"one","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_banner");;
 
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
			include(THEME_ROOT."product_detail.php");
		 ;
 break;
case "news" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"news","task"=>"find","type"=>"in_category","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"news.cdate desc","paginate"=>1,"page"=>$_PARAM[1],"length"=>6,"separate"=>0,"count"=>1,"data_key"=>"event_all");
$request_route[3] = array("module" =>"news","task"=>"find","type"=>"in_category","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"news.cdate desc","paginate"=>1,"page"=>$_PARAM[1],"length"=>9,"separate"=>0,"count"=>1,"data_key"=>"news_all");
$request_route[4] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_followus");
$request_route[5] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("4"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_appointed");
$request_route[6] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_quickmenu");
$request_route[7] = array("module" =>"banners","task"=>"find","type"=>"one","key"=>array("4"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"news_banner");;
 
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
case "gallery" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"galleries","task"=>"find","type"=>"one","key"=>array("1"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"gallery_all");
$request_route[3] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_followus");
$request_route[4] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("4"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_appointed");
$request_route[5] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_quickmenu");
$request_route[6] = array("module" =>"banners","task"=>"find","type"=>"one","key"=>array("5"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"gallery_banner");;
 
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
			include(THEME_ROOT."gallery.php");
		 ;
 break;
default: 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_followus");
$request_route[3] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("4"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_appointed");
$request_route[4] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"footer_quickmenu");
$request_route[5] = array("module" =>"galleries","task"=>"find","type"=>"one","key"=>array("1"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_gallery");
$request_route[6] = array("module" =>"news","task"=>"find","type"=>"in_category","key"=>array("3"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"news.cdate desc limit 0,12","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_events");
$request_route[7] = array("module" =>"news","task"=>"find","type"=>"in_category","key"=>array("2"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"news.cdate desc limit 0,12","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_news");
$request_route[8] = array("module" =>"products","task"=>"find","type"=>"all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"products.sequence asc limit 0,20","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_all_product");
$request_route[9] = array("module" =>"products","task"=>"find","type"=>"in_category","key"=>array("6"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"products.sequence asc limit 0,1","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_show_product");
$request_route[10] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("1"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_know_about");
$request_route[11] = array("module" =>"banners","task"=>"find","type"=>"one","key"=>array("1"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_banner");; 
 
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