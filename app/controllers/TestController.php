<?php

use Phalcon\Http\Client\Request;

class TestController extends BaseController
{
    public function initialize(){
        parent::initialize();
    }

    public function testAction()
    {
        echo shell_exec('curl -I ya.ru');

        $this->view->disable();
    }

    // 200: city JSON is catched from origin
    public function getCityJSONAction(){

        for($i=0;$i<1;$i++) {
            $cities = new Cities();

            $result = $cities->query()->where('country_id = 1')->andWhere('http_status IS NULL')->limit(1)->execute();

            if ($city = $result->getFirst()) {
                echo $city->getCityId() . ': ' . $city->getTitleEn();

                $jsonStr = $this->getLocationJSON($city->getTitleEn(), $city->getRegionRu(), 'Россия');

                echo $jsonStr.'<br />' . PHP_EOL;

                $city->setJson($jsonStr);
                $city->setHTTPStatus(200);

//                if ($city->save()) {
//                    echo ' OK';
//                }
            } else {
                echo 'No data';
            }

            echo '<br /><br />' . PHP_EOL;
        }

        $this->view->disable();
    }

    private function getLocationJSON($location = '', $region = '', $country = ''){

        $location = empty($location) ? $this->request->get('location') : $location;
        //if(!empty($region)) $location .= ' '.$region;
        if(!empty($country)) $location .= ' '.$country;

        $location = urlencode($location);

        $bashCommand = <<<EOD
curl -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36" -H "Content-Type: text/plain; charset=utf-8" -H "Accept: application/json" -H "Accept-Encoding: gzip, deflate, sdch" -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6" -X GET 'http://booking.com/autocomplete?lang=ru&pid=6b1c422f5a320072&sid=02da8e0f4b98680e70edc35ac6b45492&aid=304142&stype=1&force_ufi=&cities_first=1&should_split=1&sugv=br&e_acb1=1&e_acb2=1&eb=0&add_themes=1&themes_match_start=0&include_synonyms=1&e_nr_labels=1&e_obj_labels=1&exclude_some_hotels=1&include_dest_count=1&max_results=10&include_extra_synonyms=0&term=$location'
EOD;

        $output = shell_exec($bashCommand);

        echo $output;

        return $output;

        $output = json_decode($output);

        $stdOut = array();

        if(!empty($output->city)){
            foreach($output->city as $set){
                /*
                [cc1] => ru
                [city_ufi] =>
                [dest_id] => -2960561
                [dest_type] => city
                [hotels] => 1386
                [label] => Moscow, Russia
                [label_highlighted] => <b>Moscow</b>, Russia
                [labels] => Array
                    (
                        [0] => stdClass Object
                            (
                                [hl] => 1
                                [required] => 1
                                [text] => Moscow
                                [type] => city
                            )

                        [1] => stdClass Object
                            (
                                [required] => 1
                                [text] => Russia
                                [type] => country
                            )

                    )

                [lc] => en
                [meta] => Array
                    (
                    )

                [nr_hotels] => 1386
                [nr_hotels_25] => 1503
                [nr_hotels_label] => 1386 вариантов размещения
                [rtl] => 0
                [type] => ci
                */
                $stdOut[] = $set->label_highlighted;
                $stdOut[] = '<a href="http://booking.com/searchresults.ru.html?city='.$set->dest_id.'" target="_blank">'.$set->nr_hotels_label.'</a>';
                $stdOut[] = '';


            }

            echo '<h1>'.$location.'</h1>';

            echo implode('<br />', $stdOut);
        }
    }
}
