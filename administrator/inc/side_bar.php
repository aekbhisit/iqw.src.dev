<?php
$allow_module = array(		
    'dashboard'=>true,
	'pages'=>false,
	'blogs'=>false,
	'news'=>true,
	'banners'=>true,
	'clips'=>true,
	'products'=>false,
	'modules'=>false,
	'catalogs'=>false,
    'catalogs_field'=>false,
	'rooms'=>false,
	'tours'=>false,
	'orders'=>false,
	'members'=>false,
	'users'=>false,
	'advertisement'=>false,
	'galleries'=>true,
	'calendar'=>false,
	'comments'=>false,
	'contacts'=>true,
	'newsletters'=>false,
	'downloads'=>true,
	'files'=>true,
	'statistics'=>false,
	'restaurants'=>false,
	'finds'=>true,
	'configs'=>true,
    'addon'=>true,
    'content'=>true,
    'htmlzone'=>true,
);
?>
    <!-- Sidebar -->
                <div id="da-sidebar">
                    <!-- Main Navigation -->
                    <div id="da-main-nav" class="da-button-container">
                        <ul>
                        	<?php if($allow_module['dashboard']){?>
                            <li <?=($module_active=='dashboard')?'class="active"':''?>>
                            	<a href="<?=($module_active=='dashboard')?'dashboard.php':'../dashboard/dashboard.php'?>">
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/home.png" alt="Dashboard" />
                                    </span>
                                	หน้าหลัก
                                </a>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['content']){?>
                            <li <?=($module_active=='content')?'class="active"':''?>>
                                <a href="../content/index.php">
                                    <!-- Icon Container -->
                                    <span class="da-nav-icon">
                                        <img src="../images/icons/black/32/home.png" alt="Content" />
                                    </span>
                                    คอนเทน
                                </a>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['pages']){?>
                            <li <?=($module_active=='pages')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/computer_imac.png" alt="Page" />
                                    </span>
                                	เว็บเพจ
                                </a>
                                 <ul  <?=($module_active=='pages')?'class="open"':'class="closed"'?>>
                                	<li ><a href="<?=($module_active=='pages')?'categories.php':'../pages/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='pages')?'index.php':'../pages/index.php'?>">จัดการหน้า</a></li>
                                </ul> 
                            </li>
                           <?php  }// if  ?>
                            <?php if($allow_module['news']){?>
                            <li <?=($module_active=='news')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/digg_3.png" alt="News" />
                                    </span>
                                	ข่าวสาร
                                </a>
                                 <ul <?=($module_active=='news')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='news')?'categories.php':'../news/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='news')?'index.php':'../news/index.php'?>">จัดการข่าวสาร</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                               <?php if($allow_module['blogs']){?>
                            <li <?=($module_active=='blogs')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/single_document.png" alt="Blog" />
                                    </span>
                                	บล็อก 
                                </a>
                                 <ul  <?=($module_active=='blogs')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='blogs')?'categories.php':'../blogs/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='blogs')?'index.php':'../blogs/index.php'?>">จัดการบล็อก</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['products']){?>
                            <li <?=($module_active=='products')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/scan_label.png" alt="Catalog" />
                                    </span>
                                	สินค้า 
                                </a>
                                 <ul <?=($module_active=='products')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='products')?'products-categories.php':'../products/products-categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='products')?'products.php':'../products/products.php'?>">จัดการสินค้า</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['modules']){?>
                            <li <?=($module_active=='modules')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/scan_label.png" alt="Catalog" />
                                    </span>
                                	Modules 
                                </a>
                                 <ul <?=($module_active=='modules')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='modules')?'categories.php':'../modules/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='modules')?'index.php':'../modules/index.php'?>">จัดการสินค้า</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['catalogs']){?>
                            <li <?=($module_active=='catalogs')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/iphone_4.png" alt="Catalog" />
                                    </span>
                                	โครงการบ้าน 
                                </a>
                                 <ul <?=($module_active=='catalogs')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='catalogs')?'categories.php':'../catalogs/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='catalogs')?'index.php':'../catalogs/index.php'?>">จัดการ โครงการ</a></li>
                                    <?php if($allow_module['catalogs_field']){?>
                                    <li><a href="<?=($module_active=='catalogs')?'attr.php':'../catalogs/attr.php'?>">จัดการฟิวด์</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                              <?php if($allow_module['rooms']){?>
                            <li <?=($module_active=='rooms')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/apartment_building.png" alt="Catalog" />
                                    </span>
                                	ห้องพัก 
                                </a>
                                 <ul <?=($module_active=='rooms')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='rooms')?'categories.php':'../rooms/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='rooms')?'index.php':'../rooms/index.php'?>">จัดการแคตาล็อก</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                               <?php if($allow_module['banners']){?>
                            <li <?=($module_active=='banners')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/presentation.png" alt="Baner" />
                                    </span>
                                	สไลด์/แบนเนอร์
                                </a>
                                 <ul <?=($module_active=='banners')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='banners')?'categories.php':'../banners/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='banners')?'index.php':'../banners/index.php'?>">จัดการสไลด์/แบนเนอร์</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                              <?php if($allow_module['galleries']){?>
                              <li <?=($module_active=='galleries')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/images_2.png" alt="Gallery" />
                                    </span>
                                    แกลลอรี่
                                </a>
                                  <ul <?=($module_active=='galleries')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='galleries')?'categories.php':'../galleries/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='galleries')?'index.php':'../galleries/index.php'?>">จัดการอัลบั้ม</a></li>
                                </ul>
                            </li>
                           <?php  }// if  ?>
                            <?php if($allow_module['clips']){?>
                            <li <?=($module_active=='clips')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/film_camera.png" alt="Clips" />
                                    </span>
                                	สินค้า 
                                </a>
                                 <ul  <?=($module_active=='clips')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='clips')?'categories.php':'../clips/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='clips')?'index.php':'../clips/index.php'?>">จัดการสินค้า</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['downloads']){?>
                            <li <?=($module_active=='downloads')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/download.png" alt="News" />
                                    </span>
                                	ดาวน์โหลด
                                </a>
                                 <ul <?=($module_active=='downloads')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='downloads')?'categories.php':'../downloads/categories.php'?>">จัดการหมวดหมู่</a></li>
                                	<li><a href="<?=($module_active=='downloads')?'index.php':'../downloads/index.php'?>">จัดการดาวน์โหลด</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['orders']){?>
                             <li <?=($module_active=='orders')?'class="active"':''?>>
                            	<a href="<?=($module_active=='orders')?'index.php':'../orders/index.php'?>">
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/shopping_cart_3.png" alt="Order" />
                                    </span>
                                	รายการสั่งซื้อ
                                </a>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['members']){?>
                             <li <?=($module_active=='members')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/users.png" alt="Member" />
                                    </span>
                                	สมาชิก
                                </a>
                                 <ul  <?=($module_active=='members')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='members')?'categories.php':'../members/categories.php'?>">จัดการกลุ่มสมาชิก</a></li>
                                	<li><a href="<?=($module_active=='members')?'index.php':'../members/index.php'?>">จัดการสมาชิก</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['users']){?>
                             <li <?=($module_active=='users')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/single_user.png" alt="User" />
                                    </span>
                                	ผู้ดูแลระบบ
                                </a>
                                 <ul  <?=($module_active=='users')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='users')?'categories.php':'../users/categories.php'?>">จัดการกลุ่มผู้ดูแลระบบ</a></li>
                                	<li><a href="<?=($module_active=='users')?'index.php':'../users/index.php'?>">จัดการผู้ดูแลระบบ</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                          
                            <?php if($allow_module['calendar']){?>
                             <li <?=($module_active=='calendar')?'class="active"':''?>>
                            	<a href="<?=($module_active=='calendar')?'calendar.php':'../calendar/calendar.php'?>">
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/day_calendar.png" alt="Calendar" />
                                    </span>
                                	กิจกรรม
                                </a>
                            </li>
                             <?php  }// if  ?>
                            <?php if($allow_module['comments']){?>
                            <li <?=($module_active=='comments')?'class="active"':''?>>
                            	<a href="javascript:void(0)" >
                               		<!--<span class="da-nav-count">99</span>-->
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/speech_bubbles.png" alt="Comment" />
                                    </span>
                                	ความเห็น
                                </a>
                                 <ul <?=($module_active=='comments')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='comments')?'comments-categories.php':'../comments/comments-categories.php'?>">จัดการระบบ</a></li>
                                	<li><a href="<?=($module_active=='comments')?'comments.php':'../comments/comments.php'?>">จัดการความเห็น</a></li>
                                </ul>
                            </li>
                            <?php  }// if  ?>
                            <?php if($allow_module['contacts']){?>
                             <li <?=($module_active=='contacts')?'class="active"':''?>>
                            	<a href="javascript:void(0)">
                                	<!--<span class="da-nav-count">99</span>-->
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/mail.png" alt="Contact" />
                                    </span>
                                	ติดต่อเรา
                                </a>
                                 <ul  <?=($module_active=='contacts')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='contacts')?'categories.php':'../contacts/categories.php'?>">จัดการที่ติดต่อ</a></li>
                                	<li><a href="<?=($module_active=='contacts')?'index.php':'../contacts/index.php'?>">จัดการข้อความติดต่อ</a></li>
                                </ul>
                            </li>
                             <?php  }// if  ?>
                            <?php if($allow_module['addon']){?>
                            <li <?=($module_active=='addon')?'class="active"':''?>>
                                <a href="<?=($module_active=='addon')?'index.php':'../addon/index.php'?>">
                                    <!--<span class="da-nav-count">99</span>-->
                                    <!-- Icon Container -->
                                    <span class="da-nav-icon">
                                        <img src="../images/icons/black/32/mail.png" alt="Contact" />
                                    </span>
                                    สถิติโปรเจค
                                </a>
                            </li>
                            <?php  } // if  ?>
                            <?php if($allow_module['newsletters']){?>
                             <li <?=($module_active=='newsletters')?'class="active"':''?>>
                            	<a href="<?=($module_active=='newsletters')?'index.php':'../newsletters/index.php'?>">
                                	<!--<span class="da-nav-count">99</span>-->
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/mail.png" alt="Contact" />
                                    </span>
                                	จดหมายข่าว
                                </a>
                            </li>
                             <?php  } // if  ?>
                            <?php if($allow_module['files']){?>
                              <li <?=($module_active=='files')?'class="active"':''?>>
                            	<a href="<?=($module_active=='files')?'index.php':'../files/index.php'?>">
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/folder.png" alt="File Handling" />
                                    </span>
                                	จัดการไฟล์
                                </a>
                            </li>
                            <?php  } // if  ?>
                            <?php if($allow_module['statistics']){?>
                            <li <?=($module_active=='statistics')?'class="active"':''?>>
                            	<a href="<?=($module_active=='statistics')?'index.php':'../statistics/index.php'?>">
                                	<!-- Nav Notification -->
                                  <!--  <span class="da-nav-count">99</span>-->
                                	<!-- Icon Container -->
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/graph.png" alt="Charts" />
                                    </span>
                                	สถิติ
                                </a>
                            </li>
                            <?php  } // if  ?>
                            <?php if($allow_module['finds']){?>
                            <li <?=($module_active=='finds')?'class="active"':''?>>
                            	<a href="javascript:void(0)">
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/laptop.png" alt="Contact" />
                                    </span>
                                	Finds
                                </a>
                                 <ul  <?=($module_active=='finds')?'class="open"':'class="closed"'?>>
                                 	<li><a href="<?=($module_active=='finds')?'index.php':'../finds/index.php'?>">จัดการ Queries</a></li>
                                	 <li><a href="<?=($module_active=='finds')?'categories.php':'../finds/categories.php'?>">จัดการ Route</a></li>
                                </ul>
                            </li>
                            <?php } // if ?>
                            <?php if($allow_module['htmlzone']){?>
                            <li <?=($module_active=='htmlzone')?'class="active"':''?>>
                                <a href="javascript:void(0)">
                                    <span class="da-nav-icon">
                                        <img src="../images/icons/black/32/laptop.png" alt="Contact" />
                                    </span>
                                    Block HTML
                                </a>
                                 <ul  <?=($module_active=='htmlzone')?'class="open"':'class="closed"'?>>
                                    <li><a href="../htmlzone/index.php">Create Block</a></li>
                                    <li><a href="../htmlzone/htmlzone.php">Create Input</a></li>
                                </ul>
                            </li>
                            <?php }// if ?>
                            <?php if($allow_module['configs']){?>
                            <li <?=($module_active=='configs')?'class="active"':''?>>
                            	<a href="javascript:void(0)">
                                	<span class="da-nav-icon">
                                    	<img src="../images/icons/black/32/cog_5.png" alt="Configs" />
                                    </span>
                                	ตั้งค่าเว็บไซต์
                                </a>
                                 <ul  <?=($module_active=='configs')?'class="open"':'class="closed"'?>>
                                	<li><a href="<?=($module_active=='configs')?'configs-site.php':'../configs/configs-site.php'?>">ตั้งค่าข้อมูลหลัก</a></li>
                               		<li><a href="<?=($module_active=='configs')?'configs-email.php':'../configs/configs-email.php'?>">ตั้งค่าอีเมล</a></li>
                                </ul>
                            </li>
                               <?php }// if ?>
                        </ul>
                    </div>
                </div>
                <!--   end side bar -->