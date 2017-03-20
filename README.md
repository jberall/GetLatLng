Get Latitude Longitude
======================
Takes an address array and return an array of latitude, longitude and latlng

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

You also need to install 
composer require wenbin1989/yii2-curl:dev-master
or add 
"wenbin1989/yii2-curl": "dev-master"

Either run

```
composer require jberall/getlatlng:dev-master 
```

or add

```
"jberall/getlatlng": "dev-master"

```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?php 
        $arrAdd = [
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

//you will need to initilize the class so we can reference the google key.
use jberall\getlatlng\GetLatLng;

    $latlng = new GetLatLng();
    $arrLatLng = $latlng->getLatLngGoogle($this->toArray());
    $this->latlng = $arrLatLng['latlng'] ?? null;
?>