<?php

error_reporting(E_ERROR);

// include('../classes/dbconnect.php');
// include('../classes/nest.php');
// include('../nest.conf.php');
// include('../functions/convertKelvin.php');
// include('../functions/encrypt_decrypt.php');

include('/opt/nest-bootstrap/includes/classes/dbconnect.php');
include('/opt/nest-bootstrap/includes/classes/nest.php');
include('/opt/nest-bootstrap/includes/nest.conf.php');
include('/opt/nest-bootstrap/includes/functions/convertKelvin.php');
include('/opt/nest-bootstrap/includes/functions/encrypt_decrypt.php');

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
	$nest_password_decrypt = decrypt($nest_password, ENCRYPTION_KEY);
	// $timestamp = date('Y-m-d H:i:s');
	$timestamp = time();

  try {
	$nest = new Nest($nest_username, $nest_password_decrypt);
  }
  catch(Exception $e) {
    print "Error collecting data for " . $user_id . " Exception: " . $e . "\n";
    continue;
  }
  try {
    $nest_devices = $nest->getDevices();

  	foreach($nest_devices as $device)
  	{
  		// Gather information from the Nest class object for storage
    		$infos = $nest->getDeviceInfo($device);
    		$energy = $nest->getEnergyLatest($device);
    		$weather_nest = $nest->getWeather($user_location);

    		// Gather the device information for storage
  		$device_serial_number = $infos->serial_number;
  		$nest_location = $infos->where;
  		$device_name = $infos->name;

  		$battery_level = $infos->current_state->battery_level;

  		// Outside weather pulled from the nest class
  		$outside_humidity = $weather_nest->outside_humidity;
  		$outside_temperature = $weather_nest->outside_temperature;

  		// Inside weather pulled from the nest class
  		$temperature = $infos->current_state->temperature;
  		$humidity = $infos->current_state->humidity;

  		// Current running statistics for the graph
  		$ac = $infos->current_state->ac == "" ? 0 : $infos->current_state->ac;
  		$heat = $infos->current_state->heat == "" ? 0 : $infos->current_state->heat;
  		$scale = $infos->scale;$time_to_target = $infos->target->time_to_target;

  		// if heat/cool is off API returns the away temp in an array.
  		// consisting of [low temp, high temp] - commented out line is to keep both, but for now let's just take the low temp
  		//$target = is_array($infos->target->temperature) ? implode(',', $infos->target->temperature) : round($infos->target->temperature, 1);
  		$target = is_array($infos->target->temperature) ? $infos->target->temperature[0] : round($infos->target->temperature, 1);

  		// Queries Nest site every time for all devices in your account
  		// If you add a new Nest one day it should show up and be populated into your DB
  		// and a new graph will start to be rendered without intervention
  		// INSERT IGNORE is MySQL specific
  		$insert_statement = $db_connect->prepare("
  	        INSERT IGNORE INTO devices
  	        SET device_serial_number = :device_serial_number,
  	          user_id = :user_id,
  	          device_location = :device_location,
  	          device_name = :device_name
  	    ");
  	    $insert_statement->execute(array(
  	        'device_serial_number' => $device,
  	        'user_id' => $user_id,
  	        'device_location' => $nest_location,
  	        'device_name' => $device_name,
  	    ));
  		///////**************************

  	    $update_data_statement = $db_connect->prepare("
  	      INSERT INTO data SET
  	        user_id = :user_id,
  	        timestamp = :timestamp,
  	        heating = :heating,
  	        cooling = :cooling,
  	        target = :target,
  	        current = :current,
  	        humidity = :humidity,
  	        outside_temp = :outside_temp,
  	        outside_humidity = :outside_humidity,
  	        battery_level = :battery_level,
  	        device_serial_number = :device_serial_number
  	    ");
  	    $update_data_statement->execute(array(
  	      'user_id' => $user_id,
  	      'timestamp' => $timestamp,
  	      'heating' => $heat,
  	      'cooling' => $ac,
  	      'target' => $target,
  	      'current' => $temperature,
  	      'humidity' => $humidity,
  	      'outside_temp' => $outside_temperature,
  	      'outside_humidity' => $outside_humidity,
  				'battery_level' => $battery_level,
  	      'device_serial_number' => $device_serial_number
  	    ));
  	}
  }
  catch(Exception $e) {
    print "Error collecting data for " . $user_id . " Exception: " . $e . "\n";
    continue;
  }
  sleep(10);
}
