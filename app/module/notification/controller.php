<?php
if(isset($_GET['task'])){
	$task = $_GET['task'] ;
	switch($task){
		case 'getCategoriesData':
			$columns = array('order','name','author','date');
			$iTotal = 100 ;
			 $iFilteredTotal =  30 ;
			$output = array(
					"sEcho" => intval($_GET['sEcho']),
					"iTotalRecords" => $iTotal,
					"iTotalDisplayRecords" => $iFilteredTotal,
					"aaData" => array()
				);
			
			
			if(true){	
				$iconbar = '	<a href="#"><img src="images/icons/color/target.png" /></a>&nbsp;';
			}else{
				$iconbar = '	<a href="#"><img src="images/icons/color/stop.png" /></a>&nbsp;';
			}
									
			$iconbar .=	'<!--<a href="#"><img src="images/icons/color/magnifier.png" /></a>-->
                                	 <a href="#"><img src="images/icons/color/pencil.png" /></a>
                                    <a href="#"><img src="images/icons/color/cross.png" /></a>';
			for($i=0;$i<10;$i++){
				$order = '<a href="#"><img src="images/icons/black/16/arrow_up_small.png" /></a>
							     <a href="#"><img src="images/icons/black/16/arrow_down_small.png" /></a>
							    <!--<input name="order_'.$i.'" type="text" style="width:25px" value="'.$i.'"  />-->
							  ';
				$row_chk = '<input name="table_select_'.$i.'" id="table_select_'.$i.'" type="checkbox" value="'.$i.'" />&nbsp;'.($i+1);
				$rows[$i] = array(0=>$row_chk,1=>'name-'.$i,2=>'author-'.$i,3=>date('Ydm'),4=>$order,5=>$iconbar ,"DT_RowClass"=>'row'.$i+1,"DT_RowId"=>'row'.$i+1);
			}
			foreach($rows  as  $key => $row){
				$output["aaData"][] = $row ;
			}
				echo json_encode($output) ;
			break;
		case 'getNotifTopRight':
	
			$notif = array(
				0=>array(
							'title'=>'notification 1',
							'date'=>date("Y-m-d") ,
							'read'=>'read'
				),
				1=>array(
							'title'=>'notification 2',
							'date'=>date("Y-m-d") ,
							'read'=>'read'
				),
				2=>array(
							'title'=>'notification 3',
							'date'=>date("Y-m-d") ,
							'read'=>'unread'
				)
			);
			echo json_encode($notif);
			break;	
		
	}// switch
}// if isset
?>