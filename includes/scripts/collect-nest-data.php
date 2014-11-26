<?php

include('../classes/dbconnect.php');
include('../classes/nest.php');
include('../nest.conf.php');
include('../functions/convertKelvin.php');
include('../functions/encrypt_decrypt.php');

try {
  $db_connect = DBConnect::getConnection();
}
catch(Exception $e) {
  die('Error: ' . $e->getMessage());
}

$users_statement = $db_connect->prepare('select * from users');
$users_statement->execute();

while ( $row = $users_statement->fetch())
{
	$user_id = $row['user_id'];
	$user_location = $row['user_location'];
	if (empty($user_location))
	{
		$user_location = 30303;
	}
	$nest_username = $row['nest_username'];
	$nest_password = $row['nest_password'];
	$nest_password_decrypt = trim(decrypt($nest_password, ENCRYPTION_KEY));

	// User and pass configuration
	define('USERNAME', $nest_username);
	define('PASSWORD', $nest_password_decrypt);

	// Open weathermap api call
	$weather_json = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($user_location);
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

    $update_data_statement = $db_connect->prepare("
      INSERT INTO data SET
        user_id = :user_id,
        timestamp = :timestamp,
        timestamp_offset = :timestamp_offset,
        heating = :heating,
        cooling = :cooling,
        target = :target,
        current = :current,
        humidity = :humidity,
        outside_temp = :outside_temp,
        outside_humidity = :outside_humidity
    ");
    $update_data_statement->execute(array(
      'user_id' => $user_id,
      'timestamp' => $timestamp,
      'timestamp_offset' => $timestamp_offset,
      'heating' => $heat,
      'cooling' => $ac,
      'target' => $target,
      'current' => $temperature,
      'humidity' => $humidity,
      'outside_temp' => $outside_temperature,
      'outside_humidity' => $outside_humidity,
    ));

		$update_location_statement = $db_connect->prepare("
		  UPDATE users
		  SET user_location_lat = :user_location_lat,
		    user_location_long = :user_location_long,
		    scale = :scale
      WHERE user_id = :user_id
		");
    $update_location_statement->execute(array(
      'user_location_lat' => $user_lat,
      'user_location_long' => $user_long,
      'scale' => $scale,
      'user_id' => $user_id,
    ));
	}
}