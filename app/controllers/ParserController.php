<?php

use Phalcon\Http\Client\Request;
use Phalcon\Exception as PException;

class ParserController extends BaseController
{
    public function initialize(){
        parent::initialize();
    }

    public function idCountriesAction()
    {
        $countries = new Countries();

        $rows = $countries->query()->orderBy('title_en ASC')->execute();

        foreach ($rows as $row) {
            echo $row->getTitleEn().'<br />';

            $row->setBcomId(1);

            $row->update();
        }
    }

    // 200: city JSON is catched from origin
    public function getCityJSONAction(){

        for($i=0;$i<5;$i++) {
            $cities = new Cities();

            $result = $cities->query()->where('country_id = 1')->andWhere('http_status IS NULL')->orderBy('city_id')->limit(1)->execute();

            if ($city = $result->getFirst()) {
                echo $city->getCityId() . ': ' . $city->getTitleEn();

                $jsonStr = $this->getLocationJSON($city->getTitleEn(), $city->getRegionRu(), 'Россия');

                echo $jsonStr.'<br />' . PHP_EOL;

                $city->setJson($jsonStr);
                $city->setHTTPStatus(200);

                if ($city->save()) {
                    echo ' OK';
                }
            } else {
                echo 'No data';
            }

            echo '<br /><br />' . PHP_EOL;
        }

        $this->view->disable();
    }

    // curl -i
    // -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36"
    // -H "Content-Type: text/plain; charset=utf-8"
    // -H "Accept: */*"
    // -H "Accept-Encoding: gzip, deflate, sdch"
    // -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6"
    // -H "Cookie: utag_main=v_id:0149e65771d7001ecb26216f6ca605066003705e00bd0$_sn:1$_ss:1$_pn:1%3Bexp-session$_st:1416910530839$ses_id:1416908730839%3Bexp-session; zz_cook_segment=1; _ga=GA1.2.272112262.1416908731; __utma=1.272112262.1416908731.1416908731.1416908731.1; __utmc=1; __utmz=1.1416908731.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); bkng=11UmFuZG9tSVYkc2RlIyh9YWJdm48m5cJDWuLLIYaigN7j6j48cUdQU8ks0%2Fk62aC%2BxdDf7fqoRnTtnrQ7rtxNgi9Nffvg1Sj17HjkVCAzuJbqR8QXty4azG%2BIzhFHqNRBFT1J1B8B%2FYIgcqxushhAUAh2A4WabiSsfZRFPR2tf2qTMKNI0dulkCB53sH2MK8L149VfRyORNE%3D"
    // -X GET 'http://booking.com/autocomplete?lang=ru&pid=6b1c422f5a320072&sid=02da8e0f4b98680e70edc35ac6b45492&aid=304142&stype=1&force_ufi=&cities_first=1&should_split=1&sugv=br&e_acb1=1&e_acb2=1&eb=0&add_themes=1&themes_match_start=0&include_synonyms=1&e_nr_labels=1&e_obj_labels=1&exclude_some_hotels=1&include_dest_count=1&max_results=10&include_extra_synonyms=0&term=Italy'

    private function getLocationJSON($slocation = '', $region = '', $country = ''){

        $location = empty($slocation) ? $this->request->get('location') : $slocation;
        if(!empty($region)) $location .= ' '.$region;
        if(!empty($country)) $location .= ' '.$country;

        $location = urlencode($location);

        $bashCommand = <<<EOD
curl -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36" -H "Content-Type: text/plain; charset=utf-8" -H "Accept: application/json" -H "Accept-Encoding: gzip, deflate, sdch" -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6" -X GET 'http://booking.com/autocomplete?lang=ru&pid=6b1c422f5a320072&sid=02da8e0f4b98680e70edc35ac6b45492&aid=304142&stype=1&force_ufi=&cities_first=1&should_split=1&sugv=br&e_acb1=1&e_acb2=1&eb=0&add_themes=1&themes_match_start=0&include_synonyms=1&e_nr_labels=1&e_obj_labels=1&exclude_some_hotels=1&include_dest_count=1&max_results=10&include_extra_synonyms=0&term=$location'
EOD;

        $output = shell_exec($bashCommand);

        $outputJSON = json_decode($output);

        if(!empty($outputJSON->city)){
            return $output;
        }else{
            $location = empty($slocation) ? $this->request->get('location') : $slocation;
            if(!empty($country)) $location .= ' '.$country;

            $location = urlencode($location);

            $bashCommand = <<<EOD
curl -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36" -H "Content-Type: text/plain; charset=utf-8" -H "Accept: application/json" -H "Accept-Encoding: gzip, deflate, sdch" -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6" -X GET 'http://booking.com/autocomplete?lang=ru&pid=6b1c422f5a320072&sid=02da8e0f4b98680e70edc35ac6b45492&aid=304142&stype=1&force_ufi=&cities_first=1&should_split=1&sugv=br&e_acb1=1&e_acb2=1&eb=0&add_themes=1&themes_match_start=0&include_synonyms=1&e_nr_labels=1&e_obj_labels=1&exclude_some_hotels=1&include_dest_count=1&max_results=10&include_extra_synonyms=0&term=$location'
EOD;
            return shell_exec($bashCommand);
        }

        $stdOut = array();

        if(!empty($outputJSON->city)){


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

    // 202: hotels count > 0
    public function getCityHotelsAction(){
        $rows = 50;

        $cities = new Cities();

        $result = $cities->query()->where('country_id = 1')->andWhere('hotels > 0')->andWhere('shift < hotels')->limit(1)->execute();

        if($city = $result->getFirst()){
            $shift = $city->getShift();

            $shift = $shift ? $shift : 0;

            echo $city->getCityId().': '.$city->getTitleEn().', shift: '.$shift.'<br />' . PHP_EOL;

            $dest_id = $city->getDestId();

            $bashCommand = <<<EOD
curl -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36" -H "Content-Type: text/plain; charset=utf-8" -H "Accept: */*" -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6" -X GET 'http://www.booking.com/searchresults.ru.html?city={$dest_id}&lang=ru&rows={$rows}&offset={$shift}'
EOD;

            $output = shell_exec($bashCommand);

            //echo $output;

            $fname = $_SERVER['DOCUMENT_ROOT'].'/rawHotels/'.$city->getCityId().'_'.$shift.'_'.($shift + $rows).'.html';

            if($res = fopen($fname, 'a')){
                fwrite($res, $output);

                fclose($res);
            }else {
                echo 'Cant open file '.$fname.' for writing'.'<br />' . PHP_EOL;
                exit;
            }

            $city->setHTTPStatus(202);
            $city->setShift(($shift + $rows));

            if($city->save()){
                echo ' OK';
            }
        }else{
            echo 'No data';
        }

        $this->view->disable();
    }

    // 202
    public function getHotelAction()
    {
        try {
            $hotels = new Hotels();

            $result = $hotels->query()
                ->where('url_orig IS NOT NULL')
                ->andWhere('status IS NULL')
                ->orderBy('hotel_id')
                ->limit(1)
                ->execute();

            if (!$hotel = $result->getFirst()) throw new \Phalcon\Exception('No data');

            $fname = $_SERVER['DOCUMENT_ROOT'].'/rawHotels/items/'.$hotel->getHotelId().'.html';

            if(!$res = fopen($fname, 'a')) throw new PException('Cannot open file for writing');

            $location = $hotel->getUrlOrig();

            $bashCommand = <<<EOD
curl -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36" -H "Content-Type: text/plain; charset=utf-8" -H "Accept: text/plain" -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6" -X GET -L 'http://booking.com$location'
EOD;

            $output = shell_exec($bashCommand);

            fwrite($res, $output);

            fclose($res);

            $hotel->setStatus(202);

            if(!$hotel->update()) throw new \Phalcon\Exception('Status update failed');

            echo $hotel->getHotelId().'.html stored<br />' . PHP_EOL;

        }catch (PException $e){
            echo $e->getMessage().'<br >' . PHP_EOL;
        }

        $this->view->disable();
    }

    protected function curl($data = array())
    {
        $provider  = Request::getProvider();

        $provider->setBaseUri($data['url']);

        $provider->header->set('User-Agent', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36');
        $provider->header->set('Content-Type', 'text/plain; charset=utf-8');
        $provider->header->set('Accept', '*/*');

        if(empty($data['location'])) $data['location'] = '/';
        if(empty($data['params'])) $data['params'] = array();

        $response = $provider->get($data['location'], $data['params']);

        return $response;

    }
}
