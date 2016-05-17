<?php
/**
 * User: Nostalgie
 * Date: 26-Apr-16
 * Time: 11:25 AM
 */
require_once("GeoLocation.php");

$nosta_location= GeoLocation::fromDegrees(40.5187154, -74.4120953);
$chengdu= GeoLocation::fromDegrees(40.65, -73.95);

echo "The distance between me and Chengdu(miles) is:".
        $nosta_location->distanceTo($chengdu,'miles'). "<br/>";
echo "The distance between me and Chengdu(Km) is:".
        $nosta_location->distanceTo($chengdu,'kilometers') ."<br/>";

// get degrees from the Google map//  how this can't
// this location can be got from hbuilder API
$location= 'Chengdu Neusoft University';
$Google_res= GeoLocation::getGeocodeFromGoogle($location);
$lat= $Google_res->results[0]->geometry->location->lat;
$long= $Google_res->results[0]->geometry->location->lng;
echo $lat . ', ' . $long;