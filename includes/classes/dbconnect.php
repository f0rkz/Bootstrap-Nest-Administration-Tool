<?php

class DBConnect {
  static function getConnection() {
    static $connection;
    if (is_null($connection)) {
      $uri = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME. ';charset=utf8';
      $connection = new PDO($uri, DB_USER, DB_PASS);
      $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    return $connection;
  }
}
