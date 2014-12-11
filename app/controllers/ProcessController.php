<?php

class ProcessController extends BaseController
{
    public function initialize(){
        parent::initialize();
    }

    public function getCityJSONAction(){

        $cities = new Cities();

        $result = $cities->query()->where('country_id = 1')->andWhere('json IS NOT NULL')->andWhere('http_status = 200')->limit(1)->execute();

        if($city = $result->getFirst()){
            echo $city->getCityId().': '.$city->getTitleEn().'<br />' . PHP_EOL;

            $json = $city->getJson();

            $json = json_decode($json);

            if(!empty($json->city)){

                $cityData = new stdClass();

                foreach($json->city as $cityData){
                    if($cityData->type == 'ci') {
                        break;
                    }
                }

                // dest_id
                echo $cityData->dest_id.'<br />';
                // hotels count
                echo $cityData->hotels.'<br />';
            }

            $city->setDestId($cityData->dest_id);
            $city->setHolels($cityData->hotels);
            $city->setHTTPStatus(201);

            if($city->save()){
                echo ' OK';
            }

        }else{
            echo 'No data';
        }


        $this->view->disable();
    }

}
