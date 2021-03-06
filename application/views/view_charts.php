<!DOCTYPE html>
<!-- Using Highcharts -->
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME ?> | <?php echo $title ?></title>
    <link rel="shortcut icon" href="<?php echo base_url("img/favicon.png") ?>">
	<script type="text/javascript" src="http://chemlabaccs.com/js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="http://chemlabaccs.com/highcharts/Highcharts-3.0.7/js/highcharts.js"></script>
	
	<style type="text/css">
		Div.graph {
			width: 45%;
			float: left;
		}
		Div#accPerMonth {
			width: 90%;
		}
		Div#accRateMonth {
			width: 90%;
		}
	</style>
	
	<script type="text/javascript">	
		jQuery(document).ready(function()
		{
			console.log("Creating Graphs");
	/*	$('#accPerBuild').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: 'Accidents by Building'
				},
				xAxis: {
					categories: ['Chemistry', 'Biology', 'Physics']
				},
				yAxis: {
					title: {
						text: 'Total Accidents'
					}
				},
				series: <?php //echo $series_data;?>
			});
	*/		
			
			$('#accPerRate').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: {
				text: 'Accidents per severity'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000000',
						connectorColor: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'Percent with specified severity',
				data: <?php $count = 0;
							echo '[ ';
							foreach($severity_data as $severity) {
								echo '[\'' . $severity['name'] . '\', ' . $severity['data'] . ']'; 
								if($count != count($time_data)-1)
								{
									$count++;
									echo ', ';
								}
							} echo '] ';?>
			}]
			});
			
			$('#accPerMonth').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: 'Accidents Per Month'
				},
				xAxis: {
					categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
				},
				yAxis: {
					title: {
						text: 'Total Accidents'
					}
				},
				series: <?php echo $month_data;?>
			});
			
			$('#accRateMonth').highcharts({
            title: {
                text: 'Accidents Severity Per Month',
                x: -20 //center
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Accidents in <?php echo date('Y');?>'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: <?php echo $sev_month_data;?>
        });
			
			
			$('#accPerTime').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: {
				text: 'Accidents per time of day'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000000',
						connectorColor: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'Percent occuring within time range',
				data: <?php $count = 0;
							echo '[ ';
							foreach($time_data as $time) {
								echo '[\'' . $time['name'] . '\', ' . $time['data'] . ']'; 
								if($count != count($time_data)-1)
								{
									$count++;
									echo ', ';
								}
							} echo '] ';?>
			}]
			});
		});
	</script>
</head>

<body>
	<div class="graph" id="accPerMonth">
	</div>
	
<!--	<div class="graph" id="accPerBuild">
	</div>
	
	<div class="graph" id="accPerTime">
	</div>
-->
	<div class="graph" id="accPerRate">
	</div>
	
	<div class="graph" id="accRateMonth">
	</div>
	

</body>

</html>