<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Mestá SR</title>
		
		<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
		<script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
		<script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		
		<link rel="stylesheet" href="css/style.css">
		<script type="text/javascript" src="js/search.js"></script>
	</head>

	<body>
		<div class="container-fluid">
			<div class="row">
				<nav class="navbar navbar-default">
				  <div class="container-fluid">
					<div class="navbar-header">
					  <a class="navbar-brand" href="#"><img src="imgs/search.ico" alt="logo" width="30" height="25"/></a>
					</div>
					
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					  <ul class="nav navbar-nav">
						<li class="active"><a href="#">Vyhľadávanie<span class="sr-only">(current)</span></a></li>
						<li><a href="#">O webe</a></li>
					  </ul>
					  
					  <ul class="nav navbar-nav navbar-right">
						<li><a href="#">Link</a></li>
					  </ul>
					</div>
				  </div>
				</nav>
			</div>
		</div>
	
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-2">
				</div>
				
				<div class="col-md-7">
					<div class="row">
						<form role="search">
							<div class="row">
								<div class="form-group">
								  <input type="text" id="input" class="form-control" placeholder="Názov obce, starostu, email alebo web">
								  <div class="dropdown">
									<div href="#" class="dropdown-toggle" data-toggle="dropdown"></div>
									<div id="results" class="dropdown-menu col-xs-12" role="menu"></div>
								  </div>
								</div>
							</div>
						</form>
					</div>
					
					<div class="row margin_top" id="output">
						<div class="col-md-4">
							<div class="row">
								<h2 id="city_nazov"><h2>
							</div>
							
							<div class="row">
								<div class="col-md-5">
									<table class="table_border">
										<tr>
											<td nowrap class="text_align_right grey">obecný úrad:</td>
											<td nowrap><span id="city_urad" class="bold"></span></td>
										</tr>
										<tr>
											<td class="text_align_right grey">starosta:</td>
											<td nowrap><span id="city_starosta" class="bold"></span></td>
										</tr>
										<tr>
											<td class="text_align_right grey">tel:</td>
											<td nowrap><span id="city_tel" class="bold"></span></td>
										</tr>
										<tr>
											<td class="text_align_right grey">web:</td>
											<td><span id="city_web" class="bold"></span></td>
										</tr>
										<tr>
											<td class="text_align_right grey">fax:</td>
											<td nowrap><span id="city_fax" class="bold"></span></td>
										</tr>
										<tr>
											<td class="text_align_right grey">email:</td>
											<td><span id="city_email" class="bold"></span></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						
						<div class="col-md-2">
							<br><br><img id="city_erb" src="" alt="erb"/>
						</div>
						
						<div class="col-md-6">
							<div id="map"></div>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
				
				</div>
			</div>
			
			<div class="navbar navbar-inverse navbar-fixed-bottom" role="navigation">
				<div class="container">
					<div class="navbar-text pull-left">
						<p>© 2016 Ján Kanás</p>
						<p>Email: janokanas@gmail.com</p>
					</div>
				</div>
			</div>
			
		</div>

		<!--Google maps-->
		<script async defer
			src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_apikey; ?>&callback=initMap">
		</script>
	</body>
</html>