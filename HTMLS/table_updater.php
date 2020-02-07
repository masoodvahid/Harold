<?php
require_once('config.php');

$table_name = date("Ymd");

$last_records = $mysqli->query("SELECT * FROM `".$table_name."` ORDER BY id DESC LIMIT 1") or die(mysqli_error($mysqli));


$record = mysqli_fetch_assoc($last_records);


$output = '{"id": '.$record["id"].', "exchange_rate": '.$record["exchange_rate"].' , "time": "'.$record["submitted_at"].'", "averages": '.$record["averages"].', "markets": '.$record["markets"].'}';
// $oo = json_encode( $output );
// echo json_encode($record);
// var_dump($oo)
echo $output;


// while($record = mysqli_fetch_assoc($last_records)){	
// 	$results[$record['source']]['name'] = $record['source'];
// 	$results[$record['source']]['asks'] = json_decode($record['asks'],true);
// 	$results[$record['source']]['bids'] = json_decode($record['bids'],true);	
// 	$results[$record['source']]['period'] = $record['period'];
// 	$results[$record['source']]['time'] = $record['submitted_at'];
// 	$results[$record['source']]['currency'] = $record['currency'];
// 	$results[$record['source']]['record_order'] = $record['record_order'];
// }

// echo json_encode($results);
// unset($results);

?>