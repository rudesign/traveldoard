<?php

use Phalcon\Exception as PException;

class ProcessController extends BaseController
{
    public function initialize()
    {
        parent::initialize();
    }

    // 201: city JSON is processed
    public function getCityJSONAction()
    {

        for($i=0;$i<5;$i++) {

            $cities = new Cities();

            $result = $cities->query()->where('country_id = 1')->andWhere('json IS NOT NULL')->andWhere('http_status = 200')->limit(1)->execute();

            if ($city = $result->getFirst()) {
                echo $city->getCityId() . ': ' . $city->getTitleEn() . '<br />' . PHP_EOL;

                $json = $city->getJson();

                $json = json_decode($json);

                if (!empty($json->city) && empty($json->__did_you_mean__)) {

                    $cityData = new stdClass();

                    foreach ($json->city as $cityData) {
                        if ($cityData->type == 'ci') {
                            break;
                        }
                    }

                    // dest_id
                    echo $cityData->dest_id . '<br />';
                    // hotels count
                    echo $cityData->hotels . '<br />';
                }

                $city->setDestId((!empty($cityData->dest_id) ? $cityData->dest_id : 0));
                $city->setHolels((!empty($cityData->hotels) ? $cityData->hotels : 0));
                $city->setHTTPStatus(201);

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

    public function getCityHotelsAction()
    {
        try {

            $itemsLimit = 50;

            // QueryParser location
            $lPath = $this->config->application->libraryDir . 'querypath/src/qp.php';

            require $lPath;

            if(!function_exists('qp')) throw new PException('No parser');

            // dir to look through
            $path = $_SERVER['DOCUMENT_ROOT'] . '/rawHotels';

            //echo $path.'<br />' . PHP_EOL;

            if (!$handle = opendir($path)) throw new PException('Cannot read dir');

            // walk over all stored files in the dir
            $filesCount = 0;
            while (($entry = readdir($handle)) !== false) {
                if (strlen($entry) > 2) {

                    $fname = $path . '/' . $entry;

                    if (is_file($fname)) {
                        $filesCount++;
                        $filesize = filesize($fname);

                        echo $entry . ' in '.$filesize.' bytes<br /><br />' . PHP_EOL;

                        $newFname = $path . '/done/' . $entry;

                        if($filesize) {

                            // get city id from the file name
                            $cityId = 0;
                            if ($rawEntry = explode('_', $entry)) $cityId = (int)reset($rawEntry);

                            // get city hotels count
                            $cities = new Cities();
                            $city = $cities->query()->where('city_id=' . $cityId)->columns('hotels')->execute()->getFirst();
                            // get stored city hotels count
                            $hotels = new Hotels();
                            $hotelsCount = $hotels->query()->where('city_id=' . $cityId)->columns('COUNT(*) as count')->execute()->getFirst();

                            echo 'Stored ' . $hotelsCount->count . ' from ' . $city->hotels . '<br /><br />' . PHP_EOL;

                            $qp = htmlqp($fname, '.nodates_hotels');

                            if ($qp->count()) {
                                $i = 0;
                                $s = $hotelsCount->count;
                                while (($i < $itemsLimit) && ($s < $city->hotels) && $item = $qp->find('.sr_item.sr_item_no_dates')->eq($i)) {

                                    $a = $item->find('h3 a')->eq(0);

                                    if ($hotelIdOrig = (int)$item->attr('data-hotelid')) {

                                        $name = trim($a->text());
                                        $address = trim($item->find('div.address')->text());
                                        $urlOrig = trim($a->attr('href'));
                                        $thumbUriOrig = trim($item->find('img.hotel_image')->attr('src'));

                                        echo $hotelIdOrig . '<br />' . PHP_EOL;
                                        echo $name . '<br />' . PHP_EOL;
                                        echo $address . '<br />' . PHP_EOL;
                                        echo $urlOrig . '<br />' . PHP_EOL;
                                        echo $thumbUriOrig . '<br />' . PHP_EOL;

                                        $hotels = new Hotels();

                                        $hotels->setCityId($cityId);
                                        $hotels->setName($name);
                                        $hotels->setAddress($address);
                                        $hotels->setHotelIdOrig($hotelIdOrig);
                                        $hotels->setUrlOrig($urlOrig);
                                        $hotels->setThumbUriOrig($thumbUriOrig);

                                        try {
                                            if ($hotels->create() != false) {
                                                echo 'OK' . PHP_EOL;
                                            } else {
                                                echo 'Failed ' . PHP_EOL;
                                                foreach ($hotels->getMessages() as $value) {
                                                    echo $value->getMessage();
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            echo $e->getMessage();
                                        }

                                        echo '<br /><br />' . PHP_EOL;
                                    }

                                    $i++;
                                    $s++;
                                }
                            } else echo 'No hotels grid<br />' . PHP_EOL;
                        }

                        if(rename($fname, $newFname)) echo 'Source file renamed<br />' . PHP_EOL; else echo 'Source file rename failed<br />' . PHP_EOL;

                        break;
                    }
                }
            }
            echo $filesCount.' file(s) in total<br />';

        }catch (PException $e){
            echo $e->getLine().'<br />' . PHP_EOL;
            echo $e->getMessage();
        }

        $this->view->disable();
    }

    public function getHotelAction()
    {
        try {

            // QueryParser location
            $lPath = $this->config->application->libraryDir . 'querypath/src/qp.php';

            require $lPath;

            if(!function_exists('qp')) throw new PException('No parser');

            // dir to look through
            $path = $_SERVER['DOCUMENT_ROOT'] . '/rawHotels/items';

            //echo $path.'<br />' . PHP_EOL;

            if (!$handle = opendir($path)) throw new PException('Cannot read dir');

            // walk over all stored files in the dir
            while (($entry = readdir($handle)) !== false) {
                if (strlen($entry) > 2) {

                    $fname = $path . '/' . $entry;

                    if (is_file($fname)) {

                        echo $entry . '<br />' . PHP_EOL;

                        $newFname = $path . '/done/' . $entry;

                        // get city id from the file name
                        $hotelId = 0;
                        if ($rawEntry = explode('.', $entry)) $hotelId = (int) reset($rawEntry);

                        $hotels = new Hotels();
                        if(!$hotel = $hotels->query()->where('hotel_id='.$hotelId)->execute()->getFirst()) throw new PException('No data');

                        $qp = htmlqp($fname, '.wrap-hotelpage-top');

                        if(!$qp->count()) throw new PException('No html slice');

                        echo 'Process <a href="/rawHotels/items/'.$hotel->getHotelId().'.html" target="_blank">'.$hotel->getName().'</a>, '.$hotel->cities->getTitleRu().'<br />' . PHP_EOL;

//                        <span id="hp_address_subtitle" class="jq_tooltip" rel="14" data-source="top_link" data-coords="37.610327093193064,55.75103368673392" data-node_tt_id="location_score_tooltip" data-bbox="37.58050561,55.74550902,37.62325346,55.76068242" data-width="350" title="">
//                            Улица Моховая 10, Здание 1, Москва, Россия,
//                        </span>

                        $address = trim($qp->find('#hp_address_subtitle')->text());

                        if(!empty($address)){
                            $hotel->setAddress($address);
                            echo 'Address: '.$address.'<br />' . PHP_EOL;
                        }

                        $hotel->save();

                        //if(rename($fname, $newFname)) echo 'Source file renamed'; else echo 'Source file rename failed';

                        break;
                    }
                }
            }

        }catch (PException $e){
            echo $e->getMessage();
        }

        $this->view->disable();
    }

}
