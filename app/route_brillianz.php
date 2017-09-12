<?PHP
$Q =(isset($_GET["q"]))?$_GET["q"]:""; 
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
case "หน้าหลัก" : 
case "Home" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("16"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.cdate desc limit 0,2","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_service");
$request_route[3] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("15"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.cdate desc limit 0,2","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_news");
$request_route[4] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("14"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.cdate desc limit 0,3","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_knowledge");
$request_route[5] = array("module" =>"banners","task"=>"find","type"=>"one","key"=>array("7"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_banner");;
 
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
case "เกี่ยวกับเรา" : 
case "AboutUs" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"pages","task"=>"find","type"=>"one","key"=>array("5"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"about_us");
$request_route[3] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("17"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.sequence asc limit 0,10","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"aboutus_blogs");;
 
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
case "สินค้าของเรา" : 
case "Products" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"catalogs","task"=>"find","type"=>"category_all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_category");;
 
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
			include(THEME_ROOT."product_main.php");
		 ;
 break;
case "หมวดหมู่สินค้า" : 
case "Category" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"catalogs","task"=>"find","type"=>"category_all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_category");;
 
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
			include(THEME_ROOT."product_categories.php");
		 ;
 break;
case "รายการสินค้า" : 
case "productlist" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"catalogs","task"=>"find","type"=>"category_all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_category");
$request_route[3] = array("module" =>"catalogs","task"=>"find","type"=>"in_category","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"catalogs.sequence asc","paginate"=>1,"page"=>$_PARAM[2],"length"=>12,"separate"=>0,"count"=>1,"data_key"=>"in_category");;
 
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
			include(THEME_ROOT."product_list.php");
		 ;
 break;
case "รายละเอียดสินค้า" : 
case "productdetail" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"catalogs","task"=>"find","type"=>"category_all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_category");
$request_route[3] = array("module" =>"catalogs","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_detail");;
 
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
case "โปรโมชั่น" : 
case "promotion" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("18"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"promotion");;
 
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
			include(THEME_ROOT."promotion.php");
		 ;
 break;
case "ผลงานอ้างอิง" : 
case "portfolio" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"galleries","task"=>"find","type"=>"in_category","key"=>array("11"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"galleries.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"galleries");;
 
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
			include(THEME_ROOT."portfolio.php");
		 ;
 break;
case "สินค้าระบายสต๊อก" : 
case "clearance" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"catalogs","task"=>"find","type"=>"all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"AND catalogs.id IN (SELECT catalogs_attrs_val.catalog_id FROM catalogs_attrs_val WHERE catalogs_attrs_val.attr_id=81 and catalogs_attrs_val.default_val=3 )","order"=>"","paginate"=>1,"page"=>$_PARAM[1],"length"=>12,"separate"=>0,"count"=>1,"data_key"=>"clearstock");
$request_route[3] = array("module" =>"catalogs","task"=>"find","type"=>"category_all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_category");;
 
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
			include(THEME_ROOT."clearance.php");
		 ;
 break;
case "ติดต่อเรา" : 
case "contactus" : 
 ;
 
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
			include(THEME_ROOT."contactus.php");
		 ;
 break;
case "ข่าวสาร" : 
case "news" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("15"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"news");;
 
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
case "เรียนรู้เรื่องไฟ" : 
case "knowledges" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("14"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"knowledge");;
 
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
			include(THEME_ROOT."knowledge.php");
		 ;
 break;
case "หมวดหมู่สินค้า" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"catalogs","task"=>"find","type"=>"in_category","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"catalogs.sequence asc","paginate"=>1,"page"=>$_PARAM[2],"length"=>12,"separate"=>0,"count"=>1,"data_key"=>"in_category");;
 
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
			include(THEME_ROOT."product_list.php");
		 ;
 break;
case "รายละเอียดโปรโมชั่น" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"blog_one");;
 
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
			include(THEME_ROOT."promotion_detail.php");
		 ;
 break;
case "รายละเอียดข่าวสาร" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"blog_one");;
 
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
			include(THEME_ROOT."news_detail.php");
		 ;
 break;
case "รายละเอียดเรียนรู้เรื่องไฟ" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"blog_one");;
 
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
			include(THEME_ROOT."knowledge_detail.php");
		 ;
 break;
case "รายละเอียดบริการของเรา" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"blog_one");;
 
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
			include(THEME_ROOT."service_detail.php");
		 ;
 break;
case "บริการของเรา" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("16"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.sequence asc","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"service");;
 
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
			include(THEME_ROOT."service.php");
		 ;
 break;
case "ดูผลงานอ้างอิง" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"galleries","task"=>"find","type"=>"one","key"=>$_PARAM[1],"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"gallery");;
 
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
			include(THEME_ROOT."portfolio_detail.php");
		 ;
 break;
case "ค้นหาสินค้า" : 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"catalogs","task"=>"find","type"=>"category_all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"product_category");
$request_route[3] = array("module" =>"catalogs","task"=>"find","type"=>"all","key"=>array(""),"slug"=>1,"status"=>1,"search"=>"","filter"=>"FILTER_FUNCTON","order"=>"","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"product_search");;
 
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
			include(THEME_ROOT."product_search.php");
		 ;
 break;
default: 
 $request_route[1] = array("module" => "configs","task"=>"front_getSiteConfig","type"=>"","key"=>NULL,"status"=>"","search"=>NULL,"order"=>NULL,"sort"=>NULL,"paginate"=>NULL,"count"=>false,"slugkey"=>false );
$request_route[2] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("16"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.cdate desc limit 0,2","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_service");
$request_route[3] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("15"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.cdate desc limit 0,2","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_news");
$request_route[4] = array("module" =>"blogs","task"=>"find","type"=>"in_category","key"=>array("14"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"blogs.cdate desc limit 0,3","paginate"=>0,"page"=>1,"length"=>1,"separate"=>0,"count"=>1,"data_key"=>"home_knowledge");
$request_route[5] = array("module" =>"banners","task"=>"find","type"=>"one","key"=>array("7"),"slug"=>0,"status"=>1,"search"=>"","filter"=>"","order"=>"","paginate"=>0,"page"=>1,"length"=>0,"separate"=>0,"count"=>0,"data_key"=>"home_banner");; 
 
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