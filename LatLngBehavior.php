<?php

namespace jberall\getlatlng;

use Yii;
use yii\db\ActiveRecord;
use yii\base\InvalidParamException;
use jberall\getlatlng\GetLatLng;

/**
 * Description of LatLngBehavior
 *  Makes a call google maps using the function jberall\getlatlng\GetLatLng<br>
 *  returns a latitude,longitude point.
 *
 * @author Jonathan Berall <jberall@gmail.com>
 */
class LatLngBehavior extends \yii\base\Behavior {
    /**
     *
     * @var string column in the database for the lattitude,longitude point.
     */
    public $latlng = 'latlng';
    
    /**
     *
     * @var string 
     */
    public $google_api_key;

    /**
     * On ActiveRecord Event
     * @return 
     */
   

    public function events()
    {        
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'assignLatLng',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'assignLatLng',
                       
            ];
    }

    public function assignLatLng() {
        if (!$this->owner->hasProperty($this->latlng)) {
            throw new InvalidParamException(get_class($this->owner) . ' has no attribute property named "' . $this->latlng . '".');
        }

        $latlng = new GetLatLng(['google_api_key'=>$this->google_api_key]);
        $arrLatLng = $latlng->getLatLngGoogle($this->owner->toArray());
        $this->owner{$this->latlng} = $arrLatLng['latlng'] ?? null;
    }
        
}
