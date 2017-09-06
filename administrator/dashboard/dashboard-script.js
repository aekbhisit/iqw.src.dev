// JavaScript Document
(function($) {
	$(document).ready(function(e) {
		loadCircularStat();
		getCountAllModule();
		var v = $("#da-ex-wizard-form").validate({ onsubmit: false });
		$("#da-ex-wizard-form").daWizard({
			forwardOnly: false, 
			onLeaveStep: function(index, elem) {
				return v.form();
			}, 
			onBeforeSubmit: function() {
				return v.form();
			}
		});
		/*
		$("#da-ex-calendar-gcal").fullCalendar({
			events: 'http://www.google.com/calendar/feeds/usa__en%40holiday.calendar.google.com/public/basic',
				
			eventClick: function(event) {
				// opens events in a popup window
				window.open(event.url, 'gcalevent', 'width=700,height=600');
				return false;
			}
		});
		*/
		google.setOnLoadCallback(drawCharts);
		function drawCharts() {
			drawLineChart();
		}
					
		function drawLineChart() {
				var d= new Date();
				var url = "../../app/index.php?module=statistics&task=getVisitorGraphData&d"+d.getTime() ;
				$.get(url,function(d){
					creatLineGraph(d) ;
				});
				//	alert(json_str) ;
			
				/*
			var data = google.visualization.arrayToDataTable(
		[["Month","Visitor","Page view"],["Jan",0,0],["Feb",0,0],["Mar",0,0],["Apr",0,0],["May",0,0],["Jun",0,0],["Jul",0,0],["Aug",2,7],["Sep",0,0],["Oct",0,0],["Nov",0,0],["Dec",0,0]
			]);
			*/
	
		}
	});
}) (jQuery);

function  loadCircularStat(){
	var d= new Date();
	var url = "../../app/index.php?module=statistics&task=getVisitorCircular&d"+d.getTime() ;
		$('#showCircularStat').load(url, function(data) {
		$(".da-circular-stat").daCircularStat();
		
		});
}

function creatLineGraph(d){
	var json_arr =  $.parseJSON(d) ;
	var chartData = [];
    $.each(json_arr,function(i,v){
      	chartData.push([v[0],v[1],v[2]]);
	}
    );
		var data = new google.visualization.DataTable();
					data.addColumn('string', 'Month');
					data.addColumn('number', 'Visitor');
					data.addColumn('number', 'Pages View');
					data.addRows( chartData) ; 
					var options = {};
					var chart = new google.visualization.LineChart($('#da-ex-gchart-line').get(0));
					$(window).on('debouncedresize', function() { chart.draw(data, options); });
					chart.draw(data, options);
}

function sentMailSupport(){
	var d = new Date();	
	var url = "../../app/index.php?module=supports&task=supportMail&d"+d.getTime() ;
	$.ajax({
		  type: 'POST', 
		  url: url, 
		  enctype: 'multipart/form-data', 
		  data: $('#client_msg').serialize(),
		  beforeSend: function() {
				$('#client_msg').validate({ 
					rules: {
					client_msg_title: {
						required: true
					},
					client_msg_text: {
						required: true
					}
				}, 
				invalidHandler: function(form, validator) {
					var errors = validator.numberOfInvalids();
					if (errors) {
						var message = errors == 1
						? 'ผิดพลาด ต้องใส่ข้อมูลให้ครบ'
						: 'ผิดพลาด ต้องใส่ข้อมูลให้ครบ';
						$("#form-error").html(message).show();
					} else {
						$("#form-error").hide();
					}
				}
				 });
				return $('#client_msg').valid();
			  },
		  success: function(data){
			 $.jGrowl("แจ้งเตือน ! <br> ส่งอีเมลถึงผู้พัฒนาระบบเสร็จ", {position: "bottom-right"});
			// gotoManagePage()
		 }
	});
}

function getCountAllModule(){
	var d= new Date();
	var url = "../../app/index.php?module=statistics&task=showCountAllModule&d"+d.getTime() ;
	// $.getJSON(url,function(data){
	// 		$.each(data,function(i,v){
	// 			var id =  "#show_"+i+"_count" ;
	// 			$(id).html(v);
	// 		});
	// });
}