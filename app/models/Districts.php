<?php

class Districts extends \Phalcon\Mvc\Model
{

    protected $district_id;

    protected $name;

    protected $name_en;

    protected $city_id;

    public function initialize()
    {
        $this->hasOne('city_id', 'Cities', 'city_id');
    }

    public function setDistrictId($district_id)
    {
        $this->district_id = $district_id;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setNameEn($name_en)
    {
        $this->name_en = $name_en;

        return $this;
    }

    public function setCityId($city_id)
    {
        $this->city_id = $city_id;

        return $this;
    }

    public function getDistrictId()
    {
        return $this->district_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNameEn()
    {
        return $this->name_en;
    }

    public function getCityId()
    {
        return $this->city_id;
    }

    public function columnMap()
    {
        return array(
            'district_id' => 'district_id', 
            'name' => 'name', 
            'name_en' => 'name_en', 
            'city_id' => 'city_id'
        );
    }

}
