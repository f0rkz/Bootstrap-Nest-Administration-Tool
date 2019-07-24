<?php

error_reporting(E_ERROR);
/**********************************************************************************
/ Bootstrap Nest Control Panel
/ 2014 made by Nick Gray aka f0rkz
/ Feel free to modify at will. Released under the you break it you bought
/ it license.
/ Any issues, please email f0rkz@f0rkznet.net
/ For more information about the application, please see the README file
/**********************************************************************************/

include('../includes/common.php');

$timezone = timezone();
$timestamp = timestamp();
$timestamp_offset = timestamp_offset();

// Figure out what page to render
$request = $_GET;
$input = $_POST;

$nav_brand_url = BRAND_URL;
$nav_brand_name = BRAND_NAME;

$login = new Login();

/**********************************************************************************
/ Not Logged in processes
/ Anything ran under the if login->isUserLoggedIn False will be pages rendered
/ for the not logged in user.
/**********************************************************************************/

if ($login->isUserLoggedIn() == false && !(isset($request['cmd']) && ($request['cmd'] == 'generate_graph' || $request['cmd'] == 'graph_data')))
{
	if (isset($request['page']) && $request['page'] == 'register')
	{
		$registration = new Registration();
		if (isset($registration)) 
		{
	    	if ($registration->errors) 
	    	{
        		foreach ($registration->errors as $error) 
        		{
	        	    $tpl_error = new Template("../includes/templates/error.tpl");
        			$tpl_error->set("error_text", $error);
	        	    echo $tpl_error->fetch();
        		}
    		}
    		if ($registration->messages) 
    		{
	        	foreach ($registration->messages as $message) 
	        	{
	        		$success_message = $message . " <a href=\"/\">Back to home</a>";
        			$tpl_success = new Template("../includes/templates/success.tpl");
        			$tpl_success->set("success_text", $success_message);
	        	    echo $tpl_success->fetch();
	        	    header("refresh:5; url=index.php"); 
        		}
    		}
		}

		$tpl_head = new Template("../includes/templates/head-login.tpl");
		$tpl_registration = new Template("../includes/templates/register.tpl");
		$tpl_foot = new Template("../includes/templates/foot.tpl");

		echo $tpl_head->fetch();
		echo $tpl_registration->fetch();
		echo $tpl_foot->fetch();
	}
	else
	{
		if ($request == null && defined('DEFAULT_USER') && !is_null(DEFAULT_USER))
		{
			$username = DEFAULT_USER;
		}
		else
		{
			if (isset($login)) 
			{
				if ($login->errors) 
				{
			   		foreach ($login->errors as $error) 
			   		{
		        	    $tpl_error = new Template("../includes/templates/error.tpl");
	        			$tpl_error->set("error_text", $error);
		        	    echo $tpl_error->fetch();				
		        	}
				}
			}
			$tpl_head = new Template("../includes/templates/head-login.tpl");
			$tpl_login = new Template("../includes/templates/login.tpl");
			$tpl_foot = new Template("../includes/templates/foot.tpl");

			$tpl_head->set('title', "Nest Administration Tool");

			echo $tpl_head->fetch();
			echo $tpl_login->fetch();
			echo $tpl_foot->fetch();
		}
	}
}

/**********************************************************************************
/ Logged in user processes
/ Anything ran under the if login->isUserLoggedIn True will be pages rendered
/ for the user's session.
/**********************************************************************************/

if ($request == null)
{
	if ($login->isUserLoggedIn() == true)
	{
		$username = $_SESSION['user_name'];
	}

	if (isset($username))
	{
		$tpl_head = new Template("../includes/templates/head.tpl");
		$tpl_nav = ($login->isUserLoggedIn() == true)? new Template("../includes/templates/nav-user.tpl") : new Template("../includes/templates/nav-public.tpl");
		$tpl_foot = new Template("../includes/templates/foot.tpl");
		$tpl_chart_nest_stats = new Template("../includes/templates/chart_nest_stats.tpl");

		$tpl_head->set('title', "Nest Administration Tool: Graphs");
		$tpl_nav->set('nav_brand_url', $nav_brand_url);
		$tpl_nav->set('nav_brand_name', $nav_brand_name);

		echo $tpl_head->fetch();
		echo $tpl_nav->fetch();

		$db_connect = DBConnect::getConnection();
	    $devices_statement = $db_connect->prepare('
	    	SELECT devices.device_serial_number, devices.device_location, devices.device_name 
	    	FROM users, devices 
	    	WHERE users.user_id = devices.user_id 
	    	AND users.user_name = :user_name');
	    $devices_statement->execute(array('user_name' => $username));
		while ( $user_row = $devices_statement->fetch())
		{
			$device_serial_number = $user_row['device_serial_number'];
			$device_name = $user_row['device_name'];
			if ($device_name == "Not Set"){
			    $device_name = $user_row['device_location'];
			}
			$tpl_chart_nest_stats->set('device_serial_number', $device_serial_number);
			$tpl_chart_nest_stats->set('device_name', $device_name);
			echo $tpl_chart_nest_stats->fetch();	// Graph data
		}
		echo $tpl_foot->fetch();
	}
}

if (isset($request['page']) && $request['page'] == 'profile')
{
	if ($login->isUserLoggedIn() == true)
	{
		$username = $_SESSION['user_name'];

		// Get the user's information
		$db_connect = DBConnect::getConnection();
    $user_id_statement = $db_connect->prepare("select user_id, nest_username, user_location from users where user_name = :username");
    $user_id_statement->execute(array(
      'username' => $username,
    ));
	$user_results = $user_id_statement->fetchAll();
    $user_row = $user_results[0];

    $user_id = $user_row['user_id'];
    $user_location = $user_row['user_location'];
    $nest_username = $user_row['nest_username'];

		if (isset($request['postsettings']) && $request['postsettings'] == 'update')
		{
			$nest_username = $input['nest']['username'];
			$nest_password = $input['nest']['password'];
			$nest_location = $input['nest']['location'];

			$nest_password_encrypt = encrypt($nest_password, ENCRYPTION_KEY);

			$update_statement = $db_connect->prepare("
				UPDATE users
				SET nest_username = :nest_username,
				  nest_password = :nest_password,
				  user_location = :user_location				  
				WHERE user_id = :user_id
			");
			$update_statement->execute(array(
				'nest_username' => $nest_username,
				'nest_password' => $nest_password_encrypt,
				'user_location' => $nest_location,
				'user_id' => $user_id
			));

			define('USERNAME', $nest_username);
			define('PASSWORD', $nest_password);
			$nest = new Nest();
			$nest_devices = $nest->getDevices(); 

			foreach($nest_devices as $device)
			{
			  $device_info = $nest->getDeviceInfo($device);
			  
			  $insert_statement = $db_connect->prepare("
		        REPLACE INTO devices
		        SET device_serial_number = :device_serial_number,
		          user_id = :user_id,
		          device_location = :device_location,
		          device_name = :device_name
		      ");
		      $insert_statement->execute(array(
		        'device_serial_number' => $device,
		        'user_id' => $user_id,
		        'device_location' => $nest_location,
		        'device_name' => $device_info->name,
		      ));
			}


		  $user_location = $nest_location;

	      $message = isset($message) ? $message : '';
	       		$success_message = $message . "Updated user preferences";
	   			$tpl_success = new Template("../includes/templates/success.tpl");
	   			$tpl_success->set("success_text", $success_message);
	       	    echo $tpl_success->fetch();

		}

		$tpl_head = new Template("../includes/templates/head.tpl");
		$tpl_nav = new Template("../includes/templates/nav-user.tpl");
		$tpl_foot = new Template("../includes/templates/foot.tpl");
		$tpl_profile = new Template("../includes/templates/profile.tpl");

		$tpl_head->set('title', "Nest Administration Tool: Settings");
		$tpl_nav->set('nav_brand_url', $nav_brand_url);
		$tpl_nav->set('nav_brand_name', $nav_brand_name);

		$tpl_profile->set('nest_username', $nest_username);
		$tpl_profile->set('location', $user_location);
		echo $tpl_head->fetch();
		echo $tpl_nav->fetch();
		echo $tpl_profile->fetch();
		echo $tpl_foot->fetch();
	} 
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($request['cmd']) && ($request['cmd'] == 'generate_graph' || $request['cmd'] == 'graph_data'))
{
	if (defined('DEFAULT_USER') && !is_null(DEFAULT_USER))
	{
		$username = DEFAULT_USER;
	}

	if ($login->isUserLoggedIn() == true)
	{
		$username = $_SESSION['user_name'];
	}

	if (isset($username))
	{
		$data_js = $request['cmd'] == 'generate_graph' ?
      new Template("../includes/templates/data.js.tpl")
      : new Template('../includes/templates/graph-data.js.tpl');

		$db_connect = DBConnect::getConnection();
	    $devices_statement = $db_connect->prepare('
	    	SELECT devices.device_serial_number, devices.device_location, devices.device_name, users.user_id, users.scale
	    	FROM users, devices 
	    	WHERE users.user_id = devices.user_id 
	    	AND users.user_name = :user_name');
	    $devices_statement->execute(array('user_name' => $username));

		while ( $user_row = $devices_statement->fetch())
		{
			$user_id = $user_row['user_id'];
			$device_serial_number = $user_row['device_serial_number'];
			$device_name =  $user_row['device_name'];
			if ($device_name == "Not Set"){
			    $device_name = $user_row['device_location'];
			}
			$scale = $user_row['scale'];

      $start = $request['start'];
      if ($start && !preg_match('/^[0-9]+$/', $start)) {
        die("Invalid start parameter: $start");
      }
      if ($start) floor($start /= 1000); // Convert from JS timestamp

      $end = $request['end'];
      if ($end && !preg_match('/^[0-9]+$/', $end)) {
        die("Invalid end parameter: $end");
      }
      if ($end) ceil($end /= 1000); // Convert from JS timestamp

      // Initial data load (and more than one year) is weekly data
      $group_by = 'GROUP BY YEAR(FROM_UNIXTIME(data.timestamp)), WEEKOFYEAR(FROM_UNIXTIME(data.timestamp))';
      $range_sql = '';

      // There are start and end times, so we're getting ranges
      if ($start && $end) {
        // Find range for loading data
        $range = $end - $start;
        $range_sql = 'AND data.timestamp BETWEEN :start AND :end';

        if ($range <= 7 * 24 * 3600) {
          // one week range loads all data
          $group_by = '';
        } elseif ($range <= 32 * 24 * 3600) {
          // one month range loads hourly data
          $group_by = 'GROUP BY YEAR(FROM_UNIXTIME(data.timestamp)), MONTH(FROM_UNIXTIME(data.timestamp)), DAY(FROM_UNIXTIME(data.timestamp)), HOUR(FROM_UNIXTIME(data.timestamp))';
        } elseif ($range <= 12 * 31 * 24 * 3600) {
          // one year range loads daily data
          $group_by = 'GROUP BY YEAR(FROM_UNIXTIME(data.timestamp)), DAYOFYEAR(FROM_UNIXTIME(data.timestamp))';
        }
      }


      $sql = "(
        SELECT data.*, data.timestamp AS union_sort
        FROM data
        WHERE data.user_id = :user_id 
          AND data.device_serial_number = :device_serial_number
          $range_sql
        $group_by
        ORDER BY data.timestamp ASC
      )";

      $params = array();
      $params['user_id'] = $user_id;
      $params['device_serial_number'] = $device_serial_number;

      if (!$range_sql) {
        // Make sure to get the first and last records on initial load to set
        // extremes in graph
        $sql .= "
          UNION (
            SELECT data.*, data.timestamp AS union_sort
            FROM data
            WHERE data.user_id = :user_id_2 
              AND data.device_serial_number = :device_serial_number_2
            ORDER BY data.timestamp DESC
            LIMIT 1
          )
          UNION (
            SELECT data.*, data.timestamp AS union_sort
            FROM data
            WHERE data.user_id = :user_id_3 
              AND data.device_serial_number = :device_serial_number_3
            ORDER BY data.timestamp ASC
            LIMIT 1
          ) 
          ORDER BY union_sort";

        $params['user_id_2'] = $params['user_id_3'] = $user_id;
        $params['device_serial_number_2'] = $params['device_serial_number_3'] = $device_serial_number;
      }

      if ($range_sql) {
        $params['start'] = $start;
        $params['end'] = $end;
      }

      $data_statement = $db_connect->prepare($sql);
      $data_statement->execute($params);

			$data_temp = array();
			$data_humidity = array();
			$data_setpoint = array();
			$data_outside_temp = array();
			$data_outside_humidity = array();
			$data_battery_level = array();
			$data_cooling = array();
			$data_heating = array();
			$last_temp = null;
			$last_humidity = null;
			$last_outside_temp = null;
			$last_outside_humidity = null;
			$last_battery_level = null;
			$last_setpoint = null;
			$last_timestamp = null;
			$last_heating = null;
			$last_cooling = null;
			$min_timestamp = null;

			while ($row = $data_statement->fetch())
			{
				$timestamp = $row['timestamp'];
				// $timestamp = strtotime($row['timestamp']);
				$setpoint = $row['target'];
				$temp = $row['current'];
				$humidity = $row['humidity'];
				$outside_temp = $row['outside_temp'];
				$outside_humidity = $row['outside_humidity'];
				$battery_level = $row['battery_level'];

				// F - round temperatureto nearest degree
				// C - round temperature to nearest 0.5
				if ($scale == 'F')
				{
					$setpoint = round($setpoint);
					$temp = round($temp);
					$outside_temp = round($outside_temp);
				}
				else
				{
					$setpoint = round($setpoint * 2) / 2;
					$temp = round($temp * 2) / 2;
					$outside_temp = round($outside_temp * 2) / 2;				
				}

				$setpoint = $setpoint > 0 ? $setpoint : "null";
				$heating = $row['heating'] ? $setpoint : "null";
				$cooling = $row['cooling'] ? $setpoint : "null";

				$timestamp *= 1000; // convert from Unix timestamp to JavaScript time

        // Save minimum timestamp
        if (!$min_timestamp) $min_timestamp = $timestamp;

				if ($last_temp === null || $last_temp != $temp)
				{
					$last_temp = $temp;
					$data_temp[] .= "[$timestamp, $temp]";	
				}

				if ($last_humidity === null || $last_humidity != $humidity)
				{
					$last_humidity = $humidity;
					$data_humidity[] .= "[$timestamp, $humidity]";	
				}

				if ($last_outside_temp === null || $last_outside_temp != $outside_temp)
				{
					$last_outside_temp = $outside_temp;
					$data_outside_temp[] .= "[$timestamp, $outside_temp]";	
				}

				if ($last_outside_humidity === null || $last_outside_humidity != $outside_humidity)
				{
					$last_outside_humidity = $outside_humidity;
					$data_outside_humidity[] .= "[$timestamp, $outside_humidity]";	
				}

				if ($last_battery_level === null || $last_battery_level != $battery_level)
				{
					$last_battery_level = $battery_level;
					$data_battery_level[] .= "[$timestamp, $battery_level]";
				}

				if ($last_setpoint === null || $last_setpoint != $setpoint)
				{
					if ($setpoint == "null" && $last_timestamp != null)
					{
						// add previous point to get the line displayed correctly before away
						$data_setpoint[] .= "[$last_timestamp, $last_setpoint]";
					}

					$last_setpoint = $setpoint;
					$data_setpoint[] .= "[$timestamp, $setpoint]";
				}

				if ($last_heating === null || $last_heating != $heating)
				{
					if ($heating == "null" && $last_timestamp != null)
					{
						// add previous point to get the line displayed correctly before off
						$data_heating[] .= "[$last_timestamp, $last_heating]";
					}

					$last_heating = $heating;
					$data_heating[] .= "[$timestamp, $heating]";
				}

				if ($last_cooling === null || $last_cooling != $cooling)
				{
					if ($cooling == "null" && $last_timestamp != null)
					{
						// add previous point to get the line displayed correctly before off
						$data_cooling[] .= "[$last_timestamp, $last_cooling]";
					}

					$last_cooling = $cooling;
					$data_cooling[] .= "[$timestamp, $cooling]";
				}

				$last_timestamp = $timestamp;
			}

			// add last points in case they were skipped
			$data_temp[] .= "[$timestamp, $temp]";	
			$data_humidity[] .= "[$timestamp, $humidity]";	
			$data_outside_temp[] .= "[$timestamp, $outside_temp]";	
			$data_outside_humidity[] .= "[$timestamp, $outside_humidity]";
			$data_battery_level[] .= "[$timestamp, $battery_level]";
			$data_setpoint[] .= "[$timestamp, $setpoint]";
			$data_heating[] .= "[$timestamp, $heating]";
			$data_cooling[] .= "[$timestamp, $cooling]";

			$data_js->set('callback', $request['callback']);
      $data_js->set('min_timestamp', $min_timestamp);
      $data_js->set('max_timestamp', $last_timestamp);
			$data_js->set('device_serial_number', $device_serial_number);
			$data_js->set('device_name', $device_name);
			$data_js->set('time_offset', $timestamp_offset);
			$data_js->set('timezone', $timezone);
			$data_js->set('data_temp', $data_temp);
			$data_js->set('data_humidity', $data_humidity);
			$data_js->set('data_setpoint', $data_setpoint);
			$data_js->set('data_outside_temp', $data_outside_temp);
			$data_js->set('data_outside_humidity', $data_outside_humidity);
			$data_js->set('data_battery_level', $data_battery_level);
			$data_js->set('data_cooling', $data_cooling);
			$data_js->set('data_heating', $data_heating);
			$data_js->set('scale', $scale);
			$data_js->set('freezing_point', $scale == 'F' ? 32 : 0);
			$data_js->set('base_room_temp', $scale == 'F' ? 50 : 10);

			header("content-type: application/javascript");
			echo $data_js->fetch();

		}
	}
}

if (isset($request['logout']))
{
	$request = null;
	header("Location: index.php");
	die();
}