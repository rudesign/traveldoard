<?php

use Phalcon\Http\Client\Request;

class SabreController extends BaseController
{
    protected $data = array();

    public function initialize(){
        parent::initialize();
    }

    public function ShowAction($type= '')
    {
        $data = array();

        switch($type){
            case 'flights':
                $this->GetFights();
            break;
        }

        echo json_encode($this->data);

        $this->view->disable();
    }

    protected function GetFights()
    {
        $data = array();

        /*
        $provider  = Request::getProvider();

        $provider->setBaseUri('https://api.test.sabre.com');

        $provider->header->set('Accept', 'application/json');
        $provider->header->set('Authorization', 'Bearer Shared/IDL:IceSess\/SessMgr:1\.0.IDL/Common/!ICESMS\/ACPCRTD!ICESMSLB\/CRT.LB!-3537010813656309855!1301187!0!!E2E-1');

        $response = $provider->get('/v1/shop/flights', array(
            'origin'=>'NYC',
            'destination'=>'LAX',
            'departuredate'=>'2015-05-09',
            'returndate'=>'2015-05-14',
            'passengercount'=>1,
            'limit'=>1,
            'pointofsalecountry'=>'US',
        ));


        if($res = fopen($_SERVER['DOCUMENT_ROOT'].'/flight', 'w')){
            fwrite($res, $response->body);
            fclose($res);
        }
        */

        $response = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/flight'), true);

        $this->data['html'] = '';
        $this->showTree($response);

        /*
        foreach($response->PricedItineraries as $itinerary){

            //$set['fare'] = $itinerary->AirItineraryPricingInfo->ItinTotalFare->TotalFare->Amount;

            var_dump(get_object_vars($itinerary->AirItinerary->OriginDestinationOptions));//->OriginDestinationOption);
            die;

            $set['ElapsedTime'] = $itinerary->AirItinerary;

            //$data['itineraries'][] = $set;
        }
        */

        return true;
    }

    protected function showTree($tree = array()){
        if(!empty($tree) && is_array($tree)){
            $this->data['html'] .= '<ul>';
            foreach($tree as $key=>$branch){
                $this->data['html'] .= '<li>';
                $this->data['html'] .= $key;
                if(!is_array($branch)) {
                    $this->data['html'] .= ': <span class="bold">'.$branch.'</span>';
                }else{
                    $this->showTree($branch);
                }
                $this->data['html'] .= '</li>';
            }
            $this->data['html'] .= '</ul>';
        }
    }
}
