<?php
namespace jberall\getlatlng;
 
use Yii;
use yii\base\Component;

use linslin\yii2\curl;
/**
 * need to check if curl works
 * need to add google key.
 */

class GetLatLng extends Component
{
    CONST CONNECTION_TIMEOUT = 2;
    CONST TIMEOUT = 2;
/**
 * Makes a curl call to 'http://maps.googleapis.com/maps/api/geocode/json'
 * with the parameter of the address
 * Pass an address array<br>
 * You should only pass 'province' or 'state' and 'postal_code' or 'zip'<br>
 * A zip or postal code and country is required or null will be returned.
 * If no lattitude, longitude is returned it checks by postal code/zip country.
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
 * @param integer $connectionTimeout CURLOPT_CONNECTTIMEOUT
 * @param integer $timeout CURLOPT_TIMEOUT
 * @return boolean | array  null | ['latitude'=>$latitude,'longitude'=>$longitude,'latlng'=>$latlng]
 */
    public function getLatLngGoogle($arrAddress,$connectionTimeout = self::CONNECTION_TIMEOUT,$timeout = self::TIMEOUT) {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json';
//        $url = 'http://www.thorall.com';
//        $url = 'http://192.168.1.499';
        
        if (!is_numeric($connectionTimeout)) $connectionTimeout = self::CONNECTION_TIMEOUT;
        if (!is_numeric($timeout)) $timeout = self::TIMEOUT;
        
        //initialize
        $curl_add = $curl_add2 = '';
        
        $postal_code = $arrAddress['postal_code'] ?? '';
        if(!$postal_code) $postal_code = $arrAddress['zip'] ?? '';
        $country = $arrAddress['country'] ?? '';
       
       if (!$postal_code || !$country) return false;
       
       //build the address to send to google
       if (isset($arrAddress['address'])) $curl_add .= $arrAddress['address'] .', ';
       if (isset($arrAddress['city'])) $curl_add .= $arrAddress['city'] .', ';
       if (isset($arrAddress['province'])) {
           $curl_add2 = $arrAddress['province'] .', ';
       } elseif (isset($arrAddress['state'])) {
           $curl_add2 = $arrAddress['state'] .', ';
       }
       $curl_add2 .= $country .', ';
       $curl_add2 .= $postal_code;

//       print_r($arrAddress);exit;

// GET request with GET params

        $curl = new curl\Curl();
        
        $result = $curl
                ->setGetParams(['address'=>$curl_add.$curl_add2])
                ->setOption(CURLOPT_CONNECTTIMEOUT, $connectionTimeout)
                ->setOption(CURLOPT_TIMEOUT,$timeout)
                ->get($url);
        
        if ($curl->errorCode !== null) {
             // List of curl error codes here https://curl.haxx.se/libcurl/c/libcurl-errors.html
//            switch ($curl->errorCode) {
//                case 6:
//                    //host unknown example
//                    break;
//                case 7:
//                case 28:
//                    //timeout
//                    break;
//            }
            //send email to notify.
//            echo '<br>url: '.$url.'<br>Err Code: '.$curl->errorCode . ' err msg '.$curl->errorText.'<br>send email to notify';
            return ;

        }         
        
//        print_R($result);

       $parse = \yii\helpers\Json::decode($result);
       
       if ($parse['status'] != 'OK') {
            
           //try Again only $curl_add2

            $result = $curl
                ->setGetParams(['address'=>$curl_add2])
                ->setOption(CURLOPT_CONNECTTIMEOUT, $connectionTimeout)
                ->setOption(CURLOPT_TIMEOUT,$timeout)
                ->get($url);
            if ($curl->errorCode !== null) {
                 // List of curl error codes here https://curl.haxx.se/libcurl/c/libcurl-errors.html
                return ;             
            } 
            
       }
       
       $parse = \yii\helpers\Json::decode($result);
//       print_R($result);
       if ($parse['status'] != 'OK') {
           return ;
       }
//       print_R($parse);exit;
//        echo 'status' $parse[]
       $latitude = $parse['results'][0]['geometry']['location']['lat'];
       $longitude = $parse['results'][0]['geometry']['location']['lng'];
//        print_r ($parse['results'][0]['geometry']['location']);exit;
       $latlng =  $latitude . ','.$longitude;
      
       return ['latitude'=>$latitude,'longitude'=>$longitude,'latlng'=>$latlng];
    }

}