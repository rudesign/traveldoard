<?php

use Phalcon\Http\Client\Request;

class ParserController extends BaseController
{
    public function initialize(){
        parent::initialize();
    }

    public function idCountriesAction(){
        $countries = new Countries();

        $rows = $countries->query()->orderBy('title_en ASC')->execute();

        foreach ($rows as $row) {
            echo $row->getTitleEn().'<br />';

            $row->setBcomId(1);

            $row->update();
        }
    }

    public function curlAction(){
        $baseUrl = 'http://booking.com';

        $provider  = Request::getProvider(); // get available provider Curl or Stream

        $provider->setBaseUri($baseUrl);

        $provider->header->set('Accept', 'application/json');

        $provider->setOption(CURLOPT_COOKIESESSION, true);

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

        $response = $provider->get('autocomplete', $params);

        echo $response->header->status;

        //print_r($response);
        echo $response->body;

//        $uri = $provider->resolveUri($baseUrl.'/autocomplete');
//        $uri->extendQuery($params);
//        echo $uri->build();

    }
}
