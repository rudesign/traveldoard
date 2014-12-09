<?php

use Phalcon\Http\Client\Request;

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

    public function jAction(){
        echo 1;
    }

    public function curlAction()
    {
        $baseUrl = 'http://booking.com';

        $provider  = Request::getProvider(); // get available provider Curl or Stream

        $provider->setBaseUri($baseUrl);

        $provider->header->set('Accept', 'application/json');

//        $provider->header->set('Accept', 'text/html');
//        $provider->header->set('Accept', 'application/xhtml+xml');
//        $provider->header->set('Accept', 'application/xml');

        $params = array(
            'lang'=>'ru',
            'pid'=>'6b1c422f5a320072',
            'sid'=>'02da8e0f4b98680e70edc35ac6b45492',
            'aid'=>304142,
            'stype'=>1,
            'force_ufi'=>'',
            'cities_first'=>1,
            'should_split'=>1,
            'sugv'=>'br',
            'e_acb1'=>1,
            'e_acb2'=>1,
            'eb'=>0,
            'add_themes'=>1,
            'themes_match_start'=>0,
            'include_synonyms'=>1,
            'e_nr_labels'=>1,
            'e_obj_labels'=>1,
            'exclude_some_hotels'=>1,
            'include_dest_count'=>0,
            'max_results'=>'10',
            'include_extra_synonyms'=>0,
            'term'=>'Rome',
        );

        $provider->setOption(CURLOPT_SSL_VERIFYPEER, false);

        $response = $provider->get('/', $params);

        echo $response->header->status;

        print_r($response);
        //echo $response->body;

//        $uri = $provider->resolveUri($baseUrl.'/autocomplete');
//        $uri->extendQuery($params);
//        echo $uri->build();

    }


    // curl -i
    // -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36"
    // -H "Content-Type: text/plain; charset=utf-8"
    // -H "Accept: */*"
    // -H "Accept-Encoding: gzip, deflate, sdch"
    // -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6"
    // -H "Cookie: utag_main=v_id:0149e65771d7001ecb26216f6ca605066003705e00bd0$_sn:1$_ss:1$_pn:1%3Bexp-session$_st:1416910530839$ses_id:1416908730839%3Bexp-session; zz_cook_segment=1; _ga=GA1.2.272112262.1416908731; __utma=1.272112262.1416908731.1416908731.1416908731.1; __utmc=1; __utmz=1.1416908731.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); bkng=11UmFuZG9tSVYkc2RlIyh9YWJdm48m5cJDWuLLIYaigN7j6j48cUdQU8ks0%2Fk62aC%2BxdDf7fqoRnTtnrQ7rtxNgi9Nffvg1Sj17HjkVCAzuJbqR8QXty4azG%2BIzhFHqNRBFT1J1B8B%2FYIgcqxushhAUAh2A4WabiSsfZRFPR2tf2qTMKNI0dulkCB53sH2MK8L149VfRyORNE%3D"
    // -X GET 'http://booking.com/autocomplete?lang=ru&pid=6b1c422f5a320072&sid=02da8e0f4b98680e70edc35ac6b45492&aid=304142&stype=1&force_ufi=&cities_first=1&should_split=1&sugv=br&e_acb1=1&e_acb2=1&eb=0&add_themes=1&themes_match_start=0&include_synonyms=1&e_nr_labels=1&e_obj_labels=1&exclude_some_hotels=1&include_dest_count=1&max_results=10&include_extra_synonyms=0&term=Italy'

    public function curl1Action(){
        $url = 'http://booking.com/autocomplete?_=1417439491177&lang=ru&pid=54545cb3cc8300fa&sid=02da8e0f4b98680e70edc35ac6b45492&aid=304142&stype=1&force_ufi=&cities_first=1&should_split=1&sugv=br&e_acb1=1&eb=1&add_themes=1&sort_nr_destinations=1&themes_match_start=1&include_synonyms=1&e_nr_labels=1&e_obj_labels=1&exclude_some_hotels=&term=moscow';

        $headers = array(
            "User-Agent"=>"Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36",
            "Content-Type"=>"text/plain; charset=utf-8",
            "Accept"=>"*/*",
            "Accept-Encoding"=>"gzip, deflate, sdch",
            "Accept-Language"=>"en-US,en;q=0.8,ru;q=0.6",
            "Cookie"=>'utag_main=v_id:0149e65771d7001ecb26216f6ca605066003705e00bd0$_sn:1$_ss:1$_pn:1%3Bexp-session$_st:1416910530839$ses_id:1416908730839%3Bexp-session; zz_cook_segment=1; _ga=GA1.2.272112262.1416908731; __utma=1.272112262.1416908731.1416908731.1416908731.1; __utmc=1; __utmz=1.1416908731.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); bkng=11UmFuZG9tSVYkc2RlIyh9YWJdm48m5cJDWuLLIYaigN7j6j48cUdQU8ks0%2Fk62aC%2BxdDf7fqoRnTtnrQ7rtxNgi9Nffvg1Sj17HjkVCAzuJbqR8QXty4azG%2BIzhFHqNRBFT1J1B8B%2FYIgcqxushhAUAh2A4WabiSsfZRFPR2tf2qTMKNI0dulkCB53sH2MK8L149VfRyORNE%3D',
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        var_dump($result);
    }

    public function curl2Action(){

        $location = $this->request->get('location');

        echo '<h1>'.$location.'</h1>';

$bashCommand = <<<EOD
curl -H "User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36" -H "Content-Type: text/plain; charset=utf-8" -H "Accept: application/json" -H "Accept-Encoding: gzip, deflate, sdch" -H "Accept-Language: en-US,en;q=0.8,ru;q=0.6" -X GET 'http://booking.com/autocomplete?lang=ru&pid=6b1c422f5a320072&sid=02da8e0f4b98680e70edc35ac6b45492&aid=304142&stype=1&force_ufi=&cities_first=1&should_split=1&sugv=br&e_acb1=1&e_acb2=1&eb=0&add_themes=1&themes_match_start=0&include_synonyms=1&e_nr_labels=1&e_obj_labels=1&exclude_some_hotels=1&include_dest_count=1&max_results=10&include_extra_synonyms=0&term=$location'
EOD;
        $stdOut = array();

        $output = shell_exec($bashCommand);
        $output = json_decode($output);
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
                $stdOut[] = '<a href="http://www.booking.com/searchresults.ru.html?sid=02da8e0f4b98680e70edc35ac6b45492;dcid=1;checkin=2014-12-12;checkout=2014-12-13;dest_id='.$set->dest_id.';dest_type=city;ilp=1" target="_blank">'.$set->nr_hotels_label.'</a>';
                $stdOut[] = '';


            }
            echo implode('<br />', $stdOut);
        }
    }
}
