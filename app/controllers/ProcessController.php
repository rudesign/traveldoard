<?php

use Phalcon\Exception as PException;

class ProcessController extends BaseController
{
    private $countryId = 209;

    public function initialize()
    {
        parent::initialize();
    }

    // 201: city JSON is processed
    public function getCityJSONAction()
    {

        for($i=0;$i<10;$i++) {

            $cities = new Cities();

            $result = $cities->query()->where('country_id = '.$this->countryId)->andWhere('json IS NOT NULL')->andWhere('http_status = 200')->limit(1)->execute();

            if ($city = $result->getFirst()) {
                echo $city->getCityId() . ': ' . $city->getTitleEn() . '<br />' . PHP_EOL;

                $json = $city->getJson();

                $json = json_decode($json);

                $city->setDestId(0);
                $city->setHotels(0);
                $city->setHTTPStatus(201);

                if (!empty($json->city) && empty($json->__did_you_mean__)) {

                    $cityData = new stdClass();

                    foreach ($json->city as $cityData) {
                        if ($cityData->type == 'ci') {
                            break;
                        }
                    }

                    // dest_id
                    echo 'Dest ID: '.$cityData->dest_id . '<br />';
                    // hotels count
                    echo 'Hotels: '.$cityData->hotels . '<br />';

                    $city->setDestId(($cityData->dest_id ? $cityData->dest_id : 0));
                    $city->setHotels((!empty($cityData->hotels) ? $cityData->hotels : 0));
                }

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

    // getting hotels from html & store them into db
    // /rawHotels -> /rawHotels/done
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
                                        $address = str_replace("\r", ' ', $address);
                                        $address = str_replace("\n", ' ', $address);
                                        $address = str_replace("  ", ' ', $address);
                                        $urlOrig = trim($a->attr('href'));
                                        $thumbUriOrig = trim($item->find('img.hotel_image')->attr('src'));

                                        echo $hotelIdOrig . '<br />' . PHP_EOL;
                                        echo 'Название: '.$name . '<br />' . PHP_EOL;
                                        echo 'Населённый пункт: remote: '.$address . ', local: '.$city->getTitleRu().'<br />' . PHP_EOL;
                                        echo 'URL: <a href="http://booking.com'.$urlOrig . '" target="_blank">'.$urlOrig . '</a><br />' . PHP_EOL;
                                        echo 'Изображение: <a href="'.$thumbUriOrig . '" target="_blank">'.$thumbUriOrig . '</a><br />' . PHP_EOL;

                                        $hotels = new Hotels();

                                        $hotels->setCountryId($this->countryId);
                                        $hotels->setCityId($cityId);
                                        $hotels->setName($name);
                                        $hotels->setAddress($address);
                                        $hotels->setHotelIdOrig($hotelIdOrig);
                                        $hotels->setUrlOrig($urlOrig);
                                        $hotels->setThumbUriOrig($thumbUriOrig);
                                        $hotels->setRecCreated(time());

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

    // 203
    public function getHotelAction()
    {
        try {
            for($l=0;$l<5;$l++){

                // QueryParser location
                $lPath = $this->config->application->libraryDir . 'querypath/src/qp.php';

                @require $lPath;

                if(!function_exists('qp')) throw new PException('No parser');

                // dir to look through
                $path = $_SERVER['DOCUMENT_ROOT'] . '/rawHotels/items';

                //echo $path.'<br />' . PHP_EOL;

                if (!$handle = @opendir($path)) throw new PException('Cannot read dir');

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

                            if($hotel = $hotels->query()->where('hotel_id='.$hotelId)->limit(1)->execute()->getFirst()) {

                                $qp = htmlqp($fname, 'html');

                                if ($qp->count()){

                                    echo 'Process <a href="/rawHotels/items/' . $hotel->getHotelId() . '.html" target="_blank">' . $hotel->getName() . '</a>, ' . $hotel->cities->getTitleRu() . '<br />' . PHP_EOL;

                                    // address

                                    $data = trim($qp->find('#hp_address_subtitle')->eq(0)->text());
                                    if (!empty($data)) {
                                        echo '<b>Address:</b> ' . $data . '<br />' . PHP_EOL;
                                        $hotel->setAddressOrig($data);
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }


                                    // description

                                    $data = $qp->find('#summary')->find('p')->not('.hp_district_endorsements')->get();
                                    $text = array();
                                    foreach ($data as $item) {
                                        if (!empty($item->textContent)) $text[] = $item->textContent;
                                    }
                                    if (!empty($text)) {
                                        $text = implode(' ', $text);
                                        $hotel->setSummary($text);
                                        echo '<b>Summary:</b> ' . $text . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }


                                    // district properties
                                    /*
                                    $data = trim($qp->find('#summary')->find('.hp_district_endorsements')->eq(0)->text());
                                    if (!empty($data)) {
                                        //$hotel->setAddress($data);
                                        echo '<b>District char:</b> ' . str_replace("\n", '===', $data) . '<br />' . PHP_EOL;
                                    }
                                    */

                                    // lat
                                    $data = trim($qp->find('head')->find('meta[property="booking_com:location:latitude"]')->attr('content'));
                                    if (!empty($data)) {
                                        $hotel->setLat($data);
                                        echo '<b>Lat:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // lng
                                    $data = trim($qp->find('head')->find('meta[property="booking_com:location:longitude"]')->attr('content'));
                                    if (!empty($data)) {
                                        $hotel->setLng($data);
                                        echo '<b>Lng:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // rating
                                    $data = trim($qp->find('h1.item')->find('i.stars')->attr('class'));
                                    if (!empty($data)) {
                                        if(preg_match('/ratings_stars_(\d)/i', $data, $matches)){
                                            if($rating = end($matches)) $found = true;
                                            $hotel->setRating($rating);
                                            echo '<b>Rating:</b> ' . $rating . '<br />' . PHP_EOL;
                                            echo $hotel->save() ? 'OK' : 'Failed';
                                            echo '<br />' . PHP_EOL;
                                        }
                                    }

                                    // images
                                    $i = 0;
                                    $gallery = array();
                                    while(($data = $qp->find('.hp-gallery')->find('img')->eq($i)->attr('src')) || ($data1 = $qp->find('.hp-gallery')->find('img')->eq($i)->attr('data-lazy'))){
                                        $src =  $gallery[] = !empty($data) ? $data : $data1;
                                        echo '<a href="'.$src.'" target="_blank">'.$src.'</a><br />';
                                        $i++;
                                    }
                                    $gallery = implode(', ', $gallery);
                                    $hotel->setGallery($gallery);
                                    echo $hotel->save() ? 'Gallery OK ('.$i.' photos)' : 'Failed';
                                    echo '<br />' . PHP_EOL;

                                    // Rooms in total
                                    $data = trim($qp->find('p.summary')->eq(0)->text());
                                    if (!empty($data)) {
                                        if(preg_match('/Номеров в отеле: (\d+)/i', $data, $matches)){
                                            $rooms = end($matches);
                                            $hotel->setRooms($rooms);
                                            echo '<b>Rooms:</b> ' . $rooms . '<br />' . PHP_EOL;
                                            echo $hotel->save() ? 'OK' : 'Failed';
                                            echo '<br />' . PHP_EOL;
                                        }
                                    }

                                    // object type
                                    $data = trim($qp->find('body')->find('script')->eq(0)->text());
                                    if (!empty($data)) {
                                        if(preg_match("/atnm: '(\S+)'/i", $data, $matches)){
                                            $type = end($matches);
                                            $hotel->setType($type);
                                            echo '<b>Type:</b> ' . $type . '<br />' . PHP_EOL;
                                            echo $hotel->save() ? 'OK' : 'Failed';
                                            echo '<br />' . PHP_EOL;
                                        }
                                    }

                                    // payment types
                                    $i = 0;
                                    $types = array();
                                    while($data = $qp->find('.hp_bp_payment_method')->eq(0)->find('span.creditcard')->eq($i)->attr('class')){
                                        $data = trim(str_replace('creditcard ', '', $data));
                                        echo $data.'<br />';
                                        $types[] = $data;
                                        $i++;
                                    }
                                    $types = implode(', ', $types);
                                    $hotel->setPaymentTypes($types);
                                    echo $hotel->save() ? 'OK' : 'Failed';
                                    echo '<br />' . PHP_EOL;

                                    // room types
                                    $i = 0;
                                    $types = array();
                                    while($data = $qp->find('.roomstable')->eq(0)->find('td.ftd')->eq($i)->text()){
                                        $data = trim($data);
                                        echo $data.'<br />';
                                        $types[] = $data;
                                        $i++;
                                    }
                                    $types = implode('# ', $types);
                                    $hotel->setRoomTypes($types);
                                    echo $hotel->save() ? 'OK' : 'Failed';
                                    echo '<br />' . PHP_EOL;

                                    // Check in time

                                    $data = trim($qp->find('#checkin_policy p')->last()->text());
                                    if (!empty($data)) {
                                        $hotel->setCheckIn($data);
                                        echo '<b>Check in time:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }
                                    // Check out time
                                    $data = trim($qp->find('#checkout_policy p')->last()->text());
                                    if (!empty($data)) {
                                        $hotel->setCheckOut($data);
                                        echo '<b>Check out time:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // Languages speaking
                                    $data = trim($qp->find('div.facility_icon_id_languages p')->eq(0)->text());
                                    if (!empty($data)) {
                                        $data = str_replace("\n", ' ', $data);
                                        $data = str_replace("\r", '', $data);
                                        $hotel->setLanguages($data);
                                        echo '<b>Languages:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // Services
                                    $data = trim($qp->find('div.facility_icon_id_3 p.firstpar')->eq(0)->text());
                                    if (!empty($data)) {
                                        $data = str_replace("\n", ' ', $data);
                                        $data = str_replace("\r", '', $data);
                                        $hotel->setServices($data);
                                        echo '<b>Services:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // Extra services
                                    $data = trim($qp->find('div.facility_icon_id_1 p.firstpar')->eq(0)->text());
                                    if (!empty($data)) {
                                        $data = str_replace("\n", ' ', $data);
                                        $data = str_replace("\r", '', $data);
                                        $hotel->setExtraServices($data);
                                        echo '<b>Extra services:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // Children policy
                                    $data = trim($qp->find('#children_policy')->eq(0)->text());
                                    if (!empty($data)) {
                                        $data = str_replace("\n", ' ', $data);
                                        $data = str_replace("\r", '', $data);
                                        $data = str_replace("Размещение детей и предоставление дополнительных кроватей", '', $data);
                                        $data = str_replace("Бесплатно! ", '', $data);
                                        $data = trim($data);
                                        $hotel->setChildrenPolicy($data);
                                        echo '<b>Children policy:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // Food
                                    $data = trim($qp->find('div.facility_icon_id_7 p.firstpar')->eq(0)->text());
                                    if (!empty($data)) {
                                        $data = str_replace("\n", ' ', $data);
                                        $data = str_replace("\r", '', $data);
                                        $hotel->setFood($data);
                                        echo '<b>Food:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // Parking
                                    $data = trim($qp->find('div#parking_policy p')->last()->text());
                                    if (!empty($data)) {
                                        $data = str_replace("\n", ' ', $data);
                                        $data = str_replace("\r", '', $data);
                                        $data = str_replace("Бесплатно! ", '', $data);
                                        $hotel->setParking($data);
                                        echo '<b>Parking:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // Wellness
                                    $data = trim($qp->find('div.facility_icon_id_2 p.firstpar')->eq(0)->text());
                                    if (!empty($data)) {
                                        $data = str_replace("\n", ' ', $data);
                                        $data = str_replace("\r", '', $data);
                                        $hotel->setWellness($data);
                                        echo '<b>Wellness facilities:</b> ' . $data . '<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }

                                    // Internet
                                    $data = trim($qp->find('div#internet_policy')->eq(0)->text());
                                    if (!empty($data)) {
                                        $data = str_replace("\n", ' ', $data);
                                        $data = str_replace("\r", '', $data);
                                        $data = str_replace("Интернет", '', $data);
                                        $data = str_replace("Бесплатно! ", '', $data);
                                        $data = str_replace("Не тратьте деньги на роуминг в путешествиях!", '', $data);
                                        $data = trim($data);
                                        if(
                                            preg_match('/предоставляется на территории всего отеля бесплатно/i', $data)
                                            || preg_match('/предоставляется в номерах отеля бесплатно/i', $data)
                                            || preg_match('/предоставляется в общественных зонах бесплатно/i', $data)

                                        ){
                                            $hotel->setFreeInternet(1);
                                        }else{
                                            $hotel->setFreeInternet(0);
                                        }

                                        $hotel->setInternet($data);

                                        echo '<b>Internet:</b> ' . ($hotel->getFreeInternet() ? 'Free' : 'Paid') . ', '.$data.'<br />' . PHP_EOL;
                                        echo $hotel->save() ? 'OK' : 'Failed';
                                        echo '<br />' . PHP_EOL;
                                    }
                                }

                                $hotel->setStatus(203);
                                echo $hotel->save() ? 'Status 203 OK' : 'Status change failed';
                                echo '<br />' . PHP_EOL;
                            }else {
                                echo 'No data<br />' . PHP_EOL;
                            }

                            if(rename($fname, $newFname)) echo 'Source file renamed'; else echo 'Source file rename failed';
                            echo '<br />'.PHP_EOL;

                            break;
                        }
                    }
                }
            }

        }catch (PException $e){
            echo $e->getMessage();
        }

        $this->view->disable();
    }

    public function downloadHotelGalleryAction()
    {
        try {
            $hotels = new Hotels();
            $query = $hotels->query()
                ->where('country_id = '.$this->countryId)
                ->andWhere('download_started IS NULL')
                ->andWhere("gallery !=''")
                ->andWhere("gallery is not null")
                ->limit(1);

            if($result = $query->execute()){
                foreach($result as $hotel){

                    echo $hotel->getHotelId().': '.$hotel->getName().' <br />';

                    if($gallery = $hotel->getGallery()){

                        $hotel->setDownloadStarted(1);

                        $hotel->update();

                        $gallery = explode(', ', $gallery);
                        foreach($gallery as $index=>$item){
                            echo '<a href="'.$item.'" target="_blank">'.$item.'</a>';
                            $fPath = $_SERVER['DOCUMENT_ROOT'].'/rawHotels/items/downloaded/'.$hotel->getHotelId().'_'.$index.'.jpg';
                            if(copy($item, $fPath)) echo ' <span style="color:green;">ok</span>'; else echo ' <span style="color:red;">failed</span>';
                            echo '<br />';
                        }

                        $hotel->setGalleryDownloaded(1);

                        $hotel->setStatus(204);

                        $hotel->update();
                    }

                }
            }

        }catch (PException $e){
            echo $e->getLine().'<br />' . PHP_EOL;
            echo $e->getMessage();
        }

        $this->view->disable();
    }

    public function correctHotelOriginUrlAction()
    {
        try {
            $hotels = new Hotels();
            $query = $hotels->query();

            if($result = $query->execute()){
                foreach($result as $hotel){
                    $url = $hotel->getUrlOrig();
                    $url = explode('?', $url);
                    $url = reset($url);

                    $hotel->setUrlOrig($url);

                    $hotel->update();

                    echo $hotel->getHotelId().': '.$hotel->getName().' '.$url.'<br />';
                }
            }

        }catch (PException $e){
            echo $e->getLine().'<br />' . PHP_EOL;
            echo $e->getMessage();
        }

        $this->view->disable();
    }

    public function correctHotelAddressAction()
    {
        try {
            $hotels = new Hotels();
            $query = $hotels->query();
            //$query->limit(1);

            if($result = $query->execute()){
                foreach($result as $hotel){
                    $address = $hotel->getAddressOrig();
                    $address = preg_replace('/,$/i', '', $address);
                    $hotel->setAddressOrig($address);

                    $hotel->update();

                    echo $hotel->getHotelId().': '.$hotel->getName().'; '.$address.'<br />';
                }
            }

        }catch (PException $e){
            echo $e->getLine().'<br />' . PHP_EOL;
            echo $e->getMessage();
        }

        $this->view->disable();
    }

    public function correctHotelRegionAction()
    {
        try {
            $hotels = new Hotels();
            $query = $hotels->query();
            $query->where('country_id = 20')->andWhere('region_id IS NULL');
            //$query->limit(1);

            if($result = $query->execute()){
                $i = $k = 0;
                foreach($result as $hotel){

                    $hotel->setRegionId($hotel->cities->getRegionId());

                    if($hotel->update()) $k++;

                    //echo $hotel->getHotelId().': '.$hotel->getName().', '.$hotel->cities->getTitleRu().'<br />';

                    $i++;
                }
                echo $i.' total, '.$k.' updated';
            }

        }catch (PException $e){
            echo $e->getLine().'<br />' . PHP_EOL;
            echo $e->getMessage();
        }

        $this->view->disable();
    }

    public function correctWrongCitiesAction()
    {
        try {
            $cities = new Cities();
            $query = $cities->query();
            $query->where("json LIKE '%\"city\":[]%'")->andWhere('hotels > 0');
            $query->limit(5);

            if($result = $query->execute()){
                foreach($result as $city){
                    echo $city->getCityId().': '.$city->getTitleRu();

                    $hotels = new Hotels();

                    $mysql = 'DELETE FROM hotels WHERE city_id='.$city->getCityId();

                    $hotels->getWriteConnection()->execute($mysql);

                    $affected = $hotels->getReadConnection()->affectedRows();

                    echo ' '.($affected ? $affected : 0).' affected rows';

                    $city->setHotels(0);
                    $city->setShift(0);

                    if($city->update()) echo ' city updated';

                    echo '<br />';
                }
            }

        }catch (PException $e){
            echo $e->getLine().'<br />' . PHP_EOL;
            echo $e->getMessage();
        }

        $this->view->disable();
    }

}
