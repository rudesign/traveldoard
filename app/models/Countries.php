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
    protected $title_ua;

    /**
     *
     * @var string
     */
    protected $title_be;

    /**
     *
     * @var string
     */
    protected $title_en;

    /**
     *
     * @var string
     */
    protected $title_es;

    /**
     *
     * @var string
     */
    protected $title_pt;

    /**
     *
     * @var string
     */
    protected $title_de;

    /**
     *
     * @var string
     */
    protected $title_fr;

    /**
     *
     * @var string
     */
    protected $title_it;

    /**
     *
     * @var string
     */
    protected $title_pl;

    /**
     *
     * @var string
     */
    protected $title_ja;

    /**
     *
     * @var string
     */
    protected $title_lt;

    /**
     *
     * @var string
     */
    protected $title_lv;

    /**
     *
     * @var string
     */
    protected $title_cz;

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
     * Method to set the value of field title_ua
     *
     * @param string $title_ua
     * @return $this
     */
    public function setTitleUa($title_ua)
    {
        $this->title_ua = $title_ua;

        return $this;
    }

    /**
     * Method to set the value of field title_be
     *
     * @param string $title_be
     * @return $this
     */
    public function setTitleBe($title_be)
    {
        $this->title_be = $title_be;

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
     * Method to set the value of field title_es
     *
     * @param string $title_es
     * @return $this
     */
    public function setTitleEs($title_es)
    {
        $this->title_es = $title_es;

        return $this;
    }

    /**
     * Method to set the value of field title_pt
     *
     * @param string $title_pt
     * @return $this
     */
    public function setTitlePt($title_pt)
    {
        $this->title_pt = $title_pt;

        return $this;
    }

    /**
     * Method to set the value of field title_de
     *
     * @param string $title_de
     * @return $this
     */
    public function setTitleDe($title_de)
    {
        $this->title_de = $title_de;

        return $this;
    }

    /**
     * Method to set the value of field title_fr
     *
     * @param string $title_fr
     * @return $this
     */
    public function setTitleFr($title_fr)
    {
        $this->title_fr = $title_fr;

        return $this;
    }

    /**
     * Method to set the value of field title_it
     *
     * @param string $title_it
     * @return $this
     */
    public function setTitleIt($title_it)
    {
        $this->title_it = $title_it;

        return $this;
    }

    /**
     * Method to set the value of field title_pl
     *
     * @param string $title_pl
     * @return $this
     */
    public function setTitlePl($title_pl)
    {
        $this->title_pl = $title_pl;

        return $this;
    }

    /**
     * Method to set the value of field title_ja
     *
     * @param string $title_ja
     * @return $this
     */
    public function setTitleJa($title_ja)
    {
        $this->title_ja = $title_ja;

        return $this;
    }

    /**
     * Method to set the value of field title_lt
     *
     * @param string $title_lt
     * @return $this
     */
    public function setTitleLt($title_lt)
    {
        $this->title_lt = $title_lt;

        return $this;
    }

    /**
     * Method to set the value of field title_lv
     *
     * @param string $title_lv
     * @return $this
     */
    public function setTitleLv($title_lv)
    {
        $this->title_lv = $title_lv;

        return $this;
    }

    /**
     * Method to set the value of field title_cz
     *
     * @param string $title_cz
     * @return $this
     */
    public function setTitleCz($title_cz)
    {
        $this->title_cz = $title_cz;

        return $this;
    }

    public function setBcomId($bcom_id)
    {
        $this->bcom_id = $bcom_id;

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
     * Returns the value of field title_ua
     *
     * @return string
     */
    public function getTitleUa()
    {
        return $this->title_ua;
    }

    /**
     * Returns the value of field title_be
     *
     * @return string
     */
    public function getTitleBe()
    {
        return $this->title_be;
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
     * Returns the value of field title_es
     *
     * @return string
     */
    public function getTitleEs()
    {
        return $this->title_es;
    }

    /**
     * Returns the value of field title_pt
     *
     * @return string
     */
    public function getTitlePt()
    {
        return $this->title_pt;
    }

    /**
     * Returns the value of field title_de
     *
     * @return string
     */
    public function getTitleDe()
    {
        return $this->title_de;
    }

    /**
     * Returns the value of field title_fr
     *
     * @return string
     */
    public function getTitleFr()
    {
        return $this->title_fr;
    }

    /**
     * Returns the value of field title_it
     *
     * @return string
     */
    public function getTitleIt()
    {
        return $this->title_it;
    }

    /**
     * Returns the value of field title_pl
     *
     * @return string
     */
    public function getTitlePl()
    {
        return $this->title_pl;
    }

    /**
     * Returns the value of field title_ja
     *
     * @return string
     */
    public function getTitleJa()
    {
        return $this->title_ja;
    }

    /**
     * Returns the value of field title_lt
     *
     * @return string
     */
    public function getTitleLt()
    {
        return $this->title_lt;
    }

    /**
     * Returns the value of field title_lv
     *
     * @return string
     */
    public function getTitleLv()
    {
        return $this->title_lv;
    }

    /**
     * Returns the value of field title_cz
     *
     * @return string
     */
    public function getTitleCz()
    {
        return $this->title_cz;
    }

    public function getBcomId()
    {
        return $this->bcom_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('_countries');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'country_id' => 'country_id', 
            'title_ru' => 'title_ru', 
            'title_ua' => 'title_ua', 
            'title_be' => 'title_be', 
            'title_en' => 'title_en', 
            'title_es' => 'title_es', 
            'title_pt' => 'title_pt', 
            'title_de' => 'title_de', 
            'title_fr' => 'title_fr', 
            'title_it' => 'title_it', 
            'title_pl' => 'title_pl', 
            'title_ja' => 'title_ja', 
            'title_lt' => 'title_lt', 
            'title_lv' => 'title_lv', 
            'title_cz' => 'title_cz',
            'bcom_id' => 'bcom_id'
        );
    }

}
