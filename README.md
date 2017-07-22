SypexGeo extension for Yii2 
============================
Yii2 extension for Sypex Geo API

Sypex Geo - product for location by IP address.
Obtaining the IP address, Sypex Geo outputs information about the location of the visitor - country, region, city,
geographical coordinates and other in Russian and in English.
Sypex Geo use local compact binary database file and works very quickly.
For more information visit: http://sypexgeo.net/

This Yii2 extension allow use Sypex Geo API in Yii2 application.

Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?php
    $geo = new \jisoft\sypexgeo\Sypexgeo();

    // get by remote IP
    $geo->get();                // also returned geo data as array
    echo $geo->ip,'<br>';
    echo $geo->ipAsLong,'<br>';
    var_dump($geo->country); echo '<br>';
    var_dump($geo->region);  echo '<br>';
    var_dump($geo->city);    echo '<br>';

    // get by custom IP
    $geo->get('212.42.76.252');
?>
```
Information about country, region and city returned as array.
For example:
```html

Country
 array (
    'id' => 222,
    'iso' => 'UA',
    'continent' => 'EU',
    'lat' => 49,
    'lon' => 32,
    'name_ru' => 'Украина',
    'name_en' => 'Ukraine',
    'timezone' => 'Europe/Kiev',
  ),

Region
 array (
    'id' => 709716,
    'lat' => 48,
    'lon' => 37.5,
    'name_ru' => 'Донецкая область',
    'name_en' => 'Donets\'ka Oblast\'',
    'iso' => 'UA-14',
    'timezone' => 'Europe/Zaporozhye',
    'okato' => '14',
  ),

City
 array (
    'id' => 709717,
    'lat' => 48.023000000000003,
    'lon' => 37.802239999999998,
    'name_ru' => 'Донецк',
    'name_en' => 'Donets\'k',
    'okato' => '14101',
  ),

```

Update SypexGeo
-----

For updates SypexGeo add the given code in cron

```php
<?php
    $geo = new \jisoft\sypexgeo\SxUpdate();
    
    // Custom dir dat files
    // by default Yii::getAlias('@runtime'); or __DIR__ if not Yii Framework
    $geo->updateDir = '/tmp';
    
    // Custom url update
    $geo->cityUrl = 'http://example.com/file.zip';
    $geo->countryUrl = 'http://example.com/file.zip';
    $geo->maxUrl = 'http://example.com/file.zip'; // Be sure to specify the url


    // Update Sypex Geo City file
    $geo->updateCity();
    
    // Update Sypex Geo Country file
    $geo->updateCountry();
    
    // Update Sypex Geo Max file
    // Be sure to specify the url $geo->maxUrl
    $geo->updateMax();
    
    // Update Sypex Geo all file
    // Be sure to specify the url $geo->maxUrl
    $geo->updateAll();
?>
```

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require den67rus/yii2-sypexgeo "~1.0.0"
```

or add

```
"den67rus/yii2-sypexgeo": "*"
```

to the require section of your `composer.json` file.

