<?php

/*
main web processing
*/

include('../includes/common.php');

// Figure out what page to render
$request = $_GET;

$nav_brand_url = BRAND_URL;
$nav_brand_name = BRAND_NAME;

if ($request == null)
{
	$tpl_head = new Template("../includes/templates/head.tpl");
	$tpl_nav = new Template("../includes/templates/nav.tpl");
	$tpl_foot = new Template("../includes/templates/foot.tpl");
	$tpl_chart = new Template("../includes/templates/chart.tpl");

	$tpl_head->set('title', "Nest Administration Tool");
	$tpl_nav->set('nav_brand_url', $nav_brand_url);
	$tpl_nav->set('nav_brand_name', $nav_brand_name);

	/*
	// Query database for collected data
	*/
	$db_connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$query = "select * from data ORDER BY timestamp";

	$result = mysqli_query($db_connect, $query);
	if (mysqli_connect_errno())
	{
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	while ($row = mysqli_fetch_array($result))
	{
		$timestamp = strtotime($row['timestamp']);
		$heating = $row['heating'];
		$setpoint = $row['target'];
		$temp = $row['current'];
		$humidity = $row['humidity'];
		$timestamp *= 1000; // convert from Unix timestamp to JavaScript time
		$data_temp[] .= "[$timestamp, $temp]";
		$data_humidity[] .= "[$timestamp, $humidity]";
		$data_setpoint[] .= "[$timestamp, $setpoint]";
	}

	$tpl_chart->set('data_temp', $data_temp);
	$tpl_chart->set('data_humidity', $data_humidity);
	$tpl_chart->set('data_setpoint', $data_setpoint);

	echo $tpl_head->fetch('../includes/templates/head.tpl');
	echo $tpl_nav->fetch('../includes/templates/nav.tpl');
	echo $tpl_chart->fetch('../includes/templates/chart.tpl');
	echo $tpl_foot->fetch('../includes/templates/foot.tpl');
}

// 404 error page
else
{
	echo "This is a 404 error.";
}