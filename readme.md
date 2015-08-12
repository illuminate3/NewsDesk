# NewsDesk (CMS) : Laravel 5.1.x Beta Development


## Status / Version

Beta Development


## Description

This module provides a basenews management system.


## Functionality


### Contents
Mulit-lingual News Management


### Print Statuses
Simple print designations such as draft, edit, in print and such.


## Routes

* /admin/news
* /admin/print_statuses
* /{news}


## Install


### publish commands

General Publish "ALL" method
```
php artisan vendor:publish --provider="App\Modules\Kagi\Providers\NewsDeskServiceProvider"
```

Specific Publish tags
```
php artisan vendor:publish --provider="App\Modules\NewsDesk\Providers\NewsDeskServiceProvider" --tag="configs"
php artisan vendor:publish --provider="App\Modules\NewsDesk\Providers\NewsDeskServiceProvider" --tag="images"
php artisan vendor:publish --provider="App\Modules\NewsDesk\Providers\NewsDeskServiceProvider" --tag="vendors"
php artisan vendor:publish --provider="App\Modules\NewsDesk\Providers\NewsDeskServiceProvider" --tag="views"
```


## Packages

Intended to be used with:

* https://github.com/illuminate3/rakkoII
* https://github.com/illuminate3/Kagi

The Following are packages that are specific to this module:

* https://github.com/etrepat/baum
* https://github.com/cviebrock/eloquent-sluggable


## Screen Shots
## Thanks
