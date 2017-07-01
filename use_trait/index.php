<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/1
 * Time: 19:43
 */
require 'Geocodable.php';
require 'RetailStore.php';

$adapter = new \Ivory\HttpAdapter\GurlHttpAdapter();
$geocooder = new \Geocooder\Provider\GoogleMaps($adapter);

$store = new \experuse_interface\use_trait\RetailStore();
$store->setAddress('420 9th Avenue, New York, NY 10001 USA');
$store->setGeocoder($geocooder);

$latitude = $store->getLatitude();
$longitude = $store->getLongitude();

echo $latitude, ':', $longitude;