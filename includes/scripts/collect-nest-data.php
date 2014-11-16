<?php

include('../classes/nest.php');
include('../nest.conf.php');
include('../functions/convertKelvin.php');
include('../functions/encrypt_decrypt.php');

$db_connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$user_query = "select * from users";
$get_users = mysqli_query($db_connect, $user_query);

while ( $row = mysqli_fetch_array($get_users))
{
	$user_id = $row['user_id'];
	$user_zip = $row['user_zip'];
	if ($user_zip == 0)
	{
		$user_zip = 30303;
	}
	$nest_username = $row['nest_username'];
	$nest_password = $row['nest_password'];
	$nest_password_decrypt = trim(decrypt($nest_password, ENCRYPTION_KEY));

	// User and pass configuration
	define('USERNAME', $nest_username);
	define('PASSWORD', $nest_password_decrypt);

	// Open weathermap api call
	$user_zip = str_pad($user_zip, 5, '0', STR_PAD_LEFT);
	$weather_json = "http://api.openweathermap.org/data/2.5/weather?q=" . $user_zip;
	$weather_array = json_decode(file_get_contents($weather_json));
	$user_lat = $weather_array->coord->lat;
	$user_long = $weather_array->coord->lon;
	$outside_temperature_kelvin = $weather_array->main->temp;
	$outside_humidity = $weather_array->main->humidity;

	$timestamp = time();

	// Google maps api URL
	$google_json = "https://maps.googleapis.com/maps/api/timezone/json?location=" . $user_lat . "," . $user_long . "&timestamp=" . $timestamp;
	$google_time = json_decode(file_get_contents($google_json));
	$dst_offset = $google_time->dstOffset;
	$raw_offset = $google_time->rawOffset;
	$timestamp_offset = ( $dst_offset + $raw_offset ) / 60 / 60;
	//$local_time = $timestamp + $dst_offset + $raw_offset;

	$nest = new Nest();
	$infos = $nest->getDeviceInfo();

	if ($weather_array)
	{
		$outside_temperature_kelvin = $weather_array->main->temp;
		$outside_humidity = $weather_array->main->humidity;
		$temperature = $infos->current_state->temperature;
		$humidity = $infos->current_state->humidity;
		$ac = $infos->current_state->ac;
		$heat = $infos->current_state->heat;
		$scale = $infos->scale;
		$target = $infos->target->temperature;
		$time_to_target = $infos->target->time_to_target;
		$outside_temperature = convertKelvin($outside_temperature_kelvin, $scale);

		$fields = "user_id=\"$user_id\", timestamp=\"$timestamp\", timestamp_offset=\"$timestamp_offset\", heating=\"$heat\", cooling=\"$ac\", target=\"$target\", current=\"$temperature\", humidity=\"$humidity\", outside_temp=\"$outside_temperature\", outside_humidity=\"$outside_humidity\"";
		$server_sql = "INSERT INTO data SET $fields";
		if (!mysqli_query($db_connect, $server_sql))
		{
			die('Error: ' . mysqli_error($db_connect));
		}
		$update_location = "UPDATE users SET user_location_lat=\"$user_lat\",user_location_long=\"$user_long\" where user_id = \"$user_id\"";
		if (!mysqli_query($db_connect, $update_location))
		{
			die('Error: ' . mysqli_error($db_connect));
		}
	}
}
	

/*
$nest = new Nest();

$infos = $nest->getDeviceInfo();
$weather_array = json_decode(file_get_contents($weather_json));

if ($weather_array)
{
	// Current weather conditions
	$outside_temperature_kelvin = $weather_array->main->temp;
	$outside_humidity = $weather_array->main->humidity;
	$timestamp = time();
	$temperature = $infos->current_state->temperature;
	$humidity = $infos->current_state->humidity;
	$ac = $infos->current_state->ac;
	$heat = $infos->current_state->heat;
	$scale = $infos->scale;
	$target = $infos->target->temperature;
	$time_to_target = $infos->target->time_to_target;
	$outside_temperature = convertKelvin($outside_temperature_kelvin, $scale);

	$fields = "timestamp=\"$timestamp\", heating=\"$heat\", cooling=\"$ac\", target=\"$target\", current=\"$temperature\", humidity=\"$humidity\", outside_temp=\"$outside_temperature\", outside_humidity=\"$outside_humidity\"";
	$server_sql = "INSERT INTO data SET $fields";
	if (!mysqli_query($db_connect, $server_sql))
	{
		die('Error: ' . mysqli_error($db_connect));
	}
}
*/