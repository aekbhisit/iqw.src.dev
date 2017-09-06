    <!-- Breadcrumbs -->
    <?php if(isset($breadcrumbs)){ ?>
                    <div id="da-breadcrumb">
                        <ul>
                        <?php 
						foreach($breadcrumbs as $k=>$breadcrumb){
							if($k==0){
                        	echo '<li class="'.$breadcrumb['class'].'"><span><a href="'.$breadcrumb['link'].'"><img src="../images/icons/black/16/home.png" alt="'.$breadcrumb['alt'].'" />'.$breadcrumb['name'].'</a></span></li>';
							}else{
							echo '<li class="'.$breadcrumb['class'].'"><span><a href="'.$breadcrumb['link'].'">'.$breadcrumb['name'].'</a></span></li>';
							}
						}
						?>
                        </ul>
                    </div>
        <?php } ?>