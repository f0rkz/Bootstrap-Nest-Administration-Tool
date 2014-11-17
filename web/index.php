<?php

/**********************************************************************************
/ Bootstrap Nest Control Panel
/ 2014 made by Nick Gray aka f0rkz
/ Feel free to modify at will. Released under the you break it you bought
/ it license.
/ Any issues, please email f0rkz@f0rkznet.net
/ For more information about the application, please see the README file
/**********************************************************************************/

include('../includes/common.php');

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

if ($login->isUserLoggedIn() == false)
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
	        	    echo $tpl_error->fetch("../includes/templates/error.tpl");
        		}
    		}
    		if ($registration->messages) 
    		{
	        	foreach ($registration->messages as $message) 
	        	{
	        		$success_message = $message . " <a href=\"/\">Back to home</a>";
        			$tpl_success = new Template("../includes/templates/success.tpl");
        			$tpl_success->set("success_text", $success_message);
	        	    echo $tpl_success->fetch("../includes/templates/success.tpl");
	        	    header("refresh:5; url=index.php"); 
        		}
    		}
		}

		$tpl_head = new Template("../includes/templates/head-login.tpl");
		$tpl_registration = new Template("../includes/templates/register.tpl");
		$tpl_foot = new Template("../includes/templates/foot.tpl");

		echo $tpl_head->fetch('../includes/templates/head-login.tpl');
		echo $tpl_registration->fetch('../includes/templates/register.tpl');
		echo $tpl_foot->fetch('../includes/templates/foot.tpl');
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
	        	    echo $tpl_error->fetch("../includes/templates/error.tpl");				}
			}
		}
		$tpl_head = new Template("../includes/templates/head-login.tpl");
		$tpl_login = new Template("../includes/templates/login.tpl");
		$tpl_foot = new Template("../includes/templates/foot.tpl");

		$tpl_head->set('title', "Nest Administration Tool");

		echo $tpl_head->fetch('../includes/templates/head-login.tpl');
		echo $tpl_login->fetch('../includes/templates/login.tpl');
		echo $tpl_foot->fetch('../includes/templates/foot.tpl');
	}
}

/**********************************************************************************
/ Logged in user processes
/ Anything ran under the if login->isUserLoggedIn True will be pages rendered
/ for the user's session.
/**********************************************************************************/

if ($login->isUserLoggedIn() == true)
{
	$username = $_SESSION['user_name'];

	if ($request == null)
	{
		/*
		// Query database for collected data
		*/
		$db_connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$user_id_query = "select user_id from users where user_name = \"$username\"";
		$get_user_id = mysqli_query($db_connect, $user_id_query);
		while ( $user_row = mysqli_fetch_array($get_user_id))
		{
			$user_id = $user_row['user_id'];
		}

		$query = "select * from data where user_id = \"$user_id\" ORDER BY timestamp";

		$result = mysqli_query($db_connect, $query);
		if (mysqli_connect_errno())
		{
	        echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		while ($row = mysqli_fetch_array($result))
		{
			$timestamp = $row['timestamp'];
			$timestamp_offset = $row['timestamp_offset'];
			$heating = $row['heating'];
			$cooling = $row['cooling'];
			$setpoint = $row['target'];
			$temp = $row['current'];
			$humidity = $row['humidity'];
			$outside_temp = $row['outside_temp'];
			$outside_humidity = $row['outside_humidity'];
			
		}

		$tpl_head = new Template("../includes/templates/head.tpl");
		$tpl_nav = new Template("../includes/templates/nav.tpl");
		$tpl_foot = new Template("../includes/templates/foot.tpl");

		$tpl_stats = new Template("../includes/templates/col-md-12-container.tpl");

		$tpl_head->set('title', "Nest Administration Tool");
		$tpl_nav->set('nav_brand_url', $nav_brand_url);
		$tpl_nav->set('nav_brand_name', $nav_brand_name);

		$tpl_stats->set('container_header', "Nest Statistics");
		$tpl_stats->set('body_elements', "Stats");

		echo $tpl_head->fetch('../includes/templates/head.tpl');
		echo $tpl_nav->fetch('../includes/templates/nav.tpl');

		echo $tpl_stats->fetch('../includes/templates/col-md-12-container.tpl');
		echo $tpl_foot->fetch('../includes/templates/foot.tpl');
	}
	if (isset($request['page']) && $request['page'] == 'graphs' )
	{
		$tpl_head = new Template("../includes/templates/head.tpl");
		$tpl_nav = new Template("../includes/templates/nav.tpl");
		$tpl_foot = new Template("../includes/templates/foot.tpl");
		$tpl_chart_nest_stats = new Template("../includes/templates/chart_nest_stats.tpl");
		//$tpl_chart_unit_stats = new Template("../includes/templates/chart_unit_stats.tpl");

		$tpl_head->set('title', "Nest Administration Tool: Graphs");
		$tpl_nav->set('nav_brand_url', $nav_brand_url);
		$tpl_nav->set('nav_brand_name', $nav_brand_name);

		/*
		// Query database for collected data
		*/
		$db_connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$user_id_query = "select user_id from users where user_name = \"$username\"";
		$get_user_id = mysqli_query($db_connect, $user_id_query);
		while ( $user_row = mysqli_fetch_array($get_user_id))
		{
			$user_id = $user_row['user_id'];
		}

		$query = "select * from data where user_id = \"$user_id\" ORDER BY timestamp";

		$result = mysqli_query($db_connect, $query);
		if (mysqli_connect_errno())
		{
	        echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$data_temp = array();
		$data_humidity = array();
		$data_setpoint = array();
		$data_outside_temp = array();
		$data_outside_humidity = array();
		$data_cooling = array();
		$data_heating = array();

		while ($row = mysqli_fetch_array($result))
		{
			$timestamp = $row['timestamp'];
			$timestamp_offset = $row['timestamp_offset'];
			$heating = $row['heating'] ? $row['target'] : "null";
			$cooling = $row['cooling'] ? $row['target'] : "null";
			$setpoint = $row['target'] > 0 ? $row['target'] : "null";
			$temp = $row['current'];
			$humidity = $row['humidity'];
			$outside_temp = $row['outside_temp'];
			$outside_humidity = $row['outside_humidity'];

			$timestamp *= 1000; // convert from Unix timestamp to JavaScript time
			$data_temp[] .= "[$timestamp, $temp]";
			$data_humidity[] .= "[$timestamp, $humidity]";
			$data_setpoint[] .= "[$timestamp, $setpoint]";
			$data_outside_temp[] .= "[$timestamp, $outside_temp]";
			$data_outside_humidity[] .= "[$timestamp, $outside_humidity]";
			$data_cooling[] .= "[$timestamp, $cooling]";
			$data_heating[] .= "[$timestamp, $heating]";
		}

		$date_offset = $timestamp_offset * -1;

		$tpl_chart_nest_stats->set('date_offset', $date_offset);
		$tpl_chart_nest_stats->set('data_temp', $data_temp);
		$tpl_chart_nest_stats->set('data_humidity', $data_humidity);
		$tpl_chart_nest_stats->set('data_setpoint', $data_setpoint);
		$tpl_chart_nest_stats->set('data_outside_temp', $data_outside_temp);
		$tpl_chart_nest_stats->set('data_outside_humidity', $data_outside_humidity);
		$tpl_chart_nest_stats->set('data_cooling', $data_cooling);
		$tpl_chart_nest_stats->set('data_heating', $data_heating);

		//$tpl_chart_unit_stats->set('date_offset', $date_offset);
		//$tpl_chart_unit_stats->set('data_cooling', $data_cooling);
		//$tpl_chart_unit_stats->set('data_heating', $data_heating);

		echo $tpl_head->fetch('../includes/templates/head.tpl');
		echo $tpl_nav->fetch('../includes/templates/nav.tpl');
		echo $tpl_chart_nest_stats->fetch('../includes/templates/chart_nest_stats.tpl');
		//echo $tpl_chart_unit_stats->fetch('../includes/templates/chart_unit_stats.tpl');
		echo $tpl_foot->fetch('../includes/templates/foot.tpl');
	}
	if (isset($request['page']) && $request['page'] == 'profile')
	{

		// Get the user's information
		$db_connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$user_id_query = "select user_id, nest_username, user_zip from users where user_name = \"$username\"";
		$get_user_id = mysqli_query($db_connect, $user_id_query);
		while ( $user_row = mysqli_fetch_array($get_user_id))
		{
			$user_id = $user_row['user_id'];
			$user_zip = $user_row['user_zip'];
			$nest_username = $user_row['nest_username'];
		}
		if ($request['postsettings'] == 'update')
		{
			$nest_username = $input['nest']['username'];
			$nest_password = $input['nest']['password'];
			$nest_zipcode = $input['nest']['location'];

			$nest_password_encrypt = encrypt($nest_password, ENCRYPTION_KEY);

			$fields = "nest_username=\"$nest_username\", nest_password=\"$nest_password_encrypt\", user_zip=\"$nest_zipcode\"";
			$server_sql = "UPDATE users SET $fields WHERE user_id = $user_id";
			mysqli_query($db_connect, $server_sql);

			$user_zip = $nest_zipcode;

       		$success_message = $message . "Updated user preferences";
   			$tpl_success = new Template("../includes/templates/success.tpl");
   			$tpl_success->set("success_text", $success_message);
       	    echo $tpl_success->fetch("../includes/templates/success.tpl");

		}

		$tpl_head = new Template("../includes/templates/head.tpl");
		$tpl_nav = new Template("../includes/templates/nav.tpl");
		$tpl_foot = new Template("../includes/templates/foot.tpl");
		$tpl_profile = new Template("../includes/templates/profile.tpl");

		$tpl_head->set('title', "Nest Administration Tool: Settings");
		$tpl_nav->set('nav_brand_url', $nav_brand_url);
		$tpl_nav->set('nav_brand_name', $nav_brand_name);

		$tpl_profile->set('nest_username', $nest_username);
		$tpl_profile->set('zipcode', $user_zip );
		echo $tpl_head->fetch('../includes/templates/head.tpl');
		echo $tpl_nav->fetch('../includes/templates/nav.tpl');
		echo $tpl_profile->fetch('../includes/templates/profile.tpl');
		echo $tpl_foot->fetch('../includes/templates/foot.tpl');
	} 
}

if (isset($request['logout']))
{
	header('/');
}