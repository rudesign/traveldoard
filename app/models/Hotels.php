<?php

class Hotels extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $hotel_id;

    /**
     *
     * @var integer
     */
    protected $region_id;

    /**
     *
     * @var integer
     */
    protected $city_id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $address;

    protected $address_orig;

    protected $summary;

    /**
     *
     * @var string
     */
    protected $url_orig;

    /**
     *
     * @var integer
     */
    protected $hotel_id_orig;

    /**
     *
     * @var string
     */
    protected $thumb_uri_orig;

    /**
     *
     * @var string
     */
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

    public function setAddressOrig($address)
    {
        $this->address_orig = $address;

        return $this;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;

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

    public function getSummary()
    {
        return $this->summary;
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
            'address' => 'address', 
            'address_orig' => 'address_orig',
            'summary' => 'summary',
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
