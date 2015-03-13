<?php

class Hotels extends \Phalcon\Mvc\Model
{

    protected $hotel_id;

    protected $country_id;

    protected $region_id;

    protected $city_id;

    protected $name;

    protected $type;

    protected $rating;

    protected $address;

    protected $address_orig;

    protected $rooms;

    protected $room_types;

    protected $checkin;

    protected $checkout;

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

    protected $rec_created;

    protected $rec_modified;

    protected $rec_modified_by;

    public function initialize()
    {
        $this->hasOne('country_id', 'Countries', 'country_id');
        $this->hasOne('region_id', 'Regions', 'region_id');
        $this->hasOne('city_id', 'Cities', 'city_id');
        $this->hasOne('rec_modified_by', 'Users', 'id');
    }

    public function setHotelId($hotel_id)
    {
        $this->hotel_id = (int) $hotel_id;

        return $this;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = (int) $country_id;

        return $this;
    }

    public function setRegionId($region_id)
    {
        $this->region_id = (int) $region_id;

        return $this;
    }

    public function setCityId($city_id)
    {
        $this->city_id = (int) $city_id;

        return $this;
    }

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
        $this->rating = (int) $rating;

        return $this;
    }

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

    public function setUrlOrig($url_orig)
    {
        $this->url_orig = $url_orig;

        return $this;
    }

    public function setHotelIdOrig($hotel_id_orig)
    {
        $this->hotel_id_orig = $hotel_id_orig;

        return $this;
    }

    public function setThumbUriOrig($thumb_uri_orig)
    {
        $this->thumb_uri_orig = $thumb_uri_orig;

        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function setRecCreated($rec_created)
    {
        $this->rec_created = $rec_created;

        return $this;
    }

    public function setRecModified($rec_modified)
    {
        $this->rec_modified = $rec_modified;

        return $this;
    }

    public function setRecModifiedBy($rec_modified_by)
    {
        $this->rec_modified_by = $rec_modified_by;

        return $this;
    }

    public function getHotelId()
    {
        return $this->hotel_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function getRegionId()
    {
        return $this->region_id;
    }

    public function getCityId()
    {
        return $this->city_id;
    }

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

    public function getUrlOrig()
    {
        return $this->url_orig;
    }

    public function getHotelIdOrig()
    {
        return $this->hotel_id_orig;
    }

    public function getThumbUriOrig()
    {
        return $this->thumb_uri_orig;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getRecCreated()
    {
        return $this->rec_created;
    }

    public function getRecModified()
    {
        return $this->rec_modified;
    }

    public function getRecModifiedBy()
    {
        return $this->rec_modified_by;
    }

    public function columnMap()
    {
        return array(
            'hotel_id' => 'hotel_id', 
            'country_id' => 'country_id',
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
            'status' => 'status',
            'rec_created' => 'rec_created',
            'rec_modified' => 'rec_modified',
            'rec_modified_by' => 'rec_modified_by'
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
