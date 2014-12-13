<?php

use \Phalcon\Exception as PException;

class ProcessController extends BaseController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function getCityJSONAction()
    {

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

            $city->setDestId((!empty($cityData->dest_id) ? $cityData->dest_id : 0));
            $city->setHolels((!empty($cityData->hotels) ? $cityData->hotels : 0));
            $city->setHTTPStatus(201);

            if($city->save()){
                echo ' OK';
            }

        }else{
            echo 'No data';
        }


        $this->view->disable();
    }

    public function getCityHotelsAction()
    {
        try {
            $itemsLimit = 50;

            // QueryParser location
            $lPath = $this->config->application->libraryDir . 'querypath/src/qp.php';

            @require $lPath;

            if(!function_exists('qp')) throw new PException('No parser');

            // dir to look through
            $path = $_SERVER['DOCUMENT_ROOT'] . '/rawHotels';

            if (!$handle = opendir($path)) throw new PException('Cannot read dir');

            // walk over all stored files in the dir
            while (($entry = readdir($handle)) !== false) {
                if (strlen($entry) > 2) {

                    $fname = $path . '/' . $entry;
                    $newFname = $path . '/done/' . $entry;

                    if (is_file($fname)) {

                        // get city id from the file name
                        $cityId = 0;
                        if ($rawEntry = explode('_', $entry)) $cityId = reset($rawEntry);

                        $qp = htmlqp($fname, '.nodates_hotels');

                        $i = 0;
                        while (($i < $itemsLimit) && $item = $qp->find('.sr_item.sr_item_no_dates')->eq($i)) {

                            $a = $item->find('h3 a')->eq(0);

                            $hotelIdOrig = (int)$item->attr('data-hotelid');
                            $name = $a->text();
                            $urlOrig = $a->attr('href');
                            $thumbUriOrig = $item->find('img.hotel_image')->attr('src');

                            echo $hotelIdOrig . '<br />' . PHP_EOL;
                            echo $name . '<br />' . PHP_EOL;
                            echo $urlOrig . '<br />' . PHP_EOL;
                            echo $thumbUriOrig . '<br />' . PHP_EOL;

                            $hotels = new Hotels();

                            $hotels->setCityId($cityId);
                            $hotels->setName($name);
                            $hotels->setHotelIdOrig($hotelIdOrig);
                            $hotels->setUrlOrig($urlOrig);
                            $hotels->setThumbUriOrig($thumbUriOrig);

                            try{
                                if ($hotels->create() != false) {
                                    echo 'OK' . PHP_EOL;
                                } else {
                                    echo 'Failed ' . PHP_EOL;
                                    foreach ($hotels->getMessages() as $value) {
                                        echo $value->getMessage();
                                    }
                                }
                            }catch (\Exception $e){
                                echo $e->getMessage();
                            }

                            echo '<br /><br />' . PHP_EOL;

                            $i++;
                        }

                        if(rename($fname, $newFname)) echo 'Source file renamed'; else echo 'Source file rename failed';
                    }
                }
                break;
            }

        }catch (PException $e){
            echo $e->getMessage();
        }

        $this->view->disable();
    }

}
