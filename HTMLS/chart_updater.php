<?php
require_once('config.php');

$table_name = date("Ymd");

# $records = $mysqli->query("SELECT submitted_at, averages FROM `".$table_name."` WHERE id MOD 5 = 0 ORDER BY id DESC LIMIT 10000") or die(mysqli_error($mysqli));
$records = $mysqli->query("SELECT submitted_at, averages FROM `".$table_name."` WHERE id MOD 5 = 0") or die(mysqli_error($mysqli));

$output = array('btc_c_k'=>'','btc_k_c'=>'', 'eth_c_k'=>'', 'eth_k_c'=>'', 'time'=>'');

while($res = mysqli_fetch_assoc($records)){
	$averages = json_decode($res['averages'], true);
	$output['time'] .= explode(" ", $res['submitted_at'])[1].'|';
	$output['btc_c_k'] .= round((($averages['Kraken']['BTC']['BIDS']-$averages['Catalx']['BTC']['ASKS'])/$averages['Catalx']['BTC']['ASKS'])*100 , 2).'|';
 	$output['btc_k_c'] .= round((($averages['Catalx']['BTC']['BIDS']-$averages['Kraken']['BTC']['ASKS'])/$averages['Kraken']['BTC']['ASKS'])*100 , 2).'|';
	$output['eth_c_k'] .= round((($averages['Kraken']['ETH']['BIDS']-$averages['Catalx']['ETH']['ASKS'])/$averages['Catalx']['ETH']['ASKS'])*100 , 2).'|';
 	$output['eth_k_c'] .= round((($averages['Catalx']['ETH']['BIDS']-$averages['Kraken']['ETH']['ASKS'])/$averages['Kraken']['ETH']['ASKS'])*100 , 2).'|';
}

$output['btc_c_k'] = rtrim($output['btc_c_k'],"| ");
$output['btc_k_c'] = rtrim($output['btc_k_c'],"| ");
$output['eth_c_k'] = rtrim($output['eth_c_k'],"| ");
$output['eth_k_c'] = rtrim($output['eth_k_c'],"| ");
$output['time'] = rtrim($output['time'],"| ");

// Export Array for TradingView
/*
$output = array('c_k'=>'','k_c'=>'');
foreach($results as $result){	
		$c_k = round((($result['kraken']['bid-avg']-$result['catalx']['ask-avg'])/$result['catalx']['ask-avg'])*100 , 2);
		$output['c_k'] .= "{ time :'".$result['time']."', value:".$c_k."},";
		$k_c = round((($result['catalx']['bid-avg']-$result['kraken']['ask-avg'])/$result['kraken']['ask-avg'])*100 , 2);
		$output['k_c'] .= "{ time :'".$result['time']."', value:".$k_c."},";
}

$output['c_k']  = "([".rtrim($output['c_k'] ,", ")."])";
$output['k_c']  = "([".rtrim($output['k_c'] ,", ")."])";
*/

// unset($results);
// var_dump($output);
echo json_encode($output);

?>