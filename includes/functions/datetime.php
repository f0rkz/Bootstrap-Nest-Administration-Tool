<?php

include('/opt/nest-bootstrap/includes/nest.conf.php');

function config(){
    $date = new DateTime();
    $timezone = $date->getTimezone()->getName();
    // $timestamp = date('Y-m-d H:i:s');
    $timestamp = time();

    $user_lat = LATITUDE;
    $user_long = LONGITUDE;
    $dark_api_key = DARK_API;

    $darksky_url = "https://api.darksky.net/forecast/" . urlencode($dark_api_key) . "/" . $user_lat . "," . $user_long;
        
    $dark_raw = file_get_contents($darksky_url);
    $dark_json = json_decode($dark_raw);

    // $timezone = $dark_json->timezone;

    $timestamp_offset = $dark_json->offset;

    date_default_timezone_set($timezone);
}

function timezone(){
config;
return $timezone;
}

function timestamp(){
config;
return $timestamp;
}

function timestamp_offset(){
config;   
return $timestamp_offset;
}
