# Simple orders management app

This is a simple orders management app build on top of [Phalcon PHP][1] framework. It is a web framework delivered as a C extension providing high
performance and lower resource consumption.

This sample application provides you with a basic set of CRUD operations allowing you to:
* Add, edit and delete orders
* See a list of all orders
* Filter orders from today, last 7 days and all time
* Filter orders by user or product name

Additionally it applies a discount of 20% to the total cost of any order when at least 3 items of "Pepsi Cola" are selected.

## Get Started

### Requirements

* PHP >= 5.4
* [Apache][2] Web Server with [mod_rewrite][3] enabled or [Nginx][4] Web Server
* Latest stable [Phalcon Framework release][5] extension enabled
* [MySQL][6] >= 5.1.5

### Installation

First you need to clone this repository:

```
$ git clone git://github.com/rdimitriev/SimpleOrdersApp.git
```

Then you'll need to create the database and initialize schema:

```sh
$ echo 'CREATE DATABASE store CHARSET=utf8 COLLATE=utf8_unicode_ci' | mysql -u root
$ cat app/migrations/store.sql | mysql -u root store
```

Also you can override application config by modifying `app/config/config.php` and providing proper credentials (if needed).

Next install all needed dependencies mostly required for dev purposes (not needed for prod). Those services provide Phalcon dev tools for model/migration/etc management, as well as PHPUnit needed to run unit tests.

```
$ composer install
```

Now you can point the web server to the location of the cloned project (it doesn't need separate vhost file to work since it has its own htaccess file) as long as the project is inside your htdocs directory.


[1]: https://phalconphp.com/
[2]: http://httpd.apache.org/
[3]: http://httpd.apache.org/docs/current/mod/mod_rewrite.html
[4]: http://nginx.org/
[5]: https://github.com/phalcon/cphalcon/releases
[6]: https://www.mysql.com/
