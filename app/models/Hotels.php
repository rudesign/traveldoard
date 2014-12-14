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
    protected $city_id;

    /**
     *
     * @var string
     */
    protected $name;

    protected $address;

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

    public function initialize()
    {
        $this->hasOne("city_id", "Cities", "city_id");
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

    public function setAddress($address)
    {
        $this->address = $address;

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
     * Returns the value of field hotel_id
     *
     * @return integer
     */
    public function getHotelId()
    {
        return $this->hotel_id;
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

    public function getAddress()
    {
        return $this->address;
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
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'hotel_id' => 'hotel_id', 
            'city_id' => 'city_id', 
            'name' => 'name', 
            'address' => 'address',
            'url_orig' => 'url_orig',
            'hotel_id_orig' => 'hotel_id_orig', 
            'thumb_uri_orig' => 'thumb_uri_orig'
        );
    }

}
