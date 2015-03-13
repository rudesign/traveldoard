<?php

class Countries extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $country_id;

    /**
     *
     * @var string
     */
    protected $title_ru;

    /**
     *
     * @var string
     */
    protected $title_en;

    /**
     * Method to set the value of field country_id
     *
     * @param integer $country_id
     * @return $this
     */
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;

        return $this;
    }

    /**
     * Method to set the value of field title_ru
     *
     * @param string $title_ru
     * @return $this
     */
    public function setTitleRu($title_ru)
    {
        $this->title_ru = $title_ru;

        return $this;
    }

    /**
     * Method to set the value of field title_en
     *
     * @param string $title_en
     * @return $this
     */
    public function setTitleEn($title_en)
    {
        $this->title_en = $title_en;

        return $this;
    }

    /**
     * Returns the value of field country_id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * Returns the value of field title_ru
     *
     * @return string
     */
    public function getTitleRu()
    {
        return $this->title_ru;
    }

    /**
     * Returns the value of field title_en
     *
     * @return string
     */
    public function getTitleEn()
    {
        return $this->title_en;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'country_id' => 'country_id', 
            'title_ru' => 'title_ru', 
            'title_en' => 'title_en'
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
