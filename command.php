<?php

include_once(__DIR__ . '/SuitabilityScore.php');
include_once(__DIR__ . '/FileReader.php');


$options = getopt('s:n:');

$data = new FileReader($options['s']);
$shipmentData = $data->readFile();

$data = new FileReader($options['n']);
$driversData = $data->readFile();


$ss = new SuitabilityScore($shipmentData, $driversData);


 print_r( 'SS: ' . $ss->getMaxScore() . PHP_EOL);
 echo 'Drivers With Shipment:' . PHP_EOL;
 print_r($ss->result);
?>

