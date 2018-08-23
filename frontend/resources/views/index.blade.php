<!DOCTYPE html>
<html>
<head>
	<title>Dashboard - Ocean in Motion</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	
	<style type="text/css">
		body {
			background-color: #23303d;
			margin-top: 130px;
		}
		a {
			color: #23303d;
		}
		.mpas {
			width: 900px;
		    margin-bottom: 100px;
			-webkit-box-shadow: 0px 0px 20px rgba(0,0,0,.5);
		   	-moz-box-shadow: 0px 0px 20px rgba(0,0,0,.5);
		    box-shadow: 0px 3px 20px rgba(0,0,0,.5);
		}
		.mpa {
			background-color: white;
			padding: 15px;
		}
		.map-frame {
			border-radius: 10px;
		}
	</style>
</head>
<body>

	@include('partials/navbar')

	<div class="container mpas" style="border-radius: 15px;">
		<div class="row" style="background-color: white; padding: 25px; text-align: center; color: #23303d;">
			<div class="col">
				<h1>Marine Protected Areas</h1>
			</div>
		</div>
		@foreach($mpas as $mpa)
		<div class="row mpa">
			<div class="col-4 col-xs-3">
				<img class="map-frame" src="/img/mpas/{{ $mpa->map }}" height="128">
			</div>
			<div class="col-7 col-xs-8 my-auto">
				<div class="row">
					<div class="col">
						<a href="/mpa/{{ str_slug($mpa->name) }}"><h4>{{ $mpa->name }}</h4></a>	
					</div>
				</div>
				<div class="row">
					<div class="col">
						<span style="color: #3db1e8"><strong>MPA Area:</strong> {{ $mpa->area }} km<sup>2</sup></span>				
					</div>
				</div>
				<div class="row">
					<div class="col">
						<span style="color: #3db1e8"><strong>Year Established:</strong> {{ $mpa->year }}</span>
					</div>
				</div>
			</div>
			<div class="col-1 my-auto" style="display: none;">
				<img src="/img/warning.png" width="32">
			</div>
		</div>
		@endforeach
	</div>

</body>
</html>