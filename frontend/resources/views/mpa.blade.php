<!DOCTYPE html>
<html>
<head>
	<title>{{ $mpa->name }} - Ocean in Motion</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="{{ URL::asset('js/Chart.bundle.js') }}"></script>


	<style type="text/css">
		body {
			background-color: #23303d;
			margin-top: 130px;
		}
	</style>

	<script type="text/javascript">
		var hours = [];
		var myLineChart;
		var updateChartInterval;
		var counter = 0;
		function loadChart() {
			var ctxL = document.getElementById("lineChart").getContext('2d');
			var date = new Date();
			var hour = date.getHours();
			
			/*
			for (let i = 0; i < 24; i++) {
				hour++;
				if(hour == 24)
					hour = 0;
				hours[i] = hour;
			}
			*/
			myLineChart = new Chart(ctxL, {
			    type: 'line',
			    data: {
			        labels: hours,
			        datasets: [
			            {
			            	backgroundColor: "rgba(255, 0, 0, 0.6)",
			            	borderColor: "rgba(255, 0, 0, 0.7)",
			            	pointBackgroundColor: "rgba(255, 0, 0, 0.8)",
			                data: hours/*{!! json_encode($mpa->threat_levels) !!}*/
			            },
			        ]
			    },
			    options: {
			    	legend: {
		    	        display: false
		    	    },
			    	scales: {
			    		yAxes: [{
			    			scaleLabel: {
			    				display: true,
			    				labelString: 'Threat Levels [0 - 100]',
			    				fontColor: "#FFFFFF",
			    				fontSize: 13
			    			},
			    			gridLines: {
			    				display: false
			    			},
			    			ticks: {
			    				min: 0,
			    				max: 100,
			    				fontColor: "#FFFFFF",
			    				fontSize: 13
			    			}
			    		}],
			    		xAxes: [{
			    			scaleLabel: {
			    				display: true,
			    				labelString: 'Next 24 Hours',
			    				fontColor: "#FFFFFF",
			    				fontSize: 13
			    			},
			    			gridLines: {
			    				display: false
			    			},
			    			ticks: {
			    				fontColor: "#FFFFFF",
			    				fontSize: 13
			    			}
			    		}]
			    	},
			        responsive: true
			    }
			});
			updateChartInterval = setInterval(updateChart, 1000);
		}
		function updateChart() {
			hours.push(43);
			myLineChart.update();
			counter++;
			if(counter == 24) {
				clearInterval(updateChartInterval);
			}
		}
	</script>
</head>
<body onload="loadChart()">

	@include('partials/navbar')

	<div class="container-fluid" style="margin-bottom: 30px;">
		<div class="row" style="margin-bottom: 30px;">
			<div class="col" align="center">
				<h1 style="color: white;">{{ $mpa->name }}</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-6" style="border: solid 1px white;">
				a
			</div>
			<div class="col-6">
				<div class="row">
					<div class="col">
						<canvas id="lineChart"></canvas>
					</div>
				</div>
				<div class="row" align="center">
					<div class="col">
						<div class="row" style="border-top: solid 1px white; margin-top: 20px; padding-top: 20px; margin-bottom: 15px; color: white;" align="center">
							<div class="col">
								<h2>Highlights</h2>
							</div>
						</div>
						@foreach($mpa->event_highlights as $event)
						<div style="color: white; background-color: white; margin-bottom: 5px; padding: 15px; border-radius: 15px; width: 500px;">
							<div class="row">
								<div class="col">
									<h5 style="color: #23303d; font-weight: bold;">{{ $event[0] }}</h5>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<h6 style="color: #23303d;">{{ $event[1] }}</h6>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
				
			</div>
		</div>
		
	</div>


</body>
</html>