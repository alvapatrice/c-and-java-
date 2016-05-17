<?php

/**
 * Written by: Nostalgie Patrice
 * Date: 26-Apr-16
 * Time: 11:21 AM
 * Chengdu Neusoft University
 */
class GeoLocation {
    private $radLat;  // latitude in radians
    private $radLon;  // longitude in radians
    private $degLat;	 // latitude in degrees
    private $degLon;  // longitude in degrees

    private $angular; // angular radius
    const EARTHS_RADIUS_KM = 6371.01;
    const EARTHS_RADIUS_MI = 3958.762079;
    protected static $MIN_LAT;  // -PI/2
    protected static $MAX_LAT;  //  PI/2
    protected static $MIN_LON;  // -PI
    protected static $MAX_LON;  //  PI
    public function __construct() {
        self::$MIN_LAT = deg2rad(-90);   // -PI/2
        self::$MAX_LAT = deg2rad(90);    //  PI/2
        self::$MIN_LON = deg2rad(-180);  // -PI
        self::$MAX_LON = deg2rad(180);   //  PI
    }
    /**
     * @param double $latitude the latitude, in degrees.
     * @param double $longitude the longitude, in degrees.
     * @return GeoLocation
     */
    public static function fromDegrees($latitude, $longitude) {
        $location = new GeoLocation();
        $location->radLat = deg2rad($latitude);
        $location->radLon = deg2rad($longitude);
        $location->degLat = $latitude;
        $location->degLon = $longitude;
        $location->checkBounds();
        return $location;
    }
    /**
     * @param double $latitude the latitude, in radians.
     * @param double $longitude the longitude, in radians.
     * @return GeoLocation
     */
    public static function fromRadians($latitude, $longitude) {
        $location = new GeoLocation();
        $location->radLat = $latitude;
        $location->radLon = $longitude;
        $location->degLat = rad2deg($latitude);
        $location->degLon = rad2deg($longitude);
        $location->checkBounds();
        return $location;
    }
    protected function checkBounds() {
        if ($this->radLat < self::$MIN_LAT || $this->radLat > self::$MAX_LAT ||
            $this->radLon < self::$MIN_LON || $this->radLon > self::$MAX_LON)
            throw new \Exception("Invalid Argument");
    }
    public function distanceTo(GeoLocation $location, $unit_of_measurement) {
        $radius = $this->getEarthsRadius($unit_of_measurement);
        return acos(sin($this->radLat) * sin($location->radLat) +
            cos($this->radLat) * cos($location->radLat) *
            cos($this->radLon - $location->radLon)) * $radius;
    }
    /**
     * @return double the latitude, in degrees.
     */
    public function getLatitudeInDegrees() {
        return $this->degLat;
    }
    /**
     * @return double the longitude, in degrees.
     */
    public function getLongitudeInDegrees() {
        return $this->degLon;
    }
    /**
     * @return double the latitude, in radians.
     */
    public function getLatitudeInRadians() {
        return $this->radLat;
    }

    /**
     * @return double the longitude, in radians.
     */
    public function getLongitudeInRadians() {
        return $this->radLon;
    }

    /**
     * @return double angular radius.
     */
    public function getAngular() {
        return $this->angular;
    }
    public function __toString() {
        return "(" . $this->degLat . ", " . $this->degLon . ") = (" .
        $this->radLat . " rad, " . $this->radLon . " rad";
    }

    protected function getEarthsRadius($unit_of_measurement) {
        $u = $unit_of_measurement;
        if($u == 'miles' || $u == 'mi')
            return $radius = self::EARTHS_RADIUS_MI;
        elseif($u == 'kilometers' || $u == 'km')
            return $radius = self::EARTHS_RADIUS_KM;
        else throw new \Exception('You must supply a valid unit of measurement');
    }
    /**
     *  Retrieves Geocoding information from Google
     *  eg. $response = GeoLocation::getGeocodeFromGoogle($location);
     *		$latitude = $response->results[0]->geometry->location->lng;
     *	    $longitude = $response->results[0]->geometry->location->lng;
     *	@param string $location address, city, state, etc.
     *	@return \stdClass
     */
    public static function getGeocodeFromGoogle($location) {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($location).'&sensor=false';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return json_decode(curl_exec($ch));
    }
    public static function MilesToKilometers($miles) {
        return $miles * 1.6093439999999999;
    }
    public static function KilometersToMiles($km) {
        return $km * 0.621371192237334;
    }
}
