<?php
namespace common\components;
 
use Yii;
use yii\base\Component;


class GetLatLng extends Component
{
/**
 * Makes a curl call to 'http://maps.googleapis.com/maps/api/geocode/json'
 * with the parameter of the address
 * Pass an address array<br>
 * You should only pass 'province' or 'state' and 'postal_code' or 'zip'<br>
 * A zip or postal code is required or null will be returned.
 * 
        $arrAddress = [
            'address' => '3555 Farnam Street',
            'city' => 'Omaha',
            'province' => 'NB',
            //or 
//            'state' => 'NB',

            'country' => 'CA',
            'postal_code' => '68131',
//          or 
//          'zip' => '68131',
        ]; 
 * 
 * @param array $arrAddress 
 * @return boolean | array  null | ['latitude'=>$latitude,'longitude'=>$longitude,'latlng'=>$latlng]
 */
    public function getLatLngGoogle($arrAddress) {
        $curl_add = '';
        if (isset($arrAddress['postal_code'])) $postal_code = $arrAddress['postal_code'];
       if (isset($arrAddress['zip'])) $zip = $arrAddress['zip'];
       
       if (!$postal_code && !$zip) return false;
       
       //build the address to send to google
       if (isset($arrAddress['address'])) $curl_add .= $arrAddress['address'] .', ';
       if (isset($arrAddress['city'])) $curl_add .= $arrAddress['city'] .', ';
       if (isset($arrAddress['province'])) $curl_add .= $arrAddress['province'] .', ';
       if (isset($arrAddress['state'])) $curl_add .= $arrAddress['state'] .', ';
       if (isset($arrAddress['country'])) $curl_add .= $arrAddress['country'] .', ';
       if ($postal_code) $curl_add .= $postal_code .', ';
       if ($zip) $curl_add .= $zip .', ';
       
//       echo $curl_add;
       
//       print_r($arrAddress);exit;

        
       $curl = new \wenbin1989\yii2\curl\Curl();
//       $curl->connectionTimeout = 2;
       try {
            $result = $curl->get('http://maps.googleapis.com/maps/api/geocode/json',['address'=>$curl_add]);
       } catch (ErrorException $e) {
           return ;
       }
       $parse = \yii\helpers\Json::decode($result);
       
       if ($parse['status'] != 'OK') return ;

//       print_R($parse);exit;
//        echo 'status' $parse[]
       $latitude = $parse['results'][0]['geometry']['location']['lat'];
       $longitude = $parse['results'][0]['geometry']['location']['lng'];
//        print_r ($parse['results'][0]['geometry']['location']);exit;
       $latlng =  $latitude . ','.$longitude;

       return ['latitude'=>$latitude,'longitude'=>$longitude,'latlng'=>$latlng];
    }

}