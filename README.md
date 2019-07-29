Nest Bootstrap Control Panel
=============
A nest control panel made with Twitter Bootstrap. View historcal graphs and control your nest right from your web browser!

Features
-------------
*	Intuitive graphing using Highcharts Highstocks graphing tools

https://raw.githubusercontent.com/f0rkz/Bootstrap-Nest-Administration-Tool/master/nest-graphs.png

*	Control panel to set temperatures and tweak settings

General Prerequisites
-------------
* [Nest](https://nest.com) Account
* [DarkSky](https://darksky.net/dev/register) API Key (It's Free)

For Docker version:
* [Docker](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)

Docker Setup
=============

If you want to use this simple Nest bootstrap with Docker, please follow these steps:

* Copy the `nest.conf.php_EXAMPLE` to `nest.conf.php` in the `includes` directory and fill it in with oyur configuration values:
* *OPTIONAL - ONLY UPDATE IF NOT USING MYSQL IN DOCKER OR IF YOU WANT DIFFERENT CREDS; BE SURE TO MATCH THESE VARIABLES WITH ENV VARIABLES IN DOCKER-COMPOSE.YML*
	* `DB_HOST` - mariadb
    * `DB_USER` - nest_stats
    * `DB_PASS` = n3st_st4ts
    * `DB_NAME` = nest_stats
* *STANDARD*
	* `LATITUDE` - Latitude
	* `LONGITUDE` - Longitude
    * `DARK_API` - DarkSky API Key
    * `ENCRYPTION_KEY` - openssl encryption key
    * `DEFAULT_USER` - *optional* if you want a username displayed on front page of graph


* _(Opt)_ Edit the docker-compose.yml file to specify your ENV variables and timezone.

* Build Docker image:

	```
	docker-compose build
	```

* Run Project
	```
	docker-compose up -d
	```
* Do both build and run in one command:
	```
	docker-compose -f "docker-compose.yml" up -d --build


CLASSIC INSTALL INSTRUCTIONS
=============

Prerequisite PHP Packages
-------------
*	php7-json

Recommend cloning to and running this this from your `/opt/` directory.

Create a mysql database and give it a username and password.

	create database nest_stats;
	grant all privileges on nest_stats.* to nest_stats@localhost identified by 'some-password';

Export the tables from dbsetup.sql to the mysql database.

	mysql -unest_stats -p nest_stats < dbsetup.sql

Set up vhost with documentroot pointing to ./web/ directory

	Example: /opt/nest-bootstrap/web/

Copy nest.conf.php_EXAMPLE to nest.conf.php in the includes directory.

	cp ./includes/nest.conf.php_EXAMPLE ./includes/nest.conf.php

Edit nest.conf and follow install prompts.

Configure the crontab to collect nest data and do scheduled tasks:
Modify the path below to reflect your install:

	*/5 * * * * /bin/rm -f /tmp/nest_php_* ; /usr/bin/php /opt/nest-bootstrap/includes/scripts/collect-nest-data.php > /dev/null

You may also update the frequency of data collection by modifing the crontab as so. 5 minutes works out pretty well and keeps from flooding the Nest API with too many requests.

Create an account once you can access the tool and configure your nest login information in settings.

TO-DOs
=============
* [ ] - Add K8s support
* [ ] - Update Database `timestamp` field value from `int` to `timestamp` field type in MySQL
* [ ] - Update `includes/collect-nest-data.php` includes path to detect which dir its in
* [ ] - Convert `includes/collect-nest-data.php` to python
* [ ] - Update Docker Support for PHP-7.2
* [ ] - Add Alpine Docker option