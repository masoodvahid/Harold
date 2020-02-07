<!DOCTYPE html>
<html>
<head>
	<title>Crypto Trigger 0.7</title>
	<link rel="stylesheet" type="text/css" href="includes/styles.css">
	<link rel="shortcut icon" type="image/ico" href="favicon.ico"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="includes/jquery-3.4.1.js"></script>
	<!-- FusionCharts -->
    <script type="text/javascript" src="includes/fusioncharts.js"></script>
    <!-- jQuery-FusionCharts -->
    <script type="text/javascript" src="includes/fusioncharts.jqueryplugin.min.js"></script>
    <!-- Fusion Theme -->
    <script type="text/javascript" src="includes/fusioncharts.theme.fusion.js"></script>
    <script charset="UTF-8" src="//cdn.sendpulse.com/js/push/d58a612463fbfef50003f884063d6172_0.js" async></script>
	
	<!--<meta http-equiv="refresh" content="20">-->
</head> 
<body>

	<div class="container">		
		<div class="hero">
			<h1 class="name"><strong>Crypto</strong> Trigger</h1>
			<span class="job-title" id="exchange_rate"></span>
			<span class="email">this page data automatically update in each <span id="timer">10</span> seconds				
			</span>			
			<h4 class="lead">
			</h4>
		</div>
	</div>

<!-- Skills and intrest section -->
	<div class="container">
		<table class="bordered">
			<thead style="height: 90px; border-top: none;">
				<tr>
					<th id="time" style="border-top: none;"></th>
					<th colspan="3" style="border-top: none;">Kraken</th>
					<th colspan="3" style="border-top: none;">Catalx</th>						
				</tr>
			</thead>
			
			<tr>
				<td>Currency-Market</td>

				<td>Ave. Ask</td>
				<td>Ave. Bid</td>
				<td>Span % <br> (K->C)</td>

				<td>Ave. Ask</td>
				<td>Ave. Bid</td>			
				<td>Span % <br>(C->K)</td>
			</tr>
			<tr id="BTC-ROW"></tr>
			<tr id="ETH-ROW"></tr>
		</table>
	</div>

	<div class="container">		
		<div id="chart-container" style="height: 450px;"></div>		
	</div>

	<div class="container">		
		<div class="sections">
			<div>
			<table class="zebra" id="kraken_markets">
				<thead style="height: 90px; border-top: none;">
					<tr>
						<th colspan='5' style="text-align: center">Kraken Last Orders (BTC-CAD)</th>						
					</tr>
					<tr>
						<th rowspan=2 style="border-top: none;"></th>
						<th colspan=2 class="table-custom">ASK</th>
						<th colspan=2 class="table-custom">BID</th>
					</tr>
					<tr>						
						<th class="table-custom">Price</th>
						<th class="table-custom">Volume</th>
						<th class="table-custom">Price</th>
						<th class="table-custom">Volume</th>
					</tr>
				</thead>
				
				<?php
				for ($i=1;$i<=10;$i++){
					echo '<tr><td>'. $i .'</td>
								<td class="table-custom red" 	id="kraken_btc_ask_'.$i.'">--</td>
								<td class="table-custom" 		id="kraken_btc_ask_volume_'.$i.'">--</td>
								<td class="table-custom green" 	id="kraken_btc_bid_'.$i.'">--</td>
								<td class="table-custom" 		id="kraken_btc_bid_volume_'.$i.'">--</td>
								</tr>';
				}
				?>
				<tr>
					<td>AVG</td>
					<td colspan="2" class="table-custom red" id="kraken_btc_ask_avg"></td><td class="table-custom green" colspan="2" id="kraken_btc_bid_avg"></td>
				</tr>
			</table>
		</div>
		</div>
		<div class="sections">
			<table class="zebra">
				<thead style="height: 90px; border-top: none;">
					<tr>
						<th colspan='5' style="text-align: center">Catalx Last Orders (BTC-CAD)</th>						
					</tr>
					<tr>
						<th rowspan=2 style="border-top: none;"></th>
						<th colspan=2 class="table-custom">ASK</th>
						<th colspan=2 class="table-custom">BID</th>
					</tr>
					<tr>						
						<th class="table-custom">Price</th>
						<th class="table-custom">Volume</th>
						<th class="table-custom">Price</th>
						<th class="table-custom">Volume</th>
					</tr>
				</thead>
				
				<?php
				for ($i=1;$i<=10;$i++){
					echo '<tr><td>'. $i .'</td>
							<td class="table-custom red" 	id="catalx_btc_ask_'.$i.'">--</td>
							<td class="table-custom" 		id="catalx_btc_ask_volume_'.$i.'">--</td>
							<td class="table-custom green" 	id="catalx_btc_bid_'.$i.'">--</td>
							<td class="table-custom" 		id="catalx_btc_bid_volume_'.$i.'">--</td>
							</tr>';
				}
				?>
				<tr>
					<td>AVG</td>
					<td colspan="2" class="table-custom red" id="catalx_btc_ask_avg"></td><td class="table-custom green" colspan="2" id="catalx_btc_bid_avg"></td>
				</tr>			
			</table>
		</div>
	</div>

	<div class="container">		
		<div class="sections">
			<div>
			<table class="zebra" id="kraken_markets">
				<thead style="height: 90px; border-top: none;">
					<tr>
						<th colspan='5' style="text-align: center">Kraken Last Orders (ETH-CAD)</th>						
					</tr>
					<tr>
						<th rowspan=2 style="border-top: none;"></th>
						<th colspan=2 class="table-custom">ASK</th>
						<th colspan=2 class="table-custom">BID</th>
					</tr>
					<tr>						
						<th class="table-custom">Price</th>
						<th class="table-custom">Volume</th>
						<th class="table-custom">Price</th>
						<th class="table-custom">Volume</th>
					</tr>
				</thead>
				
				<?php
				for ($i=1;$i<=10;$i++){
					echo '<tr><td>'. $i .'</td>
								<td class="table-custom red"	id="kraken_eth_ask_'.$i.'">--</td>
								<td class="table-custom" 		id="kraken_eth_ask_volume_'.$i.'">--</td>
								<td class="table-custom green" 	id="kraken_eth_bid_'.$i.'">--</td>
								<td class="table-custom" 		id="kraken_eth_bid_volume_'.$i.'">--</td>
								</tr>';
				}
				?>
				<tr>
					<td>AVG</td>
					<td colspan="2" class="table-custom red" id="kraken_eth_ask_avg"></td><td class="table-custom green" colspan="2" id="kraken_eth_bid_avg"></td>
				</tr>
			</table>
		</div>
		</div>
		<div class="sections">
			<table class="zebra">
				<thead style="height: 90px; border-top: none;">
					<tr>
						<th colspan='5' style="text-align: center">Catalx Last Orders (ETH-CAD)</th>						
					</tr>
					<tr>
						<th rowspan=2 style="border-top: none;"></th>
						<th colspan=2 class="table-custom">ASK</th>
						<th colspan=2 class="table-custom">BID</th>
					</tr>
					<tr>						
						<th class="table-custom">Price</th>
						<th class="table-custom">Volume</th>
						<th class="table-custom">Price</th>
						<th class="table-custom">Volume</th>
					</tr>
				</thead>
				
				<?php
				for ($i=1;$i<=10;$i++){
					echo '<tr><td>'. $i .'</td>
							<td class="table-custom red" 	id="catalx_eth_ask_'.$i.'">--</td>
							<td class="table-custom" 		id="catalx_eth_ask_volume_'.$i.'">--</td>
							<td class="table-custom green" 	id="catalx_eth_bid_'.$i.'">--</td>
							<td class="table-custom" 		id="catalx_eth_bid_volume_'.$i.'">--</td>
							</tr>';
				}
				?>
				<tr>
					<td>AVG</td>
					<td colspan="2" class="table-custom red" id="catalx_eth_ask_avg"></td><td class="table-custom green" colspan="2" id="catalx_eth_bid_avg"></td>
				</tr>			
			</table>
		</div>
	</div>
		<!--
		<div class="sections">
			<h2 class="section-title">Skills</h2>

			<div class="list-card">
				<span class="exp">+ 5 years</span>
				<div>
					<h3>Object programming & frameworks</h3>
					<span>PHP, Symfony, Laravel, Silex, …</span>
				</div>
			</div>
			
			<div class="list-card">
				<span class="exp">+ 3 years</span>
				<div>
					<h3>Design integration</h3>
					<span>Style and tools, JS Frameworks</span>
				</div>
			</div>	
					
			<div class="list-card">
				<span class="exp">+ 6 years</span>
				<div>
					<h3>Linux</h3>
					<span>Scripting, Servers management and protocols, Automation</span>
				</div>
			</div>


		</div>
		<div class="sections">
				<h2 class="section-title">Interests</h2>
				
				<div class="list-card">
					<div>
						<h3>Scripting languages</h3>
						<span>PHP, JS, Bash, Python</span>
					</div>
				</div>	

				<div class="list-card">
					<div>
						<h3>Hacking</h3>
						<span>Linux, Crawlers, Bots, Network</span>
					</div>
				</div>		
		</div>
	</div>
-->
	<!-- Achievements -->
<!--
	<div class="container cards">
		
		<div class="card">
			<div class="skill-level">
				<span>+</span>
				<h2>60</h2>
			</div>

			<div class="skill-meta">
				<h3>Projects</h3>
				<span>Adapting and creating solutions for customer's needs</span>
			</div>
		</div>

			
		<div class="card">
			<div class="skill-level">
				<h2>50</h2>
				<span>%</span>
			</div>

			<div class="skill-meta">
				<h3>Web</h3>
				<span>Applications development integrating third-party services and mobile client(s)</span>
			</div>
		</div>

			
		<div class="card">
			<div class="skill-level">
				<h2>30</h2>
				<span>%</span>
			</div>

			<div class="skill-meta">
				<h3>Technical solutions</h3>
				<span>Such as web services, scripts, configurations</span>
			</div>
		</div>


		<div class="card">
			<div class="skill-level">
				<h2>20</h2>
				<span>%</span>
			</div>

			<div class="skill-meta">
				<h3>Leading</h3>
				<span>Web projects and ensure the quality of delivery</span>
			</div>
		</div>

	</div>
-->
	<!-- Timeeline -->
<!--
	<div class="container">
		<ol class="timeline">
		  <li>
		    <p class="line">Experiences</p>
		    <span class="point"></span>
		    <p class="description">
		      Lead Developer @Geronimo
		    </p>
		    <span class="date">Today - Apr. 2016</span>
		  </li>

		  <li>
		    <span class="point"></span>
		    <p class="description">
		      Freelance
		    </p>
		    <span class="date">Apr. 2016 - Sep. 2015</span>
		  </li>

		  		  <li>
		    <p class="line">Education</p>
		    <span class="point"></span>
		    <p class="description">
		      DUT "Métiers du multimédia et de l'internet"
		    </p>
		    <span class="date">2015 - 2013</span>
		  </li>

		  		  <li>
		    <span class="point"></span>
		    <p class="description">
		      Art & Design studies
		    </p>
		    <span class="date">2013 - 2008</span>
		  </li>
		</ol>

	</div>

-->

<!--
	<footer class="container">
		<span style="font-size: 16px; margin-top: ">Coded by <a href="#">Masood Vahid </a></span>
	</footer>
-->
<script>
	

	$(document).ready(function(){
		
		updater();
		chartUpdater();
		setInterval(updater, 5000);
		setInterval(chartUpdater, 30000);
		
		function chartUpdater(){
			$.ajax({
	            url: "chart_updater.php", 
	            dataType: "json",
	            cache: false,
	            success: function (data) {
	                console.log(data);
	                charter(data.time,data.btc_c_k,data.btc_k_c,data.eth_c_k,data.eth_k_c)
	            },            
        	});
		};
				

		function updater() {
			var i = 0;
			var span_btc_c_k = 0;
		    var span_btc_k_c = 0;
		    var span_eth_c_k = 0;
		    var span_eth_k_c = 0;
			$.ajax({
				url: "table_updater.php", 
				dataType: "json",
				cache: false,
				success: function (data) {
					console.log(data['markets']['Kraken']['BTC']);
					$('#exchange_rate').text(data['exchange_rate']).fadeOut(250).fadeIn(250);
					$('#time').text(data['time'].split(' ')[1]).fadeOut(250).fadeIn(250);
					// BTC
					$.each(data['markets']['Kraken']['BTC']['ASKS'], function(price, volume){		  				
		  				i++;
		  				$('#kraken_btc_ask_'+i).html(price);
		  				$('#kraken_btc_ask_volume_'+i).html(volume);		  				
		  			});
		  			$('#kraken_btc_ask_avg').text(data['averages']['Kraken']['BTC']['ASKS']).fadeOut(250).fadeIn(250);
		  			
		  			i = 0;
		  			$.each(data['markets']['Kraken']['BTC']['BIDS'], function(price, volume){		  				
		  				i++;
		  				$('#kraken_btc_bid_'+i).html(price);
		  				$('#kraken_btc_bid_volume_'+i).html(volume);		  				
		  			});
		  			$('#kraken_btc_bid_avg').text(data['averages']['Kraken']['BTC']['BIDS']).fadeOut(250).fadeIn(250);

		  			i = 0;
		  			$.each(data['markets']['Catalx']['BTC']['ASKS'], function(price, volume){		  				
		  				i++;
		  				$('#catalx_btc_ask_'+i).html(price);
		  				$('#catalx_btc_ask_volume_'+i).html(volume);		  				
		  			});
		  			$('#catalx_btc_ask_avg').text(data['averages']['Catalx']['BTC']['ASKS']).fadeOut(250).fadeIn(250);

		  			i = 0;
		  			$.each(data['markets']['Catalx']['BTC']['BIDS'], function(price, volume){		  				
		  				i++;
		  				$('#catalx_btc_bid_'+i).html(price);
		  				$('#catalx_btc_bid_volume_'+i).html(volume);		  				
		  			});
		  			$('#catalx_btc_bid_avg').text(data['averages']['Catalx']['BTC']['BIDS']).fadeOut(250).fadeIn(250);

		  			// ETH
		  			i=0;
		  			$.each(data['markets']['Kraken']['ETH']['ASKS'], function(price, volume){		  				
		  				i++;
		  				$('#kraken_eth_ask_'+i).html(price);
		  				$('#kraken_eth_ask_volume_'+i).html(volume);		  				
		  			});
		  			$('#kraken_eth_ask_avg').text(data['averages']['Kraken']['ETH']['ASKS']).fadeOut(250).fadeIn(250);

		  			i = 0;
		  			$.each(data['markets']['Kraken']['ETH']['BIDS'], function(price, volume){		  				
		  				i++;
		  				$('#kraken_eth_bid_'+i).html(price);
		  				$('#kraken_eth_bid_volume_'+i).html(volume);		  				
		  			});
		  			$('#kraken_eth_bid_avg').text(data['averages']['Kraken']['ETH']['BIDS']).fadeOut(250).fadeIn(250);

		  			i = 0;
		  			$.each(data['markets']['Catalx']['ETH']['ASKS'], function(price, volume){		  				
		  				i++;
		  				$('#catalx_eth_ask_'+i).html(price);
		  				$('#catalx_eth_ask_volume_'+i).html(volume);		  				
		  			});
		  			$('#catalx_eth_ask_avg').text(data['averages']['Catalx']['ETH']['ASKS']).fadeOut(250).fadeIn(250);

		  			i = 0;
		  			$.each(data['markets']['Catalx']['ETH']['BIDS'], function(price, volume){		  				
		  				i++;
		  				$('#catalx_eth_bid_'+i).html(price);
		  				$('#catalx_eth_bid_volume_'+i).html(volume);		  				
		  			});
		  			$('#catalx_eth_bid_avg').text(data['averages']['Catalx']['ETH']['BIDS']).fadeOut(250).fadeIn(250);

		  			//SPANS 
		  			span_btc_c_k = (((data['averages']['Kraken']['BTC']['BIDS'] - data['averages']['Catalx']['BTC']['ASKS'])/data['averages']['Catalx']['BTC']['ASKS'])*100).toFixed(2);
		  			span_btc_k_c = (((data['averages']['Catalx']['BTC']['BIDS'] - data['averages']['Kraken']['BTC']['ASKS'])/data['averages']['Kraken']['BTC']['ASKS'])*100).toFixed(2);
		  			span_eth_c_k = (((data['averages']['Kraken']['ETH']['BIDS'] - data['averages']['Catalx']['ETH']['ASKS'])/data['averages']['Catalx']['ETH']['ASKS'])*100).toFixed(2);
		  			span_eth_k_c = (((data['averages']['Catalx']['ETH']['BIDS'] - data['averages']['Kraken']['ETH']['ASKS'])/data['averages']['Kraken']['ETH']['ASKS'])*100).toFixed(2);

			        $('#BTC-ROW').html("<td> BTC </td><td>"
			        	+data['averages']['Kraken']['BTC']['ASKS']+"</td><td>"
			        	+data['averages']['Kraken']['BTC']['BIDS']+"</td><td>"			        	
			        	+span_btc_k_c+"</td><td>"

			        	+data['averages']['Catalx']['BTC']['ASKS']+"</td><td>"
			        	+data['averages']['Catalx']['BTC']['BIDS']+"</td><td>"			        	
			        	+span_btc_c_k+"</td>");

			        $('#ETH-ROW').html("<td> ETH </td><td>"
			        	+data['averages']['Kraken']['ETH']['ASKS']+"</td><td>"
			        	+data['averages']['Kraken']['ETH']['BIDS']+"</td><td>"			        	
			        	+span_eth_k_c+"</td><td>"

			        	+data['averages']['Catalx']['ETH']['ASKS']+"</td><td>"
			        	+data['averages']['Catalx']['ETH']['BIDS']+"</td><td>"			        	
			        	+span_eth_c_k+"</td>");

				},
			});
		};

		function charter(time,btc_c_k,btc_k_c,eth_c_k,eth_k_c){		
	        $("#chart-container").insertFusionCharts({
	          type: "zoomline",
	          width: "100%",
	          height: "100%",
	          dataFormat: "json",
	          dataSource: {
	            chart: {
	              caption: "Span Chart for Current Day Records",
	              subcaption: "Click & drag on the plot area to zoom & then scroll",              
	              forceaxislimits: "1",
	              pixelsperpoint: "0",
	              pixelsperlabel: "30",
	              compactdatamode: "2",
	              dataseparator: "|",
	              theme: "fusion"
	            },
	            categories: [
	              {
	                category:
	                  time
	              }
	            ],
	            dataset: [
	              {
	                seriesname: "BTC C 〰 K ",
	                data:
	                  btc_c_k
	              },
	              {
	                seriesname: "BTC K 〰 C ",
	                data:
	                  btc_k_c
	              },
	              {
	                seriesname: "ETH C 〰 K ",
	                data:
	                  eth_c_k
	              },
	              {
	                seriesname: "ETH K 〰 C ",
	                data:
	                  eth_k_c
	              }
		            ]
		          }
		    });
	    };


	});

</script>




</body>
</html>