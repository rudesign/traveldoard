<?php

/*
 * /parser/getCityJSON - получение JSON-данных о каждом населённом пункте (200)
 * /process/getCityJSON - расшифровка JSON-данных о каждом населённом пункте (201)
 * /parser/getCityHotels - download hotels list html files (202)
 * /process/getCityHotels - get hotels from html files
 * /parser/getHotel - get stored hotel html (202)
 * /process/getHotel - get hotel info from html (203)
 * /process/downloadHotelGallery - get hotel gallery (204)
 * */

use Phalcon\Http\Client\Request;
use Phalcon\Exception as PException;

class ParserController extends BaseController
{
    private $countryId = 209;
    private $countryName = 'France';

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

        for($i=0;$i<10;$i++) {
            $cities = new Cities();

            $result = $cities->query()->where('country_id = '.$this->countryId)->andWhere('http_status IS NULL')->orderBy('city_id')->limit(1)->execute();

            if ($city = $result->getFirst()) {
                echo $city->getTitleEn().', '.$city->getRegionEn().', '.$this->countryName.' ('.$city->getCityId().')';

                echo '<br />' . PHP_EOL;

                $jsonStr = $this->getLocationJSON($city->getTitleEn(), $city->getRegionEn(), $this->countryName);

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

    private function getLocationJSON($slocation = '', $region = '', $country = ''){

        $location = empty($slocation) ? $this->request->get('location') : $slocation;
        if(!empty($region)) $location .= ' '.$region;
        if(!empty($country)) $location .= ' '.$country;

        $location = urlencode($location);

        $bashCommand = <<<EOD
curl -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36" -H "Content-Type: text/plain; charset=utf-8" -H "Accept: application/json" -H "Accept-Encoding: gzip, deflate, sdch" -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6" -X GET 'http://www.booking.com/autocomplete?lang=ru&pid=6b1c422f5a320072&sid=02da8e0f4b98680e70edc35ac6b45492&aid=304142&stype=1&force_ufi=&cities_first=1&should_split=1&sugv=br&e_acb1=1&e_acb2=1&eb=0&add_themes=1&themes_match_start=0&include_synonyms=1&e_nr_labels=1&e_obj_labels=1&exclude_some_hotels=1&include_dest_count=1&max_results=10&include_extra_synonyms=0&term=$location'
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

        $result = $cities->query()->where('country_id = '.$this->countryId)->andWhere('hotels > 0')->andWhere('shift < hotels')->limit(1)->execute();

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

    // download hotel html (set 202 for hotel)
    public function getHotelAction()
    {
        try {

            for($i=0;$i<4;$i++) {

                $hotels = new Hotels();

                $result = $hotels->query()
                    ->where('url_orig IS NOT NULL')
                    ->andWhere('status IS NULL')
                    ->orderBy('hotel_id')
                    ->limit(1)
                    ->execute();

                if (!$hotel = $result->getFirst()) throw new \Phalcon\Exception('No data');

                $fname = $_SERVER['DOCUMENT_ROOT'] . '/rawHotels/items/' . $hotel->getHotelId() . '.html';

                if (!$res = fopen($fname, 'a')) throw new PException('Cannot open file for writing');

                $location = $hotel->getUrlOrig();

                if (!$location = preg_replace('/\?\S+/i', '', $location)) throw new PException('Location error');

                $hotel->setUrlOrig($location);

                echo $location . '<br />' . PHP_EOL;

                $bashCommand = <<<EOD
curl -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36" -H "Content-Type: text/plain; charset=utf-8" -H "Accept: text/plain" -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6" -X GET -L 'http://booking.com$location'
EOD;

                $output = shell_exec($bashCommand);

                if (!@fwrite($res, $output)) throw new PException('File writting failed');

                fclose($res);

                $hotel->setStatus(202);

                if (!$hotel->update()) throw new PException('Status update failed');

                echo $hotel->getHotelId() . '.html stored<br />' . PHP_EOL;
            }

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
