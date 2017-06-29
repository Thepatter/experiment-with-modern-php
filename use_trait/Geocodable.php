<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 2017/6/29
 * Time: 22:59
 */
namespace experuse_interface\use_trait;
/**
 * Geocodable性状,返回经纬度,然后在地图中绘制
 */
trait Geocodable {
    // var string
    protected $address;
    // var \Geocoder\Geocoder
    protected $geocoder;
    // var Geocoder\Result\Geocoded
    protected $geocoderResult;

    public function setGeocoder(\Geocodable\GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getLatitude()
    {
        if (isset($this->geocoderResult) === false) {
            $this->geocodeAddress();
        }
    }

    public function getLongitude()
    {
        if (isset($this->geocoderResult) === false) {
            $this->geocoderAdddress();
        }

        return $this->geocoderResult->getLongitude();
    }

    protected function geocodeAddress()
    {
        $this->geocoderResult = $this->geocoder->geocode($this->address);

        return true;
    }
}