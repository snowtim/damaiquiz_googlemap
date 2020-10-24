此版本是post版的
且地點查詢後可在地圖上標出地址及位置
API_KEY沒附在上面,因此直接看會沒地圖

第3題部分
相關檔案:

route => /routes/api.php 
controller => /app/Http/Controllers/DataimportController.php

這個部分是將所有address.zip的資料匯入資料庫的程式,主要寫在controller裡

migration路徑 => /database/migrations底下, 有三張
2020_10_19_155952_create_cities_table => city的table
2020_10_19_160031_create_areas_table => area的table
2020_10_19_160938_create_road_and_lanes_table => road的table

第4題部分
相關檔案：

route => /routes/web.php
view => /resouces/views/googlemap.blade.php    (為測試簡單做的)
controller => /app/Http/Controllers/AddressController.php
model => /app/Models/City.php
	     /app/Models/Area.php
	     /app/Models/RoadAndLane.php
Library => /app/Googlemap/googlemap.php

Library 裡頭有3支function
1.
AddressProcess($request) => 將從Form進來的 request先整理出要的部分再return整理後會用到的結果以array回傳, 其中開頭的validator會先就用戶端key的資料做驗證, 不符型態則回傳error的訊息回去

GoogleGeocodeApiProcess($addressintoapi) => 將地址送入後由Google Geocode API處理後得到地址的詳細資訊, 再把所需的經緯度之類資料整理後以array回傳

這2支配合送回AddressController.php,將前端所需資料整理後以json格式回傳給前端
這麼做似乎有點怪,因為沒直接回傳json的結果回去,而是把2支的片段資訊都先以array回傳後才在controller做最後整理以json回傳,而且Library似乎應該是不論如何就是所要的那個結果
會用這樣分開主要是覺得會與資料庫有關的部分應該只留在controller才這樣分,把整理從Form來的資料及運用Google Geocode API兩件事分開

2.
getaddress($request) => 其實就是所有部分全部放在1支function裡面,它的結果和另外2支配合用在AddressController.php的結果是一樣的,而且直接回傳整理好的json格式資訊給前端,不過function很大一包加上把會和資料庫有關的部分包含進來自覺也不是很理想,不過跑完出來就是所要output給前端的東西

另外validator用戶端未輸入的部分目前允許有null值

為方便看結果http method都先使用GET

謝謝　

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# damaiquiz_googlemap
