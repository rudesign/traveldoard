<?php

use Phalcon\Http\Client\Request;

class SabreController extends BaseController
{
    protected $data = array();

    public function initialize(){
        parent::initialize();

        $this->getToken();
    }

    public function ShowAction($type= '')
    {
        $data = array();

        switch($type){
            case 'flights':
                $this->getFights();
                $this->getMACs();
                $this->showFlights();
            break;
        }

        echo json_encode($this->data);

        $this->view->disable();
    }

    public function SourceAction($type= '')
    {
        $data = array();

        switch($type){
            case 'flights':
                $this->getFights();
                $this->data['html'] = '';
                $this->showTree($this->data['flights']);
            break;
        }

        echo json_encode($this->data);

        $this->view->disable();
    }

    protected function getFights()
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

        $this->data['flights'] = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/flight'), true);

        return true;
    }

    protected function showFlights(){
        if(!empty($this->data['flights'])){
            $this->data['html'] = '';
            foreach($this->data['flights']['PricedItineraries'] as $itinerary){
                $airItinerary = $itinerary['AirItinerary'];
                $options = $airItinerary['OriginDestinationOptions']['OriginDestinationOption'];
                $this->data['html'] .= '<div>';
                foreach($options as $option){
                    $segments = $option['FlightSegment'];
                    $this->data['html'] .= '<div class="leg">';
                    foreach($segments as $segment){
                        $departureAirportMAC = $segment['DepartureAirport']['LocationCode'];
                        $arrivalAirportMAC = $segment['ArrivalAirport']['LocationCode'];
                        $this->data['html'] .= '<p>Departure: <b>'.$departureAirportMAC.'</b>';
                        $this->data['html'] .= ' &rarr; Arrival: <b>'.$arrivalAirportMAC.'</b></p>';
                    }
                    $this->data['html'] .= '</div>';
                }
                $airItineraryPricingInfo = $itinerary['AirItineraryPricingInfo'];
                $itinTotalFare = $airItineraryPricingInfo['ItinTotalFare']['TotalFare'];
                $this->data['html'] .= 'Fare: <b>'.$itinTotalFare['Amount'].'</b>';

                $this->data['html'] .= '</div>';
            }
        }
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

    protected function getMACs($mac = '')
    {
        $provider  = Request::getProvider();

        $provider->setBaseUri('https://api.test.sabre.com');
        $provider->header->set('Accept', 'application/json');
        $provider->header->set('Authorization', 'Bearer Shared/IDL:IceSess\/SessMgr:1\.0.IDL/Common/!ICESMS\/ACPCRTD!ICESMSLB\/CRT.LB!-3537010813656309855!1301187!0!!E2E-1');

        $response = $provider->get('/v1/lists/supported/cities/'.$mac.'/airports/');

        $macs = json_decode($response->body, true);

        var_dump($macs);
        die;

        return $macs;
    }

    protected function getToken()
    {
        $clientId = 'V1:dgbpi3925kynqko3:DEVCENTER:EXT';
        $clientSecret = 'H2VH9van';

        $encoded = base64_encode(base64_encode($clientId).':'.base64_encode($clientSecret));

        $provider  = Request::getProvider();

        $provider->setBaseUri('https://api.test.sabre.com');

        $provider->header->set('Authorization', 'Basic '.$encoded);
        $provider->header->set('Content-Type', 'application/x-www-form-urlencoded');

        $response = $provider->post('/v1/auth/token', array(
            'grant_type'=>'client_credentials'
        ));

        $response = json_decode($response->body, true);

        var_dump($response);
        die;
    }
}
