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
                            $city = $cities->query()->where('city_id=' . $cityId)->execute()->getFirst();
                            // get stored city hotels count
                            $hotels = new Hotels();
                            $hotelsCount = $hotels->query()->where('city_id=' . $cityId)->columns('COUNT(*) as count')->execute()->getFirst();

                            echo 'Stored ' . $hotelsCount->count . ' from ' . $city->getHotels() . '<br /><br />' . PHP_EOL;

                            $qp = htmlqp($fname, '.nodates_hotels');

                            if ($qp->count()) {
                                $i = 0;
                                $s = $hotelsCount->count;
                                while (($i < $itemsLimit) && ($s < $city->getHotels()) && $item = $qp->find('.sr_item.sr_item_no_dates')->eq($i)) {

                                    $a = $item->find('h3 a')->eq(0);

                                    if ($hotelIdOrig = (int)$item->attr('data-hotelid')) {

                                        $name = trim($a->text());
                                        $address = trim($item->find('div.address')->text());
                                        $urlOrig = trim($a->attr('href'));
                                        $thumbUriOrig = trim($item->find('img.hotel_image')->attr('src'));

                                        echo $hotelIdOrig . '<br />' . PHP_EOL;
                                        echo 'Название: '.$name . '<br />' . PHP_EOL;
                                        echo 'Населённый пункт: remote: '.$address . ', local: '.$city->getTitleRu().'<br />' . PHP_EOL;
                                        echo 'URL: <a href="http://booking.com'.$urlOrig . '" target="_blank">'.$urlOrig . '</a><br />' . PHP_EOL;
                                        echo 'Изображение: <a href="'.$thumbUriOrig . '" target="_blank">'.$thumbUriOrig . '</a><br />' . PHP_EOL;

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

                        /*
                        1. Полное название отеля
                        2. Полный адрес отеля
                        3. Текст описания отеля
                        4. Звёздность
                        5. Фотографии отеля и номеров
                        6. Количество номеров в отеле
                        7. Пластиковые карты, которые принимает отель
                        8. Список типов номеров отеля
                        9. Время check in и check out
                        10. Правила размещения детей и домашних животных
                        11. Наличие возможности доступа в Интернет
                        12. Наличие автомобильной парковки
                        13. Сервисы в отеле
                        14. Дополнительная информация о сервисах
                        15. Рекомендованные отели (видимо по схожим характеристикам)
                        16. Языки, на которых говорит персонал отеля
                        17. Количество отзывов
                        18. Пользовательская оценка отеля из 10 баллов по различным группам оценщиков (одиночки, пары, пары с детьми и т.д.)
                        19. Пользовательская оценка отеля из 10 баллов по различным параметрам (чистота, комфорт, качество wifi и т.д.)
                        1. SEO: description, keywords
                        2. Geo: широта, долгота
                        3. ссылки на страницу отеля на других языках
                        4. Тип отеля (напр. “Гостевые дома”)
                        */

                        $address = trim($qp->find('#hp_address_subtitle')->text());

                        if(!empty($address)){
                            $hotel->setAddress($address);
                            echo 'Address: '.$address.'<br />' . PHP_EOL;
                        }

                        //$hotel->save();

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
