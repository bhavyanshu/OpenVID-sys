##OpenVID-sys

OpenVID-sys is an open source web app built on top of [Laravel framework](http://laravel.com) that provides a platform security researchers to connect with the developers of the products and discuss security flaws. Any organization can host this on their server and by registering, the security researchers can directly connect with the developers of the product.

### Documentation for Vendors

Coming soon!

### Documentation for Developers

**Setup**

* Grab the latest copy of this web app using `git clone https://github.com/bhavyanshu/OpenVID-sys.git`
* Next run `composer install` in project root to get all framework related dependencies. If you have not installed composer, then install it first and then run this command.
* Generate a new application key by running `php artisan key:generate` in project root.
* Create a new database (via phpmyadmin or CLI). Put .env file in project root. In that .env file, add following details. Replace values specific to your db configuration and smtp configuration.

```
APP_ENV=local
APP_DEBUG=true
APP_KEY=YOUR-NEWLY-GENERATED-APP-KEY-HERE

DB_HOST=localhost
DB_DATABASE=DB_NAME
DB_USERNAME=DB_USERNAME
DB_PASSWORD=DB_PASSWORD

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_FROMADDRESS=no-reply@server.com
MAIL_FROMNAME=no-reply
MAIL_USERNAME=get-from-mailtrap.io
MAIL_PASSWORD=get-from-mailtrap.io
MAIL_ENCRYPTION=tls
```
* To test database and setup tables required by web app, run `php artisan migrate --seed`.
* Next run `npm install` for laravel-elixir and other packages. Then run `npm install --save-dev del`.
* Then run `bower update` to pull in frontend related dependencies.
* Finally run `gulp` to move the required frontend files to correct locations. If you don't have gulp, then install it first.
* That's all. Now you can execute `php artisan serve` in project root to run the web app on your localhost.
* Bulk mailing is done using database queue. Make sure you add `* * * * * /path/to/php /path/to/artisan schedule:run 1>> /dev/null 2>&1` to your system crontab.


> [Don't forget to refer to the documentation - Work in progress](https://bhavyanshu.me/pages/openvid_sys/)

### Licenses

The [Laravel](http://laravel.com) framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

OpenVID-sys project is open-sourced software licensed under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0). See LICENSE file.

> Copyright 2016 Bhavyanshu Parasher

> Licensed under the Apache License, Version 2.0 (the "License"); you
may not use this project except in compliance with the License. You
may obtain a copy of the License at
> http://www.apache.org/licenses/LICENSE-2.0.

>Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
implied. See the License for the specific language governing
permissions and limitations under the License.
