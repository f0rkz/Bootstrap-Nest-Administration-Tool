Nest Bootstrap Control Panel
=============
A nest control panel made with Twitter Bootstrap. View historcal graphs and control your nest right from your web browser!

Features
-------------
*	Intuitive graphing using Highcharts Highstocks graphing tools
		http://git.f0rkznet.net/f0rkz/bootstrap-nest-administration-tool/raw/master/nest-graphs.png
*	Control panel to set temperatures and tweak settings



INSTALL INSTRUCTIONS

Prerequisite PHP Packages
php5-json
php5-mcrypt

-Create a mysql database and give it a username and password.
 create database nest_statistics;
 grant all privileves on nest_statistics.* to nest_statistics@localhost identified by 'some-password';
-Export the tables from dbsetup.sql to the mysql database.
 mysql -unest_stats -p nest_stats < dbsetup.sql
-Set up vhost with documentroot pointing to ./web/ directory
 Example: /home/user/nest.domainname.com/web/

Copy nest.conf.php_EXAMPLE to nest.conf.php in the includes directory.
 Example: cp ./includes/nest.conf.php_EXAMPLE nest.conf.php
 Exit nest.conf and follow install prompts.