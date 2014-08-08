<?php

include('../classes/nest.php');
include('../nest.conf.php');
include('../functions/convertKelvin.php');

$db_connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$weather_json = "http://api.openweathermap.org/data/2.5/weather?q=" . ZIP_CODE;

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