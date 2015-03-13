<?php

class Cities extends \Phalcon\Mvc\Model
{

    protected $city_id;

    protected $country_id;

    protected $important;

    protected $region_id;

    protected $title_ru;

    protected $area_ru;

    protected $region_ru;

    protected $title_en;

    protected $area_en;

    protected $region_en;

    protected $json;

    protected $http_status;

    protected $dest_id;

    protected $hotels;

    protected $shift;

    public function initialize()
    {
        $this->hasOne('country_id', 'Countries', 'country_id');
    }

    public function setCityId($city_id)
    {
        $this->city_id = $city_id;

        return $this;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;

        return $this;
    }

    public function setImportant($important)
    {
        $this->important = $important;

        return $this;
    }

    public function setRegionId($region_id)
    {
        $this->region_id = $region_id;

        return $this;
    }

    public function setTitleRu($title_ru)
    {
        $this->title_ru = $title_ru;

        return $this;
    }

    public function setAreaRu($area_ru)
    {
        $this->area_ru = $area_ru;

        return $this;
    }

    public function setRegionRu($region_ru)
    {
        $this->region_ru = $region_ru;

        return $this;
    }

    public function setTitleEn($title_en)
    {
        $this->title_en = $title_en;

        return $this;
    }

    public function setAreaEn($area_en)
    {
        $this->area_en = $area_en;

        return $this;
    }

    public function setRegionEn($region_en)
    {
        $this->region_en = $region_en;

        return $this;
    }

    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    public function setHttpStatus($http_status)
    {
        $this->http_status = $http_status;

        return $this;
    }

    public function setDestId($dest_id)
    {
        $this->dest_id = $dest_id;

        return $this;
    }

    public function setHotels($hotels)
    {
        $this->hotels = $hotels;

        return $this;
    }

    public function setShift($shift)
    {
        $this->shift = $shift;

        return $this;
    }

    public function getCityId()
    {
        return $this->city_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function getImportant()
    {
        return $this->important;
    }

    public function getRegionId()
    {
        return $this->region_id;
    }

    public function getTitleRu()
    {
        return $this->title_ru;
    }

    public function getAreaRu()
    {
        return $this->area_ru;
    }

    public function getRegionRu()
    {
        return $this->region_ru;
    }

    public function getTitleEn()
    {
        return $this->title_en;
    }

    public function getAreaEn()
    {
        return $this->area_en;
    }

    public function getRegionEn()
    {
        return $this->region_en;
    }

    public function getJson()
    {
        return $this->json;
    }

    public function getHttpStatus()
    {
        return $this->http_status;
    }

    public function getDestId()
    {
        return $this->dest_id;
    }

    public function getHotels()
    {
        return $this->hotels;
    }

    public function getShift()
    {
        return $this->shift;
    }

    public function columnMap()
    {
        return array(
            'city_id' => 'city_id', 
            'country_id' => 'country_id', 
            'important' => 'important', 
            'region_id' => 'region_id', 
            'title_ru' => 'title_ru', 
            'area_ru' => 'area_ru', 
            'region_ru' => 'region_ru', 
            'title_en' => 'title_en', 
            'area_en' => 'area_en', 
            'region_en' => 'region_en', 
            'json' => 'json', 
            'http_status' => 'http_status', 
            'dest_id' => 'dest_id', 
            'hotels' => 'hotels', 
            'shift' => 'shift'
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
