Get Latitude Longitude
======================
Takes an address array and return an array of latitude, longitude and latlng

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

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
    $arrLatLng = \jberall\getlatlng\GetLatLng::getLatLngGoogle($arrAddress); 
    returns null or ['latitude'=>$latitude,'longitude'=>$longitude,'latlng'=>$latlng]
?>```