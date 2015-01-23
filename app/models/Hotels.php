<?php

class Hotels extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $hotel_id;

    protected $region_id;

    protected $city_id;

    protected $name;

    protected $type;

    protected $rating;

    protected $address;

    protected $address_orig;

    protected $rooms;

    protected $roomTypes;

    protected $checkIn;

    protected $checkOut;

    protected $languages;

    protected $lat;

    protected $lng;

    protected $summary;

    protected $services;

    protected $extra_services;

    protected $payment_types;

    protected $children_policy;

    protected $food;

    protected $parking;

    protected $wellness;

    protected $free_internet;

    protected $internet;

    protected $gallery;

    protected $gallery_downloaded;

    protected $download_started;

    protected $url_orig;

    protected $hotel_id_orig;

    protected $thumb_uri_orig;

    protected $status;

    public function initialize()
    {
        $this->hasOne('city_id', 'Cities', 'city_id');
    }

    /**
     * Method to set the value of field hotel_id
     *
     * @param integer $hotel_id
     * @return $this
     */
    public function setHotelId($hotel_id)
    {
        $this->hotel_id = $hotel_id;

        return $this;
    }

    public function setRegionId($region_id)
    {
        $this->region_id = $region_id;

        return $this;
    }

    /**
     * Method to set the value of field city_id
     *
     * @param integer $city_id
     * @return $this
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    public function setAddressOrig($address)
    {
        $this->address_orig = $address;

        return $this;
    }

    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function setRoomTypes($room_types)
    {
        $this->room_types = $room_types;

        return $this;
    }

    public function setCheckIn($checkin)
    {
        $this->checkin = $checkin;

        return $this;
    }

    public function setCheckOut($checkout)
    {
        $this->checkout = $checkout;

        return $this;
    }

    public function setLanguages($languages)
    {
        $this->languages = $languages;

        return $this;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    public function setServices($services)
    {
        $this->services = $services;

        return $this;
    }

    public function setExtraServices($extra_services)
    {
        $this->extra_services = $extra_services;

        return $this;
    }

    public function setPaymentTypes($payment_types)
    {
        $this->payment_types = $payment_types;

        return $this;
    }

    public function setChildrenPolicy($children_policy)
    {
        $this->children_policy = $children_policy;

        return $this;
    }

    public function setFood($food)
    {
        $this->food = $food;

        return $this;
    }

    public function setParking($parking)
    {
        $this->parking = $parking;

        return $this;
    }

    public function setWellness($wellness)
    {
        $this->wellness = $wellness;

        return $this;
    }

    public function setFreeInternet($free_internet)
    {
        $this->free_internet = $free_internet;

        return $this;
    }

    public function setInternet($internet)
    {
        $this->internet = $internet;

        return $this;
    }

    public function setGallery($gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function setGalleryDownloaded($gallery_downloaded)
    {
        $this->gallery_downloaded = $gallery_downloaded;

        return $this;
    }

    public function setDownloadStarted($download_started)
    {
        $this->download_started = $download_started;

        return $this;
    }

    /**
     * Method to set the value of field url_orig
     *
     * @param string $url_orig
     * @return $this
     */
    public function setUrlOrig($url_orig)
    {
        $this->url_orig = $url_orig;

        return $this;
    }

    /**
     * Method to set the value of field hotel_id_orig
     *
     * @param integer $hotel_id_orig
     * @return $this
     */
    public function setHotelIdOrig($hotel_id_orig)
    {
        $this->hotel_id_orig = $hotel_id_orig;

        return $this;
    }

    /**
     * Method to set the value of field thumb_uri_orig
     *
     * @param string $thumb_uri_orig
     * @return $this
     */
    public function setThumbUriOrig($thumb_uri_orig)
    {
        $this->thumb_uri_orig = $thumb_uri_orig;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns the value of field hotel_id
     *
     * @return integer
     */
    public function getHotelId()
    {
        return $this->hotel_id;
    }

    public function getRegionId()
    {
        return $this->region_id;
    }

    /**
     * Returns the value of field city_id
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function getAddressOrig()
    {
        return $this->address_orig;
    }

    public function getRooms()
    {
        return $this->rooms;
    }

    public function getRoomTypes()
    {
        return $this->room_types;
    }

    public function getCheckIn()
    {
        return $this->checkin;
    }

    public function getCheckOut()
    {
        return $this->checkout;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function getExtraServices()
    {
        return $this->extra_services;
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function getPaymentTypes()
    {
        return $this->payment_types;
    }

    public function getChildrenPolicy()
    {
        return $this->children_policy;
    }

    public function getFood()
    {
        return $this->food;
    }

    public function getParking()
    {
        return $this->parking;
    }

    public function getWellness()
    {
        return $this->wellness;
    }

    public function getFreeInternet()
    {
        return $this->free_internet;
    }

    public function getInternet()
    {
        return $this->internet;
    }

    public function getGallery()
    {
        return $this->gallery;
    }

    public function getGalleryDownloaded()
    {
        return $this->gallery_downloaded;
    }

    public function getDownloadStarted()
    {
        return $this->download_started;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Returns the value of field url_orig
     *
     * @return string
     */
    public function getUrlOrig()
    {
        return $this->url_orig;
    }

    /**
     * Returns the value of field hotel_id_orig
     *
     * @return integer
     */
    public function getHotelIdOrig()
    {
        return $this->hotel_id_orig;
    }

    /**
     * Returns the value of field thumb_uri_orig
     *
     * @return string
     */
    public function getThumbUriOrig()
    {
        return $this->thumb_uri_orig;
    }

    /**
     * Returns the value of field status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'hotel_id' => 'hotel_id', 
            'region_id' => 'region_id',
            'city_id' => 'city_id',
            'name' => 'name',
            'type' => 'type',
            'rating' => 'rating',
            'address' => 'address',
            'address_orig' => 'address_orig',
            'rooms' => 'rooms',
            'room_types' => 'room_types',
            'checkin' => 'checkin',
            'checkout' => 'checkout',
            'languages' => 'languages',
            'summary' => 'summary',
            'services' => 'services',
            'extra_services' => 'extra_services',
            'payment_types' => 'payment_types',
            'children_policy' => 'children_policy',
            'food' => 'food',
            'parking' => 'parking',
            'wellness' => 'wellness',
            'free_internet' => 'free_internet',
            'internet' => 'internet',
            'gallery' => 'gallery',
            'gallery_downloaded' => 'gallery_downloaded',
            'download_started' => 'download_started',
            'lat' => 'lat',
            'lng' => 'lng',
            'url_orig' => 'url_orig',
            'hotel_id_orig' => 'hotel_id_orig', 
            'thumb_uri_orig' => 'thumb_uri_orig', 
            'status' => 'status'
        );
    }

    /**
     * Get count of items
     * @return int
     */
    public function getCount(){
        try{

            $query = $this->query()->columns('COUNT(*) AS total');
            $result = $query->execute();

            if(!$row = $result->getFirst()) throw new \Exception;

            return $row->total;
        }catch (\Exception $e){
            return 0;
        }
    }

}
