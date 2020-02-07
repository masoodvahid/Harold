<?php
namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
require_once('vendor/autoload.php');
require_once('///config.php');
date_default_timezone_set("America/Vancouver");
/************
*
* DB Section
*
*************/

// Set Table name , Create and orgnise DB table
$table_name = date("Ymd");						// 20190713
$table_check = mysqli_num_rows($mysqli->query("SHOW TABLES LIKE '".$table_name."' "));

if($table_check !== 0)
{	
   $record_order = (int) @$mysqli->query("SELECT record_order FROM `".$table_name."` ORDER BY record_order DESC LIMIT 1 ")->fetch_object()->record_order + 1 ;
   echo $record_order;
}
else
{
   $mysqli->query(
	  "CREATE TABLE IF NOT EXISTS `".$table_name."` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `record_order` int(11) DEFAULT NULL,
	  `source` varchar(128) DEFAULT NULL COMMENT 'Target Site name',
	  `bids` text DEFAULT NULL COMMENT 'json',
	  `asks` text DEFAULT NULL COMMENT 'json',	  		  
	  `period` int(11) DEFAULT NULL COMMENT 'seconds',
	  `currency` text DEFAULT NULL COMMENT 'BTC-CAD',	  
	  `submited_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	  ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
	") or die ( mysqli_error($mysqli) );
	$record_order = 1;
}


// start Chrome with 0 second timeout
$host = 'http://localhost:4444/wd/hub'; // this is the default
$capabilities = DesiredCapabilities::chrome();
//$capabilities = DesiredCapabilities::firefox();

$driver = RemoteWebDriver::create($host, $capabilities, 0);
$driver->get('https://catalx.io/trade/CAD-BTC');

// Re select and submit in DB in every $delay in 5 minutes
// In each 1 minutes, page reloaded again
$currency = "BTC-CAD";
(int) $each_session_time = 60; 			// Seconds  :: Must Change in cronJobs
(int) $delay = 10; 							// Seconds
(int) $loop_counter = $each_session_time / $delay;
(int) $retry_for_stale_elements = 100;		// Increase if Getting StaleElement Error
(int) $number_of_elements		= 10;		// Number of each ASK-BID elements in page

try{
	for ($k=1;$k<=$loop_counter;$k++)
	{		
		$time[$k] = date("h:i:sa");

		/*****************
		*
		*	  Catalx
		*
		******************/

		for ($j=1;$j<=$number_of_elements;$j++){
			for ($i=1;$i<$retry_for_stale_elements;$i++){
				try{
					$catalx[$k]['bid'][$j] = (float)  $driver->findElement(WebDriverBy::xpath("//tr[@class='price-level'][$j]//td[@class='bid-price']"))->getText();				
					break;
				}
				catch(StaleElementReferenceException $e){					
				}
				catch(Exception $e){
				}
				sleep(0.5);
			}

			for ($i=1;$i<$retry_for_stale_elements;$i++){			
				try{
					$catalx[$k]['ask'][$j] = (float) $driver->findElement(WebDriverBy::xpath("//tr[@class='price-level'][$j]//td[@class='ask-price']"))->getText();			
					break;
				}
				catch(StaleElementReferenceException $e){
				}
				catch(Exception $e){
				}
				sleep(0.5);
			}		
		}


		// Special Opertaion
		$json[$k]['catalx']['ask'] = json_encode( $catalx[$k]['ask'] );
		$json[$k]['catalx']['bid'] = json_encode( $catalx[$k]['bid'] );



		/***************** 
		*
		*	  Kraken 
		*
		******************/

		$data = json_decode(file_get_contents('https://api.kraken.com/0/public/Depth?pair=xbtcad&count=10'), true);
		

		$cc = 1;
		foreach ($data['result']['XXBTZCAD']['asks'] as $key => $value) {
		    $kraken[$k]['ask'][$cc] = (float) $value['0'];
		    $cc++;
		}

		$cc = 1;
		foreach ($data['result']['XXBTZCAD']['bids'] as $key => $value) {
		    $kraken[$k]['bid'][$cc] = (float) $value['0'];
		    $cc++;	    
		}	

		$json[$k]['kraken']['ask'] = json_encode( $kraken[$k]['ask'] );
		$json[$k]['kraken']['bid'] = json_encode( $kraken[$k]['bid'] );



		/***************** 
		*
		*	  Database 
		*
		******************/

		(int) $new_record_order = ( $record_order + $k )- 1 ; 
		$query = "INSERT INTO `".$table_name."`(
					`record_order`, 
					`source`, 
					`bids`, 
					`asks`,
					`period`, 
					`currency`
					) VALUES ( 
			 		'".$new_record_order ."',
			 		'Catalx',	 		
			 		'".$json[$k]['catalx']['bid']."',
			 		'".$json[$k]['catalx']['ask']."',	 		
			 		'".$delay."',
			 		'".$currency."'
			 	) ";

		$mysqli->query("$query") or die( mysqli_error($mysqli));
		unset($query);		

		$query = "INSERT INTO `".$table_name."`(
					`record_order`, 
					`source`, 
					`bids`, 
					`asks`,
					`period`, 
					`currency`
					) VALUES ( 
			 		'".$new_record_order ."',
			 		'Kraken',	 		
			 		'".$json[$k]['kraken']['bid']."',
			 		'".$json[$k]['kraken']['ask']."',	 		
			 		'".$delay."',
			 		'".$currency."'
			 	) ";

		$mysqli->query("$query")or die(mysqli_error($mysqli));
		unset($query);
		if($k!=$loop_counter){sleep ($delay);}
	}
} catch (Exception $e){
	echo 'Error';
}

var_dump($catalx[1]['ask']);
var_dump($kraken[1]['ask']);
$driver->close();
$driver->quit();